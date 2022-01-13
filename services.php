<?php

class DBService{
    private $pdo;
    private $lastError;
    function __construct(){
        $this->pdo = $this->connect_db();
        $this->lastError = NULL;
    }

    function connect_db(){
        $dsn = 'mysql:host=localhost;dbname=xzabka04';
        $username = 'xzabka04';
        $password = 'ijnim8am';
        $options = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8');
        $pdo = new PDO($dsn, $username, $password, $options);
        return $pdo;
    }

    function getErrorMessage(){
        if ($this->lastError === NULL)
            return '';
        else
            return $this->lastError[2]; //the message
    }
    
    function createUser($data){
        $stmt = $this->pdo->prepare('INSERT INTO user (name, last_name, address, phone, birth, username, pwd, role , l_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)');
        $username = $data['username'];
        $pwd = password_hash($data['password'], PASSWORD_DEFAULT);
        $name = $data['name'];
        $last_name = $data['surname'];
        $phone = $data['phone'];
        $address = $data['address'];
        $birth = $data['birth'];
        $role = $data['role'];
        $works = $data['works'];
        if ($stmt->execute([$name, $last_name, $address, $phone, $birth, $username, $pwd, $role, $works])){
            $newid = $this->pdo->lastInsertId();
            $data['id'] = $newid;
            return $data;
        }
        else{
            $this->lastError = $stmt->errorInfo();
            echo $this->lastError[1];
            echo $this->lastError[2];
            return FALSE;
        }
    }

    function updateUser($data){
        $stmt = $this->pdo->prepare('UPDATE user SET name=:name, last_name=:last_name, address=:address, phone=:phone, birth=:birth, username=:username, role=:role, l_id=:works WHERE u_id = :id');
        if ($stmt->execute($data)){
            return TRUE;
        }
        else{
            $this->lastError = $stmt->errorInfo();
            return FALSE;
        }
    }

    function deleteUser($login){
        $stmt = $this->pdo->prepare('DELETE FROM user WHERE username = ?');
        if ($stmt->execute([$login])) {
            return TRUE;
        }
        else{
            $this->lastError = $stmt->errorInfo();
            return FALSE;
        }
    }
    
    function getUser($login){
        $stmt = $this->pdo->prepare('SELECT * FROM user WHERE username = ?');
        $stmt->execute([$login]);
        return $stmt->fetch();
    }

