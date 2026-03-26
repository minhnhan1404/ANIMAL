$(document).ready(function() {
    const chatRoute = "/chatbot/ask";

    // 1. Đóng mở khung chat mượt mà
    $("#chat-circle, .chat-box-toggle").click(function() {
        $(".chat-box").fadeToggle(300);
        // Khi mở ra thì tự cuộn xuống cuối luôn cho chắc
        setTimeout(scrollToBottom, 300);
    });

    // 2. Xử lý gửi tin nhắn
    $("#chat-form").submit(function(e) {
        e.preventDefault();
        var msg = $("#chat-input-field").val().trim();
        if (msg == "") return;

        // Hiện tin nhắn người dùng
        appendMessage(msg, 'user');
        $("#chat-input-field").val('');

        // Hiện hiệu ứng "Đang suy nghĩ"
        var tempId = "loading-" + Date.now();
        var loadingHtml = `
            <div class="chat-msg bot" id="${tempId}">
                <div class="cm-msg-text">
                    <i class="fas fa-ellipsis-h fa-spin"></i> Đang suy nghĩ...
                </div>
            </div>`;
        $(".chat-logs").append(loadingHtml);
        scrollToBottom();

        // AJAX gửi đến Laravel
        $.ajax({
            url: chatRoute,
            method: "POST",
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                message: msg
            },
            success: function(response) {
                $("#" + tempId).remove();
                appendMessage(response.reply, 'bot');
            },
            error: function(xhr) {
                $("#" + tempId).remove();
                // Hiện lỗi chi tiết để Nhan dễ bắt bệnh
                var errorMsg = "Hệ thống bận tí, bạn hỏi lại sau nha!";
                if(xhr.status === 400) errorMsg = "Lỗi cấu trúc gửi đi (400).";
                if(xhr.status === 500) errorMsg = "Lỗi Server hoặc API Key (500).";

                appendMessage(errorMsg, 'bot');
                console.error("Lỗi Chatbot:", xhr.responseText);
            }
        });
    });

    // 3. Hàm thêm tin nhắn và TỰ ĐỘNG CUỘN
    function appendMessage(text, side) {
        var html = `<div class="chat-msg ${side}"><div class="cm-msg-text">${text}</div></div>`;
        $(".chat-logs").append(html);

        // Quan trọng: Phải dùng setTimeout để trình duyệt kịp nhận chiều cao mới của tin nhắn
        setTimeout(function() {
            scrollToBottom();
        }, 100);
    }

    // 4. Hàm cuộn xuống đáy "thần thánh"
    function scrollToBottom() {
        var chatBoxBody = $(".chat-box-body");
        if (chatBoxBody.length > 0) {
            chatBoxBody.stop().animate({
                scrollTop: chatBoxBody[0].scrollHeight
            }, 500);
        }
    }
});
