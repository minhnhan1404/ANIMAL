<div style="background-color: #f4f7f6; padding: 40px 20px; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;">
    <div style="max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);">
        
        <!-- Header -->
        <div style="background: linear-gradient(135deg, #054a29 0%, #138a53 100%); padding: 40px 20px; text-align: center;">
            <h1 style="color: #ffffff; margin: 0; font-size: 28px; font-weight: 700; letter-spacing: 1px;">
                ANIMALIA WORLD 🐾
            </h1>
        </div>

        <!-- Body -->
        <div style="padding: 40px 35px; color: #333333; line-height: 1.8;">
            <h2 style="margin-top: 0; color: #1a1a1a; font-size: 22px; font-weight: 600;">
                Xin chào {{ $name }},
            </h2>

            <p style="font-size: 16px; color: #555555; margin-bottom: 30px;">
                Chúng mình vừa nhận được yêu cầu <strong><span style="color: #138a53;">{{ mb_strtolower($title, 'UTF-8') }}</span></strong> cho tài khoản của bạn tại Animalia. Vui lòng sử dụng mã xác minh an toàn dưới đây để hoàn tất quá trình:
            </p>

            <!-- OTP Code -->
            <div style="background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 12px; padding: 25px; text-align: center; margin: 35px 0;">
                <span style="font-size: 38px; font-weight: 800; color: #166534; letter-spacing: 12px; display: inline-block; margin-left: 12px;">
                    {{ $code }}
                </span>
            </div>

            <table width="100%" cellpadding="0" cellspacing="0" style="margin-top: 30px; border-top: 1px solid #eeeeee; padding-top: 25px;">
                <tr>
                    <td style="vertical-align: top; padding-right: 15px; width: 24px;">
                        <span style="font-size: 22px;">🔒</span>
                    </td>
                    <td>
                        <p style="margin: 0; color: #d97706; font-size: 14px; line-height: 1.6; font-weight: 500;">
                            Mã này có hiệu lực trong vòng <strong>10 phút</strong>. Vì lý do bảo mật, vui lòng không chia sẻ mã này cho bất kỳ ai.
                        </p>
                    </td>
                </tr>
            </table>
            
            <p style="font-size: 14px; color: #888888; margin-top: 25px;">
                Nếu bạn không thực hiện yêu cầu này, xin vui lòng bỏ qua email hoặc liên hệ với bộ phận hỗ trợ của chúng mình để bảo vệ tài khoản.
            </p>
        </div>

        <!-- Footer -->
        <div style="background-color: #fafbfc; border-top: 1px solid #f1f1f1; padding: 25px; text-align: center;">
            <p style="margin: 0; color: #999999; font-size: 13px;">
                &copy; {{ date('Y') }} Animalia World.<br>
                Nền tảng chia sẻ và bách khoa toàn thư thế giới động vật.
            </p>
        </div>
    </div>
</div>
