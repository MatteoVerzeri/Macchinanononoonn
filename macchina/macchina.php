<?php
// macchina.php
session_start();
if(!isset($_SESSION["username"]) || empty($_SESSION["username"])){    
    echo "accesso non consentito";
    header("location:index.html");
}
$_SESSION["username"]="";
ob_start();
include('connessione.php');

// Esempio di query per ottenere dati dal database
$sqlp = "SELECT * FROM proprietario";
$sqla = "SELECT * FROM automobile";
$sqlr = "SELECT * FROM riparazione";
$sqlt = "SELECT * FROM tagliando";

// Verifica se è stato effettuato il reset dei filtri
if (isset($_GET['resetFiltri'])) {
    // Redirect alla stessa pagina senza parametri di filtro
    header("Location: {$_SERVER['PHP_SELF']}");
    exit();
}

// Gestisci i filtri per proprietari
$nomeFiltro = isset($_GET['nomeFiltro']) ? $_GET['nomeFiltro'] : '';
$autoFiltro = isset($_GET['autoFiltro']) ? $_GET['autoFiltro'] : '';

// Applica i filtri se sono stati definiti
if (!empty($nomeFiltro) || !empty($autoFiltro)) {
    $sqlp .= " WHERE 1=1";
    
    if (!empty($nomeFiltro)) {
        $sqlp .= " AND nome LIKE '%$nomeFiltro%'";
    }
    
    if (!empty($autoFiltro)) {
        $sqlp .= " AND auto LIKE '%$autoFiltro%'";
    }
}

// Gestisci i filtri per automobili
$marcaFiltro = isset($_GET['marcaFiltro']) ? $_GET['marcaFiltro'] : '';
$modelloFiltro = isset($_GET['modelloFiltro']) ? $_GET['modelloFiltro'] : '';
$prezzoFiltro = isset($_GET['prezzoFiltro']) ? $_GET['prezzoFiltro'] : '';

// Applica i filtri se sono stati definiti
if (!empty($marcaFiltro) || !empty($modelloFiltro) || !empty($prezzoFiltro)) {
    $sqla .= " WHERE 1=1";
    
    if (!empty($marcaFiltro)) {
        $sqla .= " AND marca LIKE '%$marcaFiltro%'";
    }
    
    if (!empty($modelloFiltro)) {
        $sqla .= " AND modello LIKE '%$modelloFiltro%'";
    }
    
    if (!empty($prezzoFiltro)) {
        $sqla .= " AND prezzo <= $prezzoFiltro";
    }
}

$resultp = $conn->query($sqlp);
$resulta = $conn->query($sqla);
$resultr = $conn->query($sqlr);
$resultt = $conn->query($sqlt);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dati dalla Macchina</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table, th, td {
            border: 1px solid black;
        }

        th, td {
            padding: 10px;
            text-align: left;
        }
    </style>
