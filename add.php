<?php include 'db.php'; ?>

<!DOCTYPE html>
<html>
    <head><title>Add Book</title></head>
<body>
    <h1>Add Book</h1>

    <!-- Add New Book Form -->
    <form method="POST">
        Title: <input type="text" name="title"><br><br>
        Author: <input type="text" name="author"><br><br>
        Year: <input type="number" name="year"><br><br>
        <button type="submit" name="submit">Add Book</button>
    </form>

    <!-- Return to Homepage -->
    <a href="index.php">
        <button>Go Back</button>
    </a>

<?php 
    // adds information from form to library table
    if($_SERVER["REQUEST_METHOD"] == "POST") {
        
        $title = $_POST["title"];
        $author = $_POST["author"];
        $year = $_POST["year"];

        $stmt = $conn->prepare("INSERT INTO books (title, author, year_published) VALUES (?, ?, ?)");
        $stmt->bind_param("ssi", $title, $author, $year);

        if ($stmt->execute()) {
            // redirects to index.php  
            header("Location: index.php");
            exit;
        } else {
            // otherwise displays error
            echo "Error: " . $stmt->error;
        }
    }
?>

</body>
</html>
