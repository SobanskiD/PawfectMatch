<?php
    // Połączenie z bazą danych
    $dbHost = "localhost";
    $dbUser = "root";
    $dbPassword = "";
    $dbName = "pawfect!";
    $db = new mysqli($dbHost, $dbUser, $dbPassword, $dbName);

    // Sprawdzenie połączenia
    if ($db->connect_error) {
        die("Błąd połączenia: " . $db->connect_error);
    }

    // Pobranie danych z formularza
    $senderId = $_POST["senderId"];
    $receiverId = 1; // Identyfikator użytkownika bieżącego (zalogowanego) - tutaj przykład dla użytkownika o id = 1

    // Pobranie wiadomości z bazy danych dla obu użytkowników
    $selectQuery = "SELECT * FROM wiadomosci WHERE (nadawca='$senderId' AND odbiorca='$receiverId') OR (nadawca='$receiverId' AND odbiorca='$senderId') ORDER BY data_wyslania ASC";
    $result = $db->query($selectQuery);

    $sentMessages = []; // Tablica przechowująca wysłane wiadomości

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $message = $row["wiadomosc"];
            $messageSender = $row["nadawca"];

            if ($messageSender == $senderId && !in_array($message, $sentMessages)) {
                // Wyświetlenie tylko wysłanej wiadomości, jeśli nie została jeszcze dodana do tablicy
                echo "<p><strong>You:</strong> $message</p>";
                $sentMessages[] = $message; // Dodanie wiadomości do tablicy wysłanych wiadomości
            } elseif ($messageSender != $senderId) {
                // Dodanie informacji, który użytkownik wysłał daną wiadomość
                $messageAuthor = "User $messageSender";
                echo "<p><strong>$messageAuthor:</strong> $message</p>";
            }
        }
    }

    // Zamknięcie połączenia z bazą danych
    $db->close();
?>
