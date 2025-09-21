DROP TABLE IF EXISTS praticien;

CREATE TABLE praticien (
    uuid CHAR(36) NOT NULL PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    ville VARCHAR(100) not null,
    email VARCHAR(255) NOT NULL UNIQUE,
    telephone VARCHAR(15) NOT NULL,
    rpps_id VARCHAR(20) NOT NULL UNIQUE,
    titre VARCHAR(50) NOT NULL,
    accepte_nouveau_patient BOOLEAN NOT NULL,
    est_organisation BOOLEAN NOT NULL
);

INSERT INTO praticien (uuid, nom, prenom,ville, email, telephone, rpps_id, titre, accepte_nouveau_patient, est_organisation) VALUES
('123e4567-e89b-12d3-a456-426614174000','Doe','John','vandoeuvre les-Nancy','john.doe@example.com','0123456789','RPPS123456','Dr',TRUE,FALSE);
