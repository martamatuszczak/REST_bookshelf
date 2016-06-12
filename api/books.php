<?php
require_once('./src/conn.php');

if($_SERVER['REQUEST_METHOD'] === 'GET') {
    if(isset($_GET['id'])) {
        $bookToShow = new Book();
        $bookToShow->loadFromDB($_GET["id"], $conn);
        $bookToShowJSON = json_encode($bookToShow->toArray());
        echo($bookToShowJSON);
    }
    else {
    $allBooksNames = Book::getBooksNames($conn);
    $allBooksNamesJSON = json_encode($allBooksNames);
    echo($allBooksNamesJSON);
    }
}

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    
        $newBook = new Book();
        $newBook->setTitle($_POST['title']);
        $newBook->setAuthor($_POST['author_name']);
        $newBook->setDescription($_POST['description']);
        $newBook->saveToDB($conn);
        $newBookJSON = json_encode($newBook);
        echo($newBookJSON);
}

if($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    
    $bookToDelete = new Book();
    parse_str(file_get_contents("php://input"), $del_vars);
    $bookId = $del_vars['id'];
    $bookToDelete->loadFromDB($bookId, $conn);
    $bookToDelete->deleteFromDB($conn);
    $bookToDeleteJSON = json_encode($bookToDelete);
    echo($bookToDeleteJSON);
}

if($_SERVER['REQUEST_METHOD'] === 'PUT') {
    
    parse_str(file_get_contents("php://input"), $put_vars);
    $bookToEdit = new Book();
    $bookId = $put_vars['id'];
    $bookToEdit->loadFromDB($bookId, $conn);
    $bookToEdit->setTitle($put_vars['editTitle']);
    $bookToEdit->setAuthor($put_vars['editAuthor']);
    $bookToEdit->setDescription($put_vars['editDescription']);
    $bookToEdit->saveToDB($conn, $bookId);
    $bookToEditJSON = json_encode($bookToEdit->toArray());
    echo($bookToEditJSON);
}