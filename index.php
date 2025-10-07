<?php
header('Content-Type: text/html');
require 'db.php';
// Load the HTML template
$template = file_get_contents("index.html");

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_button'])) {
  $subject = trim($_POST['subject']);
  $username = trim($_POST['author']);
  $message = trim($_POST['text_area']);

  if ($subject !== "" && $message !== "" &&  $username!=="") {
    $stmt = $conn->prepare("INSERT INTO posts (title, content, author) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $subject, $message, $username);
    $stmt->execute();
    $stmt->close();
    // Prevenst resubmission
    header("Location: index.php");
    exit();
  }
}
// Fetch posts from database
$result = $conn->query("SELECT id, title, author, replies FROM posts ORDER BY created_at DESC");
$rows = "";
while ($row = $result->fetch_assoc()) {
    $id = $row['id'];
    $title = htmlspecialchars($row['title']);
    $author= htmlspecialchars($row['author']);
    $replies = $row['replies'];
    $rows .= "<tr><td><a href=\"view_post.php?id=$id\">$title</a></td><td>$author</td><td>$replies</td></tr>\n";
}
// Insert data or remove section
if ($rows === "") {
    $template = str_replace(["{{discussion_head}}", "{{discussion_rows}}"], "", $template);
} else {
    $head = "<th>Discussion</th>\n<th>Started by</th>\n<th>Replies</th>";
    $template = str_replace("{{discussion_head}}", $head, $template);
    $template = str_replace("{{discussion_rows}}", $rows, $template);
}
// Output final result
echo $template;
?>
