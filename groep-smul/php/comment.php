<?php

function getComments()
{
    $comments = include 'comments.php';
    return is_array($comments) ? $comments : [];
}
function displayComments($comments)
{
    foreach ($comments as $comment) {
        echo '<div class="comment">';
        echo '<h4>' . htmlspecialchars($comment['name']) . '</h4>';
        echo '<p>' . nl2br(htmlspecialchars($comment['comment'])) . '</p>';
        echo '</div>';
    }
}

function addComment($name, $comment)
{
    // Add validation and sanitation as needed

    // Retrieve existing comments
    $comments = getComments();

    // Append the new comment to the existing comments array
    $newComment = ['name' => $name, 'comment' => $comment];
    $comments[] = $newComment;

    // Save the updated comments array to the comments.php file
    file_put_contents('comments.php', '<?php return ' . var_export($comments, true) . '; ?>');

    // Redirect back to the main page
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $comment = $_POST['comment'];

    addComment($name, $comment);
}

// Get the comments
$comments = getComments();

?>

<!DOCTYPE html>
<html>
    <link rel="stylesheet" href="../css/smoelenboek.css">

<title>smoelenboek</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<head>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
     
    </head>
    <body>
    <div id="navbar">
    <div class="navbar" id="myNavbar">
    
        <a href="../html/index-berat.html"  style=" font-family: Copperplate, Papyrus, fantasy;"class="button">Home    </a>
        <a href="../html/contact-berat.html"style=" font-family: Copperplate, Papyrus, fantasy; "class="button">Contact</a>
        <a href="./comment.php"style=" font-family: Copperplate, Papyrus, fantasy; "class="button">feedback  ↓ </a>
        <a href="../index.html"style=" font-family: Copperplate, Papyrus, fantasy; "class="button">Hub  </a>
        <i class="fa fa-university" aria-hidden="true" style="float: right; margin-right: 10px; color: aliceblue; font-size: 50px;"></i>
        <a href="javascript:void(0);" class="icon" onclick="myFunction()">
    
   
        </a>
    </div>
    </div>


    <section>
        
    <div style="margin-top:10px;padding:15px 15px 10px;font-size:30px">
    </div>
    <div class="comment-section">
    <h2 style="font-family: copperplate, copperplate Gothic light,fantasy;">Comments</h2>
    
    <!-- Display comments -->
    <?php include 'comments.php'; ?>
    
    <!-- Comment form -->
    <form action="add_comment.php" method="post">
        <label for="name"style="font-family: copperplate, copperplate Gothic light,fantasy;">Name:</label>
        <input type="text" id="name" name="name" required>
        
        <label for="comment"style="font-family: copperplate, copperplate Gothic light,fantasy;">Comment:</label>
        <textarea id="comment" name="comment" required></textarea>
        
        <button type="submit"style="font-family: copperplate, copperplate Gothic light,fantasy;">Submit Comment</button>
    </form>
</div>
</div>


<style>
    /* Custom button styles for the comment section */
    .comment-section .button {
        background-color: #13AE2D;
        color: #fff;
        padding: 10px;
        border: none;
        cursor: pointer;
        width: 200px;
        font-family: Copperplate, Copperplate Gothic Light, fantasy;
    }
</style>

<div class="comment-section">
    <h2 style="font-family: Copperplate, Copperplate Gothic Light, fantasy;">Comments</h2>

    <button onclick="toggleComments()" class="button">Show/Hide Comments</button>

    <div id="commentsContainer" style="display: none;">
        <?php displayComments($comments); ?>
    </div>

    <div class="comment-form">
        <!-- Your comment form goes here -->
    </div>
</div>



    <script>
        function myFunction() {
            var x = document.getElementById("myNavbar");
            if (x.className === "navbar") {
                x.className += " responsive";
            } else {
                x.className = "navbar";
            }
        }     
        function toggleComments() {
    console.log('Toggle comments button clicked');
    var commentsContainer = document.getElementById('commentsContainer');
    if (window.getComputedStyle(commentsContainer).display === 'none') {
        commentsContainer.style.display = 'block';
    } else {
        commentsContainer.style.display = 'none';
    }
}

    
    </script>
    


    
    <!-- pagina content -->
    
   
        


        <meta charset="UTF-8">
        <meta name="viewport"
              content="width=device-width, 
                       initial-scale=1.0">
    
    </body>
    </html>
    




</body>