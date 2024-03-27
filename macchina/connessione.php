<?php
// connessione.php
$servername = "localhost";
$username = "roott"; // Modifica: sostituisci con l'utente del database
$password = "roott"; // Modifica: sostituisci con la password del database
$dbname = "macchina";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connessione al database fallita: " . $conn->connect_error);
}
?>
