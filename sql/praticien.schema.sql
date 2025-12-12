-- =========================================
-- 1️⃣ Specialites
-- =========================================
DROP TABLE IF EXISTS specialite;

CREATE TABLE specialite (
    id INT AUTO_INCREMENT PRIMARY KEY,
    libelle VARCHAR(48) NOT NULL UNIQUE,
    description TEXT
);

-- =========================================
-- 2️⃣ Motifs de visite
-- =========================================
DROP TABLE IF EXISTS motif_visite;

CREATE TABLE motif_visite (
    id INT AUTO_INCREMENT PRIMARY KEY,
    specialite_id INT NOT NULL,
    libelle VARCHAR(128) NOT NULL,
    FOREIGN KEY (specialite_id) REFERENCES specialite(id) ON DELETE CASCADE
);

-- =========================================
-- 3️⃣ Moyens de paiement
-- =========================================
DROP TABLE IF EXISTS moyen_paiement;

CREATE TABLE moyen_paiement (
    id INT AUTO_INCREMENT PRIMARY KEY,
    libelle VARCHAR(32) NOT NULL
);

-- =========================================
-- 4️⃣ Structures
-- =========================================
DROP TABLE IF EXISTS structure;

CREATE TABLE structure (
    id CHAR(36) PRIMARY KEY,
    nom VARCHAR(48) NOT NULL,
    adresse TEXT NOT NULL,
    ville VARCHAR(128),
    code_postal VARCHAR(12),
    telephone VARCHAR(24)
);

-- =========================================
-- 5️⃣ Praticiens
-- =========================================
DROP TABLE IF EXISTS praticien;

CREATE TABLE praticien (
    id CHAR(36) PRIMARY KEY,
    nom VARCHAR(48) NOT NULL,
    prenom VARCHAR(48) NOT NULL,
    ville VARCHAR(48) NOT NULL,
    user_id CHAR(36) NOT NULL,
    FOREIGN KEY (user_id) REFERENCES user(id) ,
    telephone VARCHAR(24) NOT NULL,
    specialite_id INT NOT NULL,
    structure_id CHAR(36),
    rpps_id VARCHAR(12),
    organisation BOOLEAN DEFAULT FALSE NOT NULL,
    nouveau_patient BOOLEAN DEFAULT TRUE NOT NULL,
    titre VARCHAR(8) DEFAULT 'Dr.' NOT NULL,
    FOREIGN KEY (specialite_id) REFERENCES specialite(id),
    FOREIGN KEY (structure_id) REFERENCES structure(id)
);

-- =========================================
-- 6️⃣ Praticien <-> Motif
-- =========================================
DROP TABLE IF EXISTS praticien2motif;

CREATE TABLE praticien2motif (
    praticien_id CHAR(36),
    motif_id INT,
    PRIMARY KEY (praticien_id, motif_id),
    FOREIGN KEY (praticien_id) REFERENCES praticien(id) ON DELETE CASCADE,
    FOREIGN KEY (motif_id) REFERENCES motif_visite(id) ON DELETE CASCADE
);

-- =========================================
-- 7️⃣ Praticien <-> Moyen de paiement
-- =========================================
DROP TABLE IF EXISTS praticien2moyen;

CREATE TABLE praticien2moyen (
    praticien_id CHAR(36),
    moyen_id INT,
    PRIMARY KEY (praticien_id, moyen_id),
    FOREIGN KEY (praticien_id) REFERENCES praticien(id) ON DELETE CASCADE,
    FOREIGN KEY (moyen_id) REFERENCES moyen_paiement(id) ON DELETE CASCADE
);
CREATE TABLE indisponibilites (
    id CHAR(36) PRIMARY KEY,      
    praticien_id CHAR(36) NOT NULL,
    date_debut DATETIME NOT NULL,
    date_fin DATETIME NOT NULL,
    motif VARCHAR(255) DEFAULT NULL,
    CONSTRAINT fk_praticien FOREIGN KEY (praticien_id) REFERENCES praticien(id)
);
