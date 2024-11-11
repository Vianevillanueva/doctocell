<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombreUsuario = $_POST['nombre_usuario'];
    $contraseña = password_hash($_POST['contraseña'], PASSWORD_DEFAULT);

    $conn = new mysqli('localhost', 'root', '', 'docto');
    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("INSERT INTO usuarios (nombre_usuario, contraseña) VALUES (?, ?)");
    $stmt->bind_param("ss", $nombreUsuario, $contraseña);

    if ($stmt->execute()) {
        session_start();
        $_SESSION['usuario_id'] = $conn->insert_id; // Guardar el ID de usuario en la sesión
        header("Location: home.php"); // Redirigir a home.php
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>

<h2>Registro de Usuario</h2>
<form method="post" action="">
    <input type="text" name="nombre_usuario" placeholder="Nombre de usuario" required>
    <input type="password" name="contraseña" placeholder="Contraseña" required>
    <button type="submit">Registrarse</button>
</form>
