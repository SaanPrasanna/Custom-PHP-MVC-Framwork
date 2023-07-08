<?php

namespace App\Controllers;

use App\Models\User;
use Symfony\Component\Routing\RouteCollection;
use PHPMailer\PHPMailer\PHPMailer;


class UserController {

    // Show login page
    public function indexAction(RouteCollection $routes) {
        session_start();
        $routeToHome =  $routes->get('loginPage')->getPath();
        $routeToReset =  $routes->get('forgotPage')->getPath();
        $routeToRegister =  $routes->get('registerPage')->getPath();

        $userModel = new User();
        if (isset($_SESSION['id'])) {
            header("Location: users");
        } else {
            require_once APP_ROOT . '/views/auth/login.php';
        }
    }

    // Authenticate User Credentials and handle session
    public function userAuthentication(RouteCollection $routes) {
        session_start();
        $user = new User();
        if (isset($_POST['email']) && isset($_POST['password'])) {
            $user->setEmail($_POST['email']);
            $user->setPassword(sha1($_POST['password']));
            $user->setActiveStatus("Enabled");

            if ($user->authentication()) {
                if ($user->isVerifiedUser()) {
                    $user = $user->findUserID();
                    $_SESSION['id'] = $user->getUserID();
                    $user->setStatus('Online');
                    $user->changeStatus();
                    $msg = "Success";
                } else {
                    $msg = "Please verify your Email Address!";
                }
            } else {
                $msg = "Invalid Username or Password!";
            }
            echo json_encode(["message" => $msg]);
        } else {
            header("Location: forbidden?redirect=login");
        }
    }

    // Show user details
    public function usersPage(RouteCollection $routes) {
        session_start();
        $routeToLogout =  $routes->get('logout')->getPath();
        $user = new User();

        if (isset($_SESSION['id'])) {
            $user->setUesrID($_SESSION['id']);
            $user = $user->getUserDetails();
            require_once APP_ROOT . '/views/users.php';
        } else {
            header("Location: login");
        }
    }

    // Get all Users details
    public function allUsers(RouteCollection $routes) {
        $userModel = new User();
        if (isset($_POST['userID'])) {
            $userModel->setActiveStatus("Enabled");
            $userModel->setUesrID($_POST['userID']);
            $users = $userModel->allUsers();

            header('Content-Type: application/json');
            $data = [];
            foreach ($users as $user) {
                $data[] = $user->objectToArray();
            }
            echo json_encode($data);
        } else {
            header("Location: forbidden?redirect=all_users");
        }
    }

    // Search users by Name
    public function searchUser(RouteCollection $routes) {
        $userModel = new User();
        if (isset($_POST['userID']) && isset($_POST['name'])) {
            $userModel->setActiveStatus("Enabled");
            $userModel->setUesrID($_POST['userID']);
            $userModel->setFname($_POST['name']);

            $users = $userModel->searchUsers();
            header('Content-Type: application/json');
            $data = [];
            foreach ($users as $user) {
                $data[] = $user->objectToArray();
            }
            echo json_encode($data);
        } else {
            header("Location: forbidden?redirect=search_users");
        }
        exit;
    }

    public function userRegister(RouteCollection $routes) {
        session_start();
        $routeToLogin =  $routes->get('loginPage')->getPath();
        $userModel = new User();

        if ($_SESSION['id']) {
            header("Location: users");
        } else {
            require_once APP_ROOT . '/views/auth/register.php';
        }
    }

