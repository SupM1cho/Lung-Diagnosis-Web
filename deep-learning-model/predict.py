# predict.py

from flask import Flask, request, jsonify
import tensorflow as tf
import numpy as np
from PIL import Image
import io
import json

app = Flask(__name__)

# ========== CONFIGURATION ==========
MODEL_PATH = 'multimodal_medical_v3_auc0.7586_20250805_134028.h5'
LABELS = [
    "Cardiomegaly", "Infiltration", "Effusion", "Fibrosis", "Consolidation",
    "Emphysema", "Atelectasis", "Bronchitis", "Mass", "Pneumothorax", "Bronkiektasis"
]
SYMPTOM_KEYS = [
    "Batuk", "Sesak_napas", "Nyeri_dada", "Demam", "Wheezing", "Sianosis",
    "Penurunan_suara_napas", "Palpitasi", "Edema", "Kelelahan_penurunan_bb",
    "Batuk_darah", "Suara_usus_dada", "Kembung_perut"
]

# ========== LOAD MODEL ==========
model = tf.keras.models.load_model(MODEL_PATH, compile=False)

# ========== UTILITY FUNCTIONS ==========
def preprocess_image(image_bytes):
    img = Image.open(io.BytesIO(image_bytes)).convert("RGB")
    img = img.resize((224, 224))
    img_array = np.array(img) / 255.0
    return np.expand_dims(img_array, axis=0)

def preprocess_symptoms(symptoms_list):
    symptoms_vector = [1.0 if key.lower() in [s.lower() for s in symptoms_list] else 0.0 for key in SYMPTOM_KEYS]
    return np.expand_dims(np.array(symptoms_vector, dtype=np.float32), axis=0)

# ========== ROUTES ==========
@app.route('/predict', methods=['POST'])
def predict():
    if 'xray_image' not in request.files:
        return jsonify({'error': 'No image uploaded'}), 400

    image_file = request.files['xray_image']
    symptoms = request.form.getlist('symptoms[]') or []

    try:
        img_tensor = preprocess_image(image_file.read())
        symptom_tensor = preprocess_symptoms(symptoms)
        preds = model.predict([img_tensor, symptom_tensor])[0]

        # Ambang batas deteksi (bisa diatur sesuai kebutuhan)
        threshold = 0.5
        diagnosis = [label for i, label in enumerate(LABELS) if preds[i] >= threshold]

        return jsonify({
            'diagnosis': diagnosis,
            'probabilities': {LABELS[i]: float(preds[i]) for i in range(len(LABELS))}
        })

    except Exception as e:
        return jsonify({'error': str(e)}), 500

# ========== MAIN ==========
if __name__ == '__main__':
    app.run(host='0.0.0.0', port=5000)
