<?php include "db.php"; ?>

<?php 
    $id = $_GET['id'];
    $sql = "SELECT title FROM books where book_id=$id";
    $result = $conn->query($sql);
    if (!$result) {
        die("Database query failed: " . $conn->error);
    }
    $row = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html>
    <title>
        Check Out Book
    </title>

    <!-- Check Out Book Name Entry -->
    <body>
        <h1>Checking Out: <?php echo "{$row['title']}";?></h1>
        <h1>Enter Name: <br></h1>
        <form method="POST">
            Name: <input type="text" name="check_out_name" placeholder="Enter Name" required> <br>
            <input type="submit" value="Check Out">
        </form><br>
        
        <!-- Return to Homepage -->
        <a href="index.php">
            <button>Go Back</button>
        </a>

    </body>

</html>

<?php
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        // get value of string with no special chars from post
        $check_out_name = $conn->real_escape_string($_POST['check_out_name']);

        // updates book status
        $sql = "UPDATE books SET status='checked_out',
                                 checked_out_by='$check_out_name'
                                WHERE book_id=$id";

        if ($conn->query($sql) === TRUE) {
            $conn->close();
            // redirects to index.php
            header("Location: index.php");
            exit;
        } else {
            // otherwise displays error
            echo "Error: " . $conn->error;
        }
    }
?>