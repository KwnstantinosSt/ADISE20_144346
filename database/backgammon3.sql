-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 06, 2021 at 04:10 PM
-- Server version: 10.4.17-MariaDB
-- PHP Version: 8.0.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `backgammon3`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `change_turn` ()  BEGIN
	DECLARE turn char(1);
    SELECT P_TURN INTO turn FROM game_status;
    IF (turn = 'W') THEN
		UPDATE game_status
        SET P_TURN = 'B';
	ELSE
		UPDATE game_status
        SET P_TURN = 'W';
    END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `CLEAN_ALL` ()  BEGIN
	DELETE FROM DICE_HISTORY;
	DELETE FROM PLAYERS;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `CLEAN_BOARD` ()  BEGIN
	REPLACE INTO BOARD SELECT * FROM EMPTY_BOARD;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `DiceAZero` ()  BEGIN
	DECLARE pid INTEGER;
	
    select ID INTO pid
	from dice_history
	order by ID desc limit 1;
    
    UPDATE dice_history
    SET DICE1 = 0
    WHERE ID = pid;
  
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `DiceBZero` ()  BEGIN
	DECLARE pid INTEGER;
	
    select ID INTO pid
	from dice_history
	order by ID desc limit 1;
    
    UPDATE dice_history
    SET DICE2 = 0
    WHERE ID = pid;
  
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `first_next_dices` ()  BEGIN
		DECLARE stat,pl1,pl2 char(255);
        DECLARE p1_zaria1,p1_zaria2,p2_zaria1,p2_zaria2 tinyint(1);
		DECLARE sum_dice1, sum_dice2 tinyint(2);
        DECLARE p1_color,p2_color char(255);
        DECLARE turn char(1);
        
        SELECT `STATUS` INTO stat
		FROM game_status;
        
        SELECT `P_TURN` INTO turn
        FROM game_status;
        
		SELECT `USERNAME` INTO pl1
        FROM players
        WHERE PIECE_COLOR = 'W';

		SELECT `USERNAME` INTO pl2
        FROM players
        WHERE PIECE_COLOR = 'B';
        
        SELECT `PIECE_COLOR` INTO p1_color
        FROM players
        WHERE `USERNAME` = pl1;
        
        SELECT `PIECE_COLOR` INTO p2_color
        FROM players
        WHERE `USERNAME` = pl2;
        
       
        
        IF (stat = "STARTED" AND turn IS NULL ) THEN
				 SELECT FLOOR(RAND()*(6-1+1)) + 1 INTO p1_zaria1;
				 SELECT FLOOR(RAND()*(6-1+1)) + 1 INTO p1_zaria2;
        
				SELECT FLOOR(RAND()*(6-1+1)) + 1 INTO p2_zaria1;
				SELECT FLOOR(RAND()*(6-1+1)) + 1 INTO p2_zaria2;
                
				INSERT INTO dice_history(`USERNAME`,`DICE1`,`DICE2`)
					VALUES(pl1,p1_zaria1,p1_zaria2);
                
				INSERT INTO dice_history(`USERNAME`,`DICE1`,`DICE2`)
					VALUES(pl2,p2_zaria1,p2_zaria2); 
                    
                SELECT p1_zaria1 + p1_zaria2 INTO sum_dice1;
				SELECT p2_zaria1 + p2_zaria2 INTO sum_dice2;
					IF(sum_dice1>sum_dice2) THEN
						UPDATE game_status
                        SET P_TURN = p1_color;
                    ELSE
						UPDATE game_status
                        SET P_TURN = p2_color;
                        END IF;
		 ELSEIF (stat = "STARTED" AND turn='B' ) THEN	
			SELECT FLOOR(RAND()*(6-1+1)) + 1 INTO p2_zaria1;
			SELECT FLOOR(RAND()*(6-1+1)) + 1 INTO p2_zaria2;
			INSERT INTO dice_history(`USERNAME`,`DICE1`,`DICE2`)
					VALUES(pl2,p2_zaria1,p2_zaria2);
		 ELSEIF (stat = "STARTED" AND turn='W' ) THEN	
			SELECT FLOOR(RAND()*(6-1+1)) + 1 INTO p1_zaria1;
			SELECT FLOOR(RAND()*(6-1+1)) + 1 INTO p1_zaria2;
			INSERT INTO dice_history(`USERNAME`,`DICE1`,`DICE2`)
					VALUES(pl1,p1_zaria1,p1_zaria2);
		END IF;
	END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `get_last_dice` ()  BEGIN
	select * from dice_history
	order by id desc limit 1;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `movePiece` (`pos_old` INTEGER, `pos_new` INTEGER)  BEGIN
	DECLARE pce ,newpiece ,test , onepiece CHAR(15) ;

SELECT PIECE INTO pce FROM BOARD 
WHERE pos = pos_old;

SELECT LEFT(pce, LENGTH(pce)-1) INTO newpiece;
SELECT RIGHT(pce, 1) INTO onepiece;

UPDATE BOARD 
SET PIECE=newpiece 
WHERE pos = pos_old;

SELECT LENGTH(PIECE) INTO test
FROM BOARD
WHERE pos = pos_new;



 IF test IS NULL OR test=''  THEN
        UPDATE BOARD 
		SET PIECE=onepiece
		WHERE pos = pos_new;
      
ELSE  
		UPDATE BOARD 
		SET PIECE=concat(PIECE,onepiece)
		WHERE pos = pos_new;
  END IF;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `board`
--

