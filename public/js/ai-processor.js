document.addEventListener('DOMContentLoaded', function() {
    const aiInput = document.getElementById('ai-input');
    const dropArea = document.getElementById('drop-area');
    const previewSection = document.getElementById('preview-section');
    const previewImg = document.getElementById('preview-img');
    const btnScan = document.getElementById('btn-scan');
    const resultCard = document.getElementById('ai-result-card');
    const btnReset = document.getElementById('btn-reset');
    const animalInfo = document.getElementById('animal-info');

    // 1. Mở trình chọn file
    dropArea.addEventListener('click', () => aiInput.click());

    // 2. Hiển thị ảnh xem trước
    aiInput.addEventListener('change', function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = (e) => {
                previewImg.src = e.target.result;
                previewSection.style.display = 'block';
                dropArea.style.display = 'none';
                // Đảm bảo card kết quả cũ biến mất khi chọn ảnh mới
                resultCard.style.display = 'none';
            };
            reader.readAsDataURL(file);
        }
    });

    // 3. Gửi ảnh sang Python API & Lấy dữ liệu từ Database Laravel
    btnScan.addEventListener('click', async () => {
        const formData = new FormData();
        formData.append('image', aiInput.files[0]);

        btnScan.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang phân tích...';
        // Thêm hiệu ứng quét laser
        document.querySelector('.img-wrapper').classList.add('scanning');

        try {
            const response = await fetch('http://127.0.0.1:5000/predict', {
                method: 'POST',
                body: formData
            });

            const data = await response.json();

            if (data.predictions && data.predictions.length > 0) {
                const res = data.predictions[0];
                const label = res.label; // Ví dụ: "Bear", "Fox"

                document.getElementById('animal-name').innerText = label;
                document.getElementById('animal-conf').innerText = `Độ tin cậy: ${res.confidence}%`;
                resultCard.style.display = 'block';

                // --- BẮT ĐẦU GỌI API LARAVEL ĐỂ LẤY THÔNG TIN TỪ DATABASE ---
                fetchAnimalDetails(label);
            } else {
                alert("AI không nhận diện được loài vật nào!");
            }
        } catch (error) {
            alert("Lỗi kết nối API Python!");
        } finally {
            btnScan.innerHTML = '🔍 Bắt đầu nhận diện';
            document.querySelector('.img-wrapper').classList.remove('scanning');
        }
    });

    // Hàm lấy thông tin chi tiết từ Database Laravel
    async function fetchAnimalDetails(name) {
        animalInfo.innerHTML = '<p><i class="fas fa-sync fa-spin"></i> Đang truy xuất dữ liệu từ trang chủ...</p>';

        try {
            // Route này Nhan cần tạo trong web.php của Laravel
            const response = await fetch(`/get-animal-info/${name}`);
            const data = await response.json();

            if (data.error) {
                animalInfo.innerHTML = `<p class="text-warning"><i class="fas fa-exclamation-triangle"></i> ${data.error}</p>`;
            } else {
                // Hiển thị Tình trạng và Tập tính
                animalInfo.innerHTML = `
                    <div class="animal-details-box" style="text-align: left; color: #333;">
                        <p><strong><i class="fas fa-leaf"></i> Tình trạng:</strong> ${data.status || 'Chưa cập nhật'}</p>
                        <p><strong><i class="fas fa-paw"></i> Tập tính:</strong> ${data.behavior || 'Chưa cập nhật'}</p>
                        <hr>
                        <p>${data.description || 'Chưa có mô tả chi tiết.'}</p>
                    </div>
                `;
            }
        } catch (error) {
            animalInfo.innerHTML = '<p class="text-danger">Không thể kết nối cơ sở dữ liệu Laravel.</p>';
        }
    }

    // 4. Nút Reset quay lại trạng thái ban đầu
    if (btnReset) {
        btnReset.addEventListener('click', function() {
            resultCard.style.display = 'none';
            previewSection.style.display = 'none';
            dropArea.style.display = 'block';
            aiInput.value = "";
            document.getElementById('animal-name').innerText = "---";
            document.getElementById('animal-conf').innerText = "Độ chính xác: 0%";
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    }
});
