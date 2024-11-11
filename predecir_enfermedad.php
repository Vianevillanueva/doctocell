<?php
session_start();
$sintomas = isset($_SESSION['sintomas']) ? $_SESSION['sintomas'] : [];

if (!empty($sintomas)) {
    // URL de la API de Google Colab expuesta a través de ngrok
    $api_url = 'https://d36b-34-125-105-218.ngrok-free.ap/predecir'; // Reemplaza con la URL generada por ngrok

    // Preparar los datos en formato JSON
    $datos = json_encode(['datos' => [implode(' ', $sintomas)]]);

    // Configurar la solicitud HTTP
    $options = [
        'http' => [
            'header' => "Content-type: application/json\r\n",
            'method' => 'POST',
            'content' => $datos,
            'timeout' => 10, // Incrementar el tiempo de espera si es necesario
        ],
    ];

    // Crear un contexto de flujo para la solicitud
    $context = stream_context_create($options);

    // Enviar la solicitud a la API
    $result = @file_get_contents($api_url, false, $context);

    if ($result === FALSE) {
        echo "Error al conectar con el servicio de predicción. Verifica que la API esté en ejecución y accesible.";
    } else {
        $respuesta = json_decode($result, true);
        if (isset($respuesta['enfermedad'])) {
            echo "<h2>La enfermedad más probable es: " . htmlspecialchars($respuesta['enfermedad']) . "</h2>";
        } else {
            echo "No se pudo obtener una predicción válida.";
        }
    }
} else {
    echo "No se han proporcionado suficientes datos de síntomas.";
}
?>

