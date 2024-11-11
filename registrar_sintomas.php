<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $sintomasSeleccionados = isset($_POST['sintomas']) ? $_POST['sintomas'] : [];
    $sintomaExtra = isset($_POST['sintoma_extra']) ? trim($_POST['sintoma_extra']) : "";

    if (!empty($sintomaExtra)) {
        $sintomasSeleccionados[] = $sintomaExtra;
    }

    // Guardar los datos en variables de sesión en lugar de la URL
    $_SESSION['sintomas'] = $sintomasSeleccionados;

    // Redirigir a la página de predicción
    header("Location: predecir_enfermedad.php");
    exit();
}
?>

<h2>Registrar Síntomas</h2>
<form method="post" action="">
    <label for="sintomas">Seleccione sus síntomas:</label><br>
    <input type="checkbox" name="sintomas[]" value="tos"> Tos<br>
    <input type="checkbox" name="sintomas[]" value="dolor muscular"> Dolor muscular<br>
    <input type="checkbox" name="sintomas[]" value="nauseas"> Náuseas<br>
    <input type="checkbox" name="sintomas[]" value="cansancio"> Cansancio<br>
    <input type="checkbox" name="sintomas[]" value="fiebre"> Fiebre<br>
    <input type="checkbox" id="otroCheckbox" name="sintomas[]" value="otro" onclick="toggleOtro()"> Otro<br>
    <input type="text" id="sintomaExtra" name="sintoma_extra" placeholder="Especifique otro síntoma" disabled><br><br>
    <button type="submit">Registrar</button>
</form>

<script>
function toggleOtro() {
    var checkbox = document.getElementById('otroCheckbox');
    var inputExtra = document.getElementById('sintomaExtra');
    inputExtra.disabled = !checkbox.checked;
}
</script>
