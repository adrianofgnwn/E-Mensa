CREATE TABLE Ersteller (
                           id INT AUTO_INCREMENT PRIMARY KEY,   -- Unique creator ID
                           name VARCHAR(100) DEFAULT 'anonym',  -- Creator's name, default is 'anonym'
                           email VARCHAR(255) NOT NULL UNIQUE   -- Creator's email, must be unique
);

CREATE TABLE wunschgericht (
                               id INT AUTO_INCREMENT PRIMARY KEY,   -- Unique dish ID
                               name VARCHAR(100) NOT NULL,          -- Dish name
                               description TEXT NOT NULL,           -- Dish description
                               creation_date DATE DEFAULT CURRENT_DATE, -- Automatically sets the creation date
                               creator_id INT NOT NULL,             -- Foreign key linking to the creator's ID

    -- Define Foreign Key Constraint
                               FOREIGN KEY (creator_id) REFERENCES Ersteller(id)
                                   ON DELETE CASCADE ON UPDATE CASCADE
);
