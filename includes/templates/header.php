<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <title><?php echo gettitle() ?></title>
        <link rel="stylesheet" href="<?php echo $css;?>bootstrap.min.css"/>
        <link rel="stylesheet" href="<?php echo $css;?>font-awesome.min.css"/>
        <link rel="stylesheet" href="<?php echo $css;?>jquery-ui.css"/>
        <link rel="stylesheet" href="<?php echo $css;?>jquery.selectBoxIt.css"/>
        <link rel="stylesheet" href="<?php echo $css;?>frontend.css"/>
    </head>
    <body>
    <div class="upper-bar">
        <div class="container">
            <?php
            if(isset($_SESSION['user'])){  echo 'Welcome | '; ?>

                <img  class="my-image img-thumbnail img-circle" src="blank-user.png" alt="" />
                <div class="btn-group my-info">
                    <span class="btn btn-default dropdown-toggle" data-toggle="dropdown" >

                      <?php echo $_SESSION['user'] ?>

                    <span class="caret"></span>

                    </span>
                    <ul class="dropdown-menu">
                        <li><a href="profile.php">My Profile</a></li>
                        <li><a href="profile.php#my-items">My Items</a></li>
                        <li><a href="newad.php">New item</a></li>
                        <li><a href="logout.php">LogOut</a></li>
                    </ul>
                </div>

                <?php


                $userstatus = checkUserStatus($_SESSION['user']);
                if($userstatus == 0){

                    echo '<div class="act pull-right">Your Membership Need To Activiate By Admin</div>';
                }
            }else {
            ?>
            <a href="login.php">
                <span class="pull-right">Login/SignUp</span>
            </a>
            <?php }?>
        </div>
    </div>
    <nav class="navbar navbar-inverse">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-nav" aria-expanded="false">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="index.php">HomePage</a>
            </div>

            <div class="collapse navbar-collapse" id="app-nav">
                <ul class="nav navbar-nav navbar-right">
                    <?php
                    foreach (getCat() as $cat)
                    {
                        echo
                            '<li> 
                                <a href="categories.php?pageid='. $cat['ID'].'">'. $cat['Name'] . '</a></li>';
                    }

                    ?>
                </ul>
            </div>
        </div>
    </nav>


