<?php

include 'config.php';    //start session and connect with DB
session_start();
$user_id = $_SESSION['user_id'];

if (isset($_POST['update_profile'])) {
    //checking mail
     //update of text information
    $update_name = mysqli_real_escape_string($conn, $_POST['update_name']);
    $update_email = mysqli_real_escape_string($conn, $_POST['update_email']);
	$update_opis = mysqli_real_escape_string($conn, $_POST['update_opis']);
    $update_rasa = mysqli_real_escape_string($conn, $_POST['update_rasa']); 

    // Email validation
    if (!filter_var($update_email, FILTER_VALIDATE_EMAIL)) {
        $message[] = 'Nieprawidłowy adres email!';
    } else {
        mysqli_query($conn, "UPDATE `user_form` SET name = '$update_name', email = '$update_email',opis = '$update_opis',rasa = '$update_rasa' WHERE id = '$user_id'") or die('query failed');
        $message[] = 'Pomyślnie zaktualizowano profil!';
    }

    $old_pass = $_POST['old_pass'];
    $update_pass = mysqli_real_escape_string($conn, md5($_POST['update_pass']));  //password 2check
    $new_pass = mysqli_real_escape_string($conn, md5($_POST['new_pass']));
    $confirm_pass = mysqli_real_escape_string($conn, md5($_POST['confirm_pass']));

    if (!empty($update_pass) || !empty($new_pass) || !empty($confirm_pass)) {
        if ($update_pass != $old_pass) {
            $message[] = 'Stare hasło nie jest poprawne!';
        } elseif ($new_pass != $confirm_pass) {
            $message[] = 'Nowe hasło nie pasuje!';
        } else {
            mysqli_query($conn, "UPDATE `user_form` SET password = '$confirm_pass' WHERE id = '$user_id'") or die('query failed');
            $message[] = 'Hasło zmienione poprawnie!';
        }
    }

    $update_image = $_FILES['update_image']['name'];
    $update_image_size = $_FILES['update_image']['size'];                  //img update and size check
    $update_image_tmp_name = $_FILES['update_image']['tmp_name'];
    $update_image_folder = 'uploaded_img/' . $update_image;

    if (!empty($update_image)) {
        if ($update_image_size > 2000000) {
            $message[] = 'Rozmiar zdjęcia jest zbyt duży!';
        } else {
            $image_update_query = mysqli_query($conn, "UPDATE `user_form` SET image = '$update_image' WHERE id = '$user_id'") or die('query failed');
            if ($image_update_query) {
                move_uploaded_file($update_image_tmp_name, $update_image_folder);
            }
            $message[] = 'Zdjęcie zaktualizowano!';  //confirmation
        }
    }
}

?>

<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aktualizuj swój profil!</title>
    <!-- custom css file link  -->
    <link rel="stylesheet" href="css/style.css">

</head>

<body>

    <div class="update-profile">
        <?php
        $select = mysqli_query($conn, "SELECT * FROM `user_form` WHERE id = '$user_id'") or die('query failed');  //profile show
        if (mysqli_num_rows($select) > 0) {
            $fetch = mysqli_fetch_assoc($select);
        }
        ?>

        <form action="" method="post" enctype="multipart/form-data">
            <?php
            if ($fetch['image'] == '') {
                echo '<img src="images/default-avatar.png">';    //avatar show
            } else {
                echo '<img src="uploaded_img/' . $fetch['image'] . '">';
            }
            if (isset($message)) {
                foreach ($message as $message) {
                    echo '<div class="message">' . $message . '</div>';
                }
            }
            ?>
            <!-- form of updating profile  -->
            <div class="flex">
                <div class="inputBox">
                    <span>Nazwa użytkownika :</span>
                    <input type="text" name="update_name" value="<?php echo $fetch['name']; ?>" class="box">
                    <span>Twój email :</span>
                    <input type="email" name="update_email" value="<?php echo $fetch['email']; ?>" class="box">
                    <span>Twój opis :</span>
                    <input type="text" name="update_opis" value="<?php echo $fetch['opis']; ?>" class="box">
                    <span>Aktualizuj swoje zdjęcia:</span>
                    <input type="file" name="update_image" accept="image/jpg, image/jpeg, image/png" class="box">

                </div>
                <div class="inputBox">
                    <input type="hidden" name="old_pass" value="<?php echo $fetch['password']; ?>">
                    <span>Stare hasło :</span>
                    <input type="password" name="update_pass" placeholder="Podaj stare hasło" class="box">
                    <span>Nowe hasło :</span>
                    <input type="password" name="new_pass" placeholder="Wprowadź nowe hasło" class="box">
                    <span>Potwierdź hasło :</span>
                    <input type="password" name="confirm_pass" placeholder="Potwierdź nowe hasło" class="box">
                    <span>Rasa pupila :</span>
                    <input type="text" name="update_rasa" value="<?php echo $fetch['rasa']; ?>" class="box">
                </div>
            </div>
            <input type="submit" value="Aktualizuj profil" name="update_profile" class="btn">
            <a href="home.php" class="delete-btn">Wróć</a>
        </form>

    </div>

</body>

</html>
