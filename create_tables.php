<?php
require 'db.php';

// Drop existing tables if they exist (drop comments first because it references posts)
$conn->query("DROP TABLE IF EXISTS comments");
$conn->query("DROP TABLE IF EXISTS posts");

$sql1 = "CREATE TABLE posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    author VARCHAR(255) DEFAULT 'Anonymous',
    content TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    replies INT DEFAULT 0
  )";

// Execute first table creation
if ($conn->query($sql1) === TRUE) {
  echo "Table 'posts' created successfully.<br>";
} else {
  echo "Error creating 'posts': " . $conn->error . "<br>";
}

$sql2 = "CREATE TABLE comments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    post_id INT NOT NULL,
    parent_id INT DEFAULT NULL,
    author VARCHAR(255) DEFAULT 'Anonymous',
    comment TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (post_id) REFERENCES posts(id)
  )";

// Execute second table creation
if ($conn->query($sql2) === TRUE) {
  echo "Table 'comments' created successfully.<br>";
} else {
  echo "Error creating 'comments': " . $conn->error . "<br>";
}
