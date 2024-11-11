import os
import joblib
import mysql.connector
from flask import Flask, request, jsonify
from mysql.connector import Error
from datetime import datetime

# Inicializar la aplicación Flask
app = Flask(__name__)

# Cargar el modelo al iniciar la API
modelo = joblib.load('modelo.pkl')  # Asegúrate de que el archivo modelo.pkl esté en la ruta correcta

# Conectar a la base de datos MySQL
def get_db_connection():
    try:
        connection = mysql.connector.connect(
            host=os.environ.get('DB_HOST', 'localhost'),  # Usa tus credenciales de la base de datos
            user=os.environ.get('DB_USER', 'root'),
            password=os.environ.get('DB_PASSWORD', ''),
            database='docto'  # Nombre de tu base de datos
        )
        return connection
    except Error as e:
        print(f"Error al conectar a la base de datos: {e}")
        return None

# Ruta raíz para bienvenida
@app.route('/')
def home():
    return "Bienvenido a la API de predicción. Usa /predecir para hacer una predicción."

# Endpoint para registrar síntomas y hacer la predicción
@app.route('/predecir', methods=['POST'])
def predecir():
    # Obtener los datos de síntomas del JSON enviado
    datos = request.json.get('datos', [])
    usuario_id = request.json.get('usuario_id', None)
    
    if not datos or not usuario_id:
        return jsonify({"error": "No se proporcionaron datos o usuario_id"}), 400

    # Conectar a la base de datos para registrar los síntomas
    connection = get_db_connection()
    if connection:
        cursor = connection.cursor()

        # Convertir los síntomas a un formato adecuado para guardar en la base de datos (por ejemplo, una cadena)
        sintomas_str = ', '.join(map(str, datos))  # Convertir la lista a una cadena separada por comas
        
        # Obtener la fecha y hora actual para el campo fecha_registro
        fecha_registro = datetime.now().strftime('%Y-%m-%d %H:%M:%S')
        
        # Insertar los síntomas en la base de datos
        cursor.execute(
            "INSERT INTO sintomas (usuario_id, fecha_registro, sintomas) VALUES (%s, %s, %s)",
            (usuario_id, fecha_registro, sintomas_str)
        )
        connection.commit()  # Confirmar el cambio
        cursor.close()
        connection.close()
    else:
        return jsonify({"error": "No se pudo conectar a la base de datos."}), 500

    # Procesar los datos y hacer la predicción
    resultado = modelo.predict([datos])  # Ajusta el formato según tu modelo
    
    # Retornar la predicción en formato JSON
    return jsonify({"enfermedad": resultado[0]})

if __name__ == '__main__':
    # Obtener el puerto desde el entorno de Render o usar el valor por defecto 5000
    port = int(os.environ.get('PORT', 5001))  # 5000 es el valor por defecto
    app.run(host='0.0.0.0', port=port)  # Escuchar en todas las direcciones (0.0.0.0) y en el puerto asignado

