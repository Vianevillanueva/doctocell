<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}
?>

<h2>Bienvenido a Docto Cel</h2>
<button onclick="location.href='nuevo_chat.php'">Nuevo Chat</button>
