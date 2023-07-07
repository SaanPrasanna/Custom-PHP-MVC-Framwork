<?php

namespace App\Controllers;

use App\Models\Product;
use App\Models\User;
use Symfony\Component\Routing\RouteCollection;
use PHPMailer\PHPMailer\PHPMailer;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class UserController {

    public function indexAction(RouteCollection $routes) {
        $routeToHome =  $routes->get('loginPage')->getPath();
        $routeToReset =  $routes->get('forgotPage')->getPath();
        $routeToRegister =  $routes->get('registerPage')->getPath();

        $userModel = new User();

        require_once APP_ROOT . '/views/auth/login.php';
    }

    public function notFound(RouteCollection $routes){
        require_once APP_ROOT . '/views/404.php';
    }

    public function userAuthentication(RouteCollection $routes) {
        $user = new User();

        $user->setEmail($_POST['email']);
        $user->setPassword(sha1($_POST['password']));
        $user->setActiveStatus("Enabled");

        if ($user->authentication()) {
            if ($user->isVerifiedUser()) {
                $msg = "Success";
            } else {
                $msg = "Please verify your Email Address!";
            }
        } else {
            $msg = "Invalid Username or Password!";
        }
        echo json_encode(["message" => $msg]);
    }

    public function showUsers(RouteCollection $routes) {
        require_once APP_ROOT . '/views/users.php';
    }

    public function userRegister(RouteCollection $routes) {
        $routeToLogin =  $routes->get('loginPage')->getPath();
        $userModel = new User();

        require_once APP_ROOT . '/views/auth/register.php';
    }

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
        }
    }

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
            echo "  <body>
                    <div class='container'>";
            if ($user->isValidToken() && $user->verifyEmail()) {
                echo "
                <h1>Email Verification Successful</h1>
                <p>Your email has been successfully verified.</p>
                <p>Thank you for verifying your email address.</p>
                <a href='dashboard.php' class='btn btn-primary'>Go to Dashboard</a>
                </body>
                </html>";
            } else {
                echo "
                <h1>Email Verification Link Expired</h1>
                <p>Your email has been failed to verify, please try again later.</p>
                <a href='dashboard.php' class='btn btn-danger'>Go to Dashboard</a>
                
                </body>
                
                </html>";
            }
        }else{
            require_once APP_ROOT . '/views/404.php';
        }
    }

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
                echo "  <body>
                            <div class='container'>
                                <h1>Email Verification Link Expired</h1>
                                <p>Your email has been failed to verify, please try again later.</p>
                                <a href='dashboard.php' class='btn btn-danger'>Go to Dashboard</a>
                                </div>
                            </body>
            
                            </html>";
            }
        } else {
            require_once APP_ROOT . '/views/404.php';
        }
    }

    public function resetPassword(RouteCollection $routes) {
        $user = new User();
        $msg = "";

        $user->setUesrID($_POST['id']);
        $user = $user->getUserDetails();
        $user->setPassword(sha1($_POST['password']));

        if ($user->resetPassword()) {
            $msg = "Success";
        } else {
            $msg = "Cannot change the Password, Please try again later!";
        }
        echo json_encode(["message" => $msg, "email" => $user->getEmail()]);
    }

    public function forgotPassword(RouteCollection $routes) {
        $routeToLogin =  $routes->get('loginPage')->getPath();
        require_once APP_ROOT . '/views/auth/forgot.php';
    }


    public function forgot(RouteCollection $routes) {
        $msg = "";
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
        $host = $_SERVER['HTTP_HOST'];
        $baseUrl = $protocol . "://" . $host;

        $user = new User();
        $user->setEmail($_POST['email']);

        if ($user->emailAlreadyExist()) {
            $user->setToken(bin2hex(random_bytes(16)));
            if ($user->getNewToken()) {
                $user->setUesrID($user->findUserID($user->getEmail())['userID']);
                $user->setFname($user->findUserID($user->getEmail())['fname']);
                $user->setLname($user->findUserID($user->getEmail())['lname']);

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
    }
}
