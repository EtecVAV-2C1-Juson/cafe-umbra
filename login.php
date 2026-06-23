<?php
$tituloPagina = "Acesso"; 
include "includes/header.php";

$erroLogin = "";
$erroCadastro = "";
$sucessoCadastro = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    
    if (isset($_POST['acao_login'])) {
        $email = trim($_POST["email"]);
        $senha = trim($_POST["senha"]);

        if (!empty($email) && !empty($senha)) {
            try {
                $stmt = $pdo->prepare("SELECT * FROM clientes WHERE email = ?");
                $stmt->execute([$email]);
                $cliente = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($cliente && $senha === $cliente['senha']) {
                    $_SESSION["id_cliente"] = $cliente["id_cliente"];
                    $_SESSION["nome_cliente"] = $cliente["nome"];
                    header("Location: pedido.php");
                    exit;
                } else {
                    $erroLogin = "E-mail ou senha incorretos.";
                }
            } catch (PDOException $e) {
                $erroLogin = "Ops! Ocorreu um problema ao acessar sua conta.";
            }
        }
    }

    if (isset($_POST['acao_cadastro'])) {
        $nome = trim($_POST["cad_nome"]);
        $email = trim($_POST["cad_email"]);
        $senha = trim($_POST["cad_senha"]);

        if (!empty($nome) && !empty($email) && !empty($senha)) {
            try {
                $stmtCheck = $pdo->prepare("SELECT id_cliente FROM clientes WHERE email = ?");
                $stmtCheck->execute([$email]);
                
                if ($stmtCheck->rowCount() > 0) {
                    $erroCadastro = "Este e-mail já está cadastrado.";
                } else {
                    $stmtIns = $pdo->prepare("INSERT INTO clientes (nome, email, senha) VALUES (?, ?, ?)");
                    $stmtIns->execute([$nome, $email, $senha]);
                    $sucessoCadastro = "Cadastro realizado! Agora faça seu login.";
                }
            } catch (PDOException $e) {
                $erroCadastro = "Erro ao realizar o cadastro. Verifique os dados e tente novamente.";
            }
        }
    }
}
?>

<main class="login-principal">
    <div class="login-card">
        
        <div id="secao-login">
            <h2>Entrar no Umbra Café</h2>
            <p class="login-subtitulo">Acesse sua conta para finalizar seus pedidos.</p>

            <?php if (!empty($erroLogin)): ?>
                <div class="login-alerta-erro"><i class="fa-solid fa-circle-exclamation"></i> <?= htmlspecialchars($erroLogin); ?></div>
            <?php endif; ?>
            <?php if (!empty($sucessoCadastro)): ?>
                <div class="login-alerta-sucesso" style="background:#EBF7ED; color:#2E7D32; padding:12px; border-radius:8px; font-size:0.85rem; margin-bottom:20px; border:1px solid #C8E6C9;"><i class="fa-solid fa-circle-check"></i> <?= htmlspecialchars($sucessoCadastro); ?></div>
            <?php endif; ?>

            <form action="login.php" method="POST" class="login-form">
                <div class="form-grupo">
                    <label for="email">E-mail</label>
                    <input type="email" id="email" name="email" placeholder="seuemail@exemplo.com" required>
                </div>
                <div class="form-grupo">
                    <label for="senha">Senha</label>
                    <input type="password" id="senha" name="senha" placeholder="Digite sua senha" required>
                </div>
                <button type="submit" name="acao_login" class="btn-enviar-login">Entrar</button>
            </form>

            <div class="login-footer">
                <p>Não tem uma conta? <a href="#" onclick="alternarTela(event, 'cadastro')">Cadastre-se aqui</a></p>
            </div>
        </div>

        <div id="secao-cadastro" style="display: none;">
            <h2>Criar Conta</h2>
            <p class="login-subtitulo">Cadastre-se rápido para salvar seus pedidos.</p>

            <?php if (!empty($erroCadastro)): ?>
                <div class="login-alerta-erro"><i class="fa-solid fa-circle-exclamation"></i> <?= htmlspecialchars($erroCadastro); ?></div>
            <?php endif; ?>

            <form action="login.php" method="POST" class="login-form">
                <div class="form-grupo">
                    <label for="cad_nome">Nome Completo</label>
                    <input type="text" id="cad_nome" name="cad_nome" placeholder="Seu nome" required>
                </div>
                <div class="form-grupo">
                    <label for="cad_email">E-mail</label>
                    <input type="email" id="cad_email" name="cad_email" placeholder="seuemail@exemplo.com" required>
                </div>
                <div class="form-grupo">
                    <label for="cad_senha">Senha</label>
                    <input type="password" id="cad_senha" name="cad_senha" placeholder="Crie uma senha" required>
                </div>
                <button type="submit" name="acao_cadastro" class="btn-enviar-login">Cadastrar</button>
            </form>

            <div class="login-footer">
                <p>Já possui uma conta? <a href="#" onclick="alternarTela(event, 'login')">Entrar aqui</a></p>
            </div>
        </div>

    </div>
</main>

<script>
function alternarTela(event, tela) {
    event.preventDefault();
    
    const secaoLogin = document.getElementById('secao-login');
    const secaoCadastro = document.getElementById('secao-cadastro');

    if (tela === 'cadastro') {
        secaoLogin.style.display = 'none';
        secaoCadastro.style.display = 'block';
    } else {
        secaoLogin.style.display = 'block';
        secaoCadastro.style.display = 'none';
    }
}

<?php if (!empty($erroCadastro)): ?>
    document.getElementById('secao-login').style.display = 'none';
    document.getElementById('secao-cadastro').style.display = 'block';
<?php endif; ?>
</script>

<?php include "includes/footer.php"; ?>