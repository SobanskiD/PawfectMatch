<!DOCTYPE html>
<html>
<head>
    <title>Przeglądanie Profili Użytkowników</title>
    <style>
        .user-profile {
            display: inline-block;
            margin: 10px;
            padding: 10px;
            border: 1px solid #ccc;
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Funkcja obsługująca wysyłanie wiadomości
            $("#send-message-form").submit(function(event) {
                event.preventDefault(); // Zapobieganie domyślnej akcji formularza

                var recipient = $("#recipient-id").val();
                var message = $("#message-text").val();

                // Wysłanie wiadomości do bazy danych
                $.post("save_message.php", { recipient: recipient, message: message })
                    .done(function(data) {
                        // Obsługa zapisu wiadomości
                        console.log("Wiadomość została zapisana w bazie danych.");
                        console.log("Odpowiedź serwera: " + data);

                        // Wyczyść pola formularza po wysłaniu wiadomości
                        $("#message-text").val("");
                    })
                    .fail(function() {
                        console.log("Błąd zapisu wiadomości.");
                    });
            });
        });
    </script>
</head>
<body>
    <h1>Przeglądanie Profili Użytkowników</h1>

    <?php
    // Połączenie z bazą danych
    $db_host = 'localhost';
    $db_username = 'root';
    $db_password = '';
    $db_name = 'pawfect!';

    $conn = mysqli_connect($db_host, $db_username, $db_password, $db_name);

    // Sprawdzenie czy połączenie się powiodło
    if (!$conn) {
        die("Błąd połączenia z bazą danych: " . mysqli_connect_error());
    }

    // Zapytanie SQL do pobrania informacji o użytkownikach
    $sql = "SELECT * FROM user_form";

    // Wykonanie zapytania i pobranie wyników
    $result = mysqli_query($conn, $sql);

    // Wyświetlanie profili użytkowników
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $userId = $row['id'];
            $username = $row['name'];
            $description = $row['opis'];
            $imagePath = $row['image'];
            ?>

            <div class="user-profile">
                <h2><?php echo $username; ?></h2>
                <img src="<?php echo $imagePath; ?>" alt="Zdjęcie Profilowe">
                <p><?php echo $description; ?></p>
                <button class="message-button" data-recipient="<?php echo $userId; ?>">Wyślij wiadomość</button>
            </div>

            <?php
        }
    } else {
        echo "Brak profili użytkowników.";
    }

    // Zamknięcie połączenia z bazą danych
    mysqli_close($conn);
    ?>

    <div id="message-modal" style="display: none;">
        <h2>Wyślij wiadomość</h2>
        <form id="send-message-form">
            <input type="hidden" id="recipient-id" value="">
            <textarea id="message-text" rows="4" cols="50" placeholder="Treść wiadomości"></textarea>
            <br>
            <input type="submit" value="Wyślij">
        </form>
    </div>

    <script>
        // Obsługa kliknięcia przycisku "Wyślij wiadomość"
        $(".message-button").click(function() {
            var recipientId = $(this).data("recipient");
            $("#recipient-id").val(recipientId);
            $("#message-modal").show();
        });
    </script>
</body>
</html>