</head>
<body>

    <!-- Aggiungi moduli di filtraggio -->
    <h2>Filtri:</h2>
    <form method="get" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <!-- Filtri proprietari -->
        <label for="nomeFiltro">Filtro Nome Proprietario:</label>
        <input type="text" name="nomeFiltro" id="nomeFiltro">
        <label for="autoFiltro">Filtro Auto Proprietario:</label>
        <input type="text" name="autoFiltro" id="autoFiltro">
        <button type="submit">Filtra Proprietari</button>
    </form>

    <form method="get" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <!-- Filtri automobili -->
        <label for="marcaFiltro">Filtro Marca Auto:</label>
        <input type="text" name="marcaFiltro" id="marcaFiltro">
        <label for="modelloFiltro">Filtro Modello Auto:</label>
        <input type="text" name="modelloFiltro" id="modelloFiltro">
        <label for="prezzoFiltro">Filtro Prezzo Auto:</label>
        <input type="number" name="prezzoFiltro" id="prezzoFiltro">
        <button type="submit">Filtra Automobili</button>
        <button type="submit" name="resetFiltri">Resetta Filtri</button>
    </form>

    <!-- Risultati filtrati -->
    <div id="risultati">
        <h3>Proprietari</h3>
        <?php
        if ($resultp->num_rows > 0) {
            echo "<table>";
            while ($row = $resultp->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row["nome"] . "</td>";
                echo "<td>" . $row["cognome"] . "</td>";
                echo "<td>" . $row["auto"] . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "Nessun dato presente nel database.";
        }
        ?>
        <h3>Automobili</h3>
        <?php
        if ($resulta->num_rows > 0) {
            echo "<table>";
            while ($row = $resulta->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row["marca"] . "</td>";
                echo "<td>" . $row["modello"] . "</td>";
                echo "<td>" . $row["prezzo"] . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "Nessun dato presente nel database.";
        }
        ?>

        <h3>Riparazioni</h3>
        <?php
        if ($resultr->num_rows > 0) {
            echo "<table>";
            while ($row = $resultr->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row["descrizione"] . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "Nessun dato presente nel database.";
        }
        ?>

        <h3>Tagliandi</h3>
        <?php
        if ($resultt->num_rows > 0) {
            echo "<table>";
            while ($row = $resultt->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row["descrizione"] . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "Nessun dato presente nel database.";
        }
        ?>
    </div>

    <!-- Aggiungi modulo di aggiunta -->
    <h2>Aggiungi Nuovo proprietario:</h2>
    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <label for="nome">Nome:</label>
        <input type="text" name="nome" id="nome">
        <label for="cognome">Cognome:</label>
        <input type="text" name="cognome" id="cognome">
        <label for="auto">Auto:</label>
        <select name="auto" id="auto">
            <?php
            // Includi il file di connessione al database
            include('connessione.php');

            // Query per ottenere l'elenco delle auto dalla tabella auto
            $sqlAuto = "SELECT id, marca, modello FROM automobile";
            $resultAuto = $conn->query($sqlAuto);

            // Se ci sono auto nel database, crea le opzioni nel menu a tendina
            if ($resultAuto->num_rows > 0) {
                while ($row = $resultAuto->fetch_assoc()) {
                    echo "<option value='" . $row['id'] . "'>" . $row['marca'] . " " . $row['modello'] . "</option>";
                }
            } else {
                echo "<option value=''>Nessuna auto disponibile</option>";
            }

            // Chiudi la connessione al database
            $conn->close();
            ?>
        </select>
        <input type="submit" name="aggiungiProprietario" value="Aggiungi Riga">
    </form>

    <?php
    // Verifica se il modulo di aggiunta è stato inviato
    if (isset($_POST['aggiungiProprietario'])) {
        // Assicurati di avere una connessione al database
        include('connessione.php');

        // Recupera i dati dal modulo
        $nome = $_POST['nome'];
        $cognome = $_POST['cognome'];
        $auto = $_POST['auto'];

        // Query per inserire i dati nella tabella proprietario
        $sqlInserimento = "INSERT INTO proprietario (nome, cognome, auto) VALUES ('$nome', '$cognome', '$auto')";

        // Esegui la query di inserimento
        if ($conn->query($sqlInserimento) === TRUE) {
            echo "<p>Nuova riga inserita con successo.</p>";

            // Reindirizza l'utente per evitare di inviare nuovamente il modulo se ricarica la pagina
            header("Location: {$_SERVER['PHP_SELF']}");
            exit();
        } else {
            echo "Errore durante l'inserimento della riga: " . $conn->error;
        }

        // Chiudi la connessione al database
        $conn->close();
    }
    ?>

    <h2>Modifica Proprietario:</h2>
    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <label for="idProprietario">Seleziona Proprietario:</label>
        <select name="idProprietario" id="idProprietario">
            <?php
            // Includi il file di connessione al database
            include('connessione.php');

            // Query per ottenere l'elenco dei proprietari dalla tabella proprietario
            $sqlProprietario = "SELECT id, nome, cognome FROM proprietario";
            $resultProprietario = $conn->query($sqlProprietario);

            // Se ci sono proprietari nel database, crea le opzioni nel menu a tendina
            if ($resultProprietario->num_rows > 0) {
                while ($row = $resultProprietario->fetch_assoc()) {
                    echo "<option value='" . $row['id'] . "'>" . $row['nome'] . " " . $row['cognome'] . "</option>";
                }
            } else {
                echo "<option value=''>Nessun proprietario disponibile</option>";
            }

            // Chiudi la connessione al database
            $conn->close();
            ?>
        </select>
        <label for="newNome">Nuovo Nome:</label>
        <input type="text" name="newNome" id="newNome">
        <label for="newCognome">Nuovo Cognome:</label>
        <input type="text" name="newCognome" id="newCognome">
        <label for="newAuto">Nuova Auto:</label>
        <select name="newAuto" id="newAuto">
            <?php
            // Includi il file di connessione al database
            include('connessione.php');

            // Query per ottenere l'elenco delle auto dalla tabella auto
            $sqlAuto = "SELECT id, marca, modello FROM automobile";
            $resultAuto = $conn->query($sqlAuto);

            // Se ci sono auto nel database, crea le opzioni nel menu a tendina
            if ($resultAuto->num_rows > 0) {
                while ($row = $resultAuto->fetch_assoc()) {
                    echo "<option value='" . $row['id'] . "'>" . $row['marca'] . " " . $row['modello'] . "</option>";
                }
            } else {
                echo "<option value=''>Nessuna auto disponibile</option>";
            }

            // Chiudi la connessione al database
            $conn->close();
            ?>
        </select>
        <input type="submit" name="modificaProprietario" value="Modifica">
    </form>

    <?php
    // Verifica se il modulo di modifica è stato inviato
    if (isset($_POST['modificaProprietario'])) {
        // Assicurati di avere una connessione al database
        include('connessione.php');

        // Recupera i dati dal modulo
        $idProprietario = $_POST['idProprietario'];
        $newNome = $_POST['newNome'];
        $newCognome = $_POST['newCognome'];
        $newAuto = $_POST['newAuto'];

        // Query per aggiornare i dati nella tabella proprietario
        $sqlModifica = "UPDATE proprietario SET nome='$newNome', cognome='$newCognome', auto='$newAuto' WHERE id='$idProprietario'";

        // Esegui la query di aggiornamento
        if ($conn->query($sqlModifica) === TRUE) {
            echo "<p>Modifica effettuata con successo.</p>";

            // Reindirizza l'utente per evitare di inviare nuovamente il modulo se ricarica la pagina
            header("Location: {$_SERVER['PHP_SELF']}");
            exit();
        } else {
            echo "Errore durante la modifica: " . $conn->error;
        }

        // Chiudi la connessione al database
        $conn->close();
    }
    ?>
    
    <h2>Elimina Proprietario:</h2>
<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    <label for="idProprietarioElimina">Seleziona Proprietario da Eliminare:</label>
    <select name="idProprietarioElimina" id="idProprietarioElimina">
        <?php
        // Includi il file di connessione al database
        include('connessione.php');

        // Query per ottenere l'elenco dei proprietari dalla tabella proprietario
        $sqlProprietario = "SELECT id, CONCAT(nome, ' ', cognome) AS nome_completo FROM proprietario";
        $resultProprietario = $conn->query($sqlProprietario);

        // Se ci sono proprietari nel database, crea le opzioni nel menu a tendina
        if ($resultProprietario->num_rows > 0) {
            while ($row = $resultProprietario->fetch_assoc()) {
                echo "<option value='" . $row['id'] . "'>" . $row['nome_completo'] . "</option>";
            }
        } else {
            echo "<option value=''>Nessun proprietario disponibile</option>";
        }

        // Chiudi la connessione al database
        $conn->close();
        ?>
    </select>
    <button type="submit" name="eliminaProprietario">Elimina Proprietario</button>
</form>

<?php
// Verifica se il modulo di eliminazione è stato inviato
if (isset($_POST['eliminaProprietario'])) {
    // Assicurati di avere una connessione al database
    include('connessione.php');

    // Recupera l'ID del proprietario da eliminare
    $idProprietarioElimina = $_POST['idProprietarioElimina'];

    // Query per eliminare il proprietario dalla tabella proprietario
    $sqlElimina = "DELETE FROM proprietario WHERE id='$idProprietarioElimina'";

    // Esegui la query di eliminazione
    if ($conn->query($sqlElimina) === TRUE) {
        echo "<p>Proprietario eliminato con successo.</p>";

        // Reindirizza l'utente per evitare di inviare nuovamente il modulo se ricarica la pagina
        header("Location: {$_SERVER['PHP_SELF']}");
        exit();
    } else {
        echo "Errore durante l'eliminazione del proprietario: " . $conn->error;
    }

    // Chiudi la connessione al database
    $conn->close();
}
?>


    <h2>Aggiungi Nuova macchina:</h2>
    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <label for="marca">Marca:</label>
        <input type="text" name="marca" id="marca">
        <label for="modello">Modello:</label>
        <input type="text" name="modello" id="modello">
        <label for="prezzo">Prezzo:</label>
        <input type="text" name="prezzo" id="prezzo">
        <label for="prezzo">Riparazioni:</label>
        <select name="riparazioni" id="riparazioni">
            <option value="">Nessuna riparazione</option>
            <?php
            // Includi il file di connessione al database
            include('connessione.php');

            // Query per ottenere l'elenco delle riparazioni dalla tabella riparazione
            $sqlRiparazioni = "SELECT id, descrizione FROM riparazione";
            $resultRiparazioni = $conn->query($sqlRiparazioni);

            // Verifica se la query ha prodotto risultati
            if ($resultRiparazioni) {
                // Se ci sono riparazioni nel database, crea le opzioni nel menu a tendina
                if ($resultRiparazioni->num_rows > 0) {
                    while ($row = $resultRiparazioni->fetch_assoc()) {
                        echo "<option value='" . $row['id'] . "'>" . $row['descrizione'] . "</option>";
                    }
                } else {
                    echo "<option value=''>Nessuna riparazione disponibile</option>";
                }
            } else {
                // Se si verifica un errore nella query, visualizza un messaggio di errore
                echo "Errore nella query: " . $conn->error;
            }

            // Chiudi la connessione al database
            $conn->close();
            ?>
        </select>
        <input type="submit" name="aggiungiAutomobile" value="Aggiungi Macchina">
    </form>

    <?php
    include('connessione.php');
    // Verifica se il modulo di aggiunta macchina è stato inviato
    if (isset($_POST['aggiungiAutomobile'])) {
        // Recupera i dati dal modulo
        $marca = $_POST['marca'];
        $modello = $_POST['modello'];
        $prezzo = intval($_POST['prezzo']);
        if (!empty($_POST['riparazioni'])) {
            // Se sì, utilizza il valore selezionato
            $riparazioni = $_POST['riparazioni'];
        } else {
            // Se no, imposta $riparazioni a NULL
            $riparazioni = "NULL";
        }

        // Query per inserire i dati nella tabella automobile
        $sqlInserimento = "INSERT INTO automobile (marca, modello, prezzo, riparazioni) VALUES ('$marca', '$modello', '$prezzo', $riparazioni)";

        // Esegui la query di inserimento
        if ($conn->query($sqlInserimento) === TRUE) {
            echo "<p>Nuova riga inserita con successo.</p>";

            // Reindirizza l'utente per evitare di inviare nuovamente il modulo se ricarica la pagina
            header("Location: {$_SERVER['PHP_SELF']}");
            exit();
        } else {
            echo "Errore durante l'inserimento della riga: " . $conn->error;
        }
        $conn->close();
    }
    ob_end_flush();
    ?>
</body>
</html>
