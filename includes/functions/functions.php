<?php

                function getAll($tablename ,$orderby, $where = Null){
                    global $con;
                    $sql = $where == Null ? '' : $where;
                    $getAll = $con->prepare("SELECT * FROM $tablename $sql  ORDER BY $orderby DESC ");
                    $getAll->execute();
                    $all = $getAll->fetchAll();

                    return $all;
                }

                /*
                * to check user in database
                */

                function checkUser ($select ,$from,$value){

                    global $con;

                    $statement= $con->prepare("SELECT $select FROM $from WHERE $select = ?");
                    $statement->execute(array($value));

                    $count= $statement->rowCount();
                    return $count;

                }


            /*
             * get records from database
             */
            function getCat(){
                global $con;

                $getCat = $con->prepare("SELECT * FROM categories ORDER BY ID ASC ");
                $getCat->execute();
                $cats = $getCat->fetchAll();

                return $cats;
            }



            /*
             * check user
             */
            function checkUserStatus($user){
                global $con;

                $stmtx = $con->prepare("SELECT UserID, Username , RegStatus FROM users WHERE Username = ? AND RegStatus = 1 ");
                $stmtx -> execute (array($user));

                $status = $stmtx ->rowCount();

                return $status;
            }

            /*
             * get items from database
             */

        function getItems($where , $value,$approve = null){
            global $con;

            if($approve == null){
                $sql = 'AND Approve = 1';
            }else{
                $sql = null;
            }
            $getItems = $con->prepare("SELECT * FROM items WHERE $where = ? $sql ORDER BY Item_ID DESC ");
            $getItems->execute(array($value));
            $items = $getItems->fetchAll();

            return $items;
        }









/*
 * title function that print the page title
 * if page has the variable $pagetitle and print default title for other pages
 */




    function gettitle ()
    {
        global $pagetitle;

        if (isset($pagetitle)) {
            echo $pagetitle;
        } else {
            echo 'Default';
        }
    }

    /*
     * Redirect Function
     */
    function rediectPage($theMsg ,$url = null, $seconds = 3){

        $link = '';
        if ($url === null){
            $url = 'index.php';
        }else{
            if(isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] !== '') {
                $url = $_SERVER['HTTP_REFERER'];
                $link = 'Previous Page';
            }else
            {
                $url = 'index.php';
                $link = 'HomePage';
            }

        }
        echo  $theMsg;
        echo "<div class='alert alert-info'> You will Be Redirected to $link After $seconds Seconds.</div>";
        header("refresh:$seconds;url=$url");
        exit();
    }

    /*
     * to check item in database
     */

    function checkitem ($select ,$from,$value){

        global $con;

        $statement= $con->prepare("SELECT $select FROM $from WHERE $select = ?");
        $statement->execute(array($value));

        $count= $statement->rowCount();
        return $count;

    }

    /*
     * count number of items
     */
    function countItems($item,$table){
        global $con;
        $stmt2 = $con->prepare("SELECT COUNT($item) FROM $table");
        $stmt2->execute();

        return $stmt2->fetchColumn();
    }

    /*
     * get latest records functions
     */
    function getLatest($select ,$table,$order,$limit=5){
        global $con;

        $getstmt = $con->prepare("SELECT $select FROM $table ORDER BY $order DESC LIMIT  $limit ");
        $getstmt->execute();
        $rows = $getstmt->fetchAll();

        return $rows;
    }




