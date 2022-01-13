<?php
require "common.php";
require "services.php";
make_header('Domovská stránka');

$serv = new DBService();
$data = $serv->listTitlesHome();
$libraries = $serv->listLibraries();
echo "<h1>Domovská stránka</h1>";

if (isset($_SESSION['user'])){
    echo "Prihlásený ako: <strong>" . $_SESSION['user'] . '</strong>';
    if($_SESSION['role'] == 3){//admin
        echo "<br><a href=user_loans.php>Vaše rezervácie</a>";
        echo "<br><a href=admin_pages/users.php>Zoznam používateľov</a>";
        echo "<br><a href=admin_pages/libraries.php>Zoznam knižníc</a>";
        echo "<br><a href=worker_pages/show_orders.php>Distribútor - zoznam objednávok</a>";
        echo "<br><a href=worker_pages/titles.php>Knižné tituly</a>";
        echo "<br><a href=worker_pages/loans.php>Rezervácie</a>";
    }
    elseif($_SESSION['role'] == 2){//dist
        echo "<br><a href=user_loans.php>Vaše rezervácie</a>";
        echo "<br><a href=worker_pages/show_orders.php>Distribútor - zoznam objednávok</a>";
    }
    elseif($_SESSION['role'] == 1){//worker
        echo "<br><a href=user_loans.php>Vaše rezervácie</a>";
        echo "<br><a href=worker_pages/titles.php>Knižné tituly</a>";
        echo "<br><a href=worker_pages/loans.php>Rezervácie</a>";
    }
    else{//regular user
        echo "<br><a href=user_loans.php>Vaše rezervácie</a>";
    }

    echo '<p><a href="functions/logout.php">Odhlásiť sa</a>';
}
else{
?>
<div>
    <form action="login.php" method="post">
        <label for="login">Login</label>
        <input type="text" name="login" id="login"><br>
        <label for="password">Heslo</label>
        <input type="password" name="password" id="password"><br>
        <input type="submit" value="Prihlásiť" class="submit_button">
    </form>
</div>
    <p><a href="register.php">Registrovať</a></p>
<?php
}
?>

<script src="functions.js"></script>
<h2>Vyhľadávanie kníh</h2>
<label for="title">Názov knihy</label> <input type="text" name="title" id="title">
<label for="author">Meno autora</label> <input type="text" name="author" id="author">
<label for="genre">Žáner</label>
<select name="genre" id="genre">
<option value="všetky">všetky</option><option value="rozprávka">rozprávka</option> <option value="fantasy">fantasy</option> <option value="láska">láska</option> 
<option value="detektívka">detektívka</option> <option value="dobrodružná">dobrodružná</option> <option value="vzdelávanie">vzdelávanie</option>
</select>
<label for="library">Knižnica</label>
<select name="library" id="library">
<option value="všetky">všetky</option>
<?php
foreach($libraries as $lib) echo "<option value=\"$lib[1]\">$lib[1]</option>";
?>
</select>
<button onclick="searchBooks()">Hľadaj</button><br>

<table id="books">
  <tr>
  <th>ISBN</th> <th>Názov titulu</th> <th>Meno autora</th> <th>Žáner</th> <th>Hodnotenie</th> <th>Knižnica</th> <th>Adresa</th> <th>Otváracie hodiny</th> <th>Dostupnosť</th>
  </tr>
<?php
foreach($data as $offer){
    $isbn = $offer['isbn']; $title_name = $offer['title_name']; $author = $offer['author']; $genre = $offer['genre']; 
    $rating = $offer['rating']; $library = $offer['name']; $address = $offer['address']; $open = $offer['open']; $l_id = $offer['l_id'];
    $available = $serv->numberOfActiveBooks($isbn,$l_id); $all = $serv->numberOfBooks($isbn,$l_id);
    echo "  <tr>";
    echo"<td>$isbn</td> <td>$title_name</td> <td>$author</td> <td>$genre</td> <td>$rating</td> <td>$library</td> <td>$address</td> <td>$open</td> <td>$available / $all</td> ";
    if(isset($_SESSION['user'])){
        $account = $serv->getUser($_SESSION['user']);
        $id = $account['u_id'];
        echo "<td class=\"notItem\"><a href=vote.php?u_id=$id&l_id=$l_id&isbn=$isbn>Hlasovať za dokúpenie</a></td>";
        echo "<td class=\"notItem\"><a href=reserve_book.php?u_id=$id&l_id=$l_id&isbn=$isbn>Rezervovať knihu</a></td>";
    }
    else{
        echo "<td class=\"notItem\"><a href=register.php>Rezervovať knihu (nutná registrácia)</a></td>";
    }
    echo "  </tr>";
}

echo "</table>";


make_footer();
?>
