CREATE DATABASE IF NOT EXISTS emensawerbeseite
        character set UTF8mb4
            COLLATE utf8mb4_unicode_ci;

use emensawerbeseite;

CREATE TABLE gericht (


                         id BIGINT PRIMARY KEY,  -- int8
                         name VARCHAR(80) NOT NULL UNIQUE,
                         beschreibung VARCHAR(800) NOT NULL,
                         erfasst_am DATE NOT NULL,
                         vegetarisch BOOLEAN NOT NULL,
                        vegan BOOLEAN NOT NULL,
                        preisintern double NOT NULL,
                        preisextern double NOT NULL
);


CREATE TABLE allergen (
                          code CHAR(4) PRIMARY KEY,
                          name VARCHAR(300) NOT NULL,
                          typ VARCHAR(20) NOT NULL DEFAULT 'allergen'
);


CREATE TABLE kategorie (
                           id BIGINT PRIMARY KEY,  -- int8
                           name VARCHAR(80) NOT NULL,
                           eltern_id BIGINT,  -- Parent category reference (can be NULL)
                           bildname VARCHAR(200),
                           FOREIGN KEY (eltern_id) REFERENCES kategorie(id)  -- Self-referencing for tree structure
);

CREATE TABLE gericht_hat_allergen (
                                      code CHAR(4) NOT NULL,  -- Reference to allergen
                                      gericht_id BIGINT NOT NULL,  -- Reference to gericht
                                      PRIMARY KEY (code, gericht_id),
                                      FOREIGN KEY (code) REFERENCES allergen(code),
                                      FOREIGN KEY (gericht_id) REFERENCES gericht(id)
);

CREATE TABLE gericht_hat_kategorie (
                                       gericht_id BIGINT NOT NULL,  -- Reference to gericht
                                       kategorie_id BIGINT NOT NULL,  -- Reference to kategorie
                                       PRIMARY KEY (gericht_id, kategorie_id),
                                       FOREIGN KEY (gericht_id) REFERENCES gericht(id),
                                       FOREIGN KEY (kategorie_id) REFERENCES kategorie(id)
);