    // User Registration and sent verification email
    public function registerUser(RouteCollection $routes) {

        if (isset($_FILES['image'])) {
            $msg = "";

            $img_name = $_FILES['image']['name'];
            $img_type = $_FILES['image']['type'];
            $tmp_name = $_FILES['image']['tmp_name'];

            $img_explode = explode('.', $img_name);
            $img_ext = end($img_explode);

            $extensions = ["jpeg", "png", "jpg"];
            if (in_array($img_ext, $extensions) && in_array($img_type, ["image/jpeg", "image/jpg", "image/png"])) {
                $destinationDirectory = APP_ROOT . "/asserts/img/";
                $new_img_name = time() . $img_name;
                $destinationPath = $destinationDirectory . $new_img_name;

                if (move_uploaded_file($tmp_name, $destinationPath)) {

                    $user = new User();
                    $user->setUesrID(rand(time(), 100000000));
                    $user->setFname($_POST['fname']);
                    $user->setLname($_POST['lname']);
                    $user->setEmail($_POST['email']);
                    $user->setPassword(sha1($_POST['password']));
                    $user->setImage($new_img_name);
                    $user->setToken(bin2hex(random_bytes(16)));
                    $user->setActiveStatus("Disabled");
                    $user->setStatus("Offline");

                    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
                    $host = $_SERVER['HTTP_HOST'];
                    $baseUrl = $protocol . "://" . $host;

                    if (!$user->emailAlreadyExist()) {
                        $user->saveUser();
                        $mail = new PHPMailer(true);

                        $mail->isSMTP();
                        $mail->Host = 'smtp.gmail.com';
                        $mail->SMTPAuth = true;
                        $mail->Username   = 'info.rootgenesis@gmail.com';
                        $mail->Password   = 'ykhqiqsptadzozbk';
                        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                        $mail->SMTPSecure = 'tls';
                        $mail->Port = 587;

                        $mail->setFrom('info.rootgenesis@gmailc.om', 'Root Genesis');
                        $mail->addAddress($user->getEmail());
                        $mail->isHTML(true);
                        $mail->Subject = 'Registration Verification for REALTIME Chat App';
                        $msg = '
                        <tbody>
                            <tr>
                                <td height="20" style="line-height: 20px" colspan="3">&nbsp;</td>
                            </tr>
                            <tr>
                                <td height="1" colspan="3" style="line-height: 1px">
                                <span style="color: #ffffff; font-size: 1px; opacity: 0"
                                    >We received a registration request to REALTIME Chat App.</span
                                >
                                </td>
                            </tr>
                            <tr>
                                <td width="15" style="display: block; width: 15px">&nbsp;&nbsp;&nbsp;</td>
                                <td>
                                <table
                                    border="0"
                                    width="100%"
                                    cellspacing="0"
                                    cellpadding="0"
                                    style="border-collapse: collapse"
                                >
                                    <tbody>
                                    <tr>
                                        <td height="4" style="line-height: 4px">&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td>
                                        <span
                                            class="m_-1724164872632236456mb_text"
                                            style="
                                            font-family: Helvetica Neue, Helvetica, Lucida Grande, tahoma,
                                                verdana, arial, sans-serif;
                                            font-size: 16px;
                                            line-height: 21px;
                                            color: #141823;
                                            "
                                            ><span style="font-size: 15px"
                                            ><p></p>
                                            <div style="margin-top: 16px; margin-bottom: 20px">
                                                Hi ' . $user->getFname() . " " . $user->getLname() . ',
                                            </div>
                                            <div>
                                                We received a registration request to REALTIME Chat App.
                                            </div>
                                            Click the following button to verify your email:
                                            <p></p>
                                            <table
                                                border="0"
                                                width="100%"
                                                cellspacing="0"
                                                cellpadding="0"
                                                style="border-collapse: collapse"
                                            >
                                                <tbody>
                                                <tr>
                                                    <td height="20" style="line-height: 20px">&nbsp;</td>
                                                </tr>
                                                <tr>
                                                    <td align="middle">
                                                    <a
                                                        href="#"
                                                        style="color: #1b74e4; text-decoration: none"
                                                        target="_blank"
                                                        data-saferedirecturl="#"
                                                        ><table
                                                        border="0"
                                                        width="100%"
                                                        cellspacing="0"
                                                        cellpadding="0"
                                                        style="border-collapse: collapse"
                                                        >
                                                        <tbody>
                                                            <tr>
                                                            <td
                                                                style="
                                                                border-collapse: collapse;
                                                                border-radius: 6px;
                                                                text-align: center;
                                                                display: block;
                                                                background: #1877f2;
                                                                padding: 8px 20px 8px 20px;
                                                                "
                                                            >
                                                                <a
                                                                href="' . $baseUrl . "/mvc architecture/verify?token=" . base64_encode($user->getToken()) . "&id=" . base64_encode($user->getUserID()) . '"
                                                                style="
                                                                    color: #1b74e4;
                                                                    text-decoration: none;
                                                                    display: block;
                                                                "
                                                                target="_blank"
                                                                data-saferedirecturl="#"
                                                                ><center>
                                                                    <font size="3"
                                                                    ><span
                                                                        style="
                                                                        font-family: Helvetica Neue,
                                                                            Helvetica, Lucida Grande, tahoma,
                                                                            verdana, arial, sans-serif;
                                                                        white-space: nowrap;
                                                                        font-weight: bold;
                                                                        vertical-align: middle;
                                                                        color: #ffffff;
                                                                        font-weight: 500;
                                                                        font-family: Roboto-Medium, Roboto,
                                                                            -apple-system,
                                                                            BlinkMacSystemFont,
                                                                            Helvetica Neue, Helvetica,
                                                                            Lucida Grande, tahoma, verdana,
                                                                            arial, sans-serif;
                                                                        font-size: 17px;
                                                                        "
                                                                        >Verify&nbsp;Email</span
                                                                    ></font
                                                                    >
                                                                </center></a
                                                                >
                                                            </td>
                                                            </tr>
                                                        </tbody>
                                                        </table></a
                                                    >
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td height="8" style="line-height: 8px">&nbsp;</td>
                                                </tr>
                                                <tr>
                                                    <td height="20" style="line-height: 20px">&nbsp;</td>
                                                </tr>
                                                </tbody>
                                            </table>
                                            </span>
                                        </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td height="50" style="line-height: 50px">&nbsp;</td>
                                    </tr>
                                    </tbody>
                                </table>
                                </td>
                                <td width="15" style="display: block; width: 15px">&nbsp;&nbsp;&nbsp;</td>
                            </tr>
                            <tr>
                                <td height="20" style="line-height: 20px" colspan="3">&nbsp;</td>
                            </tr>
                            </tbody>
                        ';
                        $mail->Body = $msg;

                        $mail->send();

                        $msg = "Success";
                    } else {
                        $msg = "Email already exist!";
                    }
                } else {
                    $msg = "Failed to move the uploaded image";
                }
            } else {
                $msg = "Please upload an image file - jpeg, png, jpg";
            }
            echo json_encode(["message" => $msg]);
        } else {
            header("Location: forbidden?redirect=register");
        }
    }

