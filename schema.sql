CREATE TABLE user (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    username TEXT NOT NULL UNIQUE,
    password TEXT NOT NULL,
    foto TEXT
);

CREATE TABLE article (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    judul TEXT,
    isi TEXT,
    gambar TEXT,
    tanggal TEXT,
    username TEXT
);

CREATE TABLE gallery (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    gambar TEXT,
    tanggal TEXT,
    username TEXT
);

-- login awal: admin / admin123 (password disimpan md5)
INSERT INTO user (username, password, foto)
VALUES ('admin', '0192023a7bbd73250516f069df18b500', NULL);
