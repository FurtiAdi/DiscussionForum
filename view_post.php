<?php
header('Content-Type: text/html');
require 'db.php';
$post_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($post_id > 0) {
    // Load post
    $stmt = $conn->prepare("SELECT title, author, content, created_at FROM posts WHERE id = ?");
    $stmt->bind_param("i", $post_id);
    $stmt->execute();
    $post_result = $stmt->get_result();
    $post = $post_result->fetch_assoc();
    $stmt->close();
    // Load main template
    $template = file_get_contents("view_post_template.html");
    //Format the time
    $rawDate = htmlspecialchars($post['created_at']);
    $dateObj = new DateTime($rawDate);
    $formatted = $dateObj->format('l, j F Y, g:i A'); // e.g. Thursday, 12 June 2025, 3:59 PM
    //Fetch author
    $author = htmlspecialchars($post['author'] ?? 'Guest');
    // Fill in main post
    $template = str_replace("{{title}}", htmlspecialchars($post['title']), $template);
    $template = str_replace("{{author}}", $author, $template);
    $template = str_replace("{{created_at}}", $formatted, $template);
    $template = str_replace("{{content}}", nl2br(htmlspecialchars($post['content'])), $template);
    $template = str_replace("{{post_id}}", $post_id, $template);
    // Generate replies
    $title = htmlspecialchars($post['title']);
    $replies_html = fetchReplies( $title, $conn, $post_id);
    $template = str_replace("{{replies}}", $replies_html, $template);
    // Show the page
    echo $template;
} else {
    echo "Invalid post ID.";
}

function fetchReplies( $title, $conn, $post_id, $parent_id = null) {
    $stmt = $conn->prepare("SELECT id, author, comment, created_at FROM comments WHERE post_id = ? AND parent_id " . ($parent_id === null ? "IS NULL" : "= ?") . " ORDER BY created_at ASC");

    if ($parent_id === null) {
        $stmt->bind_param("i", $post_id);
    } else {
        $stmt->bind_param("ii", $post_id, $parent_id);
    }

    $stmt->execute();
    $result = $stmt->get_result();
    $html = "";

    $template = file_get_contents("reply_template.html");

    while ($row = $result->fetch_assoc()) {
        $reply_html = $template;
        $reply_html = str_replace("{{title}}", $title, $reply_html);
        $reply_html = str_replace("{{author}}", htmlspecialchars($row['author']), $reply_html);
        $reply_html = str_replace("{{created_at}}", htmlspecialchars($row['created_at']), $reply_html);
        $reply_html = str_replace("{{comment}}", nl2br(htmlspecialchars($row['comment'])), $reply_html);
        $reply_html = str_replace("{{id}}", $row['id'], $reply_html);
        $reply_html = str_replace("{{post_id}}", $post_id, $reply_html);
       
        // Recursively fetch child replies
        $children_html = fetchReplies($title, $conn, $post_id, $row['id']);
        $reply_html = str_replace("{{replies}}", $children_html, $reply_html);

        $html .= $reply_html;
    }

    $stmt->close();
    return $html;
}
