<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/cafe-umbra/config/conexao.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <?php if(isset($tituloPagina)): ?>
        <title><?= $tituloPagina; ?> | Umbra Café</title>
    <?php else: ?>
        <title>Umbra Café</title>
    <?php endif; ?>

    <link rel="icon" type="image/png" href="/cafe-umbra/assets/imgs/logo/icone.png">

    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <link rel="stylesheet" href="/cafe-umbra/assets/css/style.css">

</head>

<body>

<header class="header-principal">

    <div class="header-container">

        <a href="/cafe-umbra/index.php" class="header-logo">
            <img src="/cafe-umbra/assets/imgs/logo/umbra.png" alt="Umbra Café">
        </a>

       <button type="button" class="menu-btn" id="menuBtn" aria-label="Abrir menu">
            <i class="fa-solid fa-bars" id="iconeMenu"></i>
        </button>

        <nav class="nav-menu" id="navMenu">

            <div class="nav-links">
                <a href="/cafe-umbra/index.php">Início</a>
                <a href="/cafe-umbra/pedido.php">Pedido</a>
                <a href="/cafe-umbra/sobre.php">Sobre nós</a>
            </div>

            <div class="nav-usuario">

                <?php if(isset($_SESSION["id_cliente"])): ?>

                    <div class="usuario-menu">

                        <button type="button" class="usuario-btn" id="usuarioBtn">
                            
                            <div class="usuario-info" style="display: flex; align-items: center; gap: 8px;">
                                <i class="fa-solid fa-circle-user"></i>
                                <span>Olá, <?= htmlspecialchars($_SESSION["nome_cliente"]); ?></span>
                            </div>

                            <i class="fa-solid fa-chevron-down seta-dropdown" id="setaDropdown"></i>

                        </button>

                        <div class="usuario-dropdown" id="usuarioDropdown">
                            <a href="meus_pedidos.php">
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

                    <a href="login.php" class="btn-login">
                        <i class="fa-solid fa-user"></i>
                        Entrar
                    </a>

                <?php endif; ?>

            </div>

        </nav>
    </div>

</header>