<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['task'])) {
        // Add activity
        $task = $conn->real_escape_string($_POST['task']);
        $sql = "INSERT INTO todos (task) VALUES ('$task')";
        if (!$conn->query($sql)) {
            echo "Error: " . $conn->error;
        }
    } elseif (isset($_POST['update_task']) && isset($_POST['task_id'])) {
        // Update activity
        $task_id = (int)$_POST['task_id'];
        $updated_task = $conn->real_escape_string($_POST['updated_task']);
        $sql = "UPDATE todos SET task = '$updated_task' WHERE id = $task_id";
        if (!$conn->query($sql)) {
            echo "Error: " . $conn->error;
        } else {
            header('Location: index.php');
            exit;
        }
    }
}

if (isset($_GET['edit']) && isset($_GET['status'])) {
    // edit status activity
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
    // delete activity
    $id = (int)$_GET['delete'];
    $sql = "DELETE FROM todos WHERE id = $id";
    if (!$conn->query($sql)) {
        echo "Error: " . $conn->error;
    } else {
        header('Location: index.php');
        exit;
    }
}

$editing_task = null;
if (isset($_GET['edit_task'])) {
    $task_id = (int)$_GET['edit_task'];
    $sql = "SELECT * FROM todos WHERE id = $task_id";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $editing_task = $result->fetch_assoc();
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

        <?php if ($editing_task): ?>
            <form method="POST" action="">
                <input type="hidden" name="task_id" value="<?= $editing_task['id'] ?>">
                <input type="text" name="updated_task" value="<?= htmlspecialchars($editing_task['task']) ?>" required>
                <button type="submit" name="update_task">Update</button>
            </form>
        <?php else: ?>
            <form method="POST" action="">
                <input type="text" name="task" placeholder="Add a new activity" required>
                <button type="submit" name="add_task">Tambah</button>
            </form>
        <?php endif; ?>

        <ul>
            <?php while ($row = $result->fetch_assoc()): ?>
                <li class="<?= htmlspecialchars($row['status'] == 'completed' ? 'completed' : '') ?>">
                    <?= htmlspecialchars($row['task']); ?>
                    <div class="actions">
                        <a href="?edit=<?= $row['id'] ?>&status=<?= $row['status'] ?>">Selesai</a>
                        <a href="?edit_task=<?= $row['id'] ?>">Edit</a>
                        <a href="?delete=<?= $row['id'] ?>" onclick="return confirm('Are you sure you want to delete?');">Hapus</a>
                    </div>
                </li>
            <?php endwhile; ?>
        </ul>
    </div>
</body>
</html>
