<?php include "db.php"; ?>

<!DOCTYPE html>

<html>
    <head><title>Update Book</title></head>

<body>
    <?php 
    // checks for invalid book id
    if(!isset($_GET["id"]) || !is_numeric($_GET["id"])) {
        die("Error: Invalid or missing book ID");
    }

    // finds row in table using id
    $id = (int)$_GET["id"];
    $result = $conn->query("SELECT * FROM books WHERE book_id=$id");
    $row = $result->fetch_assoc();
    ?>

    <h1>Edit Book</h1>
    
    <!-- Update Book Form -->
     
    <form method="POST"> 
        Title: <input type="text" name="title" value="<?php echo $row['title']; ?>" required><br>
        Author: <input type="text" name="author" value="<?php echo $row['author']; ?>" required><br>
        Year: <input type="number" name="year" value="<?php echo $row['year_published']; ?>" required><br>
        ISBN: <input type="text" name="isbn" value="<?php echo $row['isbn']; ?>" required maxlength="5"><br>
        
        <input type="submit" name=submit value="Update Book">
    </form><br>

    <!-- Return to Homepage -->
    <a href="index.php">
        <button>Go Back</button>
    </a>    

<?php
    // updates book in table
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $title = $_POST['title'];
        $author = $_POST['author'];
        $year = $_POST['year'];
        $isbn = $_POST['isbn'];

        $stmt = $conn->prepare("UPDATE books 
                        SET title = ?, author = ?, year_published = ?, isbn = ?
                        WHERE book_id = ?");

        $stmt->bind_param("ssisi", $title, $author, $year, $isbn, $id); 

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
    }
?>

</body>
</html>

