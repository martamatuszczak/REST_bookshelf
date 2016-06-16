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
    if(!empty($_POST['title']) && !empty($_POST['author_name']) && !empty($_POST['description'])) {
        $newBook = new Book();
        $newBook->setTitle($_POST['title']);
        $newBook->setAuthor($_POST['author_name']);
        $newBook->setDescription($_POST['description']);
        $newBook->saveToDB($conn);
        $newBookJSON = json_encode($newBook);
        echo($newBookJSON);
    }   
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
    $bookToEditId = $put_vars['id'];
    $bookToEdit->editDB($conn, $bookToEditId, $put_vars['title'], $put_vars['author'], $put_vars['description']);
    $bookToEdit->saveToDB($conn, $bookId);
    $bookToEditJSON = json_encode($bookToEdit);
    echo($bookToEditJSON);   
}