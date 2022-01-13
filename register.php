<?php
require "common.php";
make_header('Registrácia');
?>

<h1>Registrácia</h1>


<form action="functions/create_account.php" method="post">
    <label for="username">Login*</label>
    <input type="text" name="username" id="username" required><br>
    
    <label for="password">Heslo*</label>
    <input type="password" name="password" id="password" required><br>
    
    <label for="name">Meno*</label>
    <input type="text" name="name" id="name" required><br>

    <label for="surname">Priezvisko</label>
    <input type="text" name="surname" id="surname"><br>

    <label for="address">Adresa</label>
    <input type="text" name="address" id="address"><br>

    <label for="birth">Dátum narodenia</label>
    <input type="date" name="birth" id="birth" value="2000-01-01"><br>

    <label for="phone">Tel. číslo</label>
    <input type="text" name="phone" id="phone"><br>
    <label>Povinné polia sú označené *</label><br>
    <input type="hidden" value="0" name="role"/>
    <input type="hidden" value="0" name="works"/>
    <input type="submit" value="Registrovať" class="submit_button">
</form>

<a href="index.php">Späť na domovskú stránku</a>

<?php
make_footer();
?>
