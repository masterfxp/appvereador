const express = require('express');
const router = express.Router();
const upload = require('../middleware/upload');

// Mock data para demonstração
let usuarios = [
    {
        id: 1,
        nome: 'Admin',
        email: 'admin@admin.com',
        cargo: 'Administrador',
        foto: null,
        created_at: new Date('2024-01-01')
    }
];

// GET /api/perfil - Obter dados do perfil
router.get('/', (req, res) => {
    try {
        // Em um sistema real, você pegaria o ID do usuário do token JWT
        const usuario = usuarios.find(u => u.id === 1);
        if (!usuario) {
            return res.status(404).json({ error: 'Usuário não encontrado' });
        }
        res.json(usuario);
    } catch (error) {
        console.error('Erro ao obter perfil:', error);
        res.status(500).json({ error: 'Erro interno do servidor' });
    }
});

// PUT /api/perfil - Atualizar dados do perfil
router.put('/', upload.single('foto'), (req, res) => {
    try {
        const { nome, email, cargo } = req.body;
        const foto = req.file ? req.file.filename : undefined;

        const usuarioIndex = usuarios.findIndex(u => u.id === 1);
        if (usuarioIndex === -1) {
            return res.status(404).json({ error: 'Usuário não encontrado' });
        }

        usuarios[usuarioIndex] = {
            ...usuarios[usuarioIndex],
            ...(nome && { nome }),
            ...(email && { email }),
            ...(cargo && { cargo }),
            ...(foto && { foto }),
            updated_at: new Date()
        };

        res.json(usuarios[usuarioIndex]);
    } catch (error) {
        console.error('Erro ao atualizar perfil:', error);
        res.status(500).json({ error: 'Erro interno do servidor' });
    }
});

module.exports = router;





