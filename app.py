import os
from flask import Flask, request, jsonify
import joblib

# Inicializar la aplicación Flask
app = Flask(__name__)

# Cargar el modelo al iniciar la API
modelo = joblib.load('modelo.pkl')

# Definir un endpoint para predicciones
@app.route('/predecir', methods=['POST'])
def predecir():
    # Obtener los datos de síntomas del JSON enviado
    datos = request.json.get('datos', [])
    if not datos:
        return jsonify({"error": "No se proporcionaron datos"}), 400

    # Procesar los datos y hacer la predicción
    resultado = modelo.predict([datos])  # Ajusta el formato según tu modelo

    # Retornar la predicción
    return jsonify({"enfermedad": resultado[0]})

if __name__ == '__main__':
    # Obtener el puerto desde el entorno de Render
    port = int(os.environ.get('PORT', 5001))  # 5000 es el valor por defecto
    app.run(host='0.0.0.0', port=port)  # Escuchar en 0.0.0.0 y en el puerto asignado por Render
