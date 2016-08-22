-- create database and tables, together with user data, for xml articles - rss/xml assignment

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Database: `xmlArticles`  or whatever your database is called.
--

-- --------------------------------------------------------

--
-- Table structure for table 'articles' 
--

DROP TABLE IF EXISTS `articles`;
CREATE TABLE IF NOT EXISTS `articles` (
  `articleID` mediumint(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `link` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `dateSaved` datetime NOT NULL,
  PRIMARY KEY  (`articleID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;


--
-- Table structure for table 'saved_articles' 
--
DROP TABLE IF EXISTS `saved_articles`;
CREATE TABLE IF NOT EXISTS `saved_articles` (
  `articleID` mediumint(255) NOT NULL,
  `subscriberID` mediumint(255) NOT NULL,
  PRIMARY KEY  (`articleID`, `subscriberID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Table structure for table `subscriber`
--

DROP TABLE IF EXISTS `subscriber`;
CREATE TABLE IF NOT EXISTS `subscriber` (
 `subscriberID` mediumint(8) NOT NULL auto_increment,
  `email` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `lastlogin` datetime NOT NULL,
  PRIMARY KEY  (`subscriberID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------
--
-- Dumping data for table `subscriber`
--
-- NOTE that the password field uses encryption, the passwords for each user is set to
-- the users FIRST NAME (in lowercase) then converted using the PHP password_hash function.
-- When logging in you'll need to use another password function to verify the password.
-- This works in MySQL

INSERT INTO `subscriber` (`email`, `name`, `password`, `lastlogin`) VALUES
('peter@xml667.com', 'Peter Jones', '$2y$10$Z0P0bDp0eLWyF623/UbwBuAm2lsD2hflJbyCrXE4tZp1PLAcZgrUe', null),
('fred@xml667.com', 'Fred Smith', '$2y$10$2TkEtrnoGmPylIO15CBzKuYNhNYilg3vYxYVEBA9wGfzDgASO6Dxq', null),
('susan@xml667.com', 'Susan Davis', '$2y$10$nxeZdoqCw0G0UoK3yKIVB.v.2jVm5ttKWckLPHhr8tU9xC/fvKb5e', null);

--
-- Constraints for table `saved_articles`
--
ALTER TABLE `saved_articles`
  ADD CONSTRAINT `articles_fk_1` FOREIGN KEY (`subscriberID`) REFERENCES `subscriber` (`subscriberID`);
ALTER TABLE `saved_articles`
  ADD CONSTRAINT `articles_fk_2` FOREIGN KEY (`articleID`) REFERENCES `articles` (`articleID`);


