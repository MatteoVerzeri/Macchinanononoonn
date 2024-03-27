<?php
session_start();
// modulo.php
include('connessione.php');

// Recupero credenziali inviate dal modulo di login
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Query per selezionare l'utente dalla tabella utente
    $sql = "SELECT * FROM utente WHERE username = '$username' AND password = '$password'";

    // Esegui la query
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        // Utente trovato, reindirizza alla pagina macchina.php
        $_SESSION['username'] = $username;
        header("Location: macchina.php");
        exit();
    } else {
        // Utente non trovato o credenziali errate, reindirizza alla pagina di login
        header("Location: index.html");
        exit();
    }
}
?>

