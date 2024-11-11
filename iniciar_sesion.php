<?php
session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombreUsuario = $_POST['nombre_usuario'];
    $contraseña = $_POST['contraseña'];

    $conn = new mysqli('localhost', 'root', '', 'docto');
    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("SELECT id, contraseña FROM usuarios WHERE nombre_usuario = ?");
    $stmt->bind_param("s", $nombreUsuario);
    $stmt->execute();
    $stmt->bind_result($id, $hashedPassword);
    $stmt->fetch();

    if ($hashedPassword && password_verify($contraseña, $hashedPassword)) {
        $_SESSION['usuario_id'] = $id;
        header("Location: home.php"); // Redirigir a home.php
    } else {
        echo "Credenciales incorrectas.";
    }

    $stmt->close();
    $conn->close();
}
?>

<h2>Iniciar Sesión</h2>
<form method="post" action="">
    <input type="text" name="nombre_usuario" placeholder="Nombre de usuario" required>
    <input type="password" name="contraseña" placeholder="Contraseña" required>
    <input type="password" name="contraseña" placeholder="Contraseña" required>
    <input type="password" name="contraseña" placeholder="Contraseña" required>
    <button type="submit">Iniciar sesión</button>
</form>
