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

-- akun login awal: jialucuk (password disimpan sebagai hash bcrypt)
INSERT INTO user (username, password, foto)
VALUES ('jialucuk', '$2y$10$nXNfr7P4AT75l9ImUCLX7u/FwoYTjXKVCeDpnNFyky/KVt8lfqluq', NULL);

-- sample data: article
INSERT INTO article (judul, isi, gambar, tanggal, username) VALUES
 ('Big Ben: The Chime That Keeps London on Time',
 'Standing on the banks of the Thames since 1859, this clock tower is more than a timekeeper; it is the heartbeat of London. Its chimes have accompanied parliamentary sessions, New Year''s Eve countdowns, and millions of visitors craning their necks in awe. A fun fact for trivia lovers: "Big Ben" is actually the name of the great bell inside, while the tower itself is called the Elizabeth Tower. Same photo, but now you know the real story.',
 '20250108222643.jpg', '2025-01-12 18:30:00', 'admin'),
 ('London Eye: 30 Minutes That Change How You See a City',
 'Rise slowly, with no rush, and suddenly all of London unfolds beneath your feet. The London Eye turns its glass capsules 135 meters above the Thames, high enough to catch Big Ben, Westminster, and the skyline stretching to the horizon. A tip from this journal: go as the sun starts to set. Golden sky, glittering river, and you won''t want to come down.',
 '20250108222659.jpg', '2025-01-12 18:25:00', 'admin'),
 ('221B Baker Street: The Fictional Address That Feels Most Real',
 'A black door, gas lamps, and a green sign reading Sherlock Holmes Museum. This is where the world''s most famous detective "lives," and where fans from all over the world come to prove that fiction can have an address. Step inside and you''ll feel as though Holmes just walked out with his pipe. Elementary, my dear traveler: one visit, a thousand theories.',
 '20250108222718.jpg', '2025-01-12 18:20:00', 'admin'),
 ('Westminster Abbey: A Thousand Years of History Behind One Gate',
 'Its twin towers rise majestically, and behind its walls lies the true story of England. Since 1066, kings and queens have been crowned here, and poets, scientists, and statesmen rest in its silence. Visit at dusk, when the lights glow on its stone walls and the square is nearly empty. It feels like walking into a history book.',
 '20250108222737.jpg', '2025-01-12 18:15:00', 'admin'),
 ('Kensington Palace: A Red-Brick Palace Full of Stories',
 'Behind the green lawns and trees of Kensington Gardens stands a red-brick palace that has been a royal home since the 17th century. Not as grand as Buckingham, but warmer, more human, and easier to love. Bring a snack, sit on the grass, and let the afternoon move slowly. This is London at its calmest.',
 '20250108222808.jpg', '2025-01-12 18:10:00', 'admin'),
 ('Tower Bridge: The Bridge That Opens Its Doors for Ships',
 'Many people think this is London Bridge, but it is Tower Bridge, the blue-and-white Victorian bridge completed in 1894. Its signature trick: the middle section lifts to let large ships pass along the Thames. If your timing is right, you can watch it open in person. The best photo? From the riverbank, with a blue sky and thin clouds like this one.',
 '20250109145330.jpg', '2025-01-12 18:05:00', 'admin');

-- sample data: gallery
INSERT INTO gallery (gambar, tanggal, username) VALUES
('20250108222643.jpg', '2025-01-12 18:30:00', 'admin'),
('20250108222659.jpg', '2025-01-12 18:25:00', 'admin'),
('20250108222718.jpg', '2025-01-12 18:20:00', 'admin'),
('20250108222737.jpg', '2025-01-12 18:15:00', 'admin'),
('20250108222808.jpg', '2025-01-12 18:10:00', 'admin'),
('20250109145330.jpg', '2025-01-12 18:05:00', 'admin');
