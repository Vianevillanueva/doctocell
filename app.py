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

    # Aquí puedes procesar los datos para que coincidan con el formato esperado por el modelo
    # Por ejemplo, convertir los síntomas en un vector como lo hicimos al entrenar el modelo
    # En este caso, asumimos que ya son compatibles con el modelo
    resultado = modelo.predict([datos])  # Ajusta el formato según tu modelo
    
    # Retornar la predicción en formato JSON
    return jsonify({"enfermedad": resultado[0]})

if __name__ == '__main__':
    app.run(debug=True)
