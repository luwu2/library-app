<?php include 'db.php'; ?>

<?php
    // updates book status
    $id = (int)$_GET['id'];
    $stmt = $conn->prepare("UPDATE books SET status='available',
                             checked_out_by=NULL
                             WHERE book_id=?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute() === TRUE) {
        $stmt->close();
        $conn->close();
        // redirects to index.php
        header("Location: index.php");
        exit;
    } else {
        // otherwise displays error
        echo "Error: " . $conn->error;
    }
?>

