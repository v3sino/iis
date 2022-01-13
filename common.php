<?php

session_start();

function make_header($title){
?>
<!DOCTYPE html> 
<html>
<head>
  <meta http-equiv="content-type" content="text/html; charset=utf-8">
  <title><?php echo $title;?></title>
  <?php 
  // CSS
    echo '<style>'; 
    include "universal.css"; 
    echo '</style>';
  ?>
</head>
<body>
<?php
}

function make_footer()
{
?>
<br>
<footer>IIS 2021</footer>
</body>
</html>
<?php
}

function redirect($dest){
    $script = $_SERVER["PHP_SELF"];
    if (strpos($dest,'/') === 0) {
        $path = $dest;
    } else {
        $path = substr($script, 0, strrpos($script, '/')) . "/$dest";
    }
    $name = $_SERVER["SERVER_NAME"];
    header("HTTP/1.1 301 Moved Permanently");
    header("Location: http://$name$path");
}

function require_user(){
    if (!isset($_SESSION['user'])){
        redirect("index.php");
    }
}

function require_admin(){
    if ($_SESSION['role'] != 3){
        echo "<h1>Access forbidden</h1>";
        echo "<a href=index.php>Back to home page</a>";
        make_footer();
        exit();
    }
}

function require_worker(){
    if (($_SESSION['role'] != 1) && ($_SESSION['role'] != 3)){
        echo "<h1>Access forbidden</h1>";
        echo "<a href=index.php>Back to home page</a>";
        make_footer();
        exit();
    }    
}

function require_distributor(){
    if (($_SESSION['role'] != 2) && ($_SESSION['role'] != 3)){
        echo "<h1>Access forbidden</h1>";
        echo "<a href=index.php>Back to home page</a>";
        make_footer();
        exit();
    }    
}

?>