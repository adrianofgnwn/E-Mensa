use emensawerbeseite;

CREATE TABLE benutzer (
                          id INT AUTO_INCREMENT PRIMARY KEY,
                          name VARCHAR(200) NOT NULL,
                          email VARCHAR(100) NOT NULL UNIQUE,
                          passwort VARCHAR(200) NOT NULL,
                          admin BOOLEAN NOT NULL DEFAULT FALSE,
                          anzahlfehler INT NOT NULL DEFAULT 0,
                          anzahlanmeldungen INT NOT NULL DEFAULT 0,
                          letzteanmeldung DATETIME,
                          letzterfehler DATETIME
);

INSERT INTO benutzer (name, email, passwort, admin) VALUES ('benutzer1', 'benutzer1@emensa.example', 'bd62be9e7d1d451c48a2405aad91526f01ab28f0', false);


DESCRIBE benutzer;
