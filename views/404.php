<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>404 Not Found</title>
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
  <!-- Custom CSS -->
  <style>
    /* Custom styles for the 404 page */
    body {
      background-color: #f8f9fa;
    }

    .error-container {
      height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      flex-direction: column;
    }

    .error-heading {
      font-size: 72px;
      font-weight: 700;
      color: #343a40;
      margin-bottom: 20px;
    }

    .error-message {
      font-size: 24px;
      font-weight: 500;
      color: #6c757d;
      margin-bottom: 40px;
    }

    .error-button {
      font-size: 18px;
      font-weight: 600;
      color: #fff;
      background-color: #007bff;
      border: none;
      border-radius: 5px;
      padding: 12px 30px;
      transition: background-color 0.3s ease;
      text-decoration: none;
    }

    .error-button:hover {
      background-color: #0056b3;
      color: #fffe;
      text-decoration: none;
    }
  </style>
</head>

<body>
  <div class="error-container">
    <h1 class="error-heading">404</h1>
    <p class="error-message">Oops! The page you're looking for was not found.</p>
    <a href="<?php echo $routeToHome ?>" class="error-button">Go Back to Homepage</a>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>

</html>
