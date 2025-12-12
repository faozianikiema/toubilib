-- =========================================
-- Table patient
-- =========================================
DROP TABLE IF EXISTS patient;

CREATE TABLE patient (
    id CHAR(36) PRIMARY KEY,                 
    nom VARCHAR(48) NOT NULL,
    prenom VARCHAR(48) NOT NULL,
    date_naissance DATE NOT NULL,
    adresse TEXT NOT NULL,
    ville VARCHAR(128) NOT NULL,
    code_postal VARCHAR(12) NOT NULL,
    telephone VARCHAR(24) NOT NULL,
    user_id CHAR(36) NOT NULL,
    FOREIGN KEY (user_id) REFERENCES user(id) 
);
