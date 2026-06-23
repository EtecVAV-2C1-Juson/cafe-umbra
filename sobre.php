<?php
$tituloPagina = "Sobre Nós"; 
include "includes/header.php";

$carrosselItens = [
    ["img" => "assets/imgs/ambiente/acafe_ambiente.png", "txt" => "Nosso salão principal, planejado para o seu conforto."],
    ["img" => "assets/imgs/ambiente/acafe_ambiente2.png", "txt" => "Um segundo ângulo aconchegante para reuniões ou estudos."],
    ["img" => "assets/imgs/ambiente/acafe_bancada.png", "txt" => "Bancada sempre pronta com grãos selecionados."],
    ["img" => "assets/imgs/ambiente/acafe_cozinha.png", "txt" => "Nossa cozinha mágica, onde saem cookies e salgados quentinhos."],
    ["img" => "assets/imgs/ambiente/acafe_maquina.png", "txt" => "Extração artesanal para garantir o melhor sabor em cada xícara."]
];
?>

<main class="sobre-principal-unico">
    <div class="sobre-conteudo-central">
        
        <div class="sobre-espaco-box-unico">
            <h3>Nosso espaço disponibiliza</h3>
            
            <div class="carrossel-container-unico">
                <button type="button" class="btn-carrossel seta-esquerda" onclick="mudarSlideManual(-1)" aria-label="Slide anterior">
                    <i class="fa-solid fa-chevron-left"></i>
                </button>
                <button type="button" class="btn-carrossel seta-direita" onclick="mudarSlideManual(1)" aria-label="Próximo slide">
                    <i class="fa-solid fa-chevron-right"></i>
                </button>

                <div class="carrossel-slides">
                    <?php foreach ($carrosselItens as $index => $item): ?>
                        <div class="slide <?= $index === 0 ? 'ativo' : ''; ?>">
                            <img src="<?= $item['img']; ?>" alt="Foto do Espaço">
                            <div class="carrossel-descricao">
                                <p><?= htmlspecialchars($item['txt']); ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <div class="carrossel-indicadores">
                    <?php foreach ($carrosselItens as $index => $item): ?>
                        <span class="bolinha <?= $index === 0 ? 'ativa' : ''; ?>" onclick="irParaSlide(<?= $index; ?>)"></span>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <div class="sobre-historia-box-unico">
            <h3>Nossa historia</h3>
            
            <div class="bloco-cronologia-unico">
                <div class="card-historia-item-unico">
                    <img src="assets/imgs/ambiente/afachada_reforma.png" alt="Reforma da Fachada">
                    <div class="texto-historia">
                        <p>A Umbra Café é uma cafeteria simples e acolhedora que se tornou um ponto de encontro para moradores da cidade. Com um ambiente tranquilo, aroma constante de café fresco e atendimento amigável, o local oferece um espaço confortável para conversar, estudar ou apenas fazer uma pausa na rotina. Seu símbolo, uma xícara acompanhada por uma lua minguante, representa a calma e o aconchego que a cafeteria busca transmitir.</p>
                    </div>
                </div>

                <div class="card-historia-item-unico">
                    <img src="assets/imgs/ambiente/afachada.png" alt="Fachada Pronta">
                    <div class="texto-historia">
                        <p>Desde sua inauguracao, a Umbra conquistou clientes pela qualidade de suas bebidas e pelo ambiente agradável. O cardápio reúne cafés, salgados e doces preparados com cuidado, tornando cada visita uma experiência agradável. Mais do que um estabelecimento comercial, a Umbra é vista por muitos como um lugar para relaxar e aproveitar os pequenos momentos do dia.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="sobre-bloco-info-unico">
            <div class="info-grid-contato">
                <div>
                    <h4><i class="fa-solid fa-location-dot"></i> Onde nos encontrar</h4>
                    <p>Rua Tabapuã, 932 - Itaim Bibi, São Paulo - SP</p>
                </div>
                <div>
                    <h4><i class="fa-solid fa-envelope"></i> E-mail</h4>
                    <p>ola.umbracafe@gmail.com</p>
                </div>
                <div>
                    <h4><i class="fa-brands fa-instagram"></i> Instagram</h4>
                    <p>@umbracafe_</p>
                </div>
            </div>
        </div>

    </div>
</main>

<script>
let slideIndex = 0;
const slides = document.querySelectorAll('.slide');
const bolinhas = document.querySelectorAll('.bolinha');

function mostrarSlide(index) {
    if (index >= slides.length) index = 0;
    if (index < 0) index = slides.length - 1;

    slides.forEach(s => s.classList.remove('ativo'));
    bolinhas.forEach(b => b.classList.remove('ativa'));
    
    slides[index].classList.add('ativo');
    bolinhas[index].classList.add('ativa');
    slideIndex = index;
}

function mudarSlideManual(direcao) {
    mostrarSlide(slideIndex + direcao);
}

function irParaSlide(index) {
    mostrarSlide(index);
}

let autoSlide = setInterval(() => mudarSlideManual(1), 6000);

document.querySelectorAll('.btn-carrossel, .bolinha').forEach(elemento => {
    elemento.addEventListener('click', () => {
        clearInterval(autoSlide);
        autoSlide = setInterval(() => mudarSlideManual(1), 6000);
    });
});
</script>

<?php include "includes/footer.php"; ?>