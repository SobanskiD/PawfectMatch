<?php
include 'config.php';
session_start();
$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
    header('location:login.php');
}

// Pobieranie informacji o użytkowniku z bazy danych
$select = mysqli_query($conn, "SELECT * FROM `user_form` WHERE id = '$user_id'") or die('query failed');
if (mysqli_num_rows($select) > 0) {
    $fetch = mysqli_fetch_assoc($select);
}

// Pobieranie wpisów użytkownika z bazy danych
$posts_query = mysqli_query($conn, "SELECT * FROM `wpisy` WHERE user_id = '$user_id'");
$posts = mysqli_fetch_all($posts_query, MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Twój profil </title>
    <link rel="stylesheet" href="posts.css">
</head>

<header>
    <div class="baner">
        <h1> Pawfect Match!</h1> <a href="wpis.php"><img src="home2.png" alt="Oglądaj wpisy!" style="border: 0"></a>
        <a href="home.php"><img src="Mariuszek.png" alt="Poznaj przyjaciół!" style="border: 0"></a>
        
    </div>
</header>

<div class="container">
    <div class="profile">
        <?php
        if ($fetch['image'] == '') {
            echo '<img src="images/default-avatar.png">';
        } else {
            echo '<img src="uploaded_img/' . $fetch['image'] . '">';
        }
        ?>
        <h3><?php echo $fetch['name']; ?></h3> <br>
        <h3><?php echo $fetch['rasa']; ?></h3> <br>
        <h3><?php echo $fetch['opis']; ?></h3>
    </div>
</div>
<div class="container">
    <div class="wpisy">
        <h2>Twoje wpisy</h2>
        <?php
        if (count($posts) > 0) {
            foreach ($posts as $post) {
                echo '<div class="wpis">';
                echo '<h3>' . $post['title'] . '</h3>';
                echo '<p>' . $post['content'] . '</p>';
                if ($post['image'] != '') {
                    echo '<img src="uploaded_images/' . $post['image'] . '">';
                }
                if ($post['video'] != '') {
                    echo '<video src="uploaded_videos/' . $post['video'] . '" controls></video>';
                }
                echo '</div>';
            }
        } else {
            echo '<p>Nie dodałeś jeszcze żadnych wpisów.</p>';
        }
        ?>
    </div>
</div>

    </div>
</div>
