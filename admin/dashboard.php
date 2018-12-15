<?php

    ob_start();
    session_start();
    if(isset($_SESSION['Username'])){
        $pagetitle = 'Dashboard';
        include 'init.php';




        //start dashboard page
        ?>
        <div class="container home-stats text-center">
            <h1>Dashboard</h1>
            <div class="row">
                <div class="col-md-3">
                    <div class="stat members">
                        <i class="fa fa-users"></i>
                        <div class="info">
                            Total Members
                            <span><a href="members.php"> <?php echo countItems('UserID','users') ?></a></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat panding">
                        <i class="fa fa-user-plus"></i>
                        <div class="info">
                        Pending Members
                        <span><a href="members.php?do=Manage&page=Panding"><?php echo checkitem ("RegStatus" ,"users",0) ?></a></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat items">
                        <i class="fa fa-tag"></i>
                         <div class="info">
                             Total items
                             <span><a href="items.php"> <?php echo countItems('Item_ID','items') ?></a></span>
                         </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat comments">
                        <i class="fa fa-comments"></i>
                        <div class="info">
                        Total Comments
                            <span><a href="items.php"> <?php echo countItems('c_id','comments') ?></a></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="container latest">
            <div class="row">
                <div class="col-sm-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-users"></i> Latest Registerd Users
                            <span class=" toggle-info pull-right">
                                <span class=" toggle-info pull-right">
                                <i class="fa fa-plus fa-lg"></i>
                            </span>
                            </span>
                        </div>
                        <div class="panel-body">
                            <ul class="list-unstyled latest-users">
                                <?php
                                $latestUsers = getLatest("*","users","UserID");
                                foreach ($latestUsers as $user) {
                                    echo '<li>';
                                    echo $user['Username'];
                                    echo '<a href="members.php?do=Edit&userid=' . $user['UserID'] . '">';
                                    echo '<span class="btn btn-success pull-right">';
                                    echo '<i class="fa fa-edit"></i> Edit';
                                    if ($user['RegStatus'] == 0) {
                                        echo "<a href=\"members.php?do=Activate&userid=" . $user['UserID'] . "\" class=\"btn btn-info pull-right activate\"><i class='fa fa-check'></i>Activate</a>";
                                    }
                                    echo '</span>';
                                    echo '</a>';
                                    echo '</li>';

                                }

                            ?>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-tag"></i> Latest Items
                            <span class=" toggle-info pull-right">
                                <i class="fa fa-plus fa-lg"></i>
                            </span>
                        </div>
                        <div class="panel-body">
                            <ul class="list-unstyled latest-users">
                            <?php
                            $latestItems = getLatest("*","items","Item_ID");
                            foreach ($latestItems as $item) {
                                echo '<li>';
                                echo $item['Name'];
                                echo '<a href="items.php?do=Edit&itemid=' . $item['Item_ID'] . '">';
                                echo '<span class="btn btn-success pull-right">';
                                echo '<i class="fa fa-edit"></i> Edit';
                                if ($item['Approve'] == 0) {
                                    echo "<a href=\"items.php?do=Approve&itemid=" . $item['Item_ID'] . "\" class=\"btn btn-info pull-right activate\"><i class='fa fa-check'></i>Activate</a>";
                                }
                                echo '</span>';
                                echo '</a>';
                                echo '</li>';

                            }

                            ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-comments-o"></i> Latest Comments
                            <span class=" toggle-info pull-right">
                                <span class=" toggle-info pull-right">
                                <i class="fa fa-plus fa-lg"></i>
                            </span>
                            </span>
                        </div>
                        <div class="panel-body">
                            <?php
                                    $stmt = $con->prepare("SELECT
                                               comments.*  , users.Username AS Member
                                              FROM comments
                                             INNER JOIN users ON users.UserID = comments.user_id ");
                                    $stmt->execute();
                                    $comments = $stmt->fetchAll();
                                foreach ($comments as $comment)
                                {
                                    echo '<div class="comment-box">';
                                        echo '<span class="member-n">'. $comment['Member'] .'</span>';
                                          echo '<p class="member-c">'. $comment['comment'] .'</p>';
                                    echo '</div>';

                                }
                            ?>

                        </div>
                    </div>
                </div>
            </div>

        </div>

    <?php

        include $tpl . 'footer.php';
    }else{
        header('Location: index.php');
        exit();
    }

    ob_end_flush();
    ?>
