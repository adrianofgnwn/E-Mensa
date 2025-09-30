USE emensawerbeseite;

CREATE TABLE `bewertung` (
                             `id` INT AUTO_INCREMENT PRIMARY KEY,          -- Unique identifier for each rating
                             `user_id` BIGINT NOT NULL,                    -- Foreign key linking to the users table
                             `gericht_id` BIGINT NOT NULL,                 -- Foreign key linking to the dishes table
                             `bemerkung` VARCHAR(255) NOT NULL,            -- User's remark with a minimum length of 5 characters
                             `sterne` ENUM('sehr gut', 'gut', 'schlecht', 'sehr schlecht') NOT NULL, -- Star rating
                                                                                                     -- ENUM: An ENUM is a string object with a value
                                                                                                     -- chosen from a list of permitted values
                             `bewertungszeitpunkt` DATETIME DEFAULT CURRENT_TIMESTAMP, -- Auto-inserted timestamp
                             `hervorgehoben` TINYINT(1) DEFAULT 0,         -- Highlight flag (0 = false, 1 = true)

                             CONSTRAINT `fk_user_id` FOREIGN KEY (`user_id`) REFERENCES `benutzer`(`id`) -- Links user_id in bewertung with id in benutzer
                                 ON DELETE CASCADE ON UPDATE CASCADE, -- Deletes ratings if the corresponding user is deleted, updates user_id if id changes
                             CONSTRAINT `fk_gericht_id` FOREIGN KEY (`gericht_id`) REFERENCES `gericht`(`id`) -- Links gericht_id in bewertung with id in gericht
                                 ON DELETE CASCADE ON UPDATE CASCADE, -- Deletes ratings if the corresponding gericht is deleted, updates user_id if id changes

                             CHECK (CHAR_LENGTH(`bemerkung`) >= 5) -- Ensures that bemerkung is at least 5 characters long.
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

DESCRIBE bewertung;
