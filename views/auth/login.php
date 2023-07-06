<?php require_once APP_ROOT . '/views/inc/header.php'; ?>

<body>
    <div class="wrapper">
        <section class="form login">
            <header class="mb-4 text-center">REALTIME Chat App</header>
            <form action="#" method="POST" enctype="multipart/form-data" autocomplete="off" id="loginForm" data-parsley-validate>
                <div class="error-text"></div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email Address</label>
                    <input type="text" name="email" id="email" class="form-control" data-parsley-type="email" placeholder="Enter your email" required data-parsley-required-message="Please enter an Email address">
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <div class="input-group">
                        <input type="password" name="password" id="password" class="form-control" data-parsley-minlength="6" data-parsley-errors-container=".error" placeholder="Enter your password" required data-parsley-required-message="Please enter a Password">
                        <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    <div class="error"></div>
                </div>
                <div class="field button">
                    <button class="btn btn-dark p-2" id="login">Continue to Chat</button>
                </div>
            </form>
            <div class="link">Not yet signed up? <a href="<?php echo $routeToRegister ?>">SignUp now</a></div>
        </section>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="/mvc architecture/asserts/javascript/parsley.min.js"></script>

    <script>
        $(document).ready(function() {

            $("#loginForm").parsley({
                errorClass: "is-invalid text-danger",
                successClass: "is-valid",
                errorsWrapper: '<span class="form-group"></span>',
                errorTemplate: '<small class="form-text text-danger"></small>'
            });

            $('form').submit(function(e) {
                e.preventDefault();
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
        });
    </script>

</body>

</html>