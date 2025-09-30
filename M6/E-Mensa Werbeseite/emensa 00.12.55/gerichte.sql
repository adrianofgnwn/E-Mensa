use emensawerbeseite;

ALTER TABLE gericht ADD COLUMN bildname VARCHAR(200) DEFAULT NULL;

UPDATE gericht
SET bildname = '1_spaghetti.jpg'
WHERE id = 1;