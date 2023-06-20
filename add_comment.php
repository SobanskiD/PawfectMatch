<?php
include 'config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    // Użytkownik nie jest zalogowany, zwróć błąd
    $response = array(
        'status' => 'error',
        'message' => 'Użytkownik nie jest zalogowany.'
    );
    echo json_encode($response);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sprawdź czy istnieje parametr post_id
    if (!isset($_POST['post_id'])) {
        $response = array(
            'status' => 'error',
            'message' => 'Brak identyfikatora wpisu.'
        );
        echo json_encode($response);
        exit;
    }

    // Sprawdź czy istnieje parametr comment
    if (!isset($_POST['comment']) || empty($_POST['comment'])) {
        $response = array(
            'status' => 'error',
            'message' => 'Brak treści komentarza.'
        );
        echo json_encode($response);
        exit;
    }

    $post_id = $_POST['post_id'];
    $comment = $_POST['comment'];
    $user_id = $_SESSION['user_id'];

    // Dodaj komentarz do bazy danych
    $insert = mysqli_query($conn, "INSERT INTO comments (post_id, user_id, comment) VALUES ('$post_id', '$user_id', '$comment')");

    if ($insert) {
        // Pobierz dane użytkownika, który dodał komentarz
        $user_query = mysqli_query($conn, "SELECT name, image FROM user_form WHERE id = '$user_id'");
        $user_data = mysqli_fetch_assoc($user_query);

        $response = array(
            'status' => 'success',
            'user_name' => $user_data['name'],
            'user_image' => $user_data['image'],
            'comment' => $comment
        );
        echo json_encode($response);
        exit;
    } else {
        $response = array(
            'status' => 'error',
            'message' => 'Wystąpił błąd podczas dodawania komentarza.'
        );
        echo json_encode($response);
        exit;
    }
} else {
    $response = array(
        'status' => 'error',
        'message' => 'Nieprawidłowe żądanie.'
    );
    echo json_encode($response);
    exit;
}
?>
