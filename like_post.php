<?php
include 'config.php';
session_start();
$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
    // Jeśli użytkownik nie jest zalogowany, zwracamy odpowiedź błędu
    $response = array(
        'status' => 'error',
        'message' => 'Użytkownik nie jest zalogowany.'
    );
    echo json_encode($response);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['post_id'])) {
    $post_id = $_POST['post_id'];

    // Sprawdzamy, czy użytkownik już polubił ten wpis
    $checkLike = mysqli_query($conn, "SELECT * FROM likes WHERE post_id = '$post_id' AND user_id = '$user_id'");
    if (mysqli_num_rows($checkLike) > 0) {
        // Użytkownik już polubił ten wpis, usuwamy polubienie
        $deleteLike = mysqli_query($conn, "DELETE FROM likes WHERE post_id = '$post_id' AND user_id = '$user_id'");
        if ($deleteLike) {
            // Pobieramy aktualną liczbę polubień dla wpisu
            $likesCount = mysqli_query($conn, "SELECT COUNT(*) as total_likes FROM likes WHERE post_id = '$post_id'");
            $likesData = mysqli_fetch_assoc($likesCount);
            $totalLikes = $likesData['total_likes'];

            // Zwracamy odpowiedź sukcesu z aktualną liczbą polubień
            $response = array(
                'status' => 'success',
                'likes_count' => $totalLikes
            );
            echo json_encode($response);
            exit;
        } else {
            // Jeśli nie udało się usunąć polubienia, zwracamy odpowiedź błędu
            $response = array(
                'status' => 'error',
                'message' => 'Wystąpił błąd podczas usuwania polubienia. Spróbuj ponownie.'
            );
            echo json_encode($response);
            exit;
        }
    } else {
        // Użytkownik jeszcze nie polubił tego wpisu, dodajemy polubienie
        $addLike = mysqli_query($conn, "INSERT INTO likes (post_id, user_id) VALUES ('$post_id', '$user_id')");
        if ($addLike) {
            // Pobieramy aktualną liczbę polubień dla wpisu
            $likesCount = mysqli_query($conn, "SELECT COUNT(*) as total_likes FROM likes WHERE post_id = '$post_id'");
            $likesData = mysqli_fetch_assoc($likesCount);
            $totalLikes = $likesData['total_likes'];

            // Zwracamy odpowiedź sukcesu z aktualną liczbą polubień
            $response = array(
                'status' => 'success',
                'likes_count' => $totalLikes
            );
            echo json_encode($response);
            exit;
        } else {
            // Jeśli nie udało się dodać polubienia do bazy danych, zwracamy odpowiedź błędu
            $response = array(
                'status' => 'error',
                'message' => 'Wystąpił błąd podczas polubienia wpisu. Spróbuj ponownie.'
            );
            echo json_encode($response);
            exit;
        }
    }
} else {
    // Jeśli żądanie nie jest typu POST lub brakuje parametru post_id, zwracamy odpowiedź błędu
    $response = array(
        'status' => 'error',
        'message' => 'Nieprawidłowe żądanie.'
    );
    echo json_encode($response);
    exit;
}
