<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $comment = $_POST['comment'];

    // Add validation and sanitation as needed

    // Append the new comment to the existing comments array
    $newComment = ['name' => $name, 'comment' => $comment];
    $comments = include 'submit_comment.php';
    $comments[] = $newComment;

    // Save the updated comments array to the comments.php file
    file_put_contents('submit_comment.php', '<?php return ' . var_export($comments, true) . '; ?>');

    // Redirect back to the main page
    header('Location: feedbackpagina.php');
    exit;
}
?>
