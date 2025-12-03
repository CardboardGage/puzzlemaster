DROP DATABASE IF EXISTS `puzzlemaster`;
CREATE DATABASE `puzzlemaster`;
USE puzzlemaster;

CREATE TABLE `user` (
	UserID int PRIMARY KEY AUTO_INCREMENT,
    Username varChar(24) NOT NULL,
    Email varChar(50) NOT NULL,
    `Password` varChar(60) NOT NULL,
    Verified bool,
    TimeCreated dateTime,
    LastLogin dateTime,
    AdminStatus bool
);

CREATE TABLE `runhistory` (
	RunID int PRIMARY KEY AUTO_INCREMENT,
    UserID int NOT NULL,
    Score int,
    LevelReached int,
    TimeOf dateTime,
    Seed varchar(16),
    ModeID int
);

CREATE TABLE `gamemode` (
	ModeID int PRIMARY KEY AUTO_INCREMENT,
    `Mode` varChar(24)
);

INSERT INTO `GameMode` (`Mode`) VALUES ('Unseeded');
INSERT INTO `GameMode` (`Mode`) VALUES ('Seeded');

