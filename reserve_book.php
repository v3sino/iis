<?php
require "common.php";
require "services.php";
make_header('Rezervácia');
require_user();

echo "<h1>Rezervácia</h1>";

$serv = new DBService();

if (isset($_GET['u_id']) && isset($_GET['l_id']) &&isset($_GET['isbn']) ){
    $u_id = $_GET['u_id'];
    $l_id = $_GET['l_id'];
    $isbn = $_GET['isbn'];

    if($serv->numberOfActiveBooks($isbn,$l_id) == 0){
        if($serv->createWaiting($u_id,$isbn,$l_id)){
            echo "<p>Všetky knihy tohto titulu sú v tejto knižnici rezervované - boli ste umiestnený do fronty</p>";
        }
        else{
            echo "<p>Všetky knihy tohto titulu sú v tejto knižnici rezervované - nepodarilo sa vás umiestniť do fronty</p>";
        }
        echo "<a href=index.php> &lt&lt Späť na domovskú stránku</a>";
        make_footer();
        exit();
    }
    
    $b_id = $serv->takeOneBook($isbn,$l_id);
    $datefrom = date('Y-m-d');
    $dateto = '2022-01-01';
    
    if ($serv->createLoan($b_id,$u_id,$datefrom,$dateto,$l_id))
        echo "<p class=\"succ\">Kniha úspešne rezervovaná</p>";
    else
        echo "<p class=\"err\">Chyba: " . $serv->getErrorMessage() . "</p>";
 }

echo "<a href=index.php> &lt&lt Späť na domovskú stránku</a>";
make_footer();
?>