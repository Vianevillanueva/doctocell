<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}
?>

<h3>Hola, bienvenido a Docto Cel</h3>
<button onclick="location.href='registrar_sintomas.php'">Hola, me siento un poco mal, ¿puedes ayudarme?</button>