    // Verify the user by link, which send to the email
    public function verifyUser(RouteCollection $routes) {

        $user = new User();
        $routeToLogin = $routes->get('loginPage')->getPath();

        if (isset($_GET['token']) && isset($_GET['id'])) {
            $token = base64_decode($_GET['token']);
            $userID = base64_decode($_GET['id']);

            $user->setToken($token);
            $user->setActiveStatus("Enabled");
            $user->setUesrID($userID);

            require_once APP_ROOT . '/views/inc/header.php';
            if ($user->isValidToken() && $user->verifyEmail()) {
                echo "
                <body>
                    <div class='wrapper'>
                        <section class='form signup'>
                            <header class='mb-4 text-center'>REALTIME Chat App</header>
                            <h2 class='text-center mb-3'>Email Verification Successfully!</h2>
                            <div class='link'>Thank you for verifying your email address.</a></div>
                            <p class='hr'></p>
                            <form action='#' method='POST' enctype='multipart/form-data' autocomplete='off' id='loginForm' data-parsley-validate>
                            <div class='field button'>
                                <a href='" . $routeToLogin . "' class='btn btn-success p-2' id='login'>Go to Login</a>
                            </div>
                        </form>
                        </section>
                    </div>
                </body>
                </html>";
            } else {
                echo "
                <body>
                    <div class='wrapper'>
                        <section class='form signup'>
                            <header class='mb-4 text-center'>REALTIME Chat App</header>
                            <h2 class='text-center mb-3'>Email Verification Link Expired!</h2>
                            <div class='link'>Your email has been failed to verify, please try again later.</a></div>
                            <p class='hr'></p>
                            <form action='#' method='POST' enctype='multipart/form-data' autocomplete='off' id='loginForm' data-parsley-validate>
                            <div class='field button'>
                                <a href='" . $routeToLogin . "' class='btn btn-danger p-2' id='login'>Go to Login</a>
                            </div>
                        </form>
                        </section>
                    </div>
                </body>
                </html>";
            }
        } else {
            header("Location: forbidden?redirect=verify");
        }
    }

    // Show Reset Password Request Page
    public function forgotPassword(RouteCollection $routes) {
        $routeToLogin =  $routes->get('loginPage')->getPath();
        require_once APP_ROOT . '/views/auth/forgot.php';
    }

