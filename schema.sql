-- schema.sql

CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    role ENUM('player', 'admin') DEFAULT 'player' NOT NULL,
    registration_date DATETIME NOT NULL,
    last_login DATETIME
);

CREATE TABLE background_images (
    image_id INT AUTO_INCREMENT PRIMARY KEY,
    image_name VARCHAR(100) NOT NULL,
    image_url VARCHAR(255) NOT NULL,
    is_active BOOLEAN DEFAULT TRUE,
    uploaded_by_user_id INT,
    FOREIGN KEY (uploaded_by_user_id) REFERENCES users(user_id)
);

CREATE TABLE user_preferences (
    preference_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNIQUE NOT NULL,
    default_puzzle_size VARCHAR(10) DEFAULT '4x4',
    preferred_background_image_id INT,
    sound_enabled BOOLEAN DEFAULT TRUE,
    animations_enabled BOOLEAN DEFAULT TRUE,
    FOREIGN KEY (user_id) REFERENCES users(user_id),
    FOREIGN KEY (preferred_background_image_id) REFERENCES background_images(image_id)
);

CREATE TABLE game_stats (
    stat_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    puzzle_size VARCHAR(10) NOT NULL,
    time_taken_seconds INT NOT NULL,
    moves_count INT NOT NULL,
    background_image_id INT,
    win_status BOOLEAN NOT NULL,
    game_date DATETIME NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(user_id),
    FOREIGN KEY (background_image_id) REFERENCES background_images(image_id)
);