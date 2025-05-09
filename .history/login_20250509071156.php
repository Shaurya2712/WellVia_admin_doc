<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/animations.css">  
    <link rel="stylesheet" href="css/main.css">  
    <link rel="stylesheet" href="css/login.css">
    <title>Login</title>
</head>
<body>
    <?php
    // Start session
    session_start();

    // Clear session variables
    $_SESSION["user"] = "";
    $_SESSION["usertype"] = "";
    
    // Set timezone
    date_default_timezone_set('Asia/Kolkata');
    $_SESSION["date"] = date('Y-m-d');
    
    // Import database
    include("connection.php");

    $error = '<label for="promter" class="form-label"></label>';

    if ($_POST) {
        $email = $_POST['useremail'];
        $password = $_POST['userpassword'];

        // Check if email exists in webuser table
        $stmt = $conn->prepare("SELECT * FROM webuser WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            $utype = $row['usertype'];

            if ($utype == 'p') {
                // Check patient credentials
                $stmt = $conn->prepare("SELECT * FROM patient WHERE pemail = ? AND ppassword = ?");
                $stmt->bind_param("ss", $email, $password);
                $stmt->execute();
                $checker = $stmt->get_result();

                if ($checker->num_rows == 1) {
                    // Patient dashboard
                    $_SESSION['user'] = $email;
                    $_SESSION['usertype'] = 'p';
                    header('location: patient/index.php');
                } else {
                    $error = '<label for="promter" class="form-label" style="color:rgb(255, 62, 62);text-align:center;">Wrong credentials: Invalid email or password</label>';
                }
            } elseif ($utype == 'a') {
                // Check admin credentials
                $stmt = $conn->prepare("SELECT * FROM admin WHERE aemail = ? AND apassword = ?");
                $stmt->bind_param("ss", $email, $password);
                $stmt->execute();
                $checker = $stmt->get_result();

                if ($checker->num_rows == 1) {
                    // Admin dashboard
                    $_SESSION['user'] = $email;
                    $_SESSION['usertype'] = 'a';
                    header('location: admin/index.php');
                } else {
                    $error = '<label for="promter" class="form-label" style="color:rgb(255, 62, 62);text-align:center;">Wrong credentials: Invalid email or password</label>';
                }
            } elseif ($utype == 'd') {
                // Check doctor credentials
                $stmt = $conn->prepare("SELECT * FROM doctor WHERE docemail = ? AND docpassword = ?");
                $stmt->bind_param("ss", $email, $password);
                $stmt->execute();
                $checker = $stmt->get_result();

                if ($checker->num_rows == 1) {
                    // Doctor dashboard
                    $_SESSION['user'] = $email;
                    $_SESSION['usertype'] = 'd';
                    header('location: doctor/index.php');
                } else {
                    $error = '<label for="promter" class="form-label" style="color:rgb(255, 62, 62);text-align:center;">Wrong credentials: Invalid email or password</label>';
                }
            }
        } else {
            $error = '<label for="promter" class="form-label" style="color:rgb(255, 62, 62);text-align:center;">We can\'t find an account for this email.</label>';
        }
        $stmt->close();
    }
    ?>

    <center>
    <div class="container">
        <table border="0" style="margin: 0;padding: 0;width: 60%;">
            <tr>
                <td>
                    <p class="header-text">Welcome Back!</p>
                </td>
            </tr>
            <div class="form-body">
                <tr>
                    <td>
                        <p class="sub-text">Login with your details to continue</p>
                    </td>
                </tr>
                <tr>
                    <form action="" method="POST">
                        <td class="label-td">
                            <label for="useremail" class="form-label">Email: </label>
                        </td>
                    “

                    </tr>
                    <tr>
                        <td class="label-td">
                            <input type="email" name="useremail" class="input-text" placeholder="Email Address" required>
                        </td>
                    </tr>
                    <tr>
                        <td class="label-td">
                            <label for="userpassword" class="form-label">Password: </label>
                        </td>
                    </tr>
                    <tr>
                        <td class="label-td">
                            <input type="password" name="userpassword" class="input-text" placeholder="Password" required>
                        </td>
                    </tr>
                    <tr>
                        <td><br>
                            <?php echo $error ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input type="submit" value="Login" class="login-btn btn-primary btn">
                        </td>
                    </tr>
                </div>
                <tr>
                    <td>
                        <br>
                        <label for="" class="sub-text" style="font-weight: 280;">Don't have an account? </label>
                        <a href="signup.php" class="hover-link1 non-style-link">Sign Up</a>
                        <br><br><br>
                    </td>
                </tr>
            </form>
        </table>
    </div>
    </center>
</body>
</html>