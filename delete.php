<?php include "db.php"; ?>

<?php 
    // checks for invalid book id
    if(!isset($_GET["id"]) || !is_numeric($_GET["id"])) {
        die("Error: Invalid or missing book ID");
    }

    $id = (int) $_GET["id"];

    $stmt = $conn->prepare("DELETE FROM books WHERE book_id=?");
    $stmt->bind_param("i", $id);
   
    if($stmt->execute() === TRUE) {
        // redirects to index.php
        header("Location: index.php");
        exit;
    } else {
        // otherwise displays error
        echo "Error: " . $conn->error;
    }

?>