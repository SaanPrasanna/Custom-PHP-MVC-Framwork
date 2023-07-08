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
                <?php
                $dateTime = date('d M Y, h:i A');

                $output = '<div class="chat outgoing">
                                <div class="details">
                                    <p>Sample Message From Outgoing</p>
                                </div>
                            </div>';
                $output .= '<div class="chat incoming">
                                <img src="asserts/img/245096430_107685328350666_5959259511000387097_n.jpg" alt="">
                                <div class="details">
                                    <p>Sample Incoming Message😁😁😁</p>
                                </div>
                            </div>';
                $output .= '<div class="chat outgoing">
                <div class="details">
                    <p>Sample Message From Outgoing</p>
                </div>
            </div>';
                $output .= '<div class="chat incoming">
                        <img src="asserts/img/245096430_107685328350666_5959259511000387097_n.jpg" alt="">
                        <div class="details">
                            <p>Sample Incoming Message</p>
                        </div>
                    </div>';
                echo $output;
                ?>

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
                    },
                    error: function(xhr, status, error) {}
                });

            });

            $(document).ready(function() {
                var chatBox = $(".chat-box");

                chatBox.mouseenter(function() {
                    chatBox.addClass("active");
                });

                chatBox.mouseleave(function() {
                    chatBox.removeClass("active");
                });
            });

        });
    </script>
</body>

</html>