<!DOCTYPE html>
<html lang="pl">
<head>
 <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Poznaj przyjaciół!</title>
    <link rel="stylesheet" href="stylprof.css">
	
</head>
<header>
<div class="baner">
<h1> Pawfect Match! </h1> <br> <h3> Znajdź swoich przyjaciół! </h3>
</div>
</header>
<body>
   <div class="profile">
     <form method="GET" action="">
        <input type="text" name="search" placeholder="Wyszukaj użytkownika">
        <input type="submit" value="Szukaj">
     </form>
     
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

        // Sprawdzenie, czy został wysłany formularz wyszukiwania
        if (isset($_GET['search'])) {
            $searchTerm = $_GET['search'];

            // Pobranie informacji o użytkownikach z bazy danych, pasujących do wyszukiwanego terminu
            $query = "SELECT * FROM user_form WHERE name LIKE '%$searchTerm%' OR rasa LIKE '%$searchTerm%'";
            $result = mysqli_query($connection, $query);

            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $userID = $row["id"];
                    $userName = $row["name"];
                    $userDescription = $row["opis"];
                    $userRace = $row["rasa"];
                    $userPhoto = $row["image"];

                    // Wyświetlanie nazwy użytkownika, opisu profilu i zdjęcia
                    echo "<div class='profiless'><br>";
                    echo "<h2>$userName</h2>";
                    echo "<h3>$userRace</h3>";
                    echo "<img src='uploaded_img/$userPhoto' class='profilowe' alt='Zdjęcie profilowe'><br>";
                    echo "<p>$userDescription</p><br>";
                    echo "<a href='chat.php?sender=$userID&receiver=$userID'><img src='message.png' alt='Wyslij wiadomosc!'></a>";
                    echo "<a href='profiles.php?user_id=$userID'><img src='profil.png' alt='Profil'></a>";					// Link do czatu
					
                    echo "<hr>";
                    echo "</div>";
                }
            } else {
                echo "Brak wyników wyszukiwania.";
            }
        } else {
            // Wyświetl komunikat, gdy nie ma wyszukiwania
            echo "Wprowadź nazwę użytkownika lub rasę w polu powyżej i kliknij 'Szukaj'";

            // Wyświetl wszystkich użytkowników
            $query = "SELECT * FROM user_form";
            $result = mysqli_query($connection, $query);

            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $userID = $row["id"];
                    $userName = $row["name"];
                    $userDescription = $row["opis"];
                    $userRace = $row["rasa"];
                    $userPhoto = $row["image"];

                    // Wyświetlanie nazwy użytkownika, opisu profilu i zdjęcia
                    echo "<div class='profiless'><br>";
                    echo "<h2>$userName</h2>";
                    echo "<h3>$userRace</h3>";
                    echo "<img src='uploaded_img/$userPhoto' class='profilowe' alt='Zdjęcie profilowe'><br>";
                    echo "<p>$userDescription</p><br>";
                    echo "<a href='chat.php?sender=$userID&receiver=$userID'><img src='message.png' alt='Wyslij wiadomosc!'></a>";
                    echo "<a href='profiles.php?user_id=$userID'><img src='profil.png' alt='Profil'></a>";					// Link do czatu
                    echo "<hr>";
                    echo "</div>";
                }
            } else {
                echo "Brak użytkowników w bazie danych.";
            }
        }

        // Zamknięcie połączenia z bazą danych
        mysqli_close($connection);
    ?>
	
	</div>
</body>
</html>
