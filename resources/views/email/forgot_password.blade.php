<div style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; max-width: 600px; margin: auto; border: 1px solid #e0e0e0; border-radius: 10px; overflow: hidden;">
    <div style="background-color: #27ae60; padding: 20px; text-align: center;">
        <h1 style="color: white; margin: 0;">Animalia World 🐾</h1>
    </div>

    <div style="padding: 30px; color: #333; line-height: 1.6;">
        <h2>Xin chào {{ $name }}!</h2>

        {{-- Dùng biến $title để hiển thị loại yêu cầu --}}
        <h3 style="color: #27ae60;">{{ $title }}</h3>

        <p>Chúng mình nhận được yêu cầu <strong>{{ strtolower($title) }}</strong> từ tài khoản của bạn.</p>
        <p>Vui lòng sử dụng mã xác nhận dưới đây để tiếp tục:</p>

        <div style="background: #f9f9f9; border: 2px dashed #27ae60; padding: 20px; text-align: center; font-size: 32px; font-weight: bold; color: #2ecc71; letter-spacing: 10px; margin: 20px 0;">
            {{ $code }}
        </div>

        <p style="color: #e74c3c; font-size: 0.9rem;">* Mã này có hiệu lực trong vòng 10 phút. Đừng chia sẻ cho ai khác nhé!</p>
    </div>

    <div style="background: #f1f1f1; padding: 15px; text-align: center; font-size: 0.8rem; color: #7f8c8d;">
        &copy; {{ date('Y') }} Animalia - Bảo tồn vẻ đẹp thiên nhiên.
    </div>
</div>
