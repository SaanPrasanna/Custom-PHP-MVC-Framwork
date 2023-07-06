<?php require_once APP_ROOT . '/views/inc/header.php'; ?>

<body>
    <div class="wrapper">
        <section class="users">
            <header>
                <div class="content">
                    <img src="https://placehold.co/400" alt="" class="img-fluid">
                    <div class="details">
                        <span>Fname Lname</span>
                        <p>Online | Offline</p>
                    </div>
                </div>
                <a href="#" class="logout">Logout</a>
            </header>
            <div class="search">
                <span class="text">Select an user to start chat</span>
                <input type="text" class="form-control" placeholder="Enter name to search...">
                <button class="btn btn-secondary"><i class="fas fa-search"></i></button>
            </div>
            <div class="users-list">
                test
            </div>
        </section>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {

            $(".search button").click(function() {
                $(".search input").toggleClass("show");
                $(".search button").toggleClass("active");
                $(".search button").focus();
                if ($(".search input").hasClass("active")) {
                    $(".search input").val("");
                    $(".search input").removeClass("active");
                }
            });
            
        });
    </script>

</body>

</html>