<?php
require "common.php";
require "services.php";
make_header('Vaše rezervácie');
require_user();
$serv = new DBService();

$username = $_SESSION['user'];
$account = $serv->getUser($username);

$data = $serv->listLoansUser($account['u_id']);

echo "<h1>Zoznam vašich aktuálnych rezervácií</h1>";

if(count($data) == 0){
    echo"<p>Nemáte aktívne rezervácie</p>";
    echo "<a href=index.php>Späť na domovskú stránku</a>";
    make_footer();
}
else{
?>
<table>
  <tr>
    <th>ISBN</th> <th>Názov titulu</th> <th>Autor</th> <th>Knižnica</th> <th>Adresa</th> <th>Dátum od</th> <th>Dátum do</th>
  </tr>
<?php
foreach($data as $loan){
    $isbn = $loan['ISBN']; $titlename = $loan['title_name']; $author = $loan['author']; $name = $loan['name']; 
    $address = $loan['address']; $datefrom = $loan['d_from']; $dateto = $loan['d_to']; 
    echo "  <tr>";
    echo"<td>$isbn</td> <td>$titlename</td> <td>$author</td> <td>$name</td> <td>$address</td> <td>$datefrom</td> <td>$dateto</td> ";
    echo "  </tr>";
}
?>
</table>

<a href="index.php">Späť na domovskú stránku</a>

<?php
make_footer();
}
?>
