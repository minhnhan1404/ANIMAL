from flask import Flask, request, jsonify
from flask_cors import CORS
from ultralytics import YOLO
import io
import mysql.connector  # Thêm cái này
from PIL import Image

app = Flask(__name__)
CORS(app)

# --- CẤU HÌNH DATABASE ---
def save_history(label, confidence):
    try:
        # Nhan sửa 'ten_database_cua_nhan' thành tên DB của Nhan (ví dụ: animal_db)
        db = mysql.connector.connect(
            host="localhost",
            user="root",
            password="",
            database="animalai"
        )
        cursor = db.cursor()
        # Lưu tên loài và độ tin cậy vào bảng
        sql = "INSERT INTO detection_history (user_name, prediction_result, confidence) VALUES (%s, %s, %s)"
        cursor.execute(sql, ("Khách", label, confidence)) # Tạm để user là Khách
        db.commit()
        db.close()
    except Exception as e:
        print(f"Lỗi lưu Database: {e}")

# Load model xịn vừa train xong
try:
    model = YOLO('best.pt')
except Exception as e:
    print(f"Lỗi tải mô hình: {e}")

@app.route('/predict', methods=['POST'])
def predict():
    try:
        if 'image' not in request.files:
            return jsonify({'error': 'Không tìm thấy ảnh'}), 400

        file = request.files['image']
        img_bytes = file.read()
        img = Image.open(io.BytesIO(img_bytes)).convert('RGB')

        results = model.predict(source=img, conf=0.25)

        predictions = []
        for r in results:
            if r.probs is not None:
                class_id = int(r.probs.top1)
                label = model.names[class_id]
                confidence = float(r.probs.top1conf)

                predictions.append({
                    'label': label,
                    'confidence': round(confidence * 100, 2)
                })

                # --- GỌI HÀM LƯU LỊCH SỬ Ở ĐÂY ---
                save_history(label, confidence)

        return jsonify({'predictions': predictions})

    except Exception as e:
        print(f"Lỗi hệ thống: {e}")
        return jsonify({'error': str(e)}), 500

if __name__ == '__main__':
    app.run(host='0.0.0.0', port=5000, debug=True)