    // Validate user and send the reset link
    public function forgot(RouteCollection $routes) {
        $msg = "";
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
        $host = $_SERVER['HTTP_HOST'];
        $baseUrl = $protocol . "://" . $host;

        $user = new User();
        if (isset($_POST['email'])) {
            $user->setEmail($_POST['email']);

            if ($user->emailAlreadyExist()) {
                $token = bin2hex(random_bytes(16));
                $user->setToken($token);
                if ($user->getNewToken()) {
                    $user = $user->findUserID();
                    $user->setToken($token);
                    $mail = new PHPMailer(true);

                    $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com';
                    $mail->SMTPAuth = true;
                    $mail->Username   = 'info.rootgenesis@gmail.com';
                    $mail->Password   = 'ykhqiqsptadzozbk';
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                    $mail->SMTPSecure = 'tls';
                    $mail->Port = 587;

                    $mail->setFrom('info.rootgenesis@gmailc.om', 'Root Genesis');
                    $mail->addAddress($user->getEmail());
                    $mail->isHTML(true);
                    $mail->Subject = 'Reset Password for REALTIME Chat App';
                    $msg = '
                        <tbody>
                            <tr>
                                <td height="20" style="line-height: 20px" colspan="3">&nbsp;</td>
                            </tr>
                            <tr>
                                <td height="1" colspan="3" style="line-height: 1px">
                                <span style="color: #ffffff; font-size: 1px; opacity: 0"
                                    >We received a password reset request for REALTIME Chat App.</span
                                >
                                </td>
                            </tr>
                            <tr>
                                <td width="15" style="display: block; width: 15px">&nbsp;&nbsp;&nbsp;</td>
                                <td>
                                <table
                                    border="0"
                                    width="100%"
                                    cellspacing="0"
                                    cellpadding="0"
                                    style="border-collapse: collapse"
                                >
                                    <tbody>
                                    <tr>
                                        <td height="4" style="line-height: 4px">&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td>
                                        <span
                                            class="m_-1724164872632236456mb_text"
                                            style="
                                            font-family: Helvetica Neue, Helvetica, Lucida Grande, tahoma,
                                                verdana, arial, sans-serif;
                                            font-size: 16px;
                                            line-height: 21px;
                                            color: #141823;
                                            "
                                            ><span style="font-size: 15px"
                                            ><p></p>
                                            <div style="margin-top: 16px; margin-bottom: 20px">
                                                Hi ' . $user->getFname() . " " . $user->getLname() . ',
                                            </div>
                                            <div>
                                            We received a password reset request for REALTIME Chat App..
                                            </div>
                                            Click the following button to reset your password:
                                            <p></p>
                                            <table
                                                border="0"
                                                width="100%"
                                                cellspacing="0"
                                                cellpadding="0"
                                                style="border-collapse: collapse"
                                            >
                                                <tbody>
                                                <tr>
                                                    <td height="20" style="line-height: 20px">&nbsp;</td>
                                                </tr>
                                                <tr>
                                                    <td align="middle">
                                                    <a
                                                        href="#"
                                                        style="color: #1b74e4; text-decoration: none"
                                                        target="_blank"
                                                        data-saferedirecturl="#"
                                                        ><table
                                                        border="0"
                                                        width="100%"
                                                        cellspacing="0"
                                                        cellpadding="0"
                                                        style="border-collapse: collapse"
                                                        >
                                                        <tbody>
                                                            <tr>
                                                            <td
                                                                style="
                                                                border-collapse: collapse;
                                                                border-radius: 6px;
                                                                text-align: center;
                                                                display: block;
                                                                background: #1877f2;
                                                                padding: 8px 20px 8px 20px;
                                                                "
                                                            >
                                                                <a
                                                                href="' . $baseUrl . "/mvc architecture/reset?token=" . base64_encode($user->getToken()) . "&id=" . base64_encode($user->getUserID()) . '"
                                                                style="
                                                                    color: #1b74e4;
                                                                    text-decoration: none;
                                                                    display: block;
                                                                "
                                                                target="_blank"
                                                                data-saferedirecturl="#"
                                                                ><center>
                                                                    <font size="3"
                                                                    ><span
                                                                        style="
                                                                        font-family: Helvetica Neue,
                                                                            Helvetica, Lucida Grande, tahoma,
                                                                            verdana, arial, sans-serif;
                                                                        white-space: nowrap;
                                                                        font-weight: bold;
                                                                        vertical-align: middle;
                                                                        color: #ffffff;
                                                                        font-weight: 500;
                                                                        font-family: Roboto-Medium, Roboto,
                                                                            -apple-system,
                                                                            BlinkMacSystemFont,
                                                                            Helvetica Neue, Helvetica,
                                                                            Lucida Grande, tahoma, verdana,
                                                                            arial, sans-serif;
                                                                        font-size: 17px;
                                                                        "
                                                                        >Verify&nbsp;Email</span
                                                                    ></font
                                                                    >
                                                                </center></a
                                                                >
                                                            </td>
                                                            </tr>
                                                        </tbody>
                                                        </table></a
                                                    >
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td height="8" style="line-height: 8px">&nbsp;</td>
                                                </tr>
                                                <tr>
                                                    <td height="20" style="line-height: 20px">&nbsp;</td>
                                                </tr>
                                                </tbody>
                                            </table>
                                            </span>
                                        </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td height="50" style="line-height: 50px">&nbsp;</td>
                                    </tr>
                                    </tbody>
                                </table>
                                </td>
                                <td width="15" style="display: block; width: 15px">&nbsp;&nbsp;&nbsp;</td>
                            </tr>
                            <tr>
                                <td height="20" style="line-height: 20px" colspan="3">&nbsp;</td>
                            </tr>
                            </tbody>
                        ';
                    $mail->Body = $msg;

                    $mail->send();
                    $msg = "Success";
                } else {
                    $msg = "Please try again later!";
                }
            } else {
                $msg = "Email does not exist in the Database";
            }
            echo json_encode(["message" => $msg]);
        } else {
            header("Location: forbidden?redirect=forgot_password");
        }
    }

