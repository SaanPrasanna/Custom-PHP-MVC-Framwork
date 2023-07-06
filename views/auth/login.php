<?php require_once APP_ROOT . '/views/inc/header.php'; ?>

<body>
    <div class="wrapper">
        <section class="form login">
            <header class="mb-4 text-center">REALTIME Chat App</header>
            <form action="#" method="POST" enctype="multipart/form-data" autocomplete="off">
                <div class="error-text"></div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email Address</label>
                    <input type="text" name="email" id="email" class="form-control" placeholder="Enter your email">
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <div class="input-group">
                        <input type="password" name="password" id="password" class="form-control" placeholder="Enter your password">
                        <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>
                <div class="field button">
                    <button class="btn btn-dark p-2">Continue to Chat</button>
                </div>
            </form>
            <div class="link">Not yet signed up? <a href="<?php echo $routeToRegister ?>">Signup now</a></div>
        </section>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
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

            $('form').submit(function(event) {
                var errors = [];

                validateField('email', errors);
                validateField('password', errors);

                if (errors.length > 0) {
                    var errorText = errors.join('<br>');
                    $('.error-text').html(errorText).show();
                    event.preventDefault();
                }
            });

            function validateField(fieldName, errors) {
                var fieldInput = $('input[name="' + fieldName + '"]');
                var errorText = fieldInput.siblings('.error-text');

                if (fieldInput.val().trim() === '') {
                    errors.push(capitalizeFirstLetter(fieldName) + ' is empty!');
                }

                errorText.hide();
            }

            function capitalizeFirstLetter(string) {
                return string.charAt(0).toUpperCase() + string.slice(1);
            }
        });
    </script>

</body>

</html>