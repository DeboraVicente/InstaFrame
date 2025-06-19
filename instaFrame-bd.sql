-- Tabela de usuários (clientes)
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150),
    role ENUM('admin', 'cliente') NOT NULL DEFAULT 'cliente',
    password_hash VARCHAR(255) NOT NULL, 
    dateDeleted DATETIME DEFAULT NULL
);

-- Tabela de eventos vinculada ao cliente
CREATE TABLE event (
    id INT AUTO_INCREMENT PRIMARY KEY,
    userId INT NOT NULL,
    eventName VARCHAR(100) NOT NULL,
    eventDate DATE,
    dateDeleted DATETIME DEFAULT NULL,
    FOREIGN KEY (userId) REFERENCES users(id) ON DELETE CASCADE
);

-- Tabela de fotos vinculada ao evento
CREATE TABLE photos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    originalName VARCHAR(255) NOT NULL,
    fileName VARCHAR(255) NOT NULL,
    path VARCHAR(255) NOT NULL,
    uploadDate DATETIME DEFAULT CURRENT_TIMESTAMP,
    eventId INT NOT NULL,
    FOREIGN KEY (eventId) REFERENCES event(id) ON DELETE CASCADE
);
