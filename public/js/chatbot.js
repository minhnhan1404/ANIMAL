$(document).ready(function() {
    const chatRoute = "/chatbot/ask";

    // 1. Đóng mở chat
    $("#chat-circle, .chat-box-toggle").click(function() {
        $(".chat-box").toggleClass("active");

        setTimeout(function() {
            scrollToBottom();
        }, 300);
    });

    // 2. Submit form
    $("#chat-form").submit(function(e) {
        e.preventDefault();

        var msg = $("#chat-input-field").val().trim();
        if (msg === "") return;

        // Tin nhắn user
        appendMessage(msg, 'user');
        $("#chat-input-field").val('');

        // Loading
        var tempId = "loading-" + Date.now();

        var loadingHtml = `
            <div class="chat-msg bot" id="${tempId}">
                <div class="cm-msg-text">
                    <i class="fas fa-ellipsis-h fa-spin"></i> Đang suy nghĩ...
                </div>
            </div>`;

        $(".chat-box-body").append(loadingHtml);
        scrollToBottom();

        // AJAX
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

                var errorMsg = "Hệ thống bận tí, bạn hỏi lại sau nha!";
                if (xhr.status === 400) errorMsg = "Lỗi cấu trúc gửi đi (400).";
                if (xhr.status === 500) errorMsg = "Lỗi Server hoặc API (500).";

                appendMessage(errorMsg, 'bot');
                console.error("Lỗi Chatbot:", xhr.responseText);
            }
        });
    });

    // 3. Thêm tin nhắn
    function appendMessage(text, side) {
        var html = `
            <div class="chat-msg ${side}">
                <div class="cm-msg-text">${text}</div>
            </div>`;

        $(".chat-box-body").append(html);

        // Delay nhẹ để DOM render xong
        setTimeout(function() {
            scrollToBottom();
        }, 50);
    }

    // 4. Scroll xuống đáy (FIX CHUẨN)
    function scrollToBottom() {
        var chatBox = $(".chat-box-body");

        if (chatBox.length > 0) {
            chatBox.scrollTop(chatBox[0].scrollHeight);
        }
    }
});
