<?php
$tituloPagina = "Fazer Pedido"; 
include "includes/header.php";

try {
    $queryCategorias = "SELECT c.*, (SELECT imagem FROM produtos WHERE categoria_id = c.id LIMIT 1) as imagem_categoria FROM categorias c";
    $resultadoCategorias = $pdo->query($queryCategorias)->fetchAll(PDO::FETCH_ASSOC);

    $queryProdutos = "SELECT * FROM produtos";
    $resultadoProdutos = $pdo->query($queryProdutos)->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erro ao carregar dados do cardápio: " . $e->getMessage());
}
?>

<main class="pedido-principal">
    
    <div class="categorias-topo-container">
        <button class="seta-cat seta-cat-esq" onclick="scrollCategorias(-1)">❮</button>
        <button class="seta-cat seta-cat-dir" onclick="scrollCategorias(1)">❯</button>
        
        <div class="categorias-topo-scroll" id="categoriasScroll">
            <div class="categoria-topo-item cat-ativa" onclick="filtrarProdutosComJS(this, 'todos')">
                <div class="circulo-img-cat">
                    <img src="assets/imgs/produtos/gcm.png" alt="Todos">
                </div>
                <span>todos</span>
            </div>

            <?php foreach ($resultadoCategorias as $cat): ?>
                <?php 
                    $imgCat = !empty($cat['imagem_categoria']) ? $cat['imagem_categoria'] : 'categoria-placeholder.png';
                ?>
                <div class="categoria-topo-item" onclick="filtrarProdutosComJS(this, '<?php echo $cat['id']; ?>')">
                    <div class="circulo-img-cat">
                        <img src="assets/imgs/produtos/<?php echo $imgCat; ?>" alt="<?php echo $cat['nome']; ?>">
                    </div>
                    <span><?php echo mb_strtolower($cat['nome'], 'UTF-8'); ?></span>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <section class="produtos-pedido-lista">
        <?php foreach ($resultadoProdutos as $prod): ?>
            <div class="card-item-pedido" data-categoria="<?php echo $prod['categoria_id']; ?>">
                <div class="card-pedido-foto">
                    <img src="assets/imgs/produtos/<?php echo $prod['imagem']; ?>" alt="<?php echo $prod['nome']; ?>">
                </div>
                <div class="card-pedido-detalhes">
                    <h3><?php echo $prod['nome']; ?></h3>
                    <span class="card-pedido-preco">R$ <?php echo number_format($prod['preco'], 2, ',', '.'); ?></span>
                    <p class="card-pedido-desc"><?php echo $prod['descricao']; ?></p>
                    <span class="card-pedido-medida"><?php echo $prod['quantidade_medida']; ?></span>
                </div>
                <button class="btn-adicionar-item" onclick="adicionarAoCarrinho(<?php echo $prod['id']; ?>)">+</button>
            </div>
        <?php endforeach; ?>
    </section>

    <button class="barra-fixa-pedido" onclick="toggleGaveta()" aria-label="Visualizar pedido">
        <span class="btn-visualizar-gaveta">Visualizar o pedido</span>
        <div class="total-pedido-container">
            <span class="txt-total">Total: <strong id="total-valor">R$ 0,00</strong></span>
            <div class="icone-carrinho-pedido">
                <span>🛒</span>
                <span class="badge-contador" id="carrinho-contador">0</span>
            </div>
        </div>
    </button>

    <div class="gaveta-carrinho" id="gavetaCarrinho">
        <div class="gaveta-header">
            <h3>Seu Pedido</h3>
            <button class="btn-fechar-gaveta" onclick="toggleGaveta()">✕</button>
        </div>
        
        <div class="gaveta-corpo" id="itens-carrinho-lista">
            <p class="carrinho-vazio-txt">Nenhum item selecionado ainda.</p>
        </div>

        <div class="gaveta-footer" id="gavetaFooter" style="display: none; padding: 15px 24px; border-top: 1px solid #f0f0f0;">
            <?php if(isset($_SESSION["id_cliente"])): ?>
                <a href="finalizar_pedido.php" class="btn-finalizar-compra">Finalizar Pedido</a>
            <?php else: ?>
                <a href="login.php" class="btn-finalizar-compra login-alerta">Faça login para finalizar</a>
            <?php endif; ?>
        </div>
    </div>
    <div class="gaveta-overlay" id="gavetaOverlay" onclick="toggleGaveta()"></div>

</main>

<script>
function scrollCategorias(direcao) {
    const scrollMenu = document.getElementById('categoriasScroll');
    scrollMenu.scrollBy({ left: 150 * direcao, behavior: 'smooth' });
}

function filtrarProdutosComJS(elemento, categoriaId) {
    document.querySelectorAll('.categoria-topo-item').forEach(item => item.classList.remove('cat-ativa'));
    elemento.classList.add('cat-ativa');

    const cards = document.querySelectorAll('.card-item-pedido');
    cards.forEach(card => {
        const categoriaDoCard = card.getAttribute('data-categoria');
        if (categoriaId === 'todos' || categoriaDoCard === categoriaId) {
            card.style.display = 'flex';
        } else {
            card.style.display = 'none';
        }
    });
}

function toggleGaveta() {
    document.getElementById('gavetaCarrinho').classList.toggle('aberta');
    document.getElementById('gavetaOverlay').classList.toggle('ativo');
}

let carrinho = [];

function adicionarAoCarrinho(id) {
    const botaoClicado = event.target;
    const cardProduto = botaoClicado.closest('.card-item-pedido');
    
    const nome = cardProduto.querySelector('.card-pedido-detalhes h3').innerText;
    const precoTexto = cardProduto.querySelector('.card-pedido-preco').innerText;
    const fotoSrc = cardProduto.querySelector('.card-pedido-foto img').src;
    
    const preco = parseFloat(precoTexto.replace('R$', '').replace('.', '').replace(',', '.').trim());
    const itemExistente = carrinho.find(item => item.id === id);

    if (itemExistente) {
        itemExistente.quantidade += 1;
    } else {
        carrinho.push({
            id: id,
            nome: nome,
            preco: preco,
            foto: fotoSrc,
            quantidade: 1
        });
    }

    atualizarInterfaceCarrinho();
}

function alterarQuantidade(id, mudanca) {
    const item = carrinho.find(item => item.id === id);
    if (!item) return;

    item.quantidade += mudanca;

    if (item.quantidade <= 0) {
        carrinho = carrinho.filter(item => item.id !== id);
    }

    atualizarInterfaceCarrinho();
}

function atualizarInterfaceCarrinho() {
    const listaGaveta = document.getElementById('itens-carrinho-lista');
    const contadorBadge = document.getElementById('carrinho-contador');
    const valorTotalTexto = document.getElementById('total-valor');
    const rodapeGaveta = document.getElementById('gavetaFooter');

    listaGaveta.innerHTML = '';

    if (carrinho.length === 0) {
        listaGaveta.innerHTML = '<p class="carrinho-vazio-txt">Nenhum item selecionado ainda.</p>';
        contadorBadge.innerText = '0';
        valorTotalTexto.innerText = 'R$ 0,00';
        rodapeGaveta.style.display = 'none'; // Esconde o botão se o carrinho estiver vazio
        return;
    }

    let totalGeral = 0;
    let totalItensContador = 0;

    carrinho.forEach(item => {
        const subtotalItem = item.preco * item.quantidade;
        totalGeral += subtotalItem;
        totalItensContador += item.quantidade;

        listaGaveta.innerHTML += `
            <div class="item-revisao-carrinho" style="display:flex; align-items:center; gap:15px; margin-bottom:15px; padding-bottom:15px; border-bottom:1px solid #f2f2f2;">
                <img src="${item.foto}" style="width:50px; height:50px; object-fit:contain;">
                <div style="flex:1; text-align:left;">
                    <h4 style="margin:0; font-size:1rem; color:#000;">${item.nome}</h4>
                    <span style="font-size:0.9rem; color:#855C75; font-weight:bold;">R$ ${subtotalItem.toFixed(2).replace('.', ',')}</span>
                </div>
                <div class="controles-qtd-gaveta" style="display:flex; align-items:center; gap:10px;">
                    <button onclick="alterarQuantidade(${item.id}, -1); event.stopPropagation();" style="width:25px; height:25px; border-radius:50%; border:1px solid #000; background:none; cursor:pointer;">-</button>
                    <span style="font-weight:bold; font-size:1rem;">${item.quantidade}</span>
                    <button onclick="alterarQuantidade(${item.id}, 1); event.stopPropagation();" style="width:25px; height:25px; border-radius:50%; border:1px solid #000; background:none; cursor:pointer;">+</button>
                </div>
            </div>
        `;
    });

    contadorBadge.innerText = totalItensContador;
    valorTotalTexto.innerText = `R$ ${totalGeral.toFixed(2).replace('.', ',')}`;
    rodapeGaveta.style.display = 'block'; // Mostra o botão se houver itens
}
</script>

<?php include "includes/footer.php"; ?>