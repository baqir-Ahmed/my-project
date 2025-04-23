CREATE DATABASE ESPORTS;
USE ESPORTS;

CREATE TABLE users (user_id INT AUTO_INCREMENT PRIMARY KEY,
name VARCHAR(100) NOT NULL,
email VARCHAR(100) UNIQUE NOT NULL);

CREATE TABLE tournament(tour_id INT AUTO_INCREMENT PRIMARY KEY,
name TEXT NOT NULL);

INSERT INTO tournament (name) VALUES ('League of Legends');
INSERT INTO tournament (name) VALUES ('Wild Rift');
INSERT INTO tournament (name) VALUES ('TFT');



CREATE TABLE user_tournament (id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    tour_id INT,
    registration_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id),
    FOREIGN KEY (tour_id) REFERENCES tournament(tour_id));
