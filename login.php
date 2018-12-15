<?php
    ob_start();
    session_start();
    $pagetitle = 'Login';

    if(isset($_SESSION['user'])){
        header('Location: index.php');
    }
    include 'init.php';

        if($_SERVER['REQUEST_METHOD'] == 'POST'){

            if(isset($_POST['login'])) {

                $user = $_POST['username'];
                $pass = $_POST['password'];
                $hashedPass = sha1($pass);

                // check if user exist in database
                $stmt = $con->prepare("SELECT UserID, Username , Password FROM users WHERE Username = ? AND Password = ? ");
                $stmt->execute(array($user, $hashedPass));
                $getid = $stmt->fetch();
                $cou = $stmt->rowCount();

                // check if cou > 0 this mean database contain information
                if ($cou > 0) {
                    $_SESSION['user'] = $user;
                    $_SESSION['usid'] = $getid['UserID'];

                    header('Location: index.php');
                    exit();
                }
            }else{
                $formErros = array();

                $username = $_POST['username'];
                $password = $_POST['password'];
                $password2 = $_POST['password2'];
                $email    = $_POST['email'];
                if(isset($_POST[$username])){
                    $filterduser = filter_var($_POST[$username],FILTER_SANITIZE_STRING);
                    if(strlen($filterduser) < 3) {
                        $formErros[] = 'Username Cannot be less than 3 Characters';
                    }
                }

                if(isset($_POST[$password]) && isset($_POST[$password2])){

                    if(empty($_POST[$password])){

                        $formErros[] = 'Sorry Password Cant Be Empty';

                    }

                    if(sha1($_POST[$password]) !== sha1($_POST[$password2])){
                        $formErros[] = 'Sorry Password Is Not Match';
                    }
                }

                if(isset($_POST[$email])){
                    $filterdEmail = filter_var($_POST[$email],FILTER_SANITIZE_EMAIL);
                    if(filter_var($filterdEmail,FILTER_VALIDATE_EMAIL) != true) {
                        $formErros[] = 'This Email Is Not Valid';
                    }
                }

                if (empty($formErrors)) {
                    //check user

                    $check = checkitem("Username", "users", $username);

                    if ($check == 1) {

                        $formErros[] = 'Sorry This User Is Exist';

                    } else {

                        // insert in database
                        $stmt = $con->prepare("INSERT INTO users(Username,Password,Email,RegStatus,Date)
                              VALUES(:zuser, :zpass,:zmail,0,now())");
                        $stmt->execute(array(
                            'zuser' => $username,
                            'zpass' => sha1($password),
                            'zmail' => $email,

                        ));

                        $successMessage = 'Congrats , Registerd Successfully';
                    }
                }
            }

        }
    ?>

    <div class="container login-page">
        <h1 class="text-center"><span class="selected" data-class="login">Login</span> | <span data-class="signup">SignUp</span></h1>
        <form class="login" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
            <input class="form-control" type="text" name="username" autocomplete="off" placeholder="UserName" required >
            <input class="form-control" type="password" name="password" autocomplete="new-password" placeholder="Password" required>
            <input class="btn btn-primary btn-block" name="login" type="submit" value="Login" >
        </form>
        <form class="signup" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
            <input class="form-control" pattern=".{3,}" title="Username Must Be than 3 Chars"  type="text" name="username" autocomplete="off" placeholder="UserName" required>
            <input class="form-control" minlength="4" type="password" name="password" autocomplete="new-password" placeholder="type a Complex Password" required>
            <input class="form-control" minlength="4" type="password" name="password2" autocomplete="new-password" placeholder="type your Password again" required>
            <input class="form-control" type="email" name="email" placeholder="type a Valid Email" required>
            <input class="btn btn-success btn-block" name="signup" type="submit" value="SignUp" >
        </form>
        <div class="the-errors text-center msg error">


            <?php
                if(!empty($formErros)){
                    foreach ($formErros as $error) {

                        echo '<div class="error msg">' . $error . '</div>';
                    }
                }
                if(isset($successMessage)){

                    echo '<div class="success msg">' . $successMessage . '</div>';
                }
            ?>


        </div>
    </div>



<?php include $tpl.'footer.php';
    ob_end_flush();
?>
