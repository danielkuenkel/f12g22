-- phpMyAdmin SQL Dump
-- version 3.4.10.1deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Erstellungszeit: 11. Nov 2012 um 08:56
-- Server Version: 5.5.24
-- PHP-Version: 5.3.10-1ubuntu3.4

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Datenbank: `student_f12g22`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `assessment`
--

CREATE TABLE IF NOT EXISTS `assessment` (
  `voting_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `recipe_id` int(11) NOT NULL,
  `voting` int(11) NOT NULL,
  PRIMARY KEY (`voting_id`),
  UNIQUE KEY `user_id` (`user_id`),
  UNIQUE KEY `recipe_id` (`recipe_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `category`
--

CREATE TABLE IF NOT EXISTS `category` (
  `category_id` int(11) NOT NULL,
  `recipe_id` int(11) NOT NULL,
  `name` varchar(20) NOT NULL,
  PRIMARY KEY (`category_id`,`recipe_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `comment`
--

CREATE TABLE IF NOT EXISTS `comment` (
  `comment_id` int(11) NOT NULL,
  `recipe_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `comment` varchar(500) NOT NULL,
  `createed` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`comment_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `event`
--

CREATE TABLE IF NOT EXISTS `event` (
  `event_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `titel` varchar(50) NOT NULL,
  `abstract` varchar(1000) NOT NULL,
  `max_participants` int(11) NOT NULL,
  `current_participants` int(11) NOT NULL,
  `cost` float NOT NULL DEFAULT '0',
  `date` date NOT NULL,
  `street` varchar(30) NOT NULL,
  `house_number` int(11) NOT NULL,
  `zipcode` int(11) NOT NULL,
  `city` varchar(20) NOT NULL,
  `time_of_day` varchar(10) NOT NULL,
  PRIMARY KEY (`event_id`),
  UNIQUE KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `event_participant`
--

CREATE TABLE IF NOT EXISTS `event_participant` (
  `event_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`event_id`,`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `favorites`
--

CREATE TABLE IF NOT EXISTS `favorites` (
  `user_id` int(11) NOT NULL,
  `recipe_id` int(11) NOT NULL,
  UNIQUE KEY `user_id` (`user_id`),
  UNIQUE KEY `recipe_id` (`recipe_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `ingredient`
--

CREATE TABLE IF NOT EXISTS `ingredient` (
  `ingredient_id` int(11) NOT NULL,
  `recipe_id` int(11) NOT NULL,
  `quantity` int(11) DEFAULT NULL,
  `ingredient` varchar(40) NOT NULL,
  `unit_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`ingredient_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `ingredient`
--

INSERT INTO `ingredient` (`ingredient_id`, `recipe_id`, `quantity`, `ingredient`, `unit_id`) VALUES
(1, 5000, 600, 'Kalbsschnitzel', 1),
(2, 5000, 3, 'Eier', NULL),
(3, 5000, 250, 'Butterschmalz', 1),
(4, 5000, NULL, 'Salz', NULL),
(5, 5000, NULL, 'Mehl', NULL),
(6, 5000, NULL, 'Semmelbrösel', NULL),
(7, 5000, 1, 'Zitrone', NULL),
(8, 300, 440, 'Hackfleisch (vom Rind oder gemischt)', 1),
(9, 300, 1, 'Zwiebel', 2),
(11, 300, 500, 'Tomaten, stückige', 1),
(12, 300, NULL, 'Rotwein, italienischer', NULL),
(13, 300, NULL, 'Salz und Pfeffer', NULL),
(14, 300, NULL, 'Kräuter, italienische', NULL),
(15, 300, 250, 'Spaghetti', 1),
(16, 300, NULL, 'Salzwasser', NULL),
(17, 300, 2, 'Olivenöl', 4);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `recipe`
--

CREATE TABLE IF NOT EXISTS `recipe` (
  `recipe_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(40) NOT NULL,
  `image_url` varchar(50) DEFAULT NULL,
  `voting` float NOT NULL DEFAULT '0',
  `total_votes` int(11) NOT NULL DEFAULT '0',
  `abstract` varchar(1000) DEFAULT NULL,
  `preparation` varchar(20000) NOT NULL,
  `cooking_time` varchar(20) NOT NULL,
  `servings` int(11) NOT NULL,
  PRIMARY KEY (`recipe_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `recipe`
--

INSERT INTO `recipe` (`recipe_id`, `user_id`, `title`, `image_url`, `voting`, `total_votes`, `abstract`, `preparation`, `cooking_time`, `servings`) VALUES
(300, 2000, 'Spaghetti Bolognese', NULL, 0, 0, 'Italienisches Nudelgericht mit Tomatensoße und Hackfleisch', 'Die Zwiebel und den Knoblauch in kleine Würfel schneiden. Hochwertiges Olivenöl in einem großen Topf erwärmen und gehackte Zwiebeln, Knoblauch und italienische Kräuter anschwitzen. Danach das Hackfleisch dazugeben und leicht anbraten lassen. Anschließend das Hackfleisch im Topf zerkleinern und mit italienischem Rotwein ablöschen.\n\nJetzt die stückigen Tomaten untermischen und auf niedriger Stufe eine Stunde köcheln lassen. So bekommt die Soße eine würzige und intensive Note vom Wein und gebratenem Fleisch. Zwischendurch evtl. Rotwein nachgießen, sodass die Soße immer flüssig bleibt und nicht anbrennt.\n\nDie Soße wartet auf die Nudeln! Dieses Sprichwort immer beherzigen, da Nudeln schneller auskühlen. Die Nudeln in kochendes Salzwasser geben und al dente kochen. Anschließend in einem Nudelsieb abgießen.\n\nDie Nudeln auf einen erwärmten Teller geben und mit der Soße übergießen. Je nach Lust und Laune darf das Gericht mit Kräutern oder geriebenem Hartkäse verziert werden.', '30 min', 2),
(5000, 2000, 'Wiener Schnitzel', NULL, 0, 0, 'Wiener Schnitzel ist ein dünnes, paniertes und ausgebackenes Schnitzel aus Kalbfleisch. Es gehört zu den bekanntesten Spezialitäten der Wiener Küche.', 'Vom Fleischer schöne Kalbsschnitzel schneiden lassen und diese am den Rändern leicht einschneiden. Die Schnitzel mit Klarsichtfolie bedecken und zart plattieren (klopfen).\nDie Stärke der Schnitzel ist individuell auf den persönlichen Geschmack abgestimmt, misst jedoch im Normalfall ca. 6 mm. Die Schnitzel beidseitig gleichmäßig salzen.\n\nDie Eier mit einer Gabel leicht verschlagen. Die Kalbsschnitzel in Mehl beidseitig wenden, durch die Eier ziehen und danach in Semmelbröseln wenden (die Brösel dabei nur zart andrücken). Die Schnitzel leicht abschütteln und überschüssige Brösel entfernen.\n\nReichlich Butterschmalz in einer passenden Pfanne ca. 2 – 3 cm hoch erhitzen. Die Schnitzel in das heiße Fett legen und unter wiederholtem Schwingen der Pfanne bräunen. Dann mittels einer Fleischgabel vorsichtig wenden und von der anderen Seite fertig backen. Mit einer Backschaufel aus der Pfanne heben.\n\nDie Schnitzel abtropfen lassen, mit Küchenkrepp das überschüssige Fett abtupfen und mit einer Zitronenspalte garniert servieren.\n\nPassende Beilagen sind Kartoffelsalat, Gurkensalat, Feldsalat oder Petersilienkartoffeln.\n\nWichtig: Zu Wiener Schnitzel wird KEINE Sauce gereicht! ', '30 min', 4);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `unit`
--

CREATE TABLE IF NOT EXISTS `unit` (
  `unit_id` int(11) NOT NULL,
  `unit_name` varchar(20) NOT NULL,
  PRIMARY KEY (`unit_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `unit`
--

INSERT INTO `unit` (`unit_id`, `unit_name`) VALUES
(1, 'g'),
(2, 'kleine'),
(3, 'Zehe(n)'),
(4, 'Esslöffel');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `user_id` int(11) NOT NULL,
  `logon_name` varchar(20) NOT NULL,
  `firstname` varchar(20) NOT NULL,
  `lastname` varchar(20) NOT NULL,
  `email` varchar(20) NOT NULL,
  `street` varchar(20) NOT NULL,
  `house_number` int(11) NOT NULL,
  `zipcode` int(11) NOT NULL,
  `city` varchar(20) NOT NULL,
  `phone_number` varchar(20) DEFAULT NULL,
  `password` varchar(35) NOT NULL,
  `activation_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `activate` bit(1) NOT NULL DEFAULT b'0',
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `logon_name` (`logon_name`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `user`
--

INSERT INTO `user` (`user_id`, `logon_name`, `firstname`, `lastname`, `email`, `street`, `house_number`, `zipcode`, `city`, `phone_number`, `password`, `activation_date`, `activate`) VALUES
(2000, 'MaxM', 'Max', 'Mustermann', 'maxm@test.de', 'Musterstr.', 11, 10115, 'Berlin', NULL, 'abcd1234', '2012-11-10 09:05:00', b'1');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
