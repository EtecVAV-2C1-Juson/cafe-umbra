<?php
$tituloPagina = "Meus Pedidos"; 
include "includes/header.php";


if (!isset($_SESSION["id_cliente"])) {
    header("Location: login.php");
    exit;
}

$id_cliente = $_SESSION["id_cliente"];
$pedidos = [];

try {
    
    $stmt = $pdo->prepare("SELECT id_pedido, horario_retirada, total_pedido, status_pedido, data_pedido FROM pedidos WHERE id_cliente = ? ORDER BY data_pedido DESC, id_pedido DESC");
    $stmt->execute([$id_cliente]);
    $pedidos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $erro = "Erro ao carregar seus pedidos.";
}
?>

<main class="pedidos-usuario-principal">
    <div class="pedidos-container">
        <h2>Meus Pedidos</h2>
        <p class="pedidos-subtitulo">Acompanhe o histórico das suas delícias no Umbra Café.</p>

        <?php if (!empty($erro)): ?>
            <div class="login-alerta-erro"><i class="fa-solid fa-circle-exclamation"></i> <?= htmlspecialchars($erro); ?></div>
        <?php endif; ?>

        <?php if (empty($pedidos)): ?>
            <div class="pedidos-vazio">
                <i class="fa-solid fa-receipt icon-vazio"></i>
                <p>Você ainda não fez nenhum pedido.</p>
                <a href="pedido.php" class="btn-ir-cardapio">Ir para o Cardápio</a>
            </div>
        <?php else: ?>
            <div class="lista-historico-pedidos">
                <?php foreach ($pedidos as $pedido): ?>
                    <div class="card-pedido-historico">
                        
                        <div class="header-card-pedido">
                            <span>Pedido <strong>#<?= $pedido['id_pedido']; ?></strong></span>
                            <span class="data-pedido"><?= date('d/m/Y', strtotime($pedido['data_pedido'])); ?></span>
                        </div>
                        
                        <div class="corpo-card-pedido">
                            <p class="horario-entrega">
                                <i class="fa-solid fa-clock"></i> 
                                Retirada agendada para às: <strong><?= date('H:i', strtotime($pedido['horario_retirada'])); ?></strong>
                            </p>
                            <p class="status-pedido">
                                Status: <span class="badge-status status-<?= strtolower($pedido['status_pedido']); ?>"><?= htmlspecialchars($pedido['status_pedido']); ?></span>
                            </p>
                        </div>

                        <div class="footer-card-pedido">
                            <span>Total do Pedido</span>
                            <strong class="total-pedido-valor">R$ <?= number_format($pedido['total_pedido'], 2, ',', '.'); ?></strong>
                        </div>
                        
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</main>

<?php include "includes/footer.php"; ?>