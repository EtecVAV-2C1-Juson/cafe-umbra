<?php
$tituloPagina = "Finalizar Pedido"; 
include "includes/header.php";

if (!isset($_SESSION["id_cliente"])) {
    header("Location: login.php");
    exit;
}

$erro = "";
$sucesso = false;
$novoIdPedido = null;
$diaDaSemana = date('N'); 
$horarioAtual = date('H:i');

if ($diaDaSemana >= 6) {
    $horaMin = "09:00";
    $horaMax = "18:00";
    $textoHorario = "Sábados e Domingos funcionamos das 09:00 às 18:00.";
} else {
    $horaMin = "08:00";
    $horaMax = "17:00";
    $textoHorario = "De segunda a sexta funcionamos das 08:00 às 17:00.";
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['concluir_pedido'])) {
    $id_cliente = $_SESSION["id_cliente"];
    $horario_retirada = trim($_POST["horario_retirada"]);
    $carrinhoJSON = $_POST["carrinho_dados"];
    $itens = json_decode($carrinhoJSON, true);

    if ($horario_retirada < $horaMin || $horario_retirada > $horaMax) {
        $erro = "Horário inválido para hoje! " . $textoHorario;
    }

    if (empty($erro) && !empty($horario_retirada) && !empty($itens)) {
        try {
            $pdo->beginTransaction();
            $total_pedido = 0;
            foreach ($itens as $item) {
                $total_pedido += $item['preco'] * $item['quantidade'];
            }

            $status_pedido = "Recebido";
            $data_pedido = date('Y-m-d');
            $stmtPedido = $pdo->prepare("INSERT INTO pedidos (id_cliente, horario_retirada, total_pedido, status_pedido, data_pedido) VALUES (?, ?, ?, ?, ?)");
            $stmtPedido->execute([$id_cliente, $horario_retirada, $total_pedido, $status_pedido, $data_pedido]);
            $novoIdPedido = $pdo->lastInsertId();
            $stmtItem = $pdo->prepare("INSERT INTO itens_pedido (id_pedido, nome_produto, quantidade, preco_unitario) VALUES (?, ?, ?, ?)");
            
            foreach ($itens as $item) {
                $stmtItem->execute([
                    $novoIdPedido,
                    $item['nome'],
                    $item['quantidade'],
                    $item['preco']
                ]);
            }

            $pdo->commit();
            $sucesso = true;
        } catch (PDOException $e) {
            $pdo->rollBack();
            $erro = "Erro ao processar o pedido. Tente novamente.";
        }
    } elseif (empty($erro)) {
        $erro = "Por favor, selecione o horário de retirada ou verifique seu carrinho.";
    }
}
?>

<main class="checkout-principal">
    <div class="checkout-container">
        <h2>Finalizar seu Pedido</h2>

        <?php if ($sucesso): ?>
            <div class="checkout-sucesso-box">
                <i class="fa-solid fa-circle-check"></i>
                <h3>Pedido realizado com sucesso!</h3>
                <p>O número do seu pedido é <strong>#<?= $novoIdPedido; ?></strong>.</p>
                <p>Te esperamos no balcão no horário selecionado!</p>
                <a href="pedido.php" class="btn-checkout-acao" onclick="limparCarrinhoLocal()">Voltar ao Cardápio</a>
            </div>
        <?php else: ?>

            <?php if (!empty($erro)): ?>
                <div class="login-alerta-erro"><i class="fa-solid fa-circle-exclamation"></i> <?= htmlspecialchars($erro); ?></div>
            <?php endif; ?>

            <div class="checkout-layout">
                <div class="checkout-resumo">
                    <h3>Resumo do Pedido</h3>
                    <div id="checkout-lista-itens"></div>
                    <div class="checkout-total">
                        <span>Total a pagar:</span>
                        <strong id="checkout-total-valor">R$ 0,00</strong>
                    </div>
                </div>

                <div class="checkout-formulario">
    <h3>Detalhes da Retirada</h3>
    <form action="finalizar_pedido.php" method="POST" id="form-checkout" onsubmit="prepararEnvio()">
        
        <input type="hidden" id="carrinho_dados" name="carrinho_dados">

        <div class="form-grupo">
            <label for="horario_retirada">Que horas você vai passar para retirar?</label>
            <div class="input-hora-wrapper" onclick="abrirRelogio()">
                <i class="fa-solid fa-clock icon-relogio-campo"></i>
                <input type="time" id="horario_retirada" name="horario_retirada" min="<?= $horaMin; ?>" max="<?= $horaMax; ?>" required>
            </div>
        </div>

        <div class="checkout-aviso-importante">
            <i class="fa-solid fa-triangle-exclamation"></i>
            <p>
                <strong>Aviso importante:</strong> Hoje funcionamos das <?= $horaMin; ?> às <?= $horaMax; ?>. Após confirmar, o seu pedido será enviado direto para a nossa cozinha. Não será possível alterá-lo ou cancelá-lo pelo site.
            </p>
        </div>

        <button type="submit" name="concluir_pedido" class="btn-checkout-acao">Confirmar e Enviar Pedido</button>
    </form>
</div>

        <?php endif; ?>
    </div>
</main>

<script>
function abrirRelogio() {
    const inputHora = document.getElementById('horario_retirada');
    if (typeof inputHora.showPicker === 'function') {
        inputHora.showPicker();
    } else {
        inputHora.focus(); 
    }
}
document.addEventListener("DOMContentLoaded", () => {
    if (typeof(Storage) !== "undefined" && !<?= $sucesso ? 'true' : 'false'; ?>) {
        const carrinhoSalvo = localStorage.getItem("carrinho_umbra");
        if (carrinhoSalvo) {
            const carrinho = JSON.parse(carrinhoSalvo);
            renderizarResumoCheckout(carrinho);
        } else {
            window.location.href = "pedido.php";
        }
    }
});

function renderizarResumoCheckout(carrinho) {
    const container = document.getElementById("checkout-lista-itens");
    const totalTexto = document.getElementById("checkout-total-valor");
    
    container.innerHTML = "";
    let total = 0;

    carrinho.forEach(item => {
        const subtotal = item.preco * item.quantidade;
        total += subtotal;

        container.innerHTML += `
            <div class="item-checkout-linha">
                <span>${item.quantidade}x ${item.nome}</span>
                <strong>R$ ${subtotal.toFixed(2).replace('.', ',')}</strong>
            </div>
        `;
    });

    totalTexto.innerText = `R$ ${total.toFixed(2).replace('.', ',')}`;
}

function prepararEnvio() {
    const carrinhoSalvo = localStorage.getItem("carrinho_umbra");
    document.getElementById("carrinho_dados").value = carrinhoSalvo;
}

function limparCarrinhoLocal() {
    localStorage.removeItem("carrinho_umbra");
}
</script>

<?php include "includes/footer.php"; ?>