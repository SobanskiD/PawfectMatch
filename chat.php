<!DOCTYPE html>
<html lang="pl">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Poznaj przyjaciół!</title>
   <link rel="stylesheet" href="stylchat1.css">
   <style>
      .wiadomosc.odczytana::after {
         content: 'Przeczytane';
         color: green;
         font-size: 12px;
         margin-left: 10px;
      }
   </style>
</head>
<body>
   <div class="container">
      <?php
         // Połączenie z bazą danych
         $host = "localhost";
         $username = "root";
         $password = "";
         $database = "pawfect!";
         $connection = mysqli_connect($host, $username, $password, $database);

         if (!$connection) {
            die("Błąd połączenia z bazą danych: " . mysqli_connect_error());
         }

         // Pobranie informacji o nadawcy i odbiorcy z parametrów URL
         $senderID = $_GET['sender'];
         $receiverID = $_GET['receiver'];

         // Pobranie nazwy nadawcy i odbiorcy z bazy danych
         $senderQuery = "SELECT name, image FROM user_form WHERE id = $senderID";
         $receiverQuery = "SELECT name, image FROM user_form WHERE id = $receiverID";

         $senderResult = mysqli_query($connection, $senderQuery);
         $receiverResult = mysqli_query($connection, $receiverQuery);

         $senderRow = mysqli_fetch_assoc($senderResult);
         $receiverRow = mysqli_fetch_assoc($receiverResult);

         $senderName = $senderRow['name'];
         $receiverName = $receiverRow['name'];
         $senderImage = $senderRow['image'];
         $receiverImage = $receiverRow['image'];

         // Wyświetlanie nazw nadawcy i odbiorcy
         echo "<h2>Prywatny czat</h2>";
         echo "<div class='user-profile'>";
         echo "<img src='uploaded_img/$senderImage' class='profilowe' alt='Zdjęcie profilowe nadawcy'>";
         echo "<p>Nadawca: $senderName</p>";
         echo "</div>";
         echo "<div class='user-profile'>";
         echo "<img src='uploaded_img/$receiverImage' class='profilowe' alt='Zdjęcie profilowe odbiorcy'>";
         echo "<p>Odbiorca: $receiverName</p>";
         echo "</div>";

         // Wyświetlanie i obsługa formularza wiadomości
         if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $message = $_POST['wiadomosc'];

            // Przetwarzanie przesłanego zdjęcia
            $image = $_FILES['zdjecie'];

            if ($image['error'] === UPLOAD_ERR_OK) {
               $tmpFilePath = $image['tmp_name'];
               $newFilePath = 'uploaded_img/' . $image['name'];

               if (move_uploaded_file($tmpFilePath, $newFilePath)) {
                  // Zapisanie wiadomości i ścieżki do zdjęcia do bazy danych
                  $insertQuery = "INSERT INTO wiadomosci (nadawca_id, odbiorca_id, wiadomosc, zdjecie, odczytana) VALUES ('$senderID', '$receiverID', '$message', '$newFilePath', '0')";
                  mysqli_query($connection, $insertQuery);
               }
            } else {
               // Zapisanie wiadomości bez zdjęcia do bazy danych
               $insertQuery = "INSERT INTO wiadomosci (nadawca_id, odbiorca_id, wiadomosc, odczytana) VALUES ('$senderID', '$receiverID', '$message', '0')";
               mysqli_query($connection, $insertQuery);
            }

            // Przetwarzanie przesłanego filmu
            $video = $_FILES['film'];

            if ($video['error'] === UPLOAD_ERR_OK) {
               $tmpFilePath = $video['tmp_name'];
               $newFilePath = 'uploaded_videos/' . $video['name'];

               if (move_uploaded_file($tmpFilePath, $newFilePath)) {
                  // Zapisanie ścieżki do filmu do bazy danych
                  $insertQuery = "INSERT INTO wiadomosci (nadawca_id, odbiorca_id, film, odczytana) VALUES ('$senderID', '$receiverID', '$newFilePath', '0')";
                  mysqli_query($connection, $insertQuery);
               }
            }
         }

         // Pobranie wiadomości między nadawcą a odbiorcą
         $messageQuery = "SELECT * FROM wiadomosci WHERE (nadawca_id = '$senderID' AND odbiorca_id = '$receiverID') OR (nadawca_id = '$receiverID' AND odbiorca_id = '$senderID')";
         $messageResult = mysqli_query($connection, $messageQuery);

         if (mysqli_num_rows($messageResult) > 0) {
            while ($messageRow = mysqli_fetch_assoc($messageResult)) {
               $messageID = $messageRow['id'];
               $messageSenderID = $messageRow['nadawca_id'];
               $messageReceiverID = $messageRow['odbiorca_id'];
               $messageContent = $messageRow['wiadomosc'];
               $messageImage = $messageRow['zdjecie'];
               $messageVideo = $messageRow['film'];
               $messageRead = $messageRow['odczytana'];

               // Sprawdzanie, czy wiadomość została wysłana przez nadawcę czy odbiorcę
               $messageClass = ($messageSenderID == $senderID) ? 'wiadomosc nadawca' : 'wiadomosc odbiorca';
               $messageReadClass = ($messageRead == '1') ? 'odczytana' : '';

               // Oznaczenie wiadomości jako przeczytanej
               if ($messageSenderID == $receiverID && $messageReceiverID == $senderID && $messageRead == '0') {
                  $updateQuery = "UPDATE wiadomosci SET odczytana = '1' WHERE id = '$messageID'";
                  mysqli_query($connection, $updateQuery);
               }

               // Wyświetlanie wiadomości
               echo "<div class='$messageClass $messageReadClass'>";
               echo "<p> $messageContent</p>";

               // Wyświetlanie zdjęcia, jeśli istnieje
               if (!empty($messageImage)) {
                  echo "<img src='$messageImage' class='zdjecie' alt='Przesłane zdjęcie'>";
               }

               // Wyświetlanie filmu, jeśli istnieje
               if (!empty($messageVideo)) {
                  echo "<video src='$messageVideo' class='film' controls></video>";
               }

               echo "</div>";
            }
         }

         // Zamknięcie połączenia z bazą danych
         mysqli_close($connection);
      ?>
   </div>
   <div class="przyciski1">
      <form method="post" action="" enctype="multipart/form-data">
         <label for="wiadomosc">Wiadomość:</label>
         <textarea name="wiadomosc" id="wiadomosc"></textarea>
         <br><br>
         <label for="zdjecie">Zdjęcie:</label>
         <input type="file" name="zdjecie" id="zdjecie">
         <br><br>
         <label for="film">Film:</label>
         <input type="file" name="film" id="film">
         <br><br>
         <input type="submit" value="Wyślij">
      </form>
   </div>
</body>
</html>
