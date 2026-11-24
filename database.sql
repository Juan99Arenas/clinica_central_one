CREATE DATABASE IF NOT EXISTS clinica_central
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE clinica_central;

CREATE TABLE IF NOT EXISTS Pacientes (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  nombre VARCHAR(80) NOT NULL,
  apellido VARCHAR(80) NOT NULL,
  correo VARCHAR(120) NOT NULL,
  telefono VARCHAR(20) DEFAULT NULL,
  creado_en TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uq_Pacientes_correo (correo)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS Especialidades (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  nombre_esp VARCHAR(100) NOT NULL,
  creado_en TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uq_Especialidades_nombre (nombre_esp)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS Medicos (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  id_especialidad INT UNSIGNED NOT NULL,
  nombre VARCHAR(80) NOT NULL,
  apellido VARCHAR(80) NOT NULL,
  creado_en TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  CONSTRAINT fk_medicos_especialidad
    FOREIGN KEY (id_especialidad) REFERENCES Especialidades(id)
    ON UPDATE CASCADE
    ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS Citas (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  id_paciente INT UNSIGNED NOT NULL,
  id_medico INT UNSIGNED NOT NULL,
  fecha_hora DATETIME NOT NULL,
  estado ENUM('Pendiente', 'Confirmada', 'Cancelada', 'Completada') NOT NULL DEFAULT 'Pendiente',
  creado_en TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  CONSTRAINT fk_citas_paciente
    FOREIGN KEY (id_paciente) REFERENCES Pacientes(id)
    ON UPDATE CASCADE
    ON DELETE CASCADE,
  CONSTRAINT fk_citas_medico
    FOREIGN KEY (id_medico) REFERENCES Medicos(id)
    ON UPDATE CASCADE
    ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO Pacientes (nombre, apellido, correo, telefono) VALUES
('Duban', 'Gómez', 'Duban.gomez@example.com', '3001234567'),
('Pablo', 'Martínez', 'Pablo.martinez@example.com', '3109876543'),
('Juan', 'Arenas', 'Juan.arenas@example.com', '3109876543'),
('Samuel', 'Molina', 'Samuel.molina@example.com', '3109876543'),
('Ronald', 'De Arenas', 'Ronald.Dearenas@example.com', '3109876543'),
('Diego', 'Rojas', 'Diego.rojas@example.com', NULL);

INSERT INTO Especialidades (nombre_esp) VALUES
('Medicina General'),
('Pediatría'),
('Cardiología'),
('Dermatología');

INSERT INTO Medicos (id_especialidad, nombre, apellido) VALUES
(1, 'Carlos', 'Mendoza'),
(2, 'Ana', 'Restrepo'),
(3, 'Luis', 'Fernández'),
(4, 'Sofía', 'Castro');

INSERT INTO Citas (id_paciente, id_medico, fecha_hora, estado) VALUES
(1, 1, '2026-11-20 09:00:00', 'Confirmada'),
(2, 2, '2026-11-20 10:30:00', 'Pendiente'),
(3, 3, '2026-11-21 14:00:00', 'Pendiente'),
(4, 1, '2026-11-22 11:00:00', 'Cancelada');