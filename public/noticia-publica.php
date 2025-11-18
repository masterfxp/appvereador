<?php
// Página pública de notícias
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notícia - UniAssessor</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .fade-in {
            animation: fadeIn 0.5s ease-in;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .news-content {
            line-height: 1.8;
        }
        .news-content p {
            margin-bottom: 1.5rem;
        }
        .news-content h2 {
            font-size: 1.5rem;
            font-weight: bold;
            margin: 2rem 0 1rem 0;
            color: #1f2937;
        }
        .news-content h3 {
            font-size: 1.25rem;
            font-weight: bold;
            margin: 1.5rem 0 1rem 0;
            color: #374151;
        }
        .share-buttons {
            position: fixed;
            right: 20px;
            top: 50%;
            transform: translateY(-50%);
            z-index: 1000;
        }
        .share-button {
            display: block;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        .share-button:hover {
            transform: scale(1.1);
        }
        .share-facebook { background: #1877f2; }
        .share-twitter { background: #1da1f2; }
        .share-whatsapp { background: #25d366; }
        .share-linkedin { background: #0077b5; }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Header -->
    <header class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center">
                    <i class="fas fa-landmark text-2xl text-blue-600 mr-2"></i>
                    <span class="text-xl font-bold text-gray-900">UniAssessor</span>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="index.html" class="text-gray-600 hover:text-gray-900">Login</a>
                    <a href="#" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">Contato</a>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Loading State -->
        <div id="loadingState" class="text-center py-12">
            <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
            <p class="mt-2 text-gray-600">Carregando notícia...</p>
        </div>

        <!-- Error State -->
        <div id="errorState" class="text-center py-12 hidden">
            <i class="fas fa-exclamation-triangle text-6xl text-red-300 mb-4"></i>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Notícia não encontrada</h3>
            <p class="text-gray-600 mb-6">A notícia que você está procurando não existe ou foi removida.</p>
            <a href="index.html" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium">
                Voltar ao início
            </a>
        </div>

        <!-- News Content -->
        <article id="newsContent" class="hidden">
            <!-- News Image -->
            <div class="mb-8">
                <div id="newsImage" class="w-full h-96 bg-gray-200 rounded-xl overflow-hidden">
                    <!-- Image will be loaded here -->
                </div>
            </div>

            <!-- News Header -->
            <header class="mb-8">
                <div class="flex items-center space-x-4 mb-4">
                    <span id="newsCategory" class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-medium"></span>
                    <span id="newsDate" class="text-gray-500 text-sm"></span>
                </div>
                <h1 id="newsTitle" class="text-4xl font-bold text-gray-900 mb-4"></h1>
                <p id="newsSummary" class="text-xl text-gray-600 mb-6"></p>
                <div class="flex items-center justify-between border-t border-b border-gray-200 py-4">
                    <div class="flex items-center space-x-4">
                        <div class="flex items-center">
                            <i class="fas fa-user text-gray-400 mr-2"></i>
                            <span id="newsAuthor" class="text-gray-600"></span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-eye text-gray-400 mr-2"></i>
                            <span id="newsViews" class="text-gray-600"></span>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4">
                        <button onclick="likeNews()" class="flex items-center text-gray-600 hover:text-red-600 transition-colors">
                            <i class="fas fa-heart mr-2"></i>
                            <span id="newsLikes">0</span>
                        </button>
                        <button onclick="shareNews()" class="flex items-center text-gray-600 hover:text-blue-600 transition-colors">
                            <i class="fas fa-share mr-2"></i>
                            Compartilhar
                        </button>
                    </div>
                </div>
            </header>

            <!-- News Body -->
            <div class="prose prose-lg max-w-none">
                <div id="newsBody" class="news-content text-gray-800">
                    <!-- News content will be loaded here -->
                </div>
            </div>

            <!-- News Footer -->
            <footer class="mt-12 pt-8 border-t border-gray-200">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <span class="text-gray-500 text-sm">Publicado em:</span>
                        <span id="newsPublishDate" class="text-gray-900 font-medium"></span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span class="text-gray-500 text-sm">Compartilhe:</span>
                        <a href="#" onclick="shareOnFacebook()" class="text-blue-600 hover:text-blue-800">
                            <i class="fab fa-facebook"></i>
                        </a>
                        <a href="#" onclick="shareOnTwitter()" class="text-blue-400 hover:text-blue-600">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" onclick="shareOnWhatsApp()" class="text-green-600 hover:text-green-800">
                            <i class="fab fa-whatsapp"></i>
                        </a>
                    </div>
                </div>
            </footer>
        </article>
    </main>

    <!-- Share Buttons (Fixed) -->
    <div class="share-buttons hidden md:block">
        <a href="#" onclick="shareOnFacebook()" class="share-button share-facebook" title="Compartilhar no Facebook">
            <i class="fab fa-facebook"></i>
        </a>
        <a href="#" onclick="shareOnTwitter()" class="share-button share-twitter" title="Compartilhar no Twitter">
            <i class="fab fa-twitter"></i>
        </a>
        <a href="#" onclick="shareOnWhatsApp()" class="share-button share-whatsapp" title="Compartilhar no WhatsApp">
            <i class="fab fa-whatsapp"></i>
        </a>
        <a href="#" onclick="shareOnLinkedIn()" class="share-button share-linkedin" title="Compartilhar no LinkedIn">
            <i class="fab fa-linkedin"></i>
        </a>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-8 mt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <div class="flex items-center justify-center mb-4">
                    <i class="fas fa-landmark text-2xl text-blue-400 mr-2"></i>
                    <span class="text-xl font-bold">UniAssessor</span>
                </div>
                <p class="text-gray-400">Sistema de Gestão Legislativa</p>
                <p class="text-gray-500 text-sm mt-2">© 2024 UniAssessor. Todos os direitos reservados.</p>
            </div>
        </div>
    </footer>

    <script>
        let currentNews = null;

        // Load news on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadNews();
        });

        async function loadNews() {
            try {
                const urlParams = new URLSearchParams(window.location.search);
                const newsId = urlParams.get('id');

                if (!newsId) {
                    showError();
                    return;
                }

                const response = await fetch(`/api/noticias/${newsId}`);
                
                if (response.ok) {
                    currentNews = await response.json();
                    renderNews();
                } else {
                    showError();
                }
            } catch (error) {
                console.error('Erro ao carregar notícia:', error);
                // Fallback to mock data
                currentNews = getMockNews();
                renderNews();
            }
        }

        function getMockNews() {
            return {
                id: 1,
                titulo: "Nova Praça é Inaugurada no Centro da Cidade",
                resumo: "A prefeitura inaugurou uma nova praça com área de lazer e equipamentos modernos",
                conteudo: `
                    <p>A nova praça foi inaugurada com a presença do prefeito e vereadores. A obra contou com investimento de R$ 500.000 e beneficia mais de 10.000 moradores da região central.</p>
                    
                    <h2>Investimentos em Infraestrutura</h2>
                    <p>O projeto faz parte de um conjunto de investimentos em infraestrutura urbana que a prefeitura está realizando em diferentes bairros da cidade. A praça conta com:</p>
                    
                    <ul>
                        <li>Área de lazer com playground moderno</li>
                        <li>Quadra poliesportiva</li>
                        <li>Pista de caminhada</li>
                        <li>Área verde com árvores nativas</li>
                        <li>Iluminação LED</li>
                    </ul>
                    
                    <h2>Benefícios para a Comunidade</h2>
                    <p>A nova praça representa um importante avanço na qualidade de vida dos moradores da região central. Além de proporcionar um espaço de lazer e convivência, a praça também contribui para:</p>
                    
                    <p>• Melhoria da qualidade do ar com o aumento da área verde<br>
                    • Promoção de atividades físicas e esportivas<br>
                    • Fortalecimento do senso de comunidade<br>
                    • Valorização imobiliária da região</p>
                    
                    <p>A inauguração contou com a presença de autoridades municipais e representantes da comunidade, que destacaram a importância desta obra para o desenvolvimento da cidade.</p>
                `,
                categoria: "Obras",
                status: "publicado",
                imagem: "praça-centro.jpg",
                data_publicacao: "2024-01-15",
                visualizacoes: 1250,
                curtidas: 89,
                autor: "João Silva"
            };
        }

        function renderNews() {
            if (!currentNews) {
                showError();
                return;
            }

            // Hide loading state
            document.getElementById('loadingState').classList.add('hidden');
            
            // Show news content
            document.getElementById('newsContent').classList.remove('hidden');

            // Populate news data
            document.getElementById('newsTitle').textContent = currentNews.titulo;
            document.getElementById('newsSummary').textContent = currentNews.resumo || '';
            document.getElementById('newsCategory').textContent = currentNews.categoria || 'Geral';
            document.getElementById('newsAuthor').textContent = currentNews.autor || 'Autor não informado';
            document.getElementById('newsViews').textContent = currentNews.visualizacoes || 0;
            document.getElementById('newsLikes').textContent = currentNews.curtidas || 0;
            
            // Format dates
            if (currentNews.data_publicacao) {
                const publishDate = new Date(currentNews.data_publicacao);
                document.getElementById('newsDate').textContent = publishDate.toLocaleDateString('pt-BR');
                document.getElementById('newsPublishDate').textContent = publishDate.toLocaleDateString('pt-BR', {
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                });
            }

            // Set page title
            document.title = `${currentNews.titulo} - UniAssessor`;

            // Load image
            const imageContainer = document.getElementById('newsImage');
            if (currentNews.imagem) {
                imageContainer.innerHTML = `<img src="/uploads/${currentNews.imagem}" alt="${currentNews.titulo}" class="w-full h-full object-cover">`;
            } else {
                imageContainer.innerHTML = '<div class="w-full h-full flex items-center justify-center text-gray-400"><i class="fas fa-image text-6xl"></i></div>';
            }

            // Load content
            document.getElementById('newsBody').innerHTML = currentNews.conteudo || '';

            // Add fade-in animation
            document.getElementById('newsContent').classList.add('fade-in');
        }

        function showError() {
            document.getElementById('loadingState').classList.add('hidden');
            document.getElementById('errorState').classList.remove('hidden');
        }

        function likeNews() {
            if (!currentNews) return;
            
            // Simulate like action
            const likesElement = document.getElementById('newsLikes');
            const currentLikes = parseInt(likesElement.textContent);
            likesElement.textContent = currentLikes + 1;
            
            // Add visual feedback
            likesElement.parentElement.classList.add('text-red-600');
            setTimeout(() => {
                likesElement.parentElement.classList.remove('text-red-600');
            }, 1000);
        }

        function shareNews() {
            if (navigator.share) {
                navigator.share({
                    title: currentNews.titulo,
                    text: currentNews.resumo,
                    url: window.location.href
                });
            } else {
                // Fallback: copy to clipboard
                navigator.clipboard.writeText(window.location.href).then(() => {
                    alert('Link copiado para a área de transferência!');
                });
            }
        }

        function shareOnFacebook() {
            const url = encodeURIComponent(window.location.href);
            window.open(`https://www.facebook.com/sharer/sharer.php?u=${url}`, '_blank', 'width=600,height=400');
        }

        function shareOnTwitter() {
            const url = encodeURIComponent(window.location.href);
            const text = encodeURIComponent(currentNews.titulo);
            window.open(`https://twitter.com/intent/tweet?url=${url}&text=${text}`, '_blank', 'width=600,height=400');
        }

        function shareOnWhatsApp() {
            const url = encodeURIComponent(window.location.href);
            const text = encodeURIComponent(`${currentNews.titulo}\n\n${currentNews.resumo}\n\nLeia mais: ${window.location.href}`);
            window.open(`https://wa.me/?text=${text}`, '_blank');
        }

        function shareOnLinkedIn() {
            const url = encodeURIComponent(window.location.href);
            window.open(`https://www.linkedin.com/sharing/share-offsite/?url=${url}`, '_blank', 'width=600,height=400');
        }
    </script>
</body>
</html>