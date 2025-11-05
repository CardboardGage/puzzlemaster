CREATE DATABASE `puzzlemaster`;
USE puzzlemaster;

CREATE TABLE `User` (
	UserID int,
    Username varChar(24),
    Email varChar(50),
    `Password` varChar(50),
    Verified bool,
    TimeCreated dateTime,
    LastLogin dateTime,
    TutorialFlag bool
);

CREATE TABLE `RunHistory` (
	RunID int,
    UserID int,
    Score int,
    LevelReached int,
    TimeOf dateTime,
    Seed int,
    ModelID int
);

CREATE TABLE `GameMode` (
	ModelID int,
    `Mode` varChar(24)
);

