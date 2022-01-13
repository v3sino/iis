<?php
require "common.php";
require "services.php";
make_header('Hlasovať');
require_user();

echo "<h1>Hlasovanie za dokúpenie kníh daného titulu do danej knižnice</h1>";

$serv = new DBService();

if (isset($_GET['u_id']) && isset($_GET['l_id']) &&isset($_GET['isbn']) ){
    $u_id = $_GET['u_id'];
    $l_id = $_GET['l_id'];
    $isbn = $_GET['isbn'];

    if($serv->votedAlready($u_id,$l_id,$isbn)){
        echo "<p>Za tento titul ste už hlasovali</p>";
        echo "<a href=index.php> &lt&lt Späť na domovskú stránku</a>";
        make_footer();
        exit();
    }
 
    if ($serv->vote($u_id,$l_id,$isbn))
        echo "<p class=\"succ\">Hlas úspešne započítaný</p>";
    else
        echo "<p class=\"err\">Chyba: " . $serv->getErrorMessage() . "</p>";
 }

echo "<a href=index.php> &lt&lt Späť na domovskú stránku</a>";
make_footer();
?>