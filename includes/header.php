<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Umbra Café</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <link rel="stylesheet" href="assets/css/estilo.css">

    <script src="assets/js/header.js"></script>

</head>

<body>

<header class="header-principal">

    <div class="header-container">

        <!-- Logo -->
        <a href="index.php" class="header-logo">
            <img src="assets/imgs/umbra.png" alt="Logo da Umbra Café">
        </a>

        <!-- Menu Mobile -->
        <input type="checkbox" id="menu-toggle" class="menu-checkbox">

        <label for="menu-toggle" class="menu-btn">
            <i class="fa-solid fa-bars"></i>
        </label>

        <!-- Navegação -->
        <nav class="nav-menu">

            <a href="index.php">Início</a>

            <a href="cardapio.php">Cardápio</a>

            <a href="pedido.php">Pedido</a>

            <a href="sobre.php">Sobre nós</a>

            <?php if(isset($_SESSION["id_cliente"])): ?>

                <!-- Usuário logado -->
                <div class="usuario-menu">

                    <button class="usuario-btn" id="usuarioBtn">

                        <span>
                            Olá,
                            <?= htmlspecialchars($_SESSION["nome_cliente"]); ?>
                        </span>

                        <i class="fa-solid fa-chevron-down seta-dropdown"></i>

                    </button>

                    <div class="usuario-dropdown" id="usuarioDropdown" aria-hidden="true">

                        <a href="meus pedidos.php">
                            <i class="fa-solid fa-receipt"></i>
                            Meus pedidos
                        </a>

                        <a href="logout.php">
                            <i class="fa-solid fa-right-from-bracket"></i>
                            Sair
                        </a>

                    </div>

                </div>

            <?php else: ?>

                <!-- Não logado -->
                <a href="login.php" class="btn-login">
                    Entrar
                </a>

            <?php endif; ?>

        </nav>

    </div>

</header>