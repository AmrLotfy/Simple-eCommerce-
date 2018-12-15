<?php

    /*
     * Manage members and edit members ( add , edit , delete )
     */


    session_start();
    $pagetitle = 'Members';
    if(isset($_SESSION['Username'])) {

        include 'init.php';

        $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

        // start manage page
        if ($do == 'Manage') { // manage page

            $query = '';

            if (isset($_GET['page']) && $_GET['page'] == 'Panding') {
                $query = ' AND RegStatus = 0';
            }
            $stmt = $con->prepare("SELECT * FROM users WHERE GroupID != 1 $query");
            $stmt->execute();
            $rows = $stmt->fetchAll();


            ?>


            <h1 class="text-center">Manage Members</h1>
            <div class="container">
                <div class="table-responsive">
                    <table class="main-table text-center table table-bordered">
                        <tr>
                            <td>#ID</td>
                            <td>Username</td>
                            <td>Email</td>
                            <td>FullName</td>
                            <td>Registerd Date</td>
                            <td>Control</td>
                        </tr>

                        <?php
                        foreach ($rows as $row) {
                            echo "<tr>";
                            echo "<td>" . $row['UserID'] . "</td>";
                            echo "<td>" . $row['Username'] . "</td>";
                            echo "<td>" . $row['Email'] . "</td>";
                            echo "<td>" . $row['FullName'] . "</td>";
                            echo "<td>" . $row['Date'] . "</td>";
                            echo "<td>
                                        <a href=\"members.php?do=Edit&userid=" . $row['UserID'] . "\" class=\"btn btn-success\"><i class='fa fa-edit'></i>Edit</a>
                                        <a href=\"members.php?do=Delete&userid=" . $row['UserID'] . "\" class=\"btn btn-danger confirm \"><i class='fa fa-close'></i>Delete</a>";
                            if ($row['RegStatus'] == 0) {
                                echo "<a href=\"members.php?do=Activate&userid=" . $row['UserID'] . "\" class=\"btn btn-info activate\"><i class='fa fa-check'></i>Activate</a>";
                            }

                            echo "</td>";
                            echo "</tr>";

                        }
                        ?>
                    </table>
                </div>

                <a href="members.php?do=Add" class="btn btn-primary"><i class="fa fa-plus"></i>New Member </a>
            </div>

            <?php
        } elseif ($do == 'Add') { //add members page ?>


            <h1 class="text-center">Add New Member</h1>
            <div class="container">

                <form class="form-horizontal" action="?do=Insert" method="POST" enctype="multipart/form-data">
                    <div class="form-group form-group-lg">
                        <lable class="col-sm-2 control-label">Username</lable>
                        <div class="col-sm-10 col-md-4">
                            <input required="required" type="text" name="username" class="form-control"
                                   placeholder="Username" autocomplete="off"/>
                        </div>
                    </div>
                    <div class="form-group form-group-lg">
                        <lable class="col-sm-2 control-label">Password</lable>
                        <div class="col-sm-10 col-md-4">
                            <input required="required" type="password" name="password" class=" password form-control"
                                   placeholder="Password Must Be Good" autocomplete="new-password"/>
                            <i class="show-pass fa fa-eye fa-2x"></i>
                        </div>
                    </div>
                    <div class="form-group form-group-lg">
                        <lable class="col-sm-2 control-label">Email</lable>
                        <div class="col-sm-10 col-md-4">
                            <input required="required" type="email" name="email" placeholder="Email Must be valid"
                                   class="form-control"/>
                        </div>
                    </div>
                    <div class="form-group form-group-lg">
                        <lable class="col-sm-2 control-label">FullName</lable>
                        <div class="col-sm-10 col-md-4">
                            <input required="required" type="text" name="fullname"
                                   placeholder="Your FullName "
                                   class="form-control"/>
                        </div>
                    </div>

                    <div class="form-group form-group-lg">
                        <lable class="col-sm-2 control-label">User Photo</lable>
                        <div class="col-sm-10 col-md-4">
                            <input required="required" type="file" name="uphoto"
                                   class="form-control"/>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10 ">
                            <input type="submit" value="Add Member" class="btn-primary btn-lg"/>
                        </div>
                    </div>
            </div>
            </form>
            </div>

            <?php

        } elseif ($do == 'Insert') {

            //Insert Member Page


            if ($_SERVER['REQUEST_METHOD'] == 'POST') {

                echo "<h1 class='text-center'>Insert Members</h1>";
                echo "<div class='container'>";

                //upload photo

                $photoName = $_FILES ['uphoto']['name'];
                $photoSize = $_FILES ['uphoto']['size'];
                $photoTmp = $_FILES ['uphoto']['tmp_name'];
                $photoType = $_FILES ['uphoto']['type'];
                $photoAllowedExtension = array ("jpeg","jpg","png","gif");

                $photoExension = strtolower(end(explode('.',$photoName)));


                //Get variables from the form
                $user = $_POST['username'];
                $pass = $_POST['password'];
                $email = $_POST['email'];
                $name = $_POST['fullname'];

                $hashedpass = sha1($_POST['password']);

                // validate the form
                $formErrors = array();
                if (strlen($user) < 3 || empty($user)) {
                    $formErrors[] = 'Username cant Be Empty or less than 3 characters';
                }
                if (empty($name)) {
                    $formErrors[] = 'FullName cant Be Empty';
                }
                if (empty($pass)) {
                    $formErrors[] = 'Password cant Be Empty';
                }
                if (empty($email)) {
                    $formErrors[] = 'Email Cant Be Empty';
                }
                if (! empty($photoName) && ! in_array($photoExension , $photoAllowedExtension)){
                    $formErrors[] = 'This Extension Is Not Allowed';
                }
                if (empty($photoName)){
                    $formErrors[] = 'User Phoro Is Required';
                }
                if ($photoSize > 4194304 ){
                    $formErrors[] = 'photo can not be larger than 4MB';
                }

                foreach ($formErrors as $error) {
                    $theMsg =  "<div class=\"alert alert-danger\">" . $error . '</div>';
                    rediectPage($theMsg, 'back');
                }

                if (empty($formErrors)) {
                    //check user
                    $photo = rand(0,10000) . '_' . $photoName;

                    move_uploaded_file($photoTmp ,"uploads\photos\\" . $photo)

                    $check = checkitem("Username", "users", $user);

                    if ($check == 1) {

                        $theMsg = "<div class='alert alert-danger'>" . 'Sorry this User is Taken</div>';
                        rediectPage($theMsg, 'back');
                    } else {

                        // insert in database
                        $stmt = $con->prepare("INSERT INTO users(Username,Password,Email,FullName,RegStatus,Date,uphoto)
                              VALUES(:zuser, :zpass,:zmail,:zname,1,now(), :zphoto)");
                        $stmt->execute(array(
                            'zuser' => $user,
                            'zpass' => $hashedpass,
                            'zmail' => $email,
                            'zname' => $name,
                            'zphoto'=> $photo

                        ));

                        $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . 'Record inserted</div>';

                        rediectPage($theMsg, 'back');
                    }

                } else {
                    echo '<div class="container">';
                    $theMsg = '<div class="aler alert-danger">you cannot Browse this page Directly</div>';
                    rediectPage($theMsg);
                    echo "</div>";
                }
            }


            } elseif ($do == 'Edit') { // Edit page

                $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;
                $stmt = $con->prepare("SELECT * FROM users WHERE UserID = ?  LIMIT 1");
                $stmt->execute(array($userid));
                $row = $stmt->fetch();
                $cou = $stmt->rowCount();

                if ($stmt->rowCount() > 0) { ?>

                    <h1 class="text-center">Edit Members</h1>
                    <div class="container">

                        <form class="form-horizontal" action="?do=update" method="POST">
                            <input type="hidden" name="userid" value="<?php echo $userid ?>"/>

                            <div class="form-group form-group-lg">
                                <lable class="col-sm-2 control-label">Username</lable>
                                <div class="col-sm-10 col-md-4">
                                    <input required="required" type="text" name="username" class="form-control"
                                           value="<?php echo $row['Username'] ?>" autocomplete="off"/>
                                </div>
                            </div>
                            <div class="form-group form-group-lg">
                                <lable class="col-sm-2 control-label">Password</lable>
                                <div class="col-sm-10 col-md-4">
                                    <input type="hidden" name="oldpassword" class="form-control"
                                           value="<?php echo $row['Password'] ?>"/>
                                    <input type="password" name="newpassword" class="form-control"
                                           autocomplete="new-password"/>
                                </div>
                            </div>
                            <div class="form-group form-group-lg">
                                <lable class="col-sm-2 control-label">Email</lable>
                                <div class="col-sm-10 col-md-4">
                                    <input required="required" type="email" name="email"
                                           value="<?php echo $row['Email'] ?>"
                                           class="form-control"/>
                                </div>
                            </div>
                            <div class="form-group form-group-lg">
                                <lable class="col-sm-2 control-label">FullName</lable>
                                <div class="col-sm-10 col-md-4">
                                    <input required="required" type="text" name="fullname"
                                           value="<?php echo $row['FullName'] ?>"
                                           class="form-control"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-10 ">
                                    <input type="submit" value="Save" class="btn-primary btn-lg"/>
                                </div>
                            </div>
                    </div>
                    </form>
                    </div>

               <?php

                } else {
                    echo "<div class='container'>";
                    $theMsg = '<div class="alert alert-danger">there is no such ID</div>';
                    rediectPage($theMsg);
                    echo "</div>";
                }
            } elseif ($do == 'update') {

                // Update page

                echo "<h1 class='text-center'>Update Members</h1>";
                echo "<div class='container'>";

                if ($_SERVER['REQUEST_METHOD'] == 'POST') {

                    //Get variables from the form
                    $id = $_POST['userid'];
                    $user = $_POST['username'];
                    $email = $_POST['email'];
                    $name = $_POST['fullname'];

                    // password trick

                    $pass = '';
                    if (empty($_POST['newpassword'])) {
                        $pass = $_POST['oldpassword'];
                    } else {
                        $pass = sha1($_POST['newpassword']);
                    }

                    // validate the form
                    $formErrors = array();
                    if (strlen($user) < 3 || empty($user)) {
                        $formErrors[] = 'Username cant Be Empty or less than 3 characters';
                    }
                    if (empty($name)) {
                        $formErrors[] = 'FullName cant Be Empty';
                    }
                    if (empty($email)) {
                        $formErrors[] = 'Email Cant Be Empty';
                    }

                    foreach ($formErrors as $error) {
                        echo "<div class=\"alert alert-danger\">" . $error . '</div>';
                    }

                    if (empty($formErrors)) {
                        $stmt = $con->prepare("UPDATE users SET Username = ? , Email = ? , FullName = ? , Password = ? WHERE UserID = ?");
                        $stmt->execute(array($user, $email, $name, $pass, $id));

                        $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . 'Record updated</div>';
                        rediectPage($theMsg, 'back');
                    }

                } else {

                    $theMsg = '<div class="alert alert-danger">you cannot Browse this page Directly</div>';
                    rediectPage($theMsg);

                }
                echo "</div>";
            } elseif ($do == 'Delete') {

                // Delete member page
                echo "<h1 class='text-center'>Delete Members</h1>";
                echo "<div class='container'>";


                $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;
                $stmt = $con->prepare("SELECT * FROM users WHERE UserID = ?  LIMIT 1");
                $stmt->execute(array($userid));
                $cou = $stmt->rowCount();

                if ($stmt->rowCount() > 0) {

                    $stmt = $con->prepare("DELETE FROM users WHERE UseriD = :zuser");
                    $stmt->bindParam(":zuser", $userid);
                    $stmt->execute();

                    $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . 'Record Deleted</div>';
                    rediectPage($theMsg, 'back');
                } else {
                    $theMsg = "<div class='alert alert-danger'>this ID Not exit</div>";
                    rediectPage($theMsg);
                }
                echo '</div>';
            } elseif ($do == 'Activate') {
                // Delete member page
                echo "<h1 class='text-center'>Activate Members</h1>";
                echo "<div class='container'>";


                $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;
                $stmt = $con->prepare("SELECT * FROM users WHERE UserID = ?  LIMIT 1");
                $stmt->execute(array($userid));
                $cou = $stmt->rowCount();

                if ($stmt->rowCount() > 0) {

                    $stmt = $con->prepare("UPDATE users SET RegStatus = 1  WHERE UserID = ?");
                    $stmt->execute(array($userid));

                    $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . 'Record Activated</div>';
                    rediectPage($theMsg, 'back');
                } else {
                    $theMsg = "<div class='alert alert-danger'>this ID Not exit</div>";
                    rediectPage($theMsg);
                }
                echo '</div>';


                include $tpl . 'footer.php';
            } else {
                header('Location: index.php');
                exit();
            }
    }
