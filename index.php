<?php
    ob_start();
    session_start();
    $pagetitle = 'HomePage';

    include 'init.php';
?>
    <div class="container">
        <div class="row">
            <?php
            $allitems = getAll('items' ,'Item_ID', 'where Approve = 1' );
            foreach ($allitems as $item)
            {
                echo '<div class="col-sm-6 col-md-3">';
                echo '<div class="thumbnail item-box ">';
                echo '<span class="price-tag">'.$item['Price'].'</span>';
                echo '<img class="img-responsive" src="blank-user.png" alt="" />';
                echo '<div class="caption">';
                echo '<h3><a href="items.php?itemid='.$item['Item_ID'].'">'. $item['Name'].'</a></h3>';
                echo '<p>'. $item['Description'].'</p>';
                echo '</div>';
                echo '</div>';
                echo '</div>';
            }
            ?>
    </div>
    </div>




<?php
include $tpl . 'footer.php';
ob_end_flush();
?>