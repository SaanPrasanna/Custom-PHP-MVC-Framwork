<?php require_once APP_ROOT . '/views/inc/header.php'; ?>

<body>
    <div class="wrapper">
        <section class="chat-area">
            <header>
                <a href="users" class="back-icon"><i class="fas fa-arrow-left"></i></a>
                <img src="asserts/img/<?php echo $user->getImage(); ?>" alt="">
                <div class="details">
                    <span><?php echo $user->getFname() . ' ' . $user->getLname(); ?></span>
                    <p><?php echo $user->getStatus(); ?></p>
                </div>
            </header>
            <div class="chat-box">
            </div>
            <form action="#" method="POST" enctype="multipart/form-data" autocomplete="off" id="chatForm" class="typing-area input-group mb-3">
                <input type="text" class="incomingID" name="incomingID" value="<?php echo $user->getUserID(); ?>" hidden>
                <input type="text" class="form-control" name="message" id="message" placeholder="Type a message here..." autocomplete="off">
                <button class="btn btn-dark" id="send" disabled><i class="fab fa-telegram-plane"></i></button>
            </form>

        </section>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {


            $("#message").focus();

            $("#message").on('keyup', function() {
                var inputValue = $(this).val();
                var sendBtn = $("#send");

                if (inputValue !== "") {
                    sendBtn.prop("disabled", false);
                } else {
                    sendBtn.prop("disabled", true);
                }
            });

            $('form').submit(function(e) {
                e.preventDefault();

                var form = $(this);
                var formData = new FormData(form[0]);

                $.ajax({
                    url: '/mvc%20architecture/sendMessage',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        console.log(response);
                        $("#message").val('');
                        $("#send").prop("disabled", true);
                        scrollToBottom();
                    },
                    error: function(xhr, status, error) {}
                });

            });
            setInterval(() => {
                var formData = new FormData();
                formData.append('incomingID', '<?php echo $user->getUserID(); ?>');

                $.ajax({
                    url: '/mvc%20architecture/getMessages',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        console.log(response);
                        if (response.length > 0) {
                            var data = '';

                            $.each(response, function(index, chat) {
                                if (<?php echo $user->getUserID() ?> === chat.incomingID) {
                                    data += '<div class="chat outgoing">';
                                    data += '<div class="details">';
                                    data += '<p>' + chat.message + '</p>';
                                    data += '</div>';
                                    data += '</div>';
                                } else {
                                    data += '<div class="chat incoming">';
                                    data += '<img src="asserts/img/' + chat.image + '" alt="">';
                                    data += '<div class="details">';
                                    data += '<p>' + chat.message + '</p>';
                                    data += '</div>';
                                    data += '</div>';

                                }
                            });

                            $(".chat-box").html(data);
                            if (!$('.chat-box').hasClass("active")) {
                                scrollToBottom();
                            }
                        } else {
                            $(".chat-box").html('<div class="text">No messages are available. Once you send message they will appear here.</div>');
                        }
                    },
                    error: function(xhr, status, error) {}
                });
            }, 500);



            var chatBox = $(".chat-box");

            chatBox.mouseenter(function() {
                chatBox.addClass("active");
            });

            chatBox.mouseleave(function() {
                chatBox.removeClass("active");
            });

            function scrollToBottom() {
                var chatBox = $('.chat-box');
                chatBox.scrollTop(chatBox[0].scrollHeight);
            }

        });
    </script>
</body>

</html>