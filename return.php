<?php include 'db.php'; ?>

<?php
    // updates book status
    $id = $_GET['id'];
    $sql = "UPDATE books SET status='available',
                             checked_out_by=NULL
                             WHERE book_id=$id";

    if ($conn->query($sql) === TRUE) {
        // redirects to index.php
        header("Location: index.php");
        exit;
    } else {
        // otherwise displays error
        echo "Error: " . $conn->error;
    }
?>