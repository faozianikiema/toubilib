-- =========================================
-- Table rendezvous
-- =========================================
DROP TABLE IF EXISTS rendezvous;

CREATE TABLE rendezvous (
    id CHAR(36) PRIMARY KEY,                
    patient_id CHAR(36) NOT NULL,             
    praticien_id CHAR(36) NOT NULL,          
    date_heure_debut DATETIME NOT NULL,
    date_heure_fin DATETIME NOT NULL,
    date_creation DATETIME DEFAULT CURRENT_TIMESTAMP,
    statut INT NOT NULL,                      
    duree INT NOT NULL,                        
    motif_visite VARCHAR(128) NOT NULL,
    
    -- Clés étrangères
    FOREIGN KEY (praticien_id) REFERENCES praticien(id) ON DELETE CASCADE,
    FOREIGN KEY (patient_id) REFERENCES patient(id) ON DELETE CASCADE
);
