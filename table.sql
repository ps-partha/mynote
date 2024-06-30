CREATE TABLE IF NOT EXISTS `users` (
 `id` int(20) NOT NULL AUTO_INCREMENT,
 `username` varchar(255) NOT NULL,
 `email` varchar(255) NOT NULL,
 `password` varchar(255) NOT NULL,
 `create_datetime` datetime NOT NULL,
 `name` varchar(255),
 `status` varchar(20),
 PRIMARY KEY (`id`)
);

CREATE TABLE password_resets (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `username` varchar(255) NOT NULL,
    `email` VARCHAR(255) NOT NULL,
    `token` VARCHAR(255) NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE user_notes (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `username` varchar(255) NOT NULL,
    `titles` VARCHAR(255),
    `messages` TEXT NOT NULL,
    `status` VARCHAR(100),
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

