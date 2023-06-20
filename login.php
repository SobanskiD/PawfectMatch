<?php

include 'config.php'; //conection with sql
session_start();

if(isset($_POST['submit'])){

   $email = mysqli_real_escape_string($conn, $_POST['email']);                 //checking data from sql
   $pass = mysqli_real_escape_string($conn, md5($_POST['password']));
   $select = mysqli_query($conn, "SELECT * FROM `user_form` WHERE email = '$email' AND password = '$pass'") or die('query failed');

   if(mysqli_num_rows($select) > 0){
      $row = mysqli_fetch_assoc($select);
      $_SESSION['user_id'] = $row['id'];
      header('location:home.php');
           }else{
             $message[] = 'Niepoprawny login lub hasło!';            //if login or password incorrect error
                 }
                     }
                       ?>

<!DOCTYPE html>
<html lang="pl">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Zaloguj się!</title>
              <!-- custom css file link  -->
                <link rel="stylesheet" href="css/style.css">

</head>
        <body>
   
           <div class="form-container">

                <form action="" method="post" enctype="multipart/form-data">
                   <h3>Zaloguj się!</h3>
                       <?php
                          if(isset($message)){
                           foreach($message as $message){
                             echo '<div class="message">'.$message.'</div>';
                                }
                                   }
                                     ?>
      <input type="email" name="email" placeholder="Wprowadź email" class="box" required>
      <input type="password" name="password" placeholder="Wprowadź hasło" class="box" required>  <!-- form -->
      <input type="submit" name="submit" value="Zaloguj się" class="btn">
      <p>Nie masz konta? <a href="register.php">Zarejestruj się teraz!</a></p>
   </form>

</div>

</body>
</html>