CREATE TABLE `board` (
  `x` tinyint(4) NOT NULL,
  `y` tinyint(4) NOT NULL,
  `pos` int(11) NOT NULL,
  `PIECE` varchar(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `board`
--

INSERT INTO `board` (`x`, `y`, `pos`, `PIECE`) VALUES
(1, 11, 1, 'WWWWWWWWWWWWWWW'),
(1, 10, 2, NULL),
(1, 9, 3, NULL),
(1, 8, 4, NULL),
(1, 7, 5, NULL),
(1, 6, 6, NULL),
(1, 5, 7, NULL),
(1, 4, 8, NULL),
(1, 3, 9, NULL),
(1, 2, 10, NULL),
(1, 1, 11, NULL),
(1, 0, 12, NULL),
(0, 0, 13, NULL),
(0, 1, 14, NULL),
(0, 2, 15, NULL),
(0, 3, 16, NULL),
(0, 4, 17, NULL),
(0, 5, 18, NULL),
(0, 6, 19, NULL),
(0, 7, 20, NULL),
(0, 8, 21, NULL),
(0, 9, 22, NULL),
(0, 10, 23, NULL),
(0, 11, 24, 'BBBBBBBBBBBBBBB');

-- --------------------------------------------------------

--
-- Table structure for table `dice_history`
--

CREATE TABLE `dice_history` (
  `ID` int(11) NOT NULL,
  `USERNAME` varchar(20) DEFAULT NULL,
  `DICE1` tinyint(1) DEFAULT NULL,
  `DICE2` tinyint(1) DEFAULT NULL,
  `TIME` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Triggers `dice_history`
--
DELIMITER $$
CREATE TRIGGER `dice_update2` BEFORE INSERT ON `dice_history` FOR EACH ROW BEGIN
		SET NEW.TIME= NOW();
	END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `empty_board`
--

CREATE TABLE `empty_board` (
  `x` tinyint(4) NOT NULL,
  `y` tinyint(4) NOT NULL,
  `pos` int(11) NOT NULL,
  `PIECE` varchar(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `empty_board`
--

INSERT INTO `empty_board` (`x`, `y`, `pos`, `PIECE`) VALUES
(1, 11, 1, 'WWWWWWWWWWWWWWW'),
(1, 10, 2, NULL),
(1, 9, 3, NULL),
(1, 8, 4, NULL),
(1, 7, 5, NULL),
(1, 6, 6, NULL),
(1, 5, 7, NULL),
(1, 4, 8, NULL),
(1, 3, 9, NULL),
(1, 2, 10, NULL),
(1, 1, 11, NULL),
(1, 0, 12, NULL),
(0, 0, 13, NULL),
(0, 1, 14, NULL),
(0, 2, 15, NULL),
(0, 3, 16, NULL),
(0, 4, 17, NULL),
(0, 5, 18, NULL),
(0, 6, 19, NULL),
(0, 7, 20, NULL),
(0, 8, 21, NULL),
(0, 9, 22, NULL),
(0, 10, 23, NULL),
(0, 11, 24, 'BBBBBBBBBBBBBBB');

-- --------------------------------------------------------

--
-- Table structure for table `game_status`
--

CREATE TABLE `game_status` (
  `STATUS` enum('NOT ACTIVE','INITIALIZED','STARTED','ENDED','ABORDED') NOT NULL DEFAULT 'NOT ACTIVE',
  `P_TURN` enum('W','B') DEFAULT NULL,
  `RESULT` enum('W','B') DEFAULT NULL,
  `LAST_ACTION` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `game_status`
--

INSERT INTO `game_status` (`STATUS`, `P_TURN`, `RESULT`, `LAST_ACTION`) VALUES
('NOT ACTIVE', NULL, NULL, '2021-01-06 13:41:43');

--
-- Triggers `game_status`
--
DELIMITER $$
CREATE TRIGGER `status_update` BEFORE UPDATE ON `game_status` FOR EACH ROW BEGIN
		SET NEW.LAST_ACTION= NOW();
	END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `players`
--

CREATE TABLE `players` (
  `USERNAME` varchar(20) DEFAULT NULL,
  `PIECE_COLOR` enum('B','W') NOT NULL,
  `AUTH` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Triggers `players`
--
DELIMITER $$
CREATE TRIGGER `status_update2` AFTER INSERT ON `players` FOR EACH ROW BEGIN
		DECLARE len INTEGER;
        SELECT COUNT(*) INTO len
		FROM PLAYERS;
        IF (len = 0 ) THEN
			UPDATE game_status
            SET STATUS = 'NOT ACTIVE',P_TURN=NULL;
		ELSEIF (len = 1) THEN
			UPDATE game_status
            SET STATUS = 'INITIALIZED',P_TURN=NULL;
		ELSEIF (len = 2) THEN
			UPDATE game_status
            SET STATUS = 'STARTED';
		END IF;
	END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `status_update3` AFTER DELETE ON `players` FOR EACH ROW BEGIN
		DECLARE len INTEGER;
        SELECT COUNT(*) INTO len
		FROM PLAYERS;
        IF (len = 0 ) THEN
			UPDATE game_status
            SET STATUS = 'NOT ACTIVE',P_TURN=NULL;
		ELSEIF (len = 1) THEN
			UPDATE game_status
            SET STATUS = 'INITIALIZED',P_TURN=NULL;
		ELSEIF (len = 2) THEN
			UPDATE game_status
            SET STATUS = 'STARTED';
		END IF;
	END
$$
DELIMITER ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `board`
--
ALTER TABLE `board`
  ADD PRIMARY KEY (`pos`);

--
-- Indexes for table `dice_history`
--
ALTER TABLE `dice_history`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `empty_board`
--
ALTER TABLE `empty_board`
  ADD PRIMARY KEY (`pos`);

--
-- Indexes for table `players`
--
ALTER TABLE `players`
  ADD PRIMARY KEY (`PIECE_COLOR`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `dice_history`
--
ALTER TABLE `dice_history`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=157;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
