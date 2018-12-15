<?php

    session_start();
    $pagetitle = 'Create New Item';
    include 'init.php';
    if(isset($_SESSION['user'])) {

    if($_SERVER['REQUEST_METHOD'] == 'POST'){

        $formErrors = array();
        $name  = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
        $desc   = filter_var($_POST['description'] , FILTER_SANITIZE_STRING);
        $price  = filter_var($_POST['price'] ,FILTER_SANITIZE_STRING);
        $country = filter_var($_POST['country'],FILTER_SANITIZE_STRING);
        $status  = filter_var($_POST['status'],FILTER_SANITIZE_NUMBER_INT);
        $category = filter_var($_POST['category'],FILTER_SANITIZE_NUMBER_INT);

        if(strlen($name) < 4){
            $formErrors[] = 'Item Title Must be At Least 4 characters';
        }
        if(strlen($desc) < 14){
            $formErrors[] = 'Item Description Must be At Least 14 characters';
        }
        if(strlen($country) < 3){
            $formErrors[] = 'Item country Must be At Least 3 characters';
        }
        if(empty($price)){
            $formErrors[] = 'Item Price Must be Not Empty';
        }
        if(empty($status)){
            $formErrors[] = 'Item status Must be Not Empty';
        }
        if(empty($category)){
            $formErrors[] = 'Item category Must be Not Empty';
        }

        if (empty($formErrors)) {


            // insert in database
            $stmt = $con->prepare("INSERT INTO items(Name,Description,Price,Country_Made,Status,Add_Date ,Cat_ID, Member_ID)
                              VALUES(:zname, :zdesc,:zprice,:zcountry,:zstatus,now() ,:zcategory ,:zmember )");
            $stmt->execute(array(
                'zname' => $name,
                'zdesc' => $desc,
                'zprice' => $price,
                'zcountry' => $country,
                'zstatus' => $status,
                'zcategory' => $category,
                'zmember' => $_SESSION['usid']
            ));

            $suMessage = 'Done';
        }



        }

?>

        <h1 class="text-center"><?php echo $pagetitle?></h1>
        <div class="create-ad block">
            <div class="container">
                <div class="panel panel-primary">
                    <div class="panel panel-heading"><?php echo $pagetitle?></div>
                    <div class="panel panel-body">
                        <div class="row">
                            <div class="col-md-8">

                                <form class="form-horizontal" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
                                    <div class="form-group form-group-lg">
                                        <lable class="col-sm-2 control-label">Name</lable>
                                        <div class="col-sm-10 col-md-8">
                                            <input required="required" type="text" name="name" class="form-control live-name"
                                                   placeholder="Name of the Item" />
                                        </div>
                                    </div>
                                    <div class="form-group form-group-lg">
                                        <lable class="col-sm-2 control-label">Description</lable>
                                        <div class="col-sm-10 col-md-8">
                                            <input required="required" type="text" name="description" class="form-control live-desc"
                                                   placeholder="Description of the item" />
                                        </div>
                                    </div>
                                    <div class="form-group form-group-lg">
                                        <lable class="col-sm-2 control-label">Price</lable>
                                        <div class="col-sm-10 col-md-8">
                                            <input required="required" type="text" name="price" class="form-control live-price"
                                                   placeholder="price of the item" />
                                        </div>
                                    </div>
                                    <div class="form-group form-group-lg">
                                        <lable class="col-sm-2 control-label">Country</lable>
                                        <div class="col-sm-10 col-md-8">
                                            <input required="required" type="text" name="country" class="form-control"
                                                   placeholder="Country of Made" />
                                        </div>
                                    </div>
                                    <div class="form-group form-group-lg">
                                        <lable class="col-sm-2 control-label">Status</lable>
                                        <div class="col-sm-10 col-md-8">
                                            <select name="status" class="">
                                                <option value="0">...</option>
                                                <option value="1">New</option>
                                                <option value="2">Used</option>
                                                <option value="3">very old</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group form-group-lg">
                                        <lable class="col-sm-2 control-label">Category</lable>
                                        <div class="col-sm-10 col-md-8">
                                            <select name="category" class="">
                                                <option value="0">...</option>
                                                <?php
                                                $cats = getAll('categories','ID');
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
                            <div class="col-md-4">
                                <div class="thumbnail item-box live-preview">
                                <span class="price-tag">$0</span>
                                <img class="img-responsive" src="blank-user.png" alt="" />
                                <div class="caption">
                                    <h3>Title</h3>
                                    <p>Description</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                          if(!empty($formErrors)){
                              foreach ($formErrors as $error){
                                  echo '<div class="alert alert-danger">'.$error.'</div>';
                              }

                          }
                          if(isset($suMessage)){

                            echo '<div class="msg success">' . $suMessage . '</div>';
                          }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    <?php
}else{
    header('Location: login.php');
    exit();
}
include $tpl . 'footer.php';
?>