CREATE TABLE user (
    id CHAR(36) PRIMARY KEY,                        
    email VARCHAR(128) UNIQUE NOT NULL,             
    password VARCHAR(255) NOT NULL,                
    role INt NOT NULL,     
    date_creation DATETIME DEFAULT CURRENT_TIMESTAMP
);
