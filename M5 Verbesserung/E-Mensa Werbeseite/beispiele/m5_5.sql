use emensawerbeseite;
DELIMITER $$

CREATE PROCEDURE increment_registration_count(IN user_id INT)
BEGIN
    UPDATE benutzer
    SET anzahlanmeldungen = anzahlanmeldungen + 1
    WHERE id = user_id;
END $$

DELIMITER ;