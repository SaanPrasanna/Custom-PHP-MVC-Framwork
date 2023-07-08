<?php
require_once APP_ROOT . '/views/inc/header.php';

?>

<body>
    <div class="wrapper">
        <section class="users">
            <header>
                <div class="content">
                    <img src="asserts/img/<?php echo $user->getImage(); ?>" alt="" class="img-fluid">
                    <div class="details">
                        <span><?php echo $user->getFname() . ' ' . $user->getLname(); ?></span>
                        <div><?php echo $user->getStatus(); ?></div>
                    </div>
                </div>
                <a href="<?php echo $routeToLogout ?>" class="btn btn-dark">Logout</a>
            </header>
            <div class="search">
                <span class="text">Select an user to start chat</span>
                <?php ?>
                <input type="text" class="form-control" id="search" placeholder="Enter name to search...">
                <button class="btn btn-secondary"><i class="fas fa-search"></i></button>
            </div>
            <div class="users-list">
            </div>
        </section>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {

            $(".search button").click(function() {
                $("#search").toggleClass("show");
                $(".search button").toggleClass("active");
                $("#search").focus();
                if (!$("search button").hasClass("active")) {
                    $("#search").val("");
                    $("search button").removeClass("active");
                }
            });

            var formData = new FormData();
            formData.append('userID', '<?php echo $user->getUserID(); ?>');
            setInterval(() => {
                $.ajax({
                    url: '/mvc%20architecture/allUsers',
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        var data = '';
                        if (!$(".search button").hasClass("active")) {
                            if (response.length > 0) {
                                $.each(response, function(index, user) {
                                    data += '<a href="chat?id=' + btoa(user.userID) + '">';
                                    data += '<div class="content">';
                                    data += '<img src="asserts/img/' + user.image + '" class="img-fluid">';
                                    data += '<div class="details">';
                                    data += '<span>' + user.fname + ' ' + user.lname + '</span>';
                                    data += '<div class="text-muted">' + user.message + '</div>';
                                    data += '</div>';
                                    data += '</div>';
                                    data += '<div class="status-dot ' + user.status + '"><i class="fas fa-circle"></i></div>';
                                    data += '</a>';
                                });
                                $(".users-list").html(data);
                            } else {
                                $(".users-list").html('<span class="fs-6">Humm... Select an user to start chat!</span>');
                            }
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                    }
                });
            }, 500);

            $(".search").keyup(function() {
                var formData = new FormData();
                formData.append('userID', '<?php echo $user->getUserID(); ?>');
                formData.append('name', $("#search").val());
                $.ajax({
                    url: '/mvc%20architecture/search',
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        console.log(response);
                        var data = '';
                        if (response.length > 0) {
                            $.each(response, function(index, user) {
                                data += '<a href="chat?id=' + btoa(user.userID) + '">';
                                data += '<div class="content">';
                                data += '<img src="asserts/img/' + user.image + '" class="img-fluid">';
                                data += '<div class="details">';
                                data += '<span>' + user.fname + ' ' + user.lname + '</span>';
                                data += '<div class="text-muted">' + user.message + '</div>';
                                data += '</div>';
                                data += '</div>';
                                data += '<div class="status-dot ' + user.status + '"><i class="fas fa-circle"></i></div>';
                                data += '</a>';
                            });
                            $(".users-list").html(data);
                        } else {
                            $(".users-list").html('<span class="fs-6">Humm... There is no user in this name!</span>');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                    }
                });
            });

        });
    </script>

</body>

</html>