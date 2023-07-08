<?php require_once APP_ROOT . '/views/inc/header.php'; ?>

<body>
  <div class="wrapper">
    <section class="form signup">
      <header class="mb-4 text-center">REALTIME Chat App</header>
      <div class="display-2 text-center">404</div>
      <div class="link">Oops! The page you're looking for was not found.</a></div>
      <p class="hr"></p>
      <form action="#" method="POST" enctype="multipart/form-data" autocomplete="off" id="loginForm" data-parsley-validate>
        <div class="field button">
          <a class="btn btn-dark p-2" id="login" href="<?php echo $routeToLogin ?>">Go Back</a>
        </div>
      </form>
    </section>
  </div>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="/mvc architecture/asserts/javascript/parsley.min.js"></script>
  <script>
    $(document).ready(function() {
      $(document). prop( 'title' , 'REALTIME Chat App | Not Found' );
    });
  </script>
</body>

</html>