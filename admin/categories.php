<?php
    ob_start();
    session_start();
    $pagetitle = 'Categories';
    if(isset($_SESSION['Username'])) {

        include 'init.php';

        $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';
        if ($do == 'Manage') {

            // manage category

            $sort = 'ASC';
            $sort_array = array('ASC', 'DESC');
            if (isset($_GET['sort']) && in_array($_GET['sort'], $sort_array)) {
                $sort = $_GET['sort'];
            }

            $stmt2 = $con->prepare("SELECT * FROM categories ORDER BY Ordering $sort");
            $stmt2->execute();
            $cats = $stmt2->fetchAll(); ?>

            <h1 class="text-center">Manage Categories</h1>
            <div class="container cate">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <i class="fa fa-edit"></i>Manage Categories
                        <div class="ordering pull-right">
                            <i class="fa fa-sort"></i> Ordering : [
                            <a class="<?php if ($sort == 'ASC'){
                                echo 'active';
                            } ?> " href="?sort=ASC">Asc</a> |
                            <a class="<?php if ($sort == 'DESC'){
                                echo 'active';
                            } ?>" href="?sort=DESC">Desc</a> ]
                        </div>
                    </div>
                    <div class="panel-body">
                        <?php
                        foreach ($cats as $cat) {
                            echo "<div class='cat'>";
                            echo "<div class='hidden-buttons'>";
                            echo "<a href='categories.php?do=Edit&catid=" . $cat['ID'] . "' class='btn btn-xs btn-primary '><i class='fa fa-edit'></i>Edit</a>";
                            echo "<a href='categories.php?do=Delete&catid=" . $cat['ID'] . "' class=' confirm btn btn-xs btn-danger '><i class='fa fa-close'></i>Delete</a>";
                            echo "</div>";
                            echo "<h3>" . $cat['Name'] . '</h3>';
                            echo "<p>";
                            if ($cat['Description'] == '') {
                                echo 'the Category Has no Description';
                            } else {
                                echo $cat['Description'];
                            }
                            echo "</p>";
                            if ($cat['Visibility'] == 1) {
                                echo '<span class="visib"><i class="fa fa-eye"></i>Hidden</span>';
                            }
                            if ($cat['Allow_Comment'] == 1) {
                                echo '<span class="comment"><i class="fa fa-close"></i>Comment Disabled</span>';
                            }
                            if ($cat['Allow_Ads'] == 1) {
                                echo '<span class="advertise"><i class="fa fa-close"></i>Ads Disabled</span>';
                            }
                            echo "</div>";
                            echo "<hr class='hre'>";
                        }
                        ?>
                    </div>
                </div>
                <a href="  categories.php?do=Add" class="add-category btn btn-primary"><i class="fa fa-plus"></i> Add
                    New Category</a>
            </div>

            <?php

        } elseif ($do == 'Add') {  // Add Page ?>

            <h1 class="text-center">Add New Category</h1>
            <div class="container">

                <form class="form-horizontal" action="?do=Insert" method="POST">
                    <div class="form-group form-group-lg">
                        <lable class="col-sm-2 control-label">Name</lable>
                        <div class="col-sm-10 col-md-4">
                            <input required="required" type="text" name="name" class="form-control"
                                   placeholder="Name of the Category" autocomplete="off"/>
                        </div>
                    </div>
                    <div class="form-group form-group-lg">
                        <lable class="col-sm-2 control-label">Description</lable>
                        <div class="col-sm-10 col-md-4">
                            <input type="text" name="description" class="form-control"
                                   placeholder="Descripe the Category"/>
                        </div>
                    </div>
                    <div class="form-group form-group-lg">
                        <lable class="col-sm-2 control-label">Ordering</lable>
                        <div class="col-sm-10 col-md-4">
                            <input type="text" name="ordering" placeholder="Number To Arrange the Category"
                                   class="form-control"/>
                        </div>
                    </div>

                    <div class="form-group form-group-lg">
                        <lable class="col-sm-2 control-label">Visible</lable>
                        <div class="col-sm-10 col-md-4">
                            <div>
                                <input id="vis-yes" type="radio" name="visibility" value="0" checked/>
                                <labe for="vis-yes">Yes</labe>
                            </div>
                            <div>
                                <input id="vis-no" type="radio" name="visibility" value="1"/>
                                <label for="vis-no">No</label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group form-group-lg">
                        <lable class="col-sm-2 control-label">Allow Commenting</lable>
                        <div class="col-sm-10 col-md-4">
                            <div>
                                <input id="com-yes" type="radio" name="commenting" value="0" checked/>
                                <labe for="com-yes">Yes</labe>
                            </div>
                            <div>
                                <input id="com-no" type="radio" name="commenting" value="1"/>
                                <label for="com-no">No</label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group form-group-lg">
                        <lable class="col-sm-2 control-label">Allow Ads</lable>
                        <div class="col-sm-10 col-md-4">
                            <div>
                                <input id="ads-yes" type="radio" name="ads" value="0" checked/>
                                <labe for="ads-yes">Yes</labe>
                            </div>
                            <div>
                                <input id="ads-no" type="radio" name="ads" value="1"/>
                                <label for="ads-no">No</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10 ">
                            <input type="submit" value="Add Category" class="btn-primary btn-lg"/>
                        </div>
                    </div>
            </div>
            </form>

            <?php

        } elseif ($do == 'Insert') {

            //Insert Category Page

            if ($_SERVER['REQUEST_METHOD'] == 'POST') {

                echo "<h1 class='text-center'>Insert Category</h1>";
                echo "<div class='container'>";

                //Get variables from the form
                $name = $_POST['name'];
                $desc = $_POST['description'];
                $order = $_POST['ordering'];
                $visible = $_POST['visibility'];
                $comment = $_POST['commenting'];
                $ads = $_POST['ads'];


                $check = checkitem("Name", "categories", $name);

                if ($check == 1) {

                    $theMsg = "<div class='alert alert-danger'>" . 'Sorry this Name is Exist</div>';
                    rediectPage($theMsg, 'back');
                } else {

                    // insert in database

                    $stmt = $con->prepare("INSERT INTO categories(Name, Description,Ordering,Visibility,Allow_Comment,Allow_Ads)
                              VALUES(:zname, :zdesc,:zorder,:zvisible,:zcomment,:zads)");
                    $stmt->execute(array(
                        'zname' => $name,
                        'zdesc' => $desc,
                        'zorder' => $order,
                        'zvisible' => $visible,
                        'zcomment' => $comment,
                        'zads' => $ads
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

        } elseif ($do == 'Edit') {

            $catid = isset($_GET['catid']) && is_numeric($_GET['catid']) ? intval($_GET['catid']) : 0;
            $stmt = $con->prepare("SELECT * FROM categories WHERE ID = ? ");
            $stmt->execute(array($catid));
            $cat = $stmt->fetch();
            $cou = $stmt->rowCount();

            if ($stmt->rowCount() > 0) { ?>

                <h1 class="text-center">Edit Category</h1>
                <div class="container">

                    <form class="form-horizontal" action="?do=Update" method="POST">
                        <input type="hidden" name="catid" value="<?php echo $catid ?>"/>
                        <div class="form-group form-group-lg">
                            <lable class="col-sm-2 control-label">Name</lable>
                            <div class="col-sm-10 col-md-4">
                                <input required="required" type="text" name="name" class="form-control"
                                       placeholder="Name of the Category" value="<?php echo $cat['Name'] ?>"/>
                            </div>
                        </div>
                        <div class="form-group form-group-lg">
                            <lable class="col-sm-2 control-label">Description</lable>
                            <div class="col-sm-10 col-md-4">
                                <input type="text" name="description" class="form-control"
                                       placeholder="Descripe the Category" value="<?php echo $cat['Description'] ?>"/>
                            </div>
                        </div>
                        <div class="form-group form-group-lg">
                            <lable class="col-sm-2 control-label">Ordering</lable>
                            <div class="col-sm-10 col-md-4">
                                <input type="text" name="ordering" placeholder="Number To Arrange the Category"
                                       class="form-control" value="<?php echo $cat['Ordering'] ?> "/>
                            </div>
                        </div>
                        <div class="form-group form-group-lg">
                            <lable class="col-sm-2 control-label">Visible</lable>
                            <div class="col-sm-10 col-md-4">
                                <div>
                                    <input id="vis-yes" type="radio" name="visibility"
                                           value="0" <?php if ($cat['Visibility'] == 0) {
                                        echo 'checked';
                                    } ?> />
                                    <label for="vis-yes">Yes</label>
                                </div>
                                <div>
                                    <input id="vis-no" type="radio" name="visibility"
                                           value="1" <?php if ($cat['Visibility'] == 1) {
                                        echo 'checked';
                                    } ?>/>
                                    <label for="vis-no">No</label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group form-group-lg">
                            <lable class="col-sm-2 control-label">Allow Commenting</lable>
                            <div class="col-sm-10 col-md-4">
                                <div>
                                    <input id="com-yes" type="radio" name="commenting"
                                           value="0" <?php if ($cat['Allow_Comment'] == 0) {
                                        echo 'checked';
                                    } ?>/>
                                    <label for="com-yes">Yes</label>
                                </div>
                                <div>
                                    <input id="com-no" type="radio" name="commenting"
                                           value="1" <?php if ($cat['Allow_Comment'] == 1) {
                                        echo 'checked';
                                    } ?>/>
                                    <label for="com-no">No</label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group form-group-lg">
                            <lable class="col-sm-2 control-label">Allow Ads</lable>
                            <div class="col-sm-10 col-md-4">
                                <div>
                                    <input id="ads-yes" type="radio" name="ads"
                                           value="0" <?php if ($cat['Allow_Ads'] == 0) {
                                        echo 'checked';
                                    } ?>/>
                                    <label for="ads-yes">Yes</label>
                                </div>
                                <div>
                                    <input id="ads-no" type="radio" name="ads"
                                           value="1" <?php if ($cat['Allow_Ads'] == 1) {
                                        echo 'checked';
                                    } ?> />
                                    <label for="ads-no">No</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10 ">
                                <input type="submit" value="SaveCategory" class="btn-primary btn-lg"/>
                            </div>
                        </div>
                </div>
                </form>

                <?php

            } else {
                echo "<div class='container'>";
                $theMsg = '<div class="alert alert-danger">there is no such ID</div>';
                rediectPage($theMsg);
                echo "</div>";
            }

        } elseif ($do == 'Update') {
            echo "<h1 class='text-center'>Update Category</h1>";
            echo "<div class='container'>";

            if ($_SERVER['REQUEST_METHOD'] == 'POST') {

                //Get variables from the form
                $id = $_POST['catid'];
                $name = $_POST['name'];
                $desc = $_POST['description'];
                $order = $_POST['ordering'];
                $visible = $_POST['visibility'];
                $comment = $_POST['commenting'];
                $ads = $_POST['ads'];

                $stmt = $con->prepare("UPDATE categories SET Name = ? , Description = ? , Ordering = ? , Visibility = ? , Allow_Comment = ? , Allow_Ads = ? WHERE ID = ?");
                $stmt->execute(array($name, $desc, $order, $visible, $comment, $ads, $id));

                $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . 'Record updated</div>';
                rediectPage($theMsg, 'back');

            } else {

                $theMsg = '<div class="alert alert-danger">you cannot Browse this page Directly</div>';
                rediectPage($theMsg);

            }
            echo "</div>";

        } elseif ($do == 'Delete') {

            echo "<h1 class='text-center'>Delete Category</h1>";
            echo "<div class='container'>";


            $catid = isset($_GET['catid']) && is_numeric($_GET['catid']) ? intval($_GET['catid']) : 0;
            $stmt = $con->prepare("SELECT * FROM categories WHERE ID = ? ");
            $stmt->execute(array($catid));
            $cou = $stmt->rowCount();

            if ($stmt->rowCount() > 0) {

                $stmt = $con->prepare("DELETE FROM categories WHERE ID = :zid");
                $stmt->bindParam(":zid", $catid);
                $stmt->execute();

                $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . 'Record Deleted</div>';
                rediectPage($theMsg, 'back');
            } else {
                $theMsg = "<div class='alert alert-danger'>this ID Not exit</div>";
                rediectPage($theMsg , 'back');
            }
            echo '</div>';



        }elseif ($do == 'Activate') {

        }


        include $tpl . 'footer.php';

    }else {
        header('Location: index.php');
        exit();
    }
    ob_end_flush();
?>
