<?php
include 'config.php';
session_start();
$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
    header('location:login.php');
}

$select = mysqli_query($conn, "SELECT wpisy.*, user_form.image AS user_image, user_form.name AS user_name FROM `wpisy` LEFT JOIN user_form ON wpisy.user_id = user_form.id ORDER BY wpisy.id DESC") or die('query failed');
?>

<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wpisy</title>
    <link rel="stylesheet" href="posts.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        function likePost(postId) {
            $.ajax({
                type: "POST",
                url: "like_post.php",
                data: {
                    post_id: postId
                },
                success: function(response) {
                    var result = JSON.parse(response);
                    if (result.status == 'success') {
                        var likesCount = result.likes_count;
                        $('#likesCount_' + postId).text(likesCount);
                    } else {
                        alert('Błąd polubienia wpisu. Spróbuj ponownie.');
                    }
                }
            });
        }

        function submitComment(postId) {
            var comment = $('#comment_' + postId).val();
            $.ajax({
                type: "POST",
                url: "add_comment.php",
                data: {
                    post_id: postId,
                    comment: comment
                },
                success: function(response) {
                    var result = JSON.parse(response);
                    if (result.status == 'success') {
                        var commentHTML = '<div class="comment"><img src="uploaded_img/' + result.user_image + '" class="avatar-comment"><span class="comment-user">' + result.user_name + '</span><p>' + result.comment + '</p></div>';
                        $('#comments_' + postId).append(commentHTML);
                        $('#comment_' + postId).val('');
                    } else {
                        alert('Błąd dodawania komentarza. Spróbuj ponownie.');
                    }
                }
            });
        }
    </script>
</head>

<header>
    <div class="baner">
        <h1> Pawfect Match!</h1> <br><a href="home.php">Strona główna</a>
        <a href="home.php">Poznaj przyjaciół!</a>
      
    </div>
</header>

<body>
    <div class="container">
        <div class="wpisy">
            <?php
            while ($row = mysqli_fetch_assoc($select)) {
                echo '<div class="wpis">';
                if (!empty($row['user_image'])) {
                    echo '<img src="uploaded_img/' . $row['user_image'] . '"class="avatar">';
                } else {
                    echo '<img src="images/default-avatar.png">';
                }
                echo '<h3>' . $row['user_name'] . '</h3>';
                echo '<h4>' . $row['title'] . '</h4>';
                echo '<h5>' . $row['content'] . '</h5>';

                if (!empty($row['image'])) {
                    echo '<img src="uploaded_images/' . $row['image'] . '" class="post">';
                }
                if (!empty($row['video'])) {
                    echo '<video controls>';
                    echo '<source src="uploaded_videos/' . $row['video'] . '" type="video/mp4">';
                    echo '</video>';
					echo'<hr>';
                }

                // Licznik polubień
                $postLikes = mysqli_query($conn, "SELECT COUNT(*) as total_likes FROM likes WHERE post_id = '" . $row['id'] . "'");
                $likesData = mysqli_fetch_assoc($postLikes);
                $totalLikes = $likesData['total_likes'];

                echo '<div class="likes-section">';
                echo '<button onclick="likePost(' . $row['id'] . ')">Polub</button>';
                echo '<span id="likesCount_' . $row['id'] . '">' . $totalLikes . '</span>';
                echo '</div>';

                // Komentarze
                $comments = mysqli_query($conn, "SELECT comments.*, user_form.image AS user_image, user_form.name AS user_name FROM comments LEFT JOIN user_form ON comments.user_id = user_form.id WHERE post_id = '" . $row['id'] . "' ORDER BY comments.id ASC");
                echo '<div id="comments_' . $row['id'] . '" class="comments-section">';
                while ($comment = mysqli_fetch_assoc($comments)) {
                    echo '<div class="comment">';
                    echo '<img src="uploaded_img/' . $comment['user_image'] . '" class="avatar-comment">';
                    echo '<span class="comment-user">' . $comment['user_name'] . '</span>';
                    echo '<p>' . $comment['comment'] . '</p>';
                    echo '</div>';
                }
                echo '</div>';

                // Formularz dodawania komentarza
                echo '<div class="comment-form">';
                echo '<input type="text" id="comment_' . $row['id'] . '" placeholder="Dodaj komentarz">';
                echo '<button onclick="submitComment(' . $row['id'] . ')">Dodaj</button>';
                echo '</div>';

                echo '</div>';
            }
            ?>

        </div>
    </div>
</body>

</html>