    function getUserByID($id){
        $stmt = $this->pdo->prepare('SELECT * FROM user WHERE u_id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    
    function isValidAccount($login, $password){
        $data = $this->getUser($login);
        return password_verify($password, $data['pwd']);
    }

    function getRole($login){
        $data = $this->getUser($login);
        return $data['role'];
    }
    
    function listUsers(){
        $stmt = $this->pdo->prepare('SELECT * FROM user WHERE role = 0');
        $stmt->execute();
        return $stmt->fetchAll();
    }

    function listWorkers(){
        $stmt = $this->pdo->prepare('SELECT * FROM user WHERE role = 1');
        $stmt->execute();
        return $stmt->fetchAll();
    }

    function listOthers(){
        $stmt = $this->pdo->prepare('SELECT * FROM user WHERE role = 3 OR role = 2');
        $stmt->execute();
        return $stmt->fetchAll();
    }

    function listLibraries(){
        $stmt = $this->pdo->prepare('SELECT * FROM library');
        $stmt->execute();
        return $stmt->fetchAll();
    }

    function getLibrary($name){
        $stmt = $this->pdo->prepare('SELECT * FROM library WHERE name = ?');
        $stmt->execute([$name]);
        return $stmt->fetch();
    }

    function deleteLibrary($name){
        $stmt = $this->pdo->prepare('DELETE FROM library WHERE name = ?');
        if ($stmt->execute([$name])) {
            return TRUE;
        }
        else{
            $this->lastError = $stmt->errorInfo();
            return FALSE;
        }
    }

    function updateLibrary($data){
        $stmt = $this->pdo->prepare('UPDATE library SET name=:name, address=:address, open=:open WHERE l_id = :id');
        if ($stmt->execute($data)){
            return TRUE;
        }
        else{
            $this->lastError = $stmt->errorInfo();
            return FALSE;
        }
    }

    function createLibrary($data){
        $stmt = $this->pdo->prepare('INSERT INTO library (name, address, open) VALUES (?, ?, ?)');
        $name = $data['name'];
        $address = $data['address'];
        $open = $data['open'];
        if ($stmt->execute([$name, $address, $open])){
            return TRUE;
        }
        else{
            $this->lastError = $stmt->errorInfo();
            echo $this->lastError[2];
            return FALSE;
        }
    }
    
    function createTitle($data){
        $stmt = $this->pdo->prepare('INSERT INTO title (ISBN, title_name, author, genre, rating) VALUES (?, ?, ?, ?, ?)');
        $isbn = $data['isbn'];
        $name = $data['name'];
        $author = $data['author'];
        $genre = $data['genre'];
        $rating = $data['rating'];
        if ($stmt->execute([$isbn, $name, $author, $genre, $rating])){
            return TRUE;
        }
        else{
            $this->lastError = $stmt->errorInfo();
            echo $this->lastError[2];
            return FALSE;
        }
    }

    function listOrders(){
        $stmt = $this->pdo->prepare('SELECT * FROM book_order NATURAL JOIN library');
        $stmt->execute();
        return $stmt->fetchAll();
    }

    function deleteOrder($id){
        $stmt = $this->pdo->prepare('DELETE FROM book_order WHERE o_id = ?');
        if ($stmt->execute([$id])) {
            return TRUE;
        }
        else{
            $this->lastError = $stmt->errorInfo();
            return FALSE;
        }
    }

    function createBook($isbn,$id){
        $stmt = $this->pdo->prepare('INSERT INTO book (isbn, l_id, active) VALUES (?, ?, 1)');
        if ($stmt->execute([$isbn, $id])){
            return TRUE;
        }
        else{
            $this->lastError = $stmt->errorInfo();
            echo $this->lastError[2];
            return FALSE;
        }
    }

    function listOtherTitles($id){
        $stmt = $this->pdo->prepare('SELECT * FROM title WHERE isbn NOT IN (SELECT isbn from offers WHERE l_id = ?)');
        $stmt->execute([$id]);
        return $stmt->fetchAll();
    }

    function listTitles($id){
        $stmt = $this->pdo->prepare('SELECT * FROM offers NATURAL JOIN title WHERE l_id = ?');
        $stmt->execute([$id]);
        return $stmt->fetchAll();
    }

    function createOffer($isbn,$id){
        $stmt = $this->pdo->prepare('INSERT INTO offers (isbn, l_id, votes) VALUES (?, ?, 0)');
        if ($stmt->execute([$isbn, $id])){
            return TRUE;
        }
        else{
            $this->lastError = $stmt->errorInfo();
            echo $this->lastError[2];
            return FALSE;
        }
    }

    function createOrder($isbn,$id,$count){
        $stmt = $this->pdo->prepare('INSERT INTO book_order (isbn, l_id, count) VALUES (?, ?, ?)');
        if ($stmt->execute([$isbn, $id, $count])){
            return TRUE;
        }
        else{
            $this->lastError = $stmt->errorInfo();
            echo $this->lastError[2];
            return FALSE;
        }
    }

    function numberOfBooks($isbn,$id){
        $stmt = $this->pdo->prepare('SELECT * FROM book WHERE isbn = ? AND l_id = ?');
        $stmt->execute([$isbn,$id]);
        $data = $stmt->fetchAll();
        return count($data);
    }

    function numberOfActiveBooks($isbn,$id){
        $stmt = $this->pdo->prepare('SELECT * FROM book WHERE isbn = ? AND l_id = ? AND active = 1');
        $stmt->execute([$isbn,$id]);
        $data = $stmt->fetchAll();
        return count($data);
    }

    function listLoansLibrary($id){
        $stmt = $this->pdo->prepare('SELECT * FROM loan JOIN user ON loan.u_id = user.u_id JOIN book ON loan.b_id = book.b_id JOIN title ON book.isbn = title.isbn WHERE loan.l_id = ?');
        $stmt->execute([$id]);
        return $stmt->fetchAll();
    }

    function listLoansAll(){
        $stmt = $this->pdo->prepare('SELECT * FROM loan JOIN user ON loan.u_id = user.u_id JOIN book ON loan.b_id = book.b_id JOIN title ON book.isbn = title.isbn');
        $stmt->execute();
        return $stmt->fetchAll();
    }

    function createLoan($b_id,$u_id,$datefrom,$dateto,$l_id){
        $stmt = $this->pdo->prepare('UPDATE book SET active = 0  WHERE b_id = ?');
        $stmt->execute([$b_id]);
        $stmt = $this->pdo->prepare('INSERT INTO loan (b_id, u_id, d_from, d_to, l_id) VALUES (?, ?, ?, ?, ?)');
        if ($stmt->execute([$b_id,$u_id,$datefrom,$dateto,$l_id])){
            return TRUE;
        }
        else{
            $this->lastError = $stmt->errorInfo();
            echo $this->lastError[2];
            return FALSE;
        }
    }

    function deleteLoan($id,$b_id){
        $stmt = $this->pdo->prepare('DELETE FROM loan WHERE ll_id = ?');
        if ($stmt->execute([$id])) {
            $stmt = $this->pdo->prepare('UPDATE book SET active = 1  WHERE b_id = ?');
            $stmt->execute([$b_id]);
            return TRUE;
        }
        else{
            $this->lastError = $stmt->errorInfo();
            return FALSE;
        }
    }

    function getBook($id){
        $stmt = $this->pdo->prepare('SELECT * FROM book WHERE b_id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    function getLoan($id){
        $stmt = $this->pdo->prepare('SELECT * FROM loan WHERE ll_id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    function updateLoan($id,$date){
        $stmt = $this->pdo->prepare('UPDATE loan SET d_to=? WHERE ll_id = ?');
        if ($stmt->execute([$date,intval($id)])){
            return TRUE;
        }
        else{
            $this->lastError = $stmt->errorInfo();
            return FALSE;
        }
    }

    function listTitlesHome(){
        $stmt = $this->pdo->prepare('SELECT title.isbn,title.title_name,title.author,title.genre,title.rating,library.name,library.address,library.open,library.l_id FROM offers JOIN title ON offers.isbn = title.isbn JOIN library ON offers.l_id = library.l_id');
        $stmt->execute();
        return $stmt->fetchAll();
    }

    function listLoansUser($id){
        $stmt = $this->pdo->prepare('SELECT * FROM loan JOIN library ON loan.l_id = library.l_id JOIN book ON loan.b_id = book.b_id JOIN title ON book.isbn = title.isbn WHERE loan.u_id = ?');
        $stmt->execute([$id]);
        return $stmt->fetchAll();
    }

    function vote($u_id,$l_id,$isbn){
        $stmt = $this->pdo->prepare('UPDATE offers SET votes = votes + 1  WHERE l_id = ? AND isbn = ?');
        $stmt->execute([$l_id,$isbn]);
        $stmt = $this->pdo->prepare('INSERT INTO voted (u_id, l_id, isbn) VALUES (?, ?, ?)');
        if ($stmt->execute([$u_id,$l_id,$isbn])){
            return TRUE;
        }
        else{
            $this->lastError = $stmt->errorInfo();
            echo $this->lastError[2];
            return FALSE;
        }
    }

    function votedAlready($u_id,$l_id,$isbn){
        $stmt = $this->pdo->prepare('SELECT * FROM voted WHERE u_id = ? AND l_id = ? AND isbn = ?');
        $stmt->execute([$u_id,$l_id,$isbn]);
        $data = $stmt->fetchAll();
        if(count($data) == 0){
            return FALSE;
        }
        else{
            return TRUE;
        }
    }

    function takeOneBook($isbn,$l_id){
        $stmt = $this->pdo->prepare('SELECT * FROM book WHERE isbn = ? AND l_id = ? AND active = 1');
        $stmt->execute([$isbn,$l_id]);
        $data = $stmt->fetchAll();
        return $data[0]['b_id'];
    }

    function createWaiting($u_id,$isbn,$l_id){
        $stmt = $this->pdo->prepare('INSERT INTO waiting (u_id, isbn, l_id) VALUES (?, ?, ?)');
        if ($stmt->execute([$u_id,$isbn,$l_id])){
            return TRUE;
        }
        else{
            $this->lastError = $stmt->errorInfo();
            echo $this->lastError[2];
            return FALSE;
        }
    }

    function deleteWaiting($u_id,$isbn,$l_id){
        $stmt = $this->pdo->prepare('DELETE FROM waiting WHERE u_id = ? AND isbn = ? AND l_id = ?');
        if ($stmt->execute([$u_id,$isbn,$l_id])) {
            return TRUE;
        }
        else{
            $this->lastError = $stmt->errorInfo();
            return FALSE;
        }
    }

    function someoneWaiting($isbn,$l_id){
        $stmt = $this->pdo->prepare('SELECT * FROM waiting WHERE isbn = ? AND l_id = ?');
        $stmt->execute([$isbn,$l_id]);
        $data = $stmt->fetchAll();
        echo "ALL WAITERS ON ISBN=$isbn AND L_ID=$l_id";
        print_r($data);
        if(count($data) > 0){
            $u_id = $data[0]['u_id'];
            $datefrom = date('Y-m-d');
            $dateto = '2022-01-01';
            $b_id = $this->takeOneBook($isbn,$l_id);
            $this->createLoan($b_id,$u_id,$datefrom,$dateto,$l_id);
            $this->deleteWaiting($u_id,$isbn,$l_id);
        }
    }
}
