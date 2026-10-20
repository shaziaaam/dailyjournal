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

-- data contoh: article
INSERT INTO article (judul, isi, gambar, tanggal, username) VALUES
('Big Ben: Dentang yang Membuat London Tak Pernah Terlambat',
 'Berdiri di tepi Sungai Thames sejak 1859, menara jam ini bukan sekadar penunjuk waktu, melainkan detak jantung London. Dentangnya menemani sidang parlemen, malam tahun baru, hingga jutaan langkah wisatawan yang mendongak dengan mulut menganga. Satu catatan kecil untuk kamu yang suka fakta unik: "Big Ben" sebenarnya nama lonceng raksasa di dalamnya, sementara menaranya bernama Elizabeth Tower. Foto boleh sama, tapi sekarang kamu tahu cerita sebenarnya.',
 '20250108222643.jpg', '2025-01-12 18:30:00', 'admin'),
('London Eye: 30 Menit yang Mengubah Cara Kamu Melihat Kota',
 'Naik pelan, tanpa terburu-buru, dan tiba-tiba seluruh London terhampar di bawah kaki. London Eye memutar kapsul kacanya setinggi 135 meter dari tepi Thames, cukup tinggi untuk menangkap Big Ben, Westminster, hingga garis cakrawala kota. Tips dari jurnal ini: datang saat matahari mulai turun. Langit emas, sungai berkilau, dan kamu tidak akan ingin turun.',
 '20250108222659.jpg', '2025-01-12 18:25:00', 'admin'),
('221B Baker Street: Alamat Fiksi yang Terasa Paling Nyata',
 'Pintu hitam, lampu gas, dan papan hijau bertuliskan Sherlock Holmes Museum. Di sinilah detektif paling terkenal sepanjang masa "tinggal", dan di sinilah penggemarnya datang dari seluruh dunia untuk membuktikan bahwa fiksi bisa punya alamat. Masuk ke dalam, dan kamu akan merasa Holmes baru saja keluar membawa pipanya. Elementary, my dear traveler: satu kunjungan, seribu teori.',
 '20250108222718.jpg', '2025-01-12 18:20:00', 'admin'),
('Westminster Abbey: Seribu Tahun Sejarah dalam Satu Pintu Gerbang',
 'Dua menara kembarnya menjulang megah, dan di balik dindingnya tersimpan sejarah Inggris yang sesungguhnya. Sejak 1066, para raja dan ratu dinobatkan di sini, dan penyair, ilmuwan, hingga negarawan beristirahat dalam keheningannya. Datanglah saat senja, ketika lampu mulai menyala di dinding batunya dan alun-alun hampir sepi. Rasanya seperti berjalan masuk ke dalam buku sejarah.',
 '20250108222737.jpg', '2025-01-12 18:15:00', 'admin'),
('Kensington Palace: Istana Bata Merah yang Menyimpan Banyak Cerita',
 'Di balik halaman rumput hijau dan pepohonan Kensington Gardens, berdiri istana bata merah yang sudah menjadi rumah keluarga kerajaan sejak abad ke-17. Tidak sebesar Buckingham, tapi justru terasa lebih hangat, lebih manusiawi, dan lebih mudah dicintai. Bawa bekal, duduk di rumput, dan biarkan sore berjalan lambat. Inilah London versi paling tenang.',
 '20250108222808.jpg', '2025-01-12 18:10:00', 'admin'),
('Tower Bridge: Jembatan yang Bisa "Membuka Pintu" untuk Kapal',
 'Banyak yang mengira ini London Bridge, padahal ini Tower Bridge, jembatan biru-putih bergaya Victoria yang selesai dibangun pada 1894. Keunikannya: bagian tengahnya bisa terangkat untuk memberi jalan kapal-kapal besar di Thames. Kalau jadwalnya pas, kamu bisa melihatnya terbuka langsung. Foto terbaiknya? Dari tepi sungai, dengan langit biru dan awan tipis seperti di sini.',
 '20250109145330.jpg', '2025-01-12 18:05:00', 'admin');

-- data contoh: gallery
INSERT INTO gallery (gambar, tanggal, username) VALUES
('20250108222643.jpg', '2025-01-12 18:30:00', 'admin'),
('20250108222659.jpg', '2025-01-12 18:25:00', 'admin'),
('20250108222718.jpg', '2025-01-12 18:20:00', 'admin'),
('20250108222737.jpg', '2025-01-12 18:15:00', 'admin'),
('20250108222808.jpg', '2025-01-12 18:10:00', 'admin'),
('20250109145330.jpg', '2025-01-12 18:05:00', 'admin');
