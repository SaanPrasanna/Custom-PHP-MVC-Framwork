<?php require_once APP_ROOT . '/views/inc/header.php'; ?>

<body>
    <div class="wrapper">
        <section class="form reset">
            <header class="mb-4 text-center">REALTIME Chat App</header>
            <form action="#" method="POST" enctype="multipart/form-data" autocomplete="off" id="resetForm" data-parsley-validate>
                <div class="alert " role="alert" id="alert" style="display: none; text-align: center;"></div>
                <div class="mb-3">
                    <label class="form-label"> <?php echo $user->getFname(); ?>, your Email <?php echo $user->getEmail(); ?> has been verified! Therefore, you can reset your password.</label>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <div class="input-group">
                        <input type="password" name="password" id="password" class="form-control" placeholder="Enter your password" data-parsley-minlength="6" required data-parsley-errors-container=".error" data-parsley-required-message="Please enter a Password">
                        <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    <div class="error"></div>
                </div>
                <div class="mb-3">
                    <label for="cPassword" class="form-label">Confirm Password</label>
                    <div class="input-group">
                        <input type="password" name="cPassword" id="cPassword" class="form-control" placeholder="Confirm your password" data-parsley-minlength="6" required data-parsley-errors-container=".errorCP" required data-parsley-required-message="Please enter a Confirm Password" data-parsley-equalto="#password" data-parsley-equalto-message="Password should be same">
                        <button class="btn btn-outline-secondary" type="button" id="toggleCPassword">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    <div class="errorCP"></div>
                </div>
                <div class="mb-3">
                    <div class="field button">
                        <button class="btn btn-dark p-2" id="reset">Reset Now</button>
                    </div>
                </div>
                <input type="hidden" name="id" value="<?php echo $user->getUserID();?>" class="required" />
            </form>
            <div class="link">Need to make a chat? <a href="<?php echo $routeToLogin; ?>">Login now</a></div>
        </section>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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

                $("#reset").text("Please Wait...");
                $("#reset").prop('disabled', true);

                var form = $(this);
                var formData = new FormData(form[0]);

                $.ajax({
                    url: '/mvc%20architecture/resetPassword',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        const data = JSON.parse(response);
                        $('#reset').prop('disabled', false);
                        if (data.message === "Success") {
                            $("div .mb-3").hide();
                            $('#alert').text("<?php echo $user->getFname(); ?>, your password has been reset.").addClass('alert-success').removeClass('alert-warning').show();
                        } else {
                            $('#alert').text(data.message).addClass('alert-warning').removeClass('alert-success').show();
                            $('#reset').html('Reset Now');
                        }
                    },
                    error: function(xhr, status, error) {}
                });

            });

            $('#togglePassword').click(function() {
                var passwordInput = $('#password');
                var passwordFieldType = passwordInput.attr('type');

                if (passwordFieldType === 'password') {
                    passwordInput.attr('type', 'text');
                    $(this).html('<i class="fas fa-eye-slash"></i>');
                } else {
                    passwordInput.attr('type', 'password');
                    $(this).html('<i class="fas fa-eye"></i>');
                }
            });

            $('#toggleCPassword').click(function() {
                var passwordInput = $('#cPassword');
                var passwordFieldType = passwordInput.attr('type');

                if (passwordFieldType === 'password') {
                    passwordInput.attr('type', 'text');
                    $(this).html('<i class="fas fa-eye-slash"></i>');
                } else {
                    passwordInput.attr('type', 'password');
                    $(this).html('<i class="fas fa-eye"></i>');
                }
            });
        });
    </script>

</body>

</html>