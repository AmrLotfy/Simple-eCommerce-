<?php

    ob_start();
    session_start();
    $pagetitle = 'Items';
    if(isset($_SESSION['Username'])) {

        include 'init.php';

        $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';
        if ($do == 'Manage') {


            $stmt = $con->prepare("SELECT items.* , categories.Name AS Category_Name ,users.Username FROM items
                                    INNER JOIN categories ON categories.ID = items.Cat_ID
                                    INNER JOIN users ON users.UserID = items.Member_ID");
            $stmt->execute();
            $items = $stmt->fetchAll();


            ?>


            <h1 class="text-center">Manage Items</h1>
            <div class="container">
                <div class="table-responsive">
                    <table class="main-table text-center table table-bordered">
                        <tr>
                            <td>#ID</td>
                            <td>Name</td>
                            <td>Desciption</td>
                            <td>Price</td>
                            <td>Added Date</td>
                            <td>Category</td>
                            <td>Member</td>
                            <td>Control</td>
                        </tr>

                        <?php
                        foreach ($items as $item) {
                            echo "<tr>";
                            echo "<td>" . $item['Item_ID'] . "</td>";
                            echo "<td>" . $item['Name'] . "</td>";
                            echo "<td>" . $item['Description'] . "</td>";
                            echo "<td>" . $item['Price'] . "</td>";
                            echo "<td>" . $item['Add_Date'] . "</td>";
                            echo "<td>" . $item['Category_Name'] . "</td>";
                            echo "<td>" . $item['Username'] . "</td>";
                            echo "<td>
                                        <a href=\"items.php?do=Edit&itemid=" . $item['Item_ID'] . "\" class=\"btn btn-success\"><i class='fa fa-edit'></i>Edit</a>
                                        <a href=\"items.php?do=Delete&itemid=" . $item['Item_ID'] . "\" class=\" confirm btn btn-danger \"><i class='fa fa-close'></i>Delete</a>";
                            if ($item['Approve'] == 0) {
                                echo "<a href=\"items.php?do=Approve&itemid=" . $item['Item_ID'] . "\" class=\"btn btn-info activate\"><i class='fa fa-check'></i>Approve</a>";
                            }

                            echo "</td>";
                            echo "</tr>";

                        }
                        ?>
                    </table>
                </div>

                <a href="items.php?do=Add" class="btn btn-primary"><i class="fa fa-plus"></i>New Item </a>
            </div>

        <?php
        } elseif ($do == 'Add') { // add item page  ?>

            <h1 class="text-center">Add New Item</h1>
            <div class="container">

                <form class="form-horizontal" action="?do=Insert" method="POST">
                    <div class="form-group form-group-lg">
                        <lablel class="col-sm-2 control-label">Name</lablel>
                        <div class="col-sm-10 col-md-4">
                            <input required="required" type="text" name="name" class="form-control"
                                   placeholder="Name of the Item" />
                        </div>
                    </div>
                    <div class="form-group form-group-lg">
                    <lablel class="col-sm-2 control-label">Description</lablel>
                    <div class="col-sm-10 col-md-4">
                        <input required="required" type="text" name="description" class="form-control"
                               placeholder="Description of the item" />
                    </div>
                  </div>
                    <div class="form-group form-group-lg">
                        <lablel class="col-sm-2 control-label">Price</lablel>
                        <div class="col-sm-10 col-md-4">
                            <input required="required" type="text" name="price" class="form-control"
                                   placeholder="price of the item" />
                        </div>
                    </div>
                    <div class="form-group form-group-lg">
                        <lablel class="col-sm-2 control-label">Country</lablel>
                        <div class="col-sm-10 col-md-4">
                            <input required="required" type="text" name="country" class="form-control"
                                   placeholder="Country of Made" />
                        </div>
                    </div>
                    <div class="form-group form-group-lg">
                        <lablel class="col-sm-2 control-label">Status</lablel>
                        <div class="col-sm-10 col-md-4">
                            <select name="status" class="">
                                <option value="0">...</option>
                                <option value="1">New</option>
                                <option value="2">Used</option>
                                <option value="3">very old</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group form-group-lg">
                        <lablel class="col-sm-2 control-label">Member</lablel>
                        <div class="col-sm-10 col-md-4">
                            <select name="member" class="">
                                <option value="0">...</option>
                                <?php
                                    $stmt = $con->prepare("SELECT * FROM users");
                                    $stmt->execute();
                                    $users = $stmt->fetchAll();
                                    foreach ($users as $user){
                                        echo "<option value='".$user['UserID']."'>".$user['Username']."</option>";
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group form-group-lg">
                    <lablel class="col-sm-2 control-label">Category</lablel>
                    <div class="col-sm-10 col-md-4">
                        <select name="category" class="">
                            <option value="0">...</option>
                            <?php
                            $stmt2 = $con->prepare("SELECT * FROM categories");
                            $stmt2->execute();
                            $cats = $stmt2->fetchAll();
                            foreach ($cats as $cat){
                                echo "<option value='".$cat['ID']."'>".$cat['Name']."</option>";
                            }
                            ?>
                        </select>
                         </div>
                        </div>
                     <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10 ">
                            <input type="submit" value="Add Item" class="btn-primary btn-lg"/>
                        </div>
                    </div>
                </form>
            </div>


          <?php

        } elseif ($do == 'Insert') {

            // Insert item page

            if ($_SERVER['REQUEST_METHOD'] == 'POST') {

                echo "<h1 class='text-center'>Insert Item</h1>";
                echo "<div class='container'>";

                //Get variables from the form
                $name = $_POST['name'];
                $desc = $_POST['description'];
                $price = $_POST['price'];
                $country = $_POST['country'];
                $st = $_POST['status'];
                $member = $_POST['member'];
                $category = $_POST['category'];

                $formErrors = array();

                if (empty($name)) {
                    $formErrors[] = 'Name cant Be Empty';

                }
                if (empty($desc)) {
                    $formErrors[] = 'Description cant Be Empty';
                }
                if (empty($price)) {
                    $formErrors[] = 'Price cant Be Empty';
                }
                if (empty($country)) {
                    $formErrors[] = 'Country Cant Be Empty';
                }
                if ($st == 0) {
                    $formErrors[] = 'You Should Choose the Status ';
                }
                if ($member == 0) {
                    $formErrors[] = 'You Should Choose the Member ';
                }
                if ($category == 0) {
                    $formErrors[] = 'You Should Choose the Category ';
                }

                }
                foreach ($formErrors as $error) {
                    $theMsg = "<div class=\"alert alert-danger\">" . $error . '</div>';
                    rediectPage($theMsg, 'back');
                }

                if (empty($formErrors)) {


                    // insert in database
                    $stmt = $con->prepare("INSERT INTO items(Name,Description,Price,Country_Made,Status,Add_Date ,Cat_ID,Member_ID)
                                  VALUES(:zname, :zdesc,:zprice,:zcountry,:zstatus,now() ,:zcategory ,:zmember )");
                    $stmt->execute(array(
                        'zname' => $name,
                        'zdesc' => $desc,
                        'zprice' => $price,
                        'zcountry' => $country,
                        'zstatus' => $status,
                        'zcategory' => $category,
                        'zmember' => $member
                    ));

                    $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . 'Record inserted</div>';
                    rediectPage($theMsg , 'back');


            } else {
                    echo '<div class="container">';
                    $theMsg = '<div class="aler alert-danger">you cannot Browse this page Directly</div>';
                    rediectPage($theMsg);
                    echo "</div>";
                }




            } elseif ($do == 'Edit') {

            $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;
            $stmt = $con->prepare("SELECT * FROM items WHERE Item_ID = ? ");
            $stmt->execute(array($itemid));
            $item = $stmt->fetch();
            $cou = $stmt->rowCount();

            if ($stmt->rowCount() > 0) { ?>

                <h1 class="text-center">Edit Item</h1>
                <div class="container">

                    <form class="form-horizontal" action="?do=Update" method="POST">
                        <input type="hidden" name="itemid" value="<?php echo $itemid ?>"/>
                        <div class="form-group form-group-lg">
                            <lablel class="col-sm-2 control-label">Name</lablel>
                            <div class="col-sm-10 col-md-4">
                                <input required="required" type="text" name="name" class="form-control"
                                       placeholder="Name of the Item" value="<?php echo $item['Name'] ?>" />
                            </div>
                        </div>
                        <div class="form-group form-group-lg">
                            <lablel class="col-sm-2 control-label">Description</lablel>
                            <div class="col-sm-10 col-md-4">
                                <input required="required" type="text" name="description" class="form-control"
                                       placeholder="Description of the item" value="<?php echo $item['Description'] ?>" />
                            </div>
                        </div>
                        <div class="form-group form-group-lg">
                            <lablel class="col-sm-2 control-label">Price</lablel>
                            <div class="col-sm-10 col-md-4">
                                <input required="required" type="text" name="price" class="form-control"
                                       placeholder="price of the item" value="<?php echo $item['Price'] ?>" />
                            </div>
                        </div>
                        <div class="form-group form-group-lg">
                            <lablel class="col-sm-2 control-label">Country</lablel>
                            <div class="col-sm-10 col-md-4">
                                <input required="required" type="text" name="country" class="form-control"
                                       placeholder="Country of Made" value="<?php echo $item['Country_Made'] ?>" />
                            </div>
                        </div>
                        <div class="form-group form-group-lg">
                            <lablel class="col-sm-2 control-label">Status</lablel>
                            <div class="col-sm-10 col-md-4">
                                <select name="status" class="">
                                    <option value="1" <?php if ($item['Status'] == 1){echo 'selected';}?>>New</option>
                                    <option value="2" <?php if ($item['Status'] == 2){echo 'selected';}?>>Used</option>
                                    <option value="3" <?php if ($item['Status'] == 3){echo 'selected';}?>>very old</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group form-group-lg">
                            <lablel class="col-sm-2 control-label">Member</lablel>
                            <div class="col-sm-10 col-md-4">
                                <select name="member" class="">
                                    <?php
                                    $stmt = $con->prepare("SELECT * FROM users");
                                    $stmt->execute();
                                    $users = $stmt->fetchAll();
                                    foreach ($users as $user){
                                        echo "<option value='".$user['UserID']."'"; if ($item['Member_ID'] == $user['UserID']){echo 'selected';} echo">".$user['Username']."</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group form-group-lg">
                            <lablel class="col-sm-2 control-label">Category</lablel>
                            <div class="col-sm-10 col-md-4">
                                <select name="category" class="">
                                    <?php
                                    $stmt2 = $con->prepare("SELECT * FROM categories");
                                    $stmt2->execute();
                                    $cats = $stmt2->fetchAll();
                                    foreach ($cats as $cat){
                                        echo "<option value='".$cat['ID']."'"; if($item['Cat_ID'] == $cat['ID']){echo 'selected';} echo ">".$cat['Name']."</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10 ">
                                <input type="submit" value="Save Item" class="btn-primary btn-lg"/>
                            </div>
                        </div>

                    </form>

                    <?php

                    $stmt = $con->prepare("SELECT
                    comments.*  , users.Username AS Member
                    FROM comments    
                    INNER JOIN users ON users.UserID = comments.user_id WHERE item_id = ?");
                    $stmt->execute(array($itemid));
                    $rows = $stmt->fetchAll();


                    ?>


                    <h1 class="text-center">Manage [ <?php echo $item['Name'] ?> ] Comments</h1>
                        <div class="table-responsive">
                            <table class="main-table text-center table table-bordered">
                                <tr>
                                    <td>Comment</td>
                                    <td>User Name</td>
                                    <td>Added Date</td>
                                    <td>Control</td>
                                </tr>

                                <?php
                                foreach ($rows as $row) {
                                    echo "<tr>";
                                    echo "<td>" . $row['comment'] . "</td>";
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

            } else {
                echo "<div class='container'>";
                $theMsg = '<div class="alert alert-danger">there is no such ID</div>';
                rediectPage($theMsg);
                echo "</div>";
            }

        } elseif ($do == 'Update') {

            echo "<h1 class='text-center'>Update Item</h1>";
            echo "<div class='container'>";

            if ($_SERVER['REQUEST_METHOD'] == 'POST') {

                //Get variables from the form
                $id = $_POST['itemid'];
                $name = $_POST['name'];
                $desc = $_POST['description'];
                $price = $_POST['price'];
                $country = $_POST['country'];
                $status = $_POST['status'];
                $member = $_POST['member'];
                $category = $_POST['category'];


                // validate the form
                $formErrors = array();
                if (empty($name)) {
                    $formErrors[] = 'Name cant Be Empty';

                }
                if (empty($desc)) {
                    $formErrors[] = 'Description cant Be Empty';
                }
                if (empty($price)) {
                    $formErrors[] = 'Price cant Be Empty';
                }
                if (empty($country)) {
                    $formErrors[] = 'Country Cant Be Empty';
                }
                if ($status == 0) {
                    $formErrors[] = 'You Should Choose the Status ';
                }
                if ($member == 0) {
                    $formErrors[] = 'You Should Choose the Member ';
                }
                if ($category == 0) {
                    $formErrors[] = 'You Should Choose the Category ';
                }

                foreach ($formErrors as $error) {
                    $theMsg = "<div class=\"alert alert-danger\">" . $error . '</div>';
                    rediectPage($theMsg, 'back');
                }

                if (empty($formErrors)) {


                    if (empty($formErrors)) {
                        $stmt = $con->prepare("UPDATE items SET Name = ? , Description = ? , Price = ? , Country_Made = ? , Status = ? , Cat_ID = ? ,Member_ID = ? WHERE Item_ID = ?");
                        $stmt->execute(array($name, $desc, $price, $country,$status,$member,$category, $id));

                        $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . 'Record updated</div>';
                        rediectPage($theMsg, 'back');
                    }

                } else {

                    $theMsg = '<div class="alert alert-danger">you cannot Browse this page Directly</div>';
                    rediectPage($theMsg);

                }
                echo "</div>";
            }

        } elseif ($do == 'Delete') {

            // delete item page

            echo "<h1 class='text-center'>Delete Item</h1>";
            echo "<div class='container'>";


            $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;
            $stmt = $con->prepare("SELECT * FROM items WHERE Item_ID = ?  ");
            $stmt->execute(array($itemid));
            $cou = $stmt->rowCount();

            if ($stmt->rowCount() > 0) {



                $stmt = $con->prepare("DELETE FROM items WHERE Item_iD = :zitem");
                $stmt->bindParam(":zitem", $itemid);
                $stmt->execute();

                $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . 'Record Deleted</div>';
                rediectPage($theMsg, 'back');
            } else {
                $theMsg = "<div class='alert alert-danger'>this ID Not exit</div>";
                rediectPage($theMsg);
            }
            echo '</div>';

        } elseif ($do == 'Approve') {

            // approve item page

            echo "<h1 class='text-center'>Approve Item</h1>";
            echo "<div class='container'>";


            $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;
            $stmt = $con->prepare("SELECT * FROM items WHERE Item_ID = ? ");
            $stmt->execute(array($itemid));
            $cou = $stmt->rowCount();

            if ($stmt->rowCount() > 0) {

                $stmt = $con->prepare("UPDATE items SET Approve = 1  WHERE Item_ID = ?");
                $stmt->execute(array($itemid));

                $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . 'Record Approved</div>';
                rediectPage($theMsg, 'back');
            } else {
                $theMsg = "<div class='alert alert-danger'>this ID Not exit</div>";
                rediectPage($theMsg);
            }
            echo '</div>';

        }


        include $tpl . 'footer.php';

    }else {
        header('Location: index.php');
        exit();
    }
    ob_end_flush();
?>











