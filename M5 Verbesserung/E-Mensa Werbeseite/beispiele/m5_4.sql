use emensawerbeseite;

-- a) View for all dishes with "suppe" in the name
CREATE VIEW view_suppengerichte AS
SELECT *
FROM gericht
WHERE name LIKE '%suppe%';

-- b) View for sign-ups per user, sorted by count
CREATE VIEW view_anmeldungen AS
SELECT
    id AS benutzer_id,
    name AS benutzer_name,
    email,
    anzahlanmeldungen AS anmeldungen
FROM benutzer
ORDER BY anzahlanmeldungen DESC;

-- c) View for vegetarian dishes and their categories
CREATE VIEW view_kategoriegerichte_vegetarisch AS
SELECT
    kategorie.id AS kategorie_id,
    kategorie.name AS kategorie_name,
    gericht.id AS gericht_id,
    gericht.name AS gericht_name,
    gericht.vegetarisch
FROM kategorie
         LEFT JOIN gericht_hat_kategorie ON kategorie.id = gericht_hat_kategorie.kategorie_id
         LEFT JOIN gericht ON gericht.id = gericht_hat_kategorie.gericht_id
WHERE gericht.vegetarisch = 1 OR gericht.id IS NULL;

-- Test Views
SELECT * FROM view_suppengerichte;
SELECT * FROM view_anmeldungen;
SELECT * FROM view_kategoriegerichte_vegetarisch;

