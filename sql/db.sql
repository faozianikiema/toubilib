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
('123e4567-e89b-12d3-a456-426614174000','Doe','John','vandoeuvre les-Nancy','john.doe@example.com','0123456789','RPPS123456','Dr',TRUE,FALSE),
('550e8400-e29b-41d4-a716-446655440000', 'Dupont', 'Jean', 'Nancy', 'jean.dupont@example.com', '0612345678', 'RPPS001', 'Dr', true, false),
('650e8400-e29b-41d4-a716-446655440000', 'Martin', 'Sophie', 'Vandoeuvre', 'sophie.martin@example.com', '0623456789', 'RPPS002', 'Dr', false, false),
('750e8400-e29b-41d4-a716-446655440000', 'Bernard', 'Luc', 'Nancy', 'luc.bernard@example.com', '0634567890', 'RPPS003', 'Dr', true, false),
('850e8400-e29b-41d4-a716-446655440000', 'Petit', 'Claire', 'Laxou', 'claire.petit@example.com', '0645678901', 'RPPS004', 'Dr', true, false),
('950e8400-e29b-41d4-a716-446655440000', 'Moreau', 'Alexandre', 'Nancy', 'alexandre.moreau@example.com', '0656789012', 'RPPS005', 'Dr', false, false);

DROP TABLE if EXISTS rendez_vous;

CREATE TABLE rendez_vous (
    id CHAR(36) PRIMARY KEY,
    praticien_id CHAR(36) NOT NULL,
    patient_id CHAR(36) NOT NULL,
    date_heure_debut DATETIME NOT NULL,
    date_heure_fin DATETIME NOT NULL,
    date_creation DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    statut INT NOT NULL, -- 0 = annulé, 1 = confirmé, etc.
    duree INT NOT NULL,
    motif_visite VARCHAR(255) NOT NULL
);

INSERT INTO rendez_vous (id, praticien_id, patient_id, date_heure_debut, date_heure_fin,date_creation, statut, duree, motif_visite)
VALUES
(UUID(), '550e8400-e29b-41d4-a716-446655440000', '550e8400-e29b-41d4-a716-446655440001', '2025-09-28 09:00:00', '2025-09-28 09:30:00',now(), 1, 30, 'Consultation générale'),
(UUID(), '550e8400-e29b-41d4-a716-446655440000', '550e8400-e29b-41d4-a716-446655440002', '2025-09-28 10:00:00', '2025-09-28 10:45:00',now(), 1, 45, 'Suivi médical'),
(UUID(), '550e8400-e29b-41d4-a716-446655440000', '550e8400-e29b-41d4-a716-446655440003', '2025-09-29 14:00:00', '2025-09-29 14:30:00',now(), 1, 30, 'Résultats analyse'),
(UUID(), '650e8400-e29b-41d4-a716-446655440000', '550e8400-e29b-41d4-a716-446655440004', '2025-09-28 11:00:00', '2025-09-28 11:30:00',now(), 1, 30, 'Cardiologie');


DROP TABLE if EXISTS patient;

CREATE TABLE patient (
    uuid CHAR(36) PRIMARY KEY,       
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    date_naissance DATE NOT NULL,
    adresse VARCHAR(255),
    ville VARCHAR(100),
    code_postal VARCHAR(20),
    email VARCHAR(150) UNIQUE,
    telephone VARCHAR(20)
);

INSERT INTO patient (uuid, nom, prenom, date_naissance, adresse, ville, code_postal, email, telephone) VALUES
('550e8400-e29b-41d4-a716-446655440001', 'Dupont', 'Marie', '1990-01-01', '12 rue des Lilas', 'Nancy', '54000', 'marie.dupont@mail.com', '0601020304'),
('550e8400-e29b-41d4-a716-446655440002', 'Martin', 'Jean', '1985-05-15', '5 avenue de Metz', 'Metz', '57000', 'jean.martin@mail.com', '0611223344'),
('550e8400-e29b-41d4-a716-446655440003', 'Durand', 'Claire', '1992-07-20', '8 boulevard de la Gare', 'Vandœuvre-lès-Nancy', '54500', 'claire.durand@mail.com', '0622334455'),
('550e8400-e29b-41d4-a716-446655440004', 'Moreau', 'Lucas', '1988-03-10', '3 impasse des Fleurs', 'Nancy', '54000', 'lucas.moreau@mail.com', '0633445566'),
('550e8400-e29b-41d4-a716-446655440005', 'Leroy', 'Sophie', '1979-11-02', '21 rue Victor Hugo', 'Metz', '57000', 'sophie.leroy@mail.com', '0644556677'),
('550e8400-e29b-41d4-a716-446655440006', 'Rousseau', 'Antoine', '1995-06-30', '10 place Stanislas', 'Nancy', '54000', 'antoine.rousseau@mail.com', '0655667788'),
('550e8400-e29b-41d4-a716-446655440007', 'Petit', 'Aline', '2000-12-12', '6 rue du Parc', 'Metz', '57000', 'aline.petit@mail.com', '0666778899'),
('550e8400-e29b-41d4-a716-446655440008', 'Faure', 'Marc', '1975-08-25', '14 avenue Jean Jaurès', 'Nancy', '54000', 'marc.faure@mail.com', '0677889900'),
('550e8400-e29b-41d4-a716-446655440009', 'Garnier', 'Isabelle', '1982-04-05', '2 rue des Tilleuls', 'Metz', '57000', 'isabelle.garnier@mail.com', '0688990011'),
('550e8400-e29b-41d4-a716-446655440010', 'Bernard', 'Thomas', '1991-09-09', '9 rue des Acacias', 'Nancy', '54000', 'thomas.bernard@mail.com', '0699001122');



