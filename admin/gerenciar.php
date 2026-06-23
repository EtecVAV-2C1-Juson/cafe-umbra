<?php
$tituloPagina = "Gerenciamento - Umbra Café";
include "../includes/header.php"; 

$sucesso = "";
$erro = "";

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['atualizar_status'])) {
    $id_pedido = $_POST['id_pedido'];
    $novo_status = $_POST['status_pedido']; // Recebe 'Pendente', 'Em preparo', 'Pronto' ou 'Finalizado'

    try {
        $stmt = $pdo->prepare("UPDATE pedidos SET status_pedido = ? WHERE id_pedido = ?");
        $stmt->execute([$novo_status, $id_pedido]);
        $sucesso = "Status do pedido #$id_pedido atualizado para '$novo_status'!";
    } catch (PDOException $e) {
        $erro = "Erro ao atualizar status: " . $e->getMessage();
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['adicionar_produto'])) {
    $categoria_id = intval($_POST['categoria_id']);
    $nome = trim($_POST['nome']);
    $descricao = !empty(trim($_POST['descricao'])) ? trim($_POST['descricao']) : null;
    $preco = str_replace(',', '.', trim($_POST['preco']));
    $quantidade_medida = !empty(trim($_POST['quantidade_medida'])) ? trim($_POST['quantidade_medida']) : null;
    
    $imagem = !empty(trim($_POST['imagem'])) ? trim($_POST['imagem']) : "default.png"; 

    if (!empty($nome) && !empty($preco)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO produtos (categoria_id, nome, descricao, preco, imagem, quantidade_medida) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$categoria_id, $nome, $descricao, $preco, $imagem, $quantidade_medida]);
            $sucesso = "Produto '$nome' cadastrado com sucesso no cardápio!";
        } catch (PDOException $e) {
            $erro = "Erro ao salvar produto: " . $e->getMessage();
        }
    } else {
        $erro = "Por favor, preencha os campos obrigatórios (Nome e Preço).";
    }
}

try {
    $stmtPedidos = $pdo->query("SELECT id_pedido, id_cliente, horario_retirada, total_pedido, status_pedido, data_pedido FROM pedidos ORDER BY data_pedido DESC, id_pedido DESC");
    $listaPedidos = $stmtPedidos->fetchAll(PDO::FETCH_ASSOC);

    $stmtItens = $pdo->query("SELECT id_pedido, nome_produto, quantidade, preco_unitario FROM itens_pedido");
    $todosItens = $stmtItens->fetchAll(PDO::FETCH_ASSOC);
    
    $itensPorPedido = [];
    foreach ($todosItens as $item) {
        $itensPorPedido[$item['id_pedido']][] = $item;
    }
} catch (PDOException $e) {
    $erro = "Erro Crítico de Banco de Dados: " . $e->getMessage();
}
?>

