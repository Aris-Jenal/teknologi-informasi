CREATE DATABASE um_tapsel;
USE um_tapsel;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    phone VARCHAR(13) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
CREATE TABLE students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
CREATE TABLE news (
    id INT AUTO_INCREMENT PRIMARY KEY,
    photo VARCHAR(255) NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    link VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
CREATE TABLE vision_mission (
    id INT PRIMARY KEY DEFAULT 1,
    vision TEXT NOT NULL,
    mission TEXT NOT NULL,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Insert default Vision and Mission
INSERT INTO vision_mission (id, vision, mission) VALUES (
    1,
    'Menjadi Program Studi Teknologi Informasi yang unggul, inovatif, dan berlandaskan nilai-nilai keislaman dalam menghasilkan lulusan yang kompeten di bidang teknologi digital pada tahun 2030.',
    "Menyelenggarakan pendidikan tinggi berbasis riset teknologi informasi.\n Mengembangkan penelitian untuk kemajuan ilmu pengetahuan dan teknologi.\nMelaksanakan pengabdian masyarakat untuk mentransformasi hasil riset.\nMembina kehidupan islami bagi sivitas akademika.\nMenjalin kerjasama untuk pengembangan riset dan pendidikan."
);
CREATE TABLE academic_calendars (
    id INT AUTO_INCREMENT PRIMARY KEY,
    image_path VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);