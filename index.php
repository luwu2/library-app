<?php include 'db.php'; ?>

<!DOCTYPE html>
<html>
<head>
        <title>Library System</title>
</head>
<body>
    <h1>Library Catalog</h1>

    <!-- Add Book -->
    <a href="add.php">Add Book<br><br></a>
    
    <!-- Search Form -->
    <form method="get" action="index.php">
        <input type="text" name="search" placeholder="Search by title, author, or year">
        <input type="submit" value="Search">
    </form>

    <!-- Library Catalog Table -->
    <table border="1" cellpadding="8">
        <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Author</th>
            <th>Year</th>
            <th>ISBN</th>
            <th>Status</th>
            <th>Actions</th>
            <th>Checked Out By</th>
        </tr>
    
    <?php

    // books per page limit
    $limit = 15; 

    // determine page from url
    if (isset($_GET['page'])) {
        $page = (int)$_GET['page'];
    } else {
        $page = 1;
    }
    if ($page < 1) {
        $page = 1;
    }

    $offset = ($page -1) * $limit;

    // get rows for current page accounting for search

    /**
     * ADD SEARCH BY ISBN
     */
    if (isset($_GET['search']) && $_GET['search'] !== '') {
        $search = $_GET['search'];

        $s_title = '%' . str_replace(' ', '%', $search) .'%';
        $s_author = '%' . str_replace(' ', '%', $search) .'%';
        $s_year = '%' . str_replace(' ', '%', $search) .'%';

        $stmt = $conn->prepare("SELECT * FROM books 
                        WHERE title LIKE '$s_title' 
                           OR author LIKE '$s_author'
                           OR year_published LIKE '$s_year'
                        LIMIT ? OFFSET ?");
        $stmt->bind_param("ii", $limit, $offset);
        $stmt->execute();
        $result = $stmt->get_result();

        // count total rows for pagination
        $count_stmt = $conn->prepare("SELECT COUNT(*) as count FROM books 
                                      WHERE title LIKE CONCAT('%', ?, '%') 
                                         OR author LIKE CONCAT('%', ?, '%') 
                                         OR year_published LIKE CONCAT('%', ?, '%')");
        $count_stmt->bind_param("sss", $search, $search, $search);
        $count_stmt->execute();
        $total_rows = $count_stmt->get_result()->fetch_assoc()['count'];
        $count_stmt->close();

    } else {
        // Get rows for current page without search
        $stmt = $conn->prepare("SELECT * FROM books LIMIT ? OFFSET ?");
        $stmt->bind_param("ii", $limit, $offset);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();

        $total_result = $conn->query("SELECT COUNT(*) as count FROM books");
        $total_rows = $total_result->fetch_assoc()['count'];
    }

    // display rows from $result
    while($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>{$row['book_id']}</td>";
        echo "<td>{$row['title']}</td>";
        echo "<td>{$row['author']}</td>";

        // adjusts for years before 0 CE
        if($row['year_published'] < 0) {
            echo "<td>" . abs($row['year_published']) . " BC</td>";
        } else {
            echo "<td>{$row['year_published']}</td>"; 
        }

        echo "<td>{$row['isbn']}</td>";

        // format status for diplay
        if($row['status'] == 'checked_out') {
            echo "<td>Checked Out</td>";
        } else if($row['status'] == 'available') {
            echo "<td>Available</td>";
        } else { // if status value invalid
            echo "Error: Invalid status.";
        }

        // actions column
        echo "<td>
            <a href='update.php?id={$row['book_id']}'>Edit</a> |
            <a href='delete.php?id={$row['book_id']}'
            onclick=\"return confirm('Are you sure you want to delete {$row['title']}?');\">Delete</a> |";

        // displays only either available or checked out 
        if ($row['status'] === 'available') {
            echo " <a href='checkout.php?id={$row['book_id']}'>Checkout</a>";
        } elseif ($row['status'] === 'checked_out') {
            echo " <a href='return.php?id={$row['book_id']}'>Return</a>";
        }

        echo "</td>";

        if($row['status'] === 'checked_out') {
            echo "<td>{$row['checked_out_by']}</td>";
        } else {
            echo "<td></td>";
        }
        
    }
    ?>
    </table>
    <?php

    $total_pages = ceil($total_rows / $limit);

    echo "<div style='margin-top:20px;'>";

    // display previous button
    if($page > 1) {
        $prev = ($page -1);
        $url = "index.php?page=$prev";
        if(isset( $_GET["search"]) && $_GET["search"] !== "") {
            $url .= "&search=" . urlencode($_GET["search"]);
        }
        echo "<a href='$url'> Previous</a> ";
    }

    // display number pages
    for ($i = 1; $i <= $total_pages; $i++) {
        // bolds page or adds link
        if ($i == $page) {
            echo "<strong>$i</strong> ";
            continue;
        }
        
        $url = "index.php?page=$i";
        if (isset($_GET['search']) && $_GET['search'] !== '') {
            // append search to url
            $url .= "&search=" . urlencode($_GET['search']);
        }
        echo "<a href='$url'>$i</a> ";
    }

    // display next button
    if($page != $total_pages) {
        $next = $page +1;
        $url = "index.php?page=$next";
        if(isset( $_GET["search"]) && $_GET["search"] !== "") {
            $url .= "&search=" . urlencode($_GET["search"]);
        }
        echo "<a href='$url'> Next</a> ";
    }

    echo "</div>";
    ?>
</body>
</html>

<?php 
$conn->close();
?>