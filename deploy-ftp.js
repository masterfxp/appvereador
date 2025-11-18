const { Client } = require('basic-ftp');
const fs = require('fs');
const path = require('path');

// Carregar configurações
const config = require('./deploy-config.json');

// Arquivos e pastas a serem ignorados
const IGNORE_PATTERNS = [
    'node_modules',
    '.git',
    '.gitignore',
    'database.sqlite',
    'config.env',
    'deploy-config.json',
    '.env',
    '.DS_Store',
    'Thumbs.db',
    '*.log',
    '.vscode',
    '.idea',
    'npm-debug.log',
    'yarn-error.log',
    'package-lock.json' // Será instalado no servidor
];

// Função para verificar se um arquivo/pasta deve ser ignorado
function shouldIgnore(filePath) {
    const relativePath = path.relative(process.cwd(), filePath);
    const fileName = path.basename(filePath);
    
    return IGNORE_PATTERNS.some(pattern => {
        if (pattern.includes('*')) {
            const regex = new RegExp(pattern.replace('*', '.*'));
            return regex.test(fileName) || regex.test(relativePath);
        }
        return fileName === pattern || relativePath.includes(pattern);
    });
}

// Função para listar todos os arquivos recursivamente
function getAllFiles(dirPath, arrayOfFiles = []) {
    const files = fs.readdirSync(dirPath);

    files.forEach(file => {
        const filePath = path.join(dirPath, file);
        
        if (shouldIgnore(filePath)) {
            return;
        }

        if (fs.statSync(filePath).isDirectory()) {
            arrayOfFiles = getAllFiles(filePath, arrayOfFiles);
        } else {
            arrayOfFiles.push(filePath);
        }
    });

    return arrayOfFiles;
}

