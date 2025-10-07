<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $post_id = (int) $_POST['post_id'];
    $comment = trim($_POST['reply']);
    $author = "Anonymous";

    // Check if parent_id is provided (for nested replies)
    $parent_id = isset($_POST['parent_id']) && $_POST['parent_id'] !== "" ? (int) $_POST['parent_id'] : null;

    if ($post_id > 0 && $comment !== "") {
         // Insert reply
        $stmt = $conn->prepare("INSERT INTO comments (post_id, parent_id, author, comment) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iiss", $post_id, $parent_id, $author, $comment);
        $stmt->execute();
        $stmt->close();

        // increase reply count by 1
        $update_stmt = $conn->prepare("UPDATE posts SET replies = replies + 1 WHERE id = ?");
        $update_stmt->bind_param("i", $post_id);
        $update_stmt->execute();
        $update_stmt->close();  

        // Redirect back to the post
        header("Location: view_post.php?id=$post_id");
        exit();
    }
}
echo "Invalid reply.";
