<?php
session_start();
if (isset($_SESSION['usuario_id'])) {
    header("Location: home.php");
    exit();
}
?>

<h2>Bienvenido a Docto Cel</h2>
<button onclick="location.href='iniciar_sesion.php'">Iniciar Sesión</button>
<button onclick="location.href='register.php'">Registrarme</button>
