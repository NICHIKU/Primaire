-- Users Table
CREATE TABLE IF NOT EXISTS users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE,
    role ENUM('Enfant', 'Professeur(e)', 'Parent') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Relatives Table (pour les relations Parent-Enfant)
CREATE TABLE IF NOT EXISTS relatives (
    relative_id INT AUTO_INCREMENT PRIMARY KEY,
    parent_id INT NOT NULL,
    child_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (parent_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (child_id) REFERENCES users(user_id) ON DELETE CASCADE,
    UNIQUE KEY parent_child_unique (parent_id, child_id)
);

-- Exercises Table (exemple simple)
CREATE TABLE IF NOT EXISTS exercises (
    exercise_id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT
);

-- Stats Table (exemple de statistiques - pourrait être plus détaillé)
CREATE TABLE IF NOT EXISTS stats (
    stat_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    exercise_id INT NOT NULL,
    score INT,
    date_taken TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (exercise_id) REFERENCES exercises(exercise_id) ON DELETE CASCADE
);