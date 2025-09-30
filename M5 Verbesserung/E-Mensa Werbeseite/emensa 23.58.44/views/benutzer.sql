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

INSERT INTO benutzer (name, email, passwort, admin) VALUES ('Admin',
                                                            'admin2@emensa.example',
                                                            'c129b324aee662b04eccf68babba85851346dff9',
                                                            true); -- Set Admin Flag TRUE


DESCRIBE benutzer;

