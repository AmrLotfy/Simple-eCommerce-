<?php
    session_start();
    $noNav = '';
    $pagetitle = 'Login';
    if(isset($_SESSION['Username'])){
        header('Location: dashboard.php');
    }
    include 'init.php';


    // check if use coming from post method
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $username = $_POST['user'];
        $password = $_POST['pass'];
        $hashedPass = sha1($password);

        // check if user exist in database
        $stmt = $con->prepare("SELECT UserID, Username , Password FROM users WHERE Username = ? AND Password = ? 
                              AND GroupID = 1 LIMIT 1");
        $stmt -> execute (array($username,$hashedPass));
        $row = $stmt ->fetch();
        $cou = $stmt ->rowCount();

        // check if cou > 0 this mean database contain information
        if ($cou > 0){
            $_SESSION['Username'] = $username;
            $_SESSION['ID'] = $row['UserID'];
            header('Location: dashboard.php');
            exit();
        }

    }
?>

    <form class="login" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
        <h4 class="text-center">Admin Login</h4>
        <input class="form-control input-lg" type="text" name="user" placeholder="Username" autocomplete="off" />
        <input class="form-control input-lg" type="password" name="pass" placeholder="Password" autocomplete="new-password"/>
        <input class="btn btn-lg btn-primary btn-block" type="submit" value="login"/>
    </form>


<?php include $tpl . 'footer.php'; ?>