    // Give access to the password reset page by link, which send to the email
    public function reset(RouteCollection $routes) {
        $routeToLogin = $routes->get('loginPage')->getPath();
        $user = new User();

        if (isset($_GET['token']) && isset($_GET['id'])) {
            $user->setToken(base64_decode($_GET['token']));
            $user->setUesrID(base64_decode($_GET['id']));
            $user->setActiveStatus("Enabled");
        }

        if (!empty($user->getToken()) && !empty($user->getUserID())) {
            if ($user->isValidToken() && $user->verifyEmail()) {
                $user = $user->getUserDetails();
                require_once APP_ROOT . '/views/auth/reset.php';
            } else {
                require_once APP_ROOT . '/views/inc/header.php';
                echo "
                <body>
                    <div class='wrapper'>
                        <section class='form reset'>
                            <header class='mb-4 text-center'>REALTIME Chat App</header>
                            <h2 class='text-center mb-3'>Password Rest Link Expired!</h2>
                            <div class='link'>Link has been failed to verify, please try again later.</a></div>
                            <p class='hr'></p>
                            <form action='#' method='POST' enctype='multipart/form-data' autocomplete='off' id='loginForm' data-parsley-validate>
                            <div class='field button'>
                                <a href='" . $routeToLogin . "' class='btn btn-danger p-2' id='login'>Go to Login</a>
                            </div>
                        </form>
                        </section>
                    </div>
                </body>
                </html>";
            }
        } else {
            header("Location: forbidden?redirect=reset");
        }
    }

    // Reset Password
    public function resetPassword(RouteCollection $routes) {
        $user = new User();
        $msg = "";

        if (isset($_POST['id']) && isset($_POST['password'])) {
            $user->setUesrID($_POST['id']);
            $user = $user->getUserDetails();
            $user->setPassword(sha1($_POST['password']));

            if ($user->resetPassword()) {
                $msg = "Success";
            } else {
                $msg = "Cannot change the Password, Please try again later!";
            }
            echo json_encode(["message" => $msg, "email" => $user->getEmail()]);
        } else {
            header("Location: forbidden?redirect=reset_password");
        }
    }

    // Logout and destroy session
    public function logout(RouteCollection $routes) {
        session_start();
        $user = new User();

        if (isset($_SESSION['id'])) {

            $user->setUesrID($_SESSION['id']);
            $user->setStatus("Offline");
            $user->changeStatus();

            unset($_SESSION['id']);
            header("Location: login?logout_successfully");
        } else {
            header("Location: login?redirect");
        }
    }

    // Show Not Found page
    public function notFound(RouteCollection $routes) {
        $routeToLogin =  $routes->get('usersPage')->getPath();
        require_once APP_ROOT . '/views/errors/404.php';
    }

    // Show Access Forbidden Page
    public function accessForbidden(RouteCollection $routes) {
        $routeToLogin =  $routes->get('usersPage')->getPath();
        require_once APP_ROOT . '/views/errors/403.php';
    }
}
