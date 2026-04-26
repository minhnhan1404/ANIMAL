from flask import Flask, request, jsonify
from flask_cors import CORS
from ultralytics import YOLO
import io
import mysql.connector  # Thêm cái này
from PIL import Image

app = Flask(__name__)
CORS(app)

# --- CẤU HÌNH DATABASE ---
def save_history(label, confidence, user_name="Khách"):
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
        cursor.execute(sql, (user_name, label, confidence))
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
        user_name = request.form.get('user_name', 'Khách')
        
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
                save_history(label, confidence, user_name)

        return jsonify({'predictions': predictions})

    except Exception as e:
        print(f"Lỗi hệ thống: {e}")
        return jsonify({'error': str(e)}), 500

@app.route('/check-comment', methods=['POST'])
def check_comment():
    data = request.get_json()
    if not data or 'content' not in data:
        return jsonify({'error': 'Thiếu nội dung'}), 400

    content = data['content'].lower()

    # 1. TỪ CẤM NGHIÊM TRỌNG (TỤC TĨU, BẠO LỰC CỰC ĐOAN) -> CHẶN LUÔN
    severe_bad_words = [
        'đụ', 'đéo', 'cút', 'chó đẻ', 'lồn', 'cặc', 'ngu học', 'giết', 
        'thảm sát', 'tận diệt', 'ăn thịt', 'hành hạ', 'đâm chém', 'phóng hỏa',
        'ngu', 'vô văn hóa'
    ]
    for word in severe_bad_words:
        if word in content:
            return jsonify({
                'is_banned': True,
                'message': 'Bình luận chứa ngôn từ thô tục hoặc bạo lực!'
            })

    # 2. THUẬT TOÁN SCORING CHO HÀNH VI BUÔN BÁN ĐỘNG VẬT
    score = 0
    
    # Nhóm 1: Động từ mua bán/giao dịch (+2 điểm)
    trade_keywords = ['mua', 'bán', 'báo giá', 'inbox', 'ib', 'giá bao nhiêu', 'ship', 'chốt', 'thanh lý', 'pass lại', 'giao dịch']
    for kw in trade_keywords:
        if kw in content:
            score += 2
            break

    # Nhóm 2: Từ khóa liên quan động vật hoang dã (+2 điểm)
    animal_keywords = [
        'chim', 'chó', 'mèo', 'kỳ đà', 'hổ', 'gấu', 'sừng', 'mật', 'vảy', 'tê giác', 'rùa', 'rắn', 'thịt', 'bé', 'con thú',
        'con này', 'bé này', 'pé này', 'em này', 'thằng này'
    ]
    for kw in animal_keywords:
        # Kiểm tra từ độc lập bằng khoảng trắng (tránh bắt nhầm chữ 'mật' trong 'bí mật')
        if f" {kw} " in f" {content} ":
            score += 2
            break

    # Nếu câu có cả Mua bán (2đ) + Động vật (2đ) = 4đ -> CẤM
    if score >= 4:
        return jsonify({
            'is_banned': True,
            'message': 'Hệ thống AI nghi ngờ bạn đang có hành vi buôn bán, giao dịch động vật!'
        })

    return jsonify({'is_banned': False})

if __name__ == '__main__':
    app.run(host='0.0.0.0', port=5000, debug=True)
