<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$_SESSION = array();
session_destroy();

// Verifica se existe a informação de qual era a página anterior
if (isset($_SERVER['HTTP_REFERER']) && !empty($_SERVER['HTTP_REFERER'])) {
    // Redireciona o usuário exatamente para onde ele estava
    header("Location: " . $_SERVER['HTTP_REFERER']);
} else {
    // Caso o navegador não consiga descobrir a página anterior, vai para a index por segurança
    header("Location: index.php");
}
exit;
?>