CREATE DATABASE `puzzlemaster`;
USE puzzlemaster;

CREATE TABLE `User` (
	UserID int PRIMARY KEY AUTO_INCREMENT,
    Username varChar(24) NOT NULL,
    Email varChar(50) NOT NULL,
    `Password` varChar(50) NOT NULL,
    Verified bool,
    TimeCreated dateTime,
    LastLogin dateTime,
    TutorialFlag bool
);

CREATE TABLE `RunHistory` (
	RunID int PRIMARY KEY,
    UserID int NOT NULL,
    Score int,
    LevelReached int,
    TimeOf dateTime,
    Seed int,
    ModeID int
);

CREATE TABLE `GameMode` (
	ModeID int PRIMARY KEY,
    `Mode` varChar(24)
);

