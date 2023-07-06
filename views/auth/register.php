<?php require_once APP_ROOT . '/views/inc/header.php'; ?>

<body>
    <div class="wrapper">
        <section class="form signup">
            <header class="mb-4 text-center">REALTIME Chat App</header>
            <form action="#" method="POST" enctype="multipart/form-data" autocomplete="off">
                <div class="error-text mb-3"></div>
                <div class="row mb-3">
                    <div class="col">
                        <div class="field input">
                            <label class="form-label">First Name</label>
                            <input type="text" name="fname" class="form-control" placeholder="First name" required>
                        </div>
                    </div>
                    <div class="col">
                        <div class="field input">
                            <label class="form-label">Last Name</label>
                            <input type="text" name="lname" class="form-control" placeholder="Last name" required>
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <div class="field input">
                        <label class="form-label">Email Address</label>
                        <input type="text" name="email" class="form-control" placeholder="Enter your email" required>
                    </div>
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
                <div class="mb-3">
                    <label for="cPassword" class="form-label">Confirm Password</label>
                    <div class="input-group">
                        <input type="password" name="cPassword" id="cPassword" class="form-control" placeholder="Confirm your password">
                        <button class="btn btn-outline-secondary" type="button" id="toggleCPassword">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>
                <div class="mb-3">
                    <div class="field image">
                        <label class="form-label">Select Image</label>
                        <input type="file" name="image" class="form-control" accept="image/x-png,image/gif,image/jpeg,image/jpg" required>
                    </div>
                </div>
                <div class="mb-3">
                    <div class="field button">
                        <button class="btn btn-dark p-2">Register</button>
                    </div>
                </div>
            </form>
            <div class="link">Already signed up? <a href="<?php echo $routeToLogin; ?>">Login now</a></div>
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
        });
        $(document).ready(function() {
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