<main class="admin-principal">
    <div class="admin-container">
        <div class="admin-header-titulo">
            <h2>Painel de Gerenciamento</h2>
            <p>Controle de pedidos e atualização do cardápio em tempo real.</p>
        </div>

        <?php if (!empty($sucesso)): ?>
            <div class="admin-alerta sucesso"><i class="fa-solid fa-circle-check"></i> <?= htmlspecialchars($sucesso); ?></div>
        <?php endif; ?>
        <?php if (!empty($erro)): ?>
            <div class="admin-alerta erro"><i class="fa-solid fa-circle-exclamation"></i> <?= htmlspecialchars($erro); ?></div>
        <?php endif; ?>

        <div class="admin-abas-menu">
            <button class="aba-btn ativa" onclick="alternarAba(event, 'aba-pedidos')">
                <i class="fa-solid fa-receipt"></i> Pedidos dos Clientes
            </button>
            <button class="aba-btn" onclick="alternarAba(event, 'aba-cardapio')">
                <i class="fa-solid fa-mug-hot"></i> Adicionar ao Cardápio
            </button>
        </div>

        <div id="aba-pedidos" class="aba-conteudo ativa">
            <h3>Pedidos Recentes</h3>
            <?php if (empty($listaPedidos)): ?>
                <p class="admin-vazio-txt">Nenhum pedido encontrado no banco de dados.</p>
            <?php else: ?>
                <div class="admin-grid-pedidos">
                    <?php foreach ($listaPedidos as $pedido): ?>
                        <div class="admin-card-pedido status-box-<?= strtolower(str_replace(' ', '-', $pedido['status_pedido'])); ?>">
                            <div class="admin-card-header">
                                <span>Pedido <strong>#<?= $pedido['id_pedido']; ?></strong></span>
                                <span class="admin-data"><?= date('d/m/Y H:i', strtotime($pedido['data_pedido'])); ?></span>
                            </div>
                            
                            <div class="admin-card-corpo">
                                <p><strong>ID do Cliente:</strong> <?= htmlspecialchars($pedido['id_cliente']); ?></p>
                                <p><strong>Horário de Retirada:</strong> <span class="admin-hora-destaque"><?= date('H:i', strtotime($pedido['horario_retirada'])); ?></span></p>
                                
                                <div class="admin-produtos-comprados">
                                    <strong>Itens do Pedido:</strong>
                                    <ul>
                                        <?php if (isset($itensPorPedido[$pedido['id_pedido']])): ?>
                                            <?php foreach ($itensPorPedido[$pedido['id_pedido']] as $item): ?>
                                                <li><?= $item['quantidade']; ?>x <?= htmlspecialchars($item['nome_produto']); ?> <small>(R$ <?= number_format($item['preco_unitario'], 2, ',', '.'); ?>/un)</small></li>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <li style="color: #999; font-style: italic;">Nenhum item registrado para este pedido</li>
                                        <?php endif; ?>
                                    </ul>
                                </div>
                            </div>

                            <div class="admin-card-footer">
                                <div class="admin-total-box">
                                    <span>Total:</span>
                                    <strong>R$ <?= number_format($pedido['total_pedido'], 2, ',', '.'); ?></strong>
                                </div>
                                
                                <form action="gerenciar.php" method="POST" class="admin-form-status">
                                    <input type="hidden" name="id_pedido" value="<?= $pedido['id_pedido']; ?>">
                                    <select name="status_pedido" onchange="this.form.submit()">
                                        <option value="Pendente" <?= $pedido['status_pedido'] === 'Pendente' ? 'selected' : ''; ?>>Pendente</option>
                                        <option value="Em preparo" <?= $pedido['status_pedido'] === 'Em preparo' ? 'selected' : ''; ?>>Em preparo</option>
                                        <option value="Pronto" <?= $pedido['status_pedido'] === 'Pronto' ? 'selected' : ''; ?>>Pronto</option>
                                        <option value="Finalizado" <?= $pedido['status_pedido'] === 'Finalizado' ? 'selected' : ''; ?>>Finalizado</option>
                                    </select>
                                    <input type="hidden" name="atualizar_status" value="1">
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <div id="aba-cardapio" class="aba-conteudo">
            <div class="admin-box-formulario">
                <h3>Cadastrar Novo Produto</h3>
                <form action="gerenciar.php" method="POST" class="form-admin-cadastro">
                    
                    <div class="admin-campo-grupo">
                        <label for="nome">Nome do Produto *</label>
                        <input type="text" id="nome" name="nome" placeholder="Ex: Cappuccino de Canela" required>
                    </div>

                    <div class="admin-form-linha-dupla">
                        <div class="admin-campo-grupo">
                            <label for="categoria_id">Categoria *</label>
                            <select id="categoria_id" name="categoria_id" required>
                                <option value="1">1 - Cafés Tradicionais</option>
                                <option value="2">2 - Chás</option>
                                <option value="3">3 - Cafés Gourmets</option>
                                <option value="4">4 - Bebidas</option>
                                <option value="5">5 - Cookies</option>
                                <option value="6">6 - Salgados</option>
                            </select>
                        </div>

                        <div class="admin-campo-grupo">
                            <label for="quantidade_medida">Quantidade / Medida</label>
                            <input type="text" id="quantidade_medida" name="quantidade_medida" placeholder="Ex: 350ml, 120g, 1 Unidade">
                        </div>
                    </div>

                    <div class="admin-form-linha-dupla">
                        <div class="admin-campo-grupo">
                            <label for="preco">Preço de Venda (R$) *</label>
                            <input type="text" id="preco" name="preco" placeholder="Ex: 14,50" required>
                        </div>
                        
                        <div class="admin-campo-grupo">
                            <label for="imagem">Nome do arquivo da Imagem</label>
                            <input type="text" id="imagem" name="imagem" placeholder="Ex: cappuccino.png (ou deixe vazio para padrão)">
                        </div>
                    </div>

                    <div class="admin-campo-grupo">
                        <label for="descricao">Descrição do Item</label>
                        <textarea id="descricao" name="descricao" rows="4" placeholder="Detalhes dos ingredientes, se contém lactose, etc..."></textarea>
                    </div>

                    <button type="submit" name="adicionar_produto" class="btn-admin-salvar">
                        <i class="fa-solid fa-plus"></i> Adicionar ao Cardápio
                    </button>
                </form>
            </div>
        </div>

    </div>
</main>

<script>
function alternarAba(event, abaId) {
    document.querySelectorAll('.aba-conteudo').forEach(aba => aba.classList.remove('ativa'));
    document.querySelectorAll('.aba-btn').forEach(btn => btn.classList.remove('ativa'));
    document.getElementById(abaId).classList.add('ativa');
    event.currentTarget.classList.add('ativa');
}
</script>

<?php include "../includes/footer.php"; ?>