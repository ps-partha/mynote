## About Website
<p align="center">
  <img src="https://i.ibb.co/wWjPRGJ/my-note-website.png" />
</p>


My Note website, similar to Google Keep, stores notes efficiently with dynamic resizing and robust search capabilities, allowing users to easily organize and retrieve their notes by various criteria.

## Installation

Live [Demo](https://www.mynotes.000.pe).

```bash
git clone https://github.com/ps-partha/mynote.git
```
## Create Database 

```python
CREATE DATABASE database-Name; 

# Then Run those SQL Command.

CREATE TABLE IF NOT EXISTS `users` (
    `id` int(20) NOT NULL AUTO_INCREMENT,
    `username` varchar(255) NOT NULL,
    `email` varchar(255) NOT NULL,
    `password` varchar(255) NOT NULL,
    `create_datetime` datetime NOT NULL,
    `name` varchar(255),
    PRIMARY KEY (`id`)
);

# Create the user_notes table

CREATE TABLE IF NOT EXISTS `user_notes` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `username` varchar(255) NOT NULL,
    `titles` VARCHAR(255),
    `messages` TEXT NOT NULL,
    `status` VARCHAR(100),
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `user_id` int(20)
);

# Create the Password reset table

CREATE TABLE password_resets (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `username` varchar(255) NOT NULL,
    `email` VARCHAR(255) NOT NULL,
    `token` VARCHAR(255) NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


```

## Usage

```python

# In sign-in.php, sign-up.php and reset_password.php file Need to add your domain name like this

window.location.href = "https://www.example.com/notes/";

# Then host any hosting site or host whatever you want.

```

## Contributing
To improving our note-taking website by reporting issues, submitting feature requests, or providing code enhancements.

Pull requests are welcome. For major changes, please open an issue first
to discuss what you would like to change.

Please make sure to update tests as appropriate.
