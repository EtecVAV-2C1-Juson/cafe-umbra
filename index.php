<?php
// Define o título dinâmico da página que o seu header já aceita
$tituloPagina = "Início"; 
include "includes/header.php";
?>

<main class="home-principal">
    
    <section class="hero-banner">
        <div class="hero-conteudo">
            <img src="assets/imgs/logo/logo.png" alt="Umbra" class="hero-logo-escrita">
            
            <p>O seu momento, com o melhor café. Venha conhecer nosso espaço aconchegante no centro da cidade.</p>
            
            <div class="hero-botoes">
                <a href="sobre.php" class="btn-home-secundario">Saiba mais</a>
                <a href="pedido.php" class="btn-home-principal">Fazer o Pedido</a>
            </div>
        </div>
    </section>

    <section class="oferecemos-secao">
        <div class="oferecemos-container">
            <span class="oferecemos-sub">O que oferecemos</span>
            <h2 class="oferecemos-titulo">Delícias feitas artesanalmente para você</h2>
            
            <div class="oferecemos-linha">
                <div class="categoria-card">
                    <img src="assets/imgs/ambiente/acafes.png" alt="Cafés">
                    <div class="categoria-info">
                        <h3>Cafés</h3>
                        <p>Do expresso clássico aos filtrados, feitos com grãos selecionados.</p>
                    </div>
                </div>
                <div class="categoria-card">
                    <img src="assets/imgs/ambiente/achas.png" alt="Chás">
                    <div class="categoria-info">
                        <h3>Chás</h3>
                        <p>Infusões naturais e blends aromáticos para uma pausa leve.</p>
                    </div>
                </div>
            </div>

            <div class="oferecemos-linha">
                <div class="categoria-card">
                    <img src="assets/imgs/ambiente/agourmet.png" alt="Cafés Gourmet">
                    <div class="categoria-info">
                        <h3>Cafés Gourmet</h3>
                        <p>Combinações com chantilly, caldas e toques especiais quentes ou gelados.</p>
                    </div>
                </div>
                <div class="categoria-card">
                    <img src="assets/imgs/ambiente/asucos.png" alt="Bebidas">
                    <div class="categoria-info">
                        <h3>Bebidas</h3>
                        <p>Sucos naturais, sodas italianas e opções refrescantes feitas na hora.</p>
                    </div>
                </div>
            </div>

            <div class="oferecemos-linha">
                <div class="categoria-card">
                    <img src="assets/imgs/ambiente/acookie.png" alt="Cookies">
                    <div class="categoria-info">
                        <h3>Cookies</h3>
                        <p>Cookies americanos crocantes por fora e macios por dentro.</p>
                    </div>
                </div>
                <div class="categoria-card">
                    <img src="assets/imgs/ambiente/asalgados.png" alt="Salgados">
                    <div class="categoria-info">
                        <h3>Salgados</h3>
                        <p>Croissants, pães de queijo e folhados assados diariamente.</p>
                    </div>
                </div>
            </div>

            <div class="oferecemos-botoes">
                <a href="pedido.php" class="btn-cardapio-completo">Faça seu pedido</a>
            </div>

        </div>
    </section>
    <section class="info-rodape-secao">
        <div class="info-rodape-container">
            
            <div class="bloco-horario">
                <h3>Horário de Funcionamento</h3>
                <table class="tabela-horarios">
                    <tr><td>DOM</td><td>09:00 - 18:00</td></tr>
                    <tr><td>SEG</td><td>08:00 - 17:00</td></tr>
                    <tr><td>TER</td><td>08:00 - 17:00</td></tr>
                    <tr><td>QUA</td><td>08:00 - 17:00</td></tr>
                    <tr><td>QUI</td><td>08:00 - 17:00</td></tr>
                    <tr><td>SEX</td><td>08:00 - 17:00</td></tr>
                    <tr><td>SAB</td><td>09:00 - 18:00</td></tr>
                </table>
            </div>

            <div class="bloco-instagram">
                <div class="instagram-conteudo">
                    <span class="insta-sub">Confira nosso Instagram</span>
                    <p>Siga a nossa página para ficar por dentro de todos os nossos lançamentos, promoções semanais e bastidores artesanais.</p>
                    <a href="https://instagram.com/cafeumbra_" target="_blank" class="btn-instagram">Entrar</a>
                </div>
            </div>

        </div>
    </section>
</main>

<?php 
include "includes/footer.php";
?>