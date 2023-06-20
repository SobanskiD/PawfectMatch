

<?php
include 'config.php';
session_start();
$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
    header('location:login.php');
}

if (isset($_GET['logout'])) {
    unset($user_id);
    session_destroy();
    header('location:login.php');
}

if (isset($_POST['submit'])) {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $image = $_FILES['image']['name'];
    $video = $_FILES['video']['name'];

    // Weryfikacja pliku obrazu
    if ($image != '') {
        $target_dir = "uploaded_images/";
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $extensions_arr = array("jpg", "jpeg", "png", "gif");

        if (in_array($imageFileType, $extensions_arr)) {
            move_uploaded_file($_FILES['image']['tmp_name'], $target_dir . $image);
        }
    }

    // Weryfikacja pliku wideo
    if ($video != '') {
        $target_dir = "uploaded_videos/";
        $target_file = $target_dir . basename($_FILES["video"]["name"]);
        $videoFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $extensions_arr = array("mp4", "avi", "mov");

        if (in_array($videoFileType, $extensions_arr)) {
            move_uploaded_file($_FILES['video']['tmp_name'], $target_dir . $video);
        }
    }

    // Dodawanie wpisu do bazy danych
    $insert = mysqli_query($conn, "INSERT INTO `wpisy` (user_id, title, content, image, video) VALUES ('$user_id', '$title', '$content', '$image', '$video')");
}

?>

<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Witamy w Pawfect Match!</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<header>
    <div class="baner">
        <h1> Pawfect Match!</h1> <a href="wpis.php"><img src="home2.png" alt="Oglądaj wpisy!" style="border: 0"></a>
        <a href="index.php"><img src="Mariuszek.png" alt="Poznaj przyjaciół!" style="border: 0"></a>
		<a href="twojewpisy.php"><img src="home2.png" alt="Twoje wpisy!" style="border: 0"></a>
        <!--relation to text messages -->
    </div>
</header>

<body>
    <div class="container">
        <div class="profile">
            <?php
            $select = mysqli_query($conn, "SELECT * FROM `user_form` WHERE id = '$user_id'") or die('query failed');
            if (mysqli_num_rows($select) > 0) {
                $fetch = mysqli_fetch_assoc($select);
            }
            if ($fetch['image'] == '') {
                echo '<img src="images/default-avatar.png">';
            } else {
                echo '<img src="uploaded_img/' . $fetch['image'] . '">';
            }
            ?>
            <h3><?php echo $fetch['name']; ?></h3> <br>
            <h3><?php echo $fetch['rasa']; ?></h3> <br>
            <h3><?php echo $fetch['opis']; ?></h3>
            <a href="update_profile.php" class="btn">Aktualizuj profil</a>
            <a href="home.php?logout=<?php echo $user_id; ?>" class="delete-btn">Wyloguj się</a>
            <p><a href="login.php">Logowanie</a> lub <a href="register.php">Zarejestruj się</a></p>
        </div>
    </div>

    <div class="container">
        <div class="profile">   
            <h1> Dodaj post którym chcesz się podzielić!</h1>
            <form method="POST" enctype="multipart/form-data">
                <input type="text" name="title"  placeholder="Dodaj tytuł swojego wpisu!" class="box1"required><br>
                <input type="text" name="content"  placeholder="Dodaj treść swojego wpisu!" class="box1" required><br>
                <input type="file" name="image" placeholder="Zdjęcie"class="btn" accept="image/*"><br>
                <input type="file" name="video" class="btn" accept="video/*"><br>
                <input type="submit" name="submit" class="delete-btn" value="Dodaj wpis">
            </form>
        </div>
    </div>

</body>

</html>
