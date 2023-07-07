<?php require_once APP_ROOT . '/views/inc/header.php'; ?>

<body>
    <div class="wrapper">
        <section class="form reset">
            <header class="mb-4 text-center">REALTIME Chat App</header>
            <form action="#" method="POST" enctype="multipart/form-data" autocomplete="off" id="resetForm" data-parsley-validate>
                <div class="alert " role="alert" id="alert" style="display: none; text-align: center;"></div>
                <div class="mb-3">
                    <label for="email" class="form-label">Please enter your email address to reset for your account.</label>
                    <input type="text" name="email" id="email" class="form-control" data-parsley-type="email" placeholder="Enter your email" required data-parsley-required-message="Please enter an Email address">
                </div>
                <div class="field button">
                    <button class="btn btn-dark p-2" id="reset">Reset Password</button>
                </div>
            </form>
            <p class="hr"></p>
            <div class="link">Let's find someone to chat? <a href="<?php echo $routeToLogin ?>">Login now</a></div>
        </section>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="/mvc architecture/asserts/javascript/parsley.min.js"></script>

    <script>
        $(document).ready(function() {

            $("#resetForm").parsley({
                errorClass: "is-invalid text-danger",
                successClass: "is-valid",
                errorsWrapper: '<span class="form-group"></span>',
                errorTemplate: '<small class="form-text text-danger"></small>'
            });

            $('form').submit(function(e) {
                e.preventDefault();
                $("#reset").text("Please Wait...").prop('disabled', true);

                var form = $(this);
                var formData = new FormData(form[0]);

                $.ajax({
                    url: '/mvc%20architecture/forgotPassword',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        const data = JSON.parse(response);
                        $('#reset').prop('disabled', false);
                        if (data.message === "Success") {
                            $("div .mb-3").hide();
                            $('#reset').hide();
                            $('#alert').text("Rest Link has been send to your " + $("#email").val() + " Email.").addClass('alert-success').removeClass('alert-warning').show();
                        } else {
                            $('#alert').text(data.message).addClass('alert-warning').removeClass('alert-success').show();
                            $('#reset').html('Reset Now');
                        }
                    },
                    error: function(xhr, status, error) {}
                });

            });
        });
    </script>

</body>

</html>