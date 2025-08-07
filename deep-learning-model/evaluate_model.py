import os
import csv
import numpy as np
from PIL import Image
import tensorflow as tf

# === Konfigurasi Model & Label ===
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
TEST_DIR = "test_images"
OUTPUT_CSV = "evaluation_results.csv"

# === Load model ===
model = tf.keras.models.load_model(MODEL_PATH, compile=False)

# === Preprocessing functions ===
def preprocess_image(image_path):
    img = Image.open(image_path).convert("RGB")
    img = img.resize((224, 224))
    img_array = np.array(img) / 255.0
    return np.expand_dims(img_array, axis=0)

def preprocess_symptoms(symptom_list):
    return np.expand_dims(np.array([
        1.0 if key.lower() in [s.lower() for s in symptom_list] else 0.0
        for key in SYMPTOM_KEYS
    ], dtype=np.float32), axis=0)

# === Simulasi kasus uji ===
test_cases = {
    "1.png": ["Batuk", "Demam"],
    "2.png": ["Sesak_napas", "Nyeri_dada"],
    "3.png": [],
    "4.png": ["Batuk_darah", "Edema"]
}

# === Evaluasi dan Simpan CSV ===
results = []

print("📁 Isi folder test_images:", os.listdir("test_images"))

for filename, symptoms in test_cases.items():
    path = os.path.join(TEST_DIR, filename)
    if not os.path.exists(path):
        print(f"❌ Gambar tidak ditemukan: {filename}")
        continue

    try:
        xray_input = preprocess_image(path)
        symptom_input = preprocess_symptoms(symptoms)
        preds = model.predict([xray_input, symptom_input])[0]

        for i, label in enumerate(LABELS):
            results.append({
                "File": filename,
                "Gejala": ", ".join(symptoms) if symptoms else "-",
                "Penyakit": label,
                "Skor (%)": round(preds[i] * 100, 2)
            })

    except Exception as e:
        print(f"❌ Gagal memproses {filename}: {str(e)}")

# === Simpan hasil ke file CSV ===
with open(OUTPUT_CSV, mode='w', newline='') as f:
    writer = csv.DictWriter(f, fieldnames=["File", "Gejala", "Penyakit", "Skor (%)"])
    writer.writeheader()
    writer.writerows(results)

print(f"\n✅ Evaluasi selesai. Hasil disimpan di: {OUTPUT_CSV}")