// Função principal de deploy
async function deploy() {
    const client = new Client();
    client.ftp.verbose = config.verbose || false;

    try {
        console.log('🔌 Conectando ao servidor FTP...');
        await client.access({
            host: config.host,
            user: config.user,
            password: config.password,
            secure: config.secure || false
        });

        console.log('✅ Conectado com sucesso!');
        console.log('📁 Navegando para o diretório remoto...');
        const remoteBaseDir = config.remoteDir || '/public_html';
        await client.cd(remoteBaseDir);
        
        console.log('📦 Coletando arquivos para upload...');
        const allFiles = getAllFiles('.');
        console.log(`📄 Total de ${allFiles.length} arquivos para upload`);

        let uploaded = 0;
        let failed = 0;

        // Criar todos os diretórios primeiro
        console.log('📁 Criando estrutura de diretórios...');
        const dirs = new Set();
        for (const filePath of allFiles) {
            const relativePath = path.relative(process.cwd(), filePath);
            const remotePath = relativePath.replace(/\\/g, '/');
            const remoteDir = path.dirname(remotePath).replace(/\\/g, '/');
            if (remoteDir !== '.' && remoteDir !== '') {
                dirs.add(remoteDir);
            }
        }

        // Criar diretórios recursivamente
        for (const dir of Array.from(dirs).sort()) {
            const parts = dir.split('/').filter(p => p);
            let currentPath = '';
            
            for (const part of parts) {
                currentPath = currentPath ? `${currentPath}/${part}` : part;
                try {
                    // Tentar garantir que o diretório existe
                    await client.ensureDir(currentPath);
                } catch (err) {
                    // Se falhar, tentar criar manualmente usando cd e mkdir
                    try {
                        const parentPath = path.dirname(currentPath).replace(/\\/g, '/');
                        if (parentPath && parentPath !== '.' && parentPath !== currentPath) {
                            await client.cd(parentPath === '/' ? remoteBaseDir : parentPath);
                        } else {
                            await client.cd(remoteBaseDir);
                        }
                        await client.ensureDir(part);
                        await client.cd(remoteBaseDir);
                    } catch (createErr) {
                        // Ignorar erros - diretório pode já existir
                    }
                }
            }
            // Voltar para o diretório base
            await client.cd(remoteBaseDir);
        }

        console.log('📤 Iniciando upload de arquivos...\n');

        // Agora fazer upload dos arquivos
        for (const filePath of allFiles) {
            const relativePath = path.relative(process.cwd(), filePath);
            try {
                const remotePath = relativePath.replace(/\\/g, '/'); // Normalizar caminhos Windows
                const remoteDir = path.dirname(remotePath).replace(/\\/g, '/');

                // Garantir que o diretório existe antes de fazer upload
                if (remoteDir !== '.' && remoteDir !== '') {
                    // Criar diretório recursivamente
                    const dirParts = remoteDir.split('/').filter(p => p);
                    let currentDirPath = '';
                    
                    for (const dirPart of dirParts) {
                        currentDirPath = currentDirPath ? `${currentDirPath}/${dirPart}` : dirPart;
                        try {
                            await client.ensureDir(currentDirPath);
                        } catch (dirErr) {
                            // Se falhar, tentar criar navegando para o diretório pai
                            try {
                                const parentDir = path.dirname(currentDirPath).replace(/\\/g, '/');
                                if (parentDir && parentDir !== '.' && parentDir !== currentDirPath) {
                                    await client.cd(parentDir === '/' ? remoteBaseDir : parentDir);
                                } else {
                                    await client.cd(remoteBaseDir);
                                }
                                await client.ensureDir(dirPart);
                                await client.cd(remoteBaseDir);
                            } catch (createErr) {
                                // Ignorar - pode já existir
                            }
                        }
                    }
                    // Garantir que estamos no diretório base
                    await client.cd(remoteBaseDir);
                }

                // Fazer upload do arquivo (forçar sobrescrita)
                console.log(`⬆️  ${relativePath}`);
                
                // Deletar arquivo remoto se existir para garantir sobrescrita completa
                try {
                    // Tentar remover o arquivo se existir
                    const fileExists = await client.size(remotePath);
                    if (fileExists !== undefined) {
                        await client.remove(remotePath);
                    }
                } catch (removeErr) {
                    // Ignorar erros - arquivo pode não existir ou já foi removido
                }
                
                // Fazer upload do arquivo (isso sobrescreve automaticamente)
                await client.uploadFrom(filePath, remotePath);
                uploaded++;
            } catch (err) {
                console.error(`❌ Erro: ${relativePath} - ${err.message}`);
                failed++;
                // Tentar novamente garantindo o diretório
                if (err.message && err.message.includes('550')) {
                    try {
                        const remotePath = relativePath.replace(/\\/g, '/');
                        const remoteDir = path.dirname(remotePath).replace(/\\/g, '/');
                        if (remoteDir !== '.' && remoteDir !== '') {
                            await client.ensureDir(remoteDir);
                            await client.uploadFrom(filePath, remotePath);
                            uploaded++;
                            failed--;
                            console.log(`✅ Reenviado com sucesso: ${relativePath}`);
                        }
                    } catch (retryErr) {
                        // Falhou novamente, continuar
                    }
                }
            }
        }

        console.log('\n✅ Deploy concluído!');
        console.log(`📊 Estatísticas:`);
        console.log(`   ✅ Uploads bem-sucedidos: ${uploaded}`);
        if (failed > 0) {
            console.log(`   ❌ Falhas: ${failed}`);
        }

    } catch (err) {
        console.error('❌ Erro durante o deploy:', err);
        process.exit(1);
    } finally {
        client.close();
    }
}

// Executar deploy
const command = process.argv[2];

if (command === 'deploy') {
    deploy().catch(err => {
        console.error('❌ Erro fatal:', err);
        process.exit(1);
    });
} else if (command === 'watch') {
    console.log('👀 Modo watch não implementado ainda. Use "npm run deploy" para fazer deploy.');
} else {
    console.log('Uso: node deploy-ftp.js [deploy|watch]');
    console.log('  deploy - Faz upload de todos os arquivos');
    console.log('  watch  - Monitora mudanças e faz deploy automático');
}

