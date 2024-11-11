from flask import Flask, request, jsonify
import joblib
import numpy as np

app = Flask(__name__)

# Cargar el modelo entrenado y el binarizador
try:
    modelo_enfermedad = joblib.load('modelo_diagnostico.pkl')  # Modelo de diagnóstico entrenado
    mlb = joblib.load('mlb.pkl')  # MultiLabelBinarizer usado para codificar síntomas
except FileNotFoundError as e:
    print("Error al cargar el modelo o el binarizador:", e)
    exit()

@app.route('/predecir', methods=['POST'])
def predecir():
    datos = request.json.get("sintomas", [])
    
    if not datos:
        return jsonify({"error": "No se proporcionaron síntomas"}), 400
    
    # Codificar los síntomas con el binarizador
    sintomas_encoded = mlb.transform([datos])  # Transformar la lista de síntomas ingresada a la representación binaria

    # Realizar la predicción
    enfermedad_predicha = modelo_enfermedad.predict(sintomas_encoded)[0]
    probabilidad_clase_predicha = np.max(modelo_enfermedad.predict_proba(sintomas_encoded)) * 100  # Obtener la probabilidad de la clase predicha

    # Retornar el resultado como JSON
    return jsonify({
        'enfermedad_predicha': str(enfermedad_predicha),
        'probabilidad': f"{probabilidad_clase_predicha:.2f}%"
    })

if __name__ == '__main__':
    app.run()
