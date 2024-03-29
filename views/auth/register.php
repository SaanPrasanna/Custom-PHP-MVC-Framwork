<?php require_once APP_ROOT . '/views/inc/header.php'; ?>

<body>
    <div class="wrapper">
        <section class="form signup">
            <header class="mb-4 text-center">REALTIME Chat App</header>
            <form action="#" method="POST" enctype="multipart/form-data" autocomplete="off" id="registerForm" data-parsley-validate>
                <div class="alert " role="alert" id="alert" style="display: none; text-align: center;"></div>
                <div class="row mb-3">
                    <div class="col">
                        <label class="form-label">First Name</label>
                        <input type="text" name="fname" class="form-control" placeholder="First name" required data-parsley-required-message="Please enter a Last Name">
                    </div>
                    <div class="col">
                        <label class="form-label">Last Name</label>
                        <input type="text" name="lname" class="form-control" placeholder="Last name" required data-parsley-required-message="Please enter a First Name">
                    </div>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email Address</label>
                    <input type="text" name="email" id="email" class="form-control" data-parsley-type="email" placeholder="Enter your email" required data-parsley-required-message="Please enter an Email address">
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
                    <div class="field image">
                        <label class="form-label">Select Image</label>
                        <input type="file" name="image" class="form-control" accept="image/x-png,image/gif,image/jpeg,image/jpg" required data-parsley-required-message="Please choose an image">
                    </div>
                </div>
                <div class="mb-3" id="regBtn">
                    <div class="field button">
                        <button class="btn btn-dark p-2" id="register">Register</button>
                    </div>
                </div>
            </form>
            <p class="hr"></p>
            <div class="link">Already signed up? <a href="<?php echo $routeToLogin; ?>">Login now</a></div>
        </section>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="<?php echo BASE_URL; ?>/asserts/javascript/parsley.min.js"></script>

    <script>
        $(document).ready(function() {

            $("#registerForm").parsley({
                errorClass: "is-invalid text-danger",
                successClass: "is-valid",
                errorsWrapper: '<span class="form-group"></span>',
                errorTemplate: '<small class="form-text text-danger"></small>'
            });


            $('form').submit(function(e) {
                e.preventDefault();

                $("#register").text("Please Wait...");
                $("#register").prop('disabled', true);

                var form = $(this);
                var formData = new FormData(form[0]);

                $.ajax({
                    url: '<?php echo BASE_URL; ?>/registerUser',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        const data = JSON.parse(response);
                        $('#register').prop('disabled', false);
                        if (data.message === "Success") {
                            $("div .mb-3").hide();
                            $('#alert').text("Verification Email sent to " + $("#email").val() + ", so before login first verify your email").addClass('alert-success').removeClass('alert-warning').show();
                        } else {
                            $('#alert').text(data.message).addClass('alert-warning').removeClass('alert-success').show();
                            $('#register').html('Register');
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