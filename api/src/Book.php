<?php

class Book {
    
    static public function getBooksNames(mysqli $conn) {
        $ret = [];
        $sql = "SELECT id, name FROM Books";
        $result = $conn->query($sql);
        
        if($result != false) {
            if($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    $ret[] = $row;
                }
            }
        }
        return $ret;
    }


    private $id;
    private $title;
    private $author;
    private $description;
    
    public function __construct() {
        $this->id = -1;
        $this->title = "";
        $this->author = "";
        $this->description ="";
        
    }
    
    public function getID() {
        return $this->id;
    }
    
    public function setTitle($newTitle) {
        $this->title = $newTitle;
    }
    
    public function getTitle() {
        return $this->title;
    }
    
    public function setAuthor($newAuthor) {
        $this->author = $newAuthor;
    }
    
    public function getAuthor() {
        return $this->author;
    }
    
    public function setDescription($newDescription) {
        $this->description = $newDescription;
    }
    
    public function getDescription() {
        return $this->description;
    }
    
    public function loadFromDB($id, mysqli $conn) {
        $sql = "SELECT * FROM Books WHERE id = {$id}";
        $result = $conn->query($sql);
        if($result != false) {
            if($result->num_rows === 1) {
                $row = $result->fetch_assoc();
                $this->id = (int)$row["id"];
                $this->title = $row["name"];
                $this->author = $row["author_name"];
                $this->description = $row["description"];
                return true;
            }
        }
    }
    
    public function saveToDB(mysqli $conn) {
        if($this->id === -1) {
            $sql = "INSERT INTO Books(name, author_name, description)
                    VALUES ('{$this->title}', '{$this->author}', '{$this->description}')";
            $result = $conn->query($sql);
            if($result == true) {
                $this->id = $conn->insert_id;
                return true;
            }
            return false;
        }
    }
    
    public function editDB(mysqli $conn, $id, $title, $author, $description) {

        $sql = "UPDATE Books SET name = '$title', author_name = '$author', description = '$description' WHERE id=$id";
        $result = $conn->query($sql);
        if($result == true) {
            return true;
        }
        return false;  
    }

    
    public function toArray() {
        $ret = [];
        
        $ret['id'] = $this->id;
        $ret['title'] = $this->title;
        $ret['author'] = $this->author;
        $ret['description'] = $this->description;
        
        return $ret;
    }
    
    public function deleteFromDB($conn) {
        $sql = "DELETE FROM Books WHERE id = {$this->id}";
        $result = $conn->query($sql);
            if($result == true) {
                return true;
            }
            return false;  
    }
}