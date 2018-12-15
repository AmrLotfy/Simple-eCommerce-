<?php

    /*
     * Manage comments
     */


    session_start();
    $pagetitle = 'Comments';
    if(isset($_SESSION['Username'])) {

        include 'init.php';

        $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

        // start manage page
        if ($do == 'Manage') { // manage page


            $stmt = $con->prepare("SELECT 
                                    comments.* , items.Name AS Item_Name , users.Username AS Member
                                    FROM comments 
                                     INNER JOIN items ON items.Item_ID = comments.item_id
                                     INNER JOIN users ON users.UserID = comments.user_id");
            $stmt->execute();
            $rows = $stmt->fetchAll();


            ?>


            <h1 class="text-center">Manage Comments</h1>
            <div class="container">
                <div class="table-responsive">
                    <table class="main-table text-center table table-bordered">
                        <tr>
                            <td>#ID</td>
                            <td>Comment</td>
                            <td>Item Name</td>
                            <td>User Name</td>
                            <td>Added Date</td>
                            <td>Control</td>
                        </tr>

                        <?php
                        foreach ($rows as $row) {
                            echo "<tr>";
                            echo "<td>" . $row['c_id'] . "</td>";
                            echo "<td>" . $row['comment'] . "</td>";
                            echo "<td>" . $row['Item_Name'] . "</td>";
                            echo "<td>" . $row['Member'] . "</td>";
                            echo "<td>" . $row['comment_date'] . "</td>";
                            echo "<td>
                                        <a href=\"comments.php?do=Edit&comid=" . $row['c_id'] . "\" class=\"btn btn-success\"><i class='fa fa-edit'></i>Edit</a>
                                        <a href=\"comments.php?do=Delete&comid=" . $row['c_id'] . "\" class=\" confirm btn btn-danger \"><i class='fa fa-close'></i>Delete</a>";
                            if ($row['status'] == 0) {
                                echo "<a href=\"comments.php?do=Approve&comid=" . $row['c_id'] . "\" class=\"btn btn-info activate\"><i class='fa fa-check'></i>Approve</a>";
                            }

                            echo "</td>";
                            echo "</tr>";

                        }
                        ?>
                    </table>
                </div>
            </div>

            <?php

        } elseif ($do == 'Edit') { // Edit page

            $comid = isset($_GET['comid']) && is_numeric($_GET['comid']) ? intval($_GET['comid']) : 0;
            $stmt = $con->prepare("SELECT * FROM comments WHERE c_id = ? ");
            $stmt->execute(array($comid));
            $row = $stmt->fetch();
            $cou = $stmt->rowCount();

            if ($stmt->rowCount() > 0) { ?>

                <h1 class="text-center">Edit Comment</h1>
                <div class="container">

                    <form class="form-horizontal" action="?do=Update" method="POST">
                        <input type="hidden" name="comid" value="<?php echo $comid ?>"/>

                        <div class="form-group form-group-lg">
                            <lable class="col-sm-2 control-label">Comment</lable>
                            <div class="col-sm-10 col-md-4">
                                <textarea class="form-control" name="comment"> <?php echo $row['comment'] ?></textarea>
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
        } elseif ($do == 'Update') {

            // Update page

            echo "<h1 class='text-center'>Update Comment</h1>";
            echo "<div class='container'>";

            if ($_SERVER['REQUEST_METHOD'] == 'POST') {

                //Get variables from the form
                $comid = $_POST['comid'];
                $comment = $_POST['comment'];

                    $stmt = $con->prepare("UPDATE comments SET comment = ?  WHERE c_id = ?");
                    $stmt->execute(array($comment,$comid));

                    $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . 'Record updated</div>';
                    rediectPage($theMsg, 'back');


            } else {

                $theMsg = '<div class="alert alert-danger">you cannot Browse this page Directly</div>';
                rediectPage($theMsg);

            }
            echo "</div>";
        } elseif ($do == 'Delete') {

            // Delete member page
            echo "<h1 class='text-center'>Delete Comment</h1>";
            echo "<div class='container'>";


            $comid = isset($_GET['comid']) && is_numeric($_GET['comid']) ? intval($_GET['comid']) : 0;
            $stmt = $con->prepare("SELECT * FROM comments WHERE c_id = ?  LIMIT 1");
            $stmt->execute(array($comid));
            $cou = $stmt->rowCount();

            if ($stmt->rowCount() > 0) {

                $stmt = $con->prepare("DELETE FROM comments WHERE c_id = :zcom");
                $stmt->bindParam(":zcom", $comid);
                $stmt->execute();

                $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . 'Record Deleted</div>';
                rediectPage($theMsg, 'back');
            } else {
                $theMsg = "<div class='alert alert-danger'>this ID Not exit</div>";
                rediectPage($theMsg);
            }
            echo '</div>';
        } elseif ($do == 'Approve') {
            // Delete member page
            echo "<h1 class='text-center'>Approve Comment</h1>";
            echo "<div class='container'>";


            $comid = isset($_GET['comid']) && is_numeric($_GET['comid']) ? intval($_GET['comid']) : 0;
            $stmt = $con->prepare("SELECT * FROM comments WHERE c_id = ? ");
            $stmt->execute(array($comid));
            $cou = $stmt->rowCount();

            if ($stmt->rowCount() > 0) {

                $stmt = $con->prepare("UPDATE comments SET status = 1  WHERE c_id = ?");
                $stmt->execute(array($comid));

                $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . 'Record Approved</div>';
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