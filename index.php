<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['task'])) {
    $task = $conn->real_escape_string($_POST['task']);
    $sql = "INSERT INTO todos (task) VALUES ('$task')";
    if (!$conn->query($sql)) {
        echo "Error: " . $conn->error;
    }
}

if (isset($_GET['edit']) && isset($_GET['status'])) {
    $id = (int)$_GET['edit'];
    $new_status = $_GET['status'] === 'completed' ? 'pending' : 'completed';
    $sql = "UPDATE todos SET status = '$new_status' WHERE id = $id";
    if (!$conn->query($sql)) {
        echo "Error: " . $conn->error;
    } else {
        header('Location: index.php');
        exit;
    }
}

if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $sql = "DELETE FROM todos WHERE id = $id";
    if (!$conn->query($sql)) {
        echo "Error: " . $conn->error;
    } else {
        header('Location: index.php');
        exit;
    }
}

$sql = "SELECT * FROM todos ORDER BY id DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>To-Do List</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>To-Do List</h1>
        <form method="POST" action="">
            <input type="text" name="task" placeholder="Add a new activity" required>
            <button type="submit" name="add_task">Tambah</button>
        </form>
        <ul>
            <?php while ($row = $result->fetch_assoc()): ?>
                <li class="<?= htmlspecialchars($row['status'] == 'completed' ? 'completed' : '') ?>">
                    <?= htmlspecialchars($row['task']); ?>
                    <div class="actions">
                        <a href="?edit=<?= $row['id'] ?>&status=<?= $row['status'] ?>">Selesai</a>
                        <a href="?delete=<?= $row['id'] ?>" onclick="return confirm('Are you sure you want to delete?');">Hapus</a>
                    </div>
                </li>
            <?php endwhile; ?>
        </ul>
    </div>

</body>
</html>
