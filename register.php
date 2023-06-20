
<?php
include 'config.php';

if(isset($_POST['submit'])){
   $email = filter_var($email,FILTER_SANITIZE_EMAIL);
   $name = mysqli_real_escape_string($conn, $_POST['name']);
   $email = mysqli_real_escape_string($conn, $_POST['email']);
   $pass = mysqli_real_escape_string($conn, md5($_POST['password']));           //files to database
   $cpass = mysqli_real_escape_string($conn, md5($_POST['cpassword']));
   $opis = mysqli_real_escape_string($conn, $_POST['opis']);
   $rasa = mysqli_real_escape_string($conn, $_POST['rasa']);
   $image = $_FILES['image']['name'];
   $image_size = $_FILES['image']['size'];
   $image_tmp_name = $_FILES['image']['tmp_name'];
   $image_folder = 'uploaded_img/'.$image;

   $select = mysqli_query($conn, "SELECT * FROM `user_form` WHERE email = '$email' AND password = '$pass'") or die('query failed');

       if(mysqli_num_rows($select) > 0){
          $message[] = 'Użytkownik o takiej nazwie już istnieje!';              //username verification
             }else{
                 if($pass != $cpass){
                    $message[] = 'Wprowadzone hasło nie zgadza się!';           //checking secondpassword and image size
                       }elseif($image_size > 2000000){
                           $message[] = 'Rozmiar zdjęcia jest za duży!';
                              }else{
                      $insert = mysqli_query($conn, "INSERT INTO `user_form`(name, opis, email, password, image,rasa) VALUES('$name','$opis', '$email', '$pass', '$image','$rasa')") or die('query failed');

                        if($insert){
                          move_uploaded_file($image_tmp_name, $image_folder);                     
                             $message[] = 'Zarejestrowano poprawnie!';                   //registration confirmation
                                 header('location:login.php');
                                   }else{
                                      $message[] = 'Rejestracja nieprawidłowa!';
                                        }
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
   <title>Zarejestruj się!</title>

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<div class="form-container">

   <form action="" method="post" enctype="multipart/form-data">
      <h3>Zarejestruj się!</h3>
      <?php
      if(isset($message)){
         foreach($message as $message){
            echo '<div class="message">'.$message.'</div>';
         }
      }
      ?>
	   
      <input type="text" name="name" placeholder="Wpisz swoją nazwę użytkownika" class="box" required>
      <input type="email" name="email" placeholder="Wprowadź email" class="box" required>
      <input type="password" name="password" placeholder="Podaj hasło" class="box" required>
      <input type="password" name="cpassword" placeholder="Potwierdź swoje hasło" class="box" required>
	  <input type="text" name="opis" placeholder="Opisz siebie i swojego pupila!" class="box" required>
	   <input type="text" name="rasa" placeholder="Podaj rasę swojego pupila!" class="box" required>
      <input type="file" name="image" class="box" accept="image/jpg, image/jpeg, image/png">
      <input type="submit" name="submit" value="Zarejestruj się!" class="btn">
      <p>Masz już konto? <a href="login.php">Zaloguj się teraz!</a></p>
   </form>

</div>

</body>
</html>