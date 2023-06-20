<?php
include 'config.php';
session_start();
$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
    header('location:login.php');
}

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $searchQuery = $_GET["query"];

    $select = mysqli_query($conn, "SELECT wpisy.*, user_form.image AS user_image, user_form.name AS user_name FROM `wpisy` LEFT JOIN user_form ON wpisy.user_id = user_form.id WHERE user_form.name LIKE '%$searchQuery%' OR wpisy.title LIKE '%$searchQuery%' ORDER BY wpisy.id DESC") or die('query failed');
}
?>

<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wyniki wyszukiwania</title>
    <link rel="stylesheet" href="stylprof.css">
</head>

<header>
    <div class="baner">
        <h1> Pawfect Match!</h1> <a href="wpis.php"><img src="home2.png" alt="Oglądaj wpisy!" style="border: 0"></a>
        <a href="home.php"><img src="Mariuszek.png" alt="Poznaj przyjaciół!" style="border: 0"></a>
    </div>
</header>

<body>
    <div class="container">
        <div class="wpisy">
            <?php
            if (mysqli_num_rows($select) > 0) {
                while ($row = mysqli_fetch_assoc($select)) {
                    echo '<div class="wpis">';
                    if (!empty($row['user_image'])) {
                        echo '<img src="uploaded_img/' . $row['user_image'] . '" class="avatar">';
                    } else {
                        echo '<img src="images/default-avatar.png">';
                    }
                    echo '<h3>' . $row['user_name'] . '</h3>';
                    echo '<h4>' . $row['title'] . '</h4>';
                    echo '<p>' . $row['content'] . '</p>';
                    if (!empty($row['image'])) {
                        echo '<img src="uploaded_images/' . $row['image'] . '" class="post">';
                    }
                    if (!empty($row['video'])) {
                        echo '<video controls>';
                        echo '<source src="uploaded_videos/' . $row['video'] . '" type="video/mp4">';
                        echo '</video>';
                    }
                    echo '</div>';
                }
            } else {
                echo '<p>Brak wyników wyszukiwania.</p>';
            }
            ?>
        </div>
    </div>
</body>

</html>
