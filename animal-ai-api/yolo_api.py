from flask import Flask, request, jsonify
from flask_cors import CORS  # BẮT BUỘC để sửa lỗi CORS
from ultralytics import YOLO
import io
from PIL import Image

app = Flask(__name__)
CORS(app)  # Cho phép Laravel truy cập vào API Python

# Đảm bảo file best.pt nằm cùng thư mục với file này
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

        # Chạy nhận diện với mô hình Classification
        results = model.predict(source=img, conf=0.25)

        predictions = []
        for r in results:
            # Đối với Classification, ta lấy top 1 (loài có xác suất cao nhất)
            if r.probs is not None:
                # Lấy ID của lớp có xác suất cao nhất
                class_id = int(r.probs.top1)
                label = model.names[class_id]
                confidence = float(r.probs.top1conf)

                predictions.append({
                    'label': label,
                    'confidence': round(confidence * 100, 2)
                })

        return jsonify({'predictions': predictions})

    except Exception as e:
        print(f"Lỗi hệ thống: {e}")
        return jsonify({'error': str(e)}), 500

if __name__ == '__main__':
    app.run(host='0.0.0.0', port=5000, debug=True)
