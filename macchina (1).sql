-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Creato il: Mar 27, 2024 alle 15:36
-- Versione del server: 10.4.32-MariaDB
-- Versione PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `macchina`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `automobile`
--

CREATE TABLE `automobile` (
  `id` int(11) NOT NULL,
  `marca` varchar(100) NOT NULL,
  `modello` varchar(100) NOT NULL,
  `prezzo` float NOT NULL,
  `riparazioni` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `automobile`
--

INSERT INTO `automobile` (`id`, `marca`, `modello`, `prezzo`, `riparazioni`) VALUES
(1, 'Volkswagen', 'polo', 10000, NULL),
(2, 'Fiat', 'Doblò', 36000, NULL),
(3, 'Renault', 'Capture', 20000, 2),
(9, 'Fiat', 'Panda', 10000, 1),
(32, 'Volkswagen', 'T-Roc', 30000, NULL);

-- --------------------------------------------------------

--
-- Struttura della tabella `esegue`
--

CREATE TABLE `esegue` (
  `id_relazione` int(11) NOT NULL,
  `costo` float NOT NULL,
  `data` date NOT NULL,
  `id_macchina` int(11) NOT NULL,
  `id_tagliando` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `esegue`
--

INSERT INTO `esegue` (`id_relazione`, `costo`, `data`, `id_macchina`, `id_tagliando`) VALUES
(1, 40, '2024-01-10', 0, 0);

-- --------------------------------------------------------

--
-- Struttura della tabella `patente`
--

CREATE TABLE `patente` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `cognome` varchar(100) NOT NULL,
  `telefono` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `proprietario`
--

CREATE TABLE `proprietario` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `cognome` varchar(100) NOT NULL,
  `auto` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `proprietario`
--

INSERT INTO `proprietario` (`id`, `nome`, `cognome`, `auto`) VALUES
(1, 'Giovanni', 'Muciacia', 32),
(2, 'Pietro', 'Patelli', 2),
(3, 'Nicolas', 'Ghirardi', 3);

-- --------------------------------------------------------

--
-- Struttura della tabella `riparazione`
--

CREATE TABLE `riparazione` (
  `id` int(11) NOT NULL,
  `descrizione` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `riparazione`
--

INSERT INTO `riparazione` (`id`, `descrizione`) VALUES
(1, 'sostituzione radiatore'),
(2, 'sostituzione fari');

-- --------------------------------------------------------

--
-- Struttura della tabella `tagliando`
--

CREATE TABLE `tagliando` (
  `id` int(11) NOT NULL,
  `descrizione` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `tagliando`
--

INSERT INTO `tagliando` (`id`, `descrizione`) VALUES
(2, 'è stato cambiato l\'olio'),
(3, 'ho sistemato i freni');

-- --------------------------------------------------------

--
-- Struttura della tabella `utente`
--

CREATE TABLE `utente` (
  `username` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `utente`
--

INSERT INTO `utente` (`username`, `password`) VALUES
('matteo', 'matteo');

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `automobile`
--
ALTER TABLE `automobile`
  ADD PRIMARY KEY (`id`),
  ADD KEY `effettua` (`riparazioni`);

--
-- Indici per le tabelle `esegue`
--
ALTER TABLE `esegue`
  ADD PRIMARY KEY (`id_relazione`);

--
-- Indici per le tabelle `patente`
--
ALTER TABLE `patente`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `proprietario`
--
ALTER TABLE `proprietario`
  ADD PRIMARY KEY (`id`),
  ADD KEY `possiede` (`auto`);

--
-- Indici per le tabelle `riparazione`
--
ALTER TABLE `riparazione`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `tagliando`
--
ALTER TABLE `tagliando`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `utente`
--
ALTER TABLE `utente`
  ADD PRIMARY KEY (`username`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `automobile`
--
ALTER TABLE `automobile`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT per la tabella `esegue`
--
ALTER TABLE `esegue`
  MODIFY `id_relazione` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT per la tabella `patente`
--
ALTER TABLE `patente`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `proprietario`
--
ALTER TABLE `proprietario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT per la tabella `riparazione`
--
ALTER TABLE `riparazione`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT per la tabella `tagliando`
--
ALTER TABLE `tagliando`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `automobile`
--
ALTER TABLE `automobile`
  ADD CONSTRAINT `effettua` FOREIGN KEY (`riparazioni`) REFERENCES `riparazione` (`id`);

--
-- Limiti per la tabella `patente`
--
ALTER TABLE `patente`
  ADD CONSTRAINT `essere` FOREIGN KEY (`id`) REFERENCES `proprietario` (`id`);

--
-- Limiti per la tabella `proprietario`
--
ALTER TABLE `proprietario`
  ADD CONSTRAINT `possiede` FOREIGN KEY (`auto`) REFERENCES `automobile` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
