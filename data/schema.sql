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
 'big-ben.jpg', '2025-12-25 14:30:00', 'jialucuk'),
 ('London Eye: 30 Minutes That Change How You See a City',
 'Rise slowly, with no rush, and suddenly all of London unfolds beneath your feet. The London Eye turns its glass capsules 135 meters above the Thames, high enough to catch Big Ben, Westminster, and the skyline stretching to the horizon. A tip from this journal: go as the sun starts to set. Golden sky, glittering river, and you won''t want to come down.',
 'london-eye.jpg', '2025-12-29 15:00:00', 'jialucuk'),
 ('221B Baker Street: The Fictional Address That Feels Most Real',
 'A black door, gas lamps, and a green sign reading Sherlock Holmes Museum. This is where the world''s most famous detective "lives," and where fans from all over the world come to prove that fiction can have an address. Step inside and you''ll feel as though Holmes just walked out with his pipe. Elementary, my dear traveler: one visit, a thousand theories.',
 'baker-street.jpg', '2025-12-26 10:00:00', 'jialucuk'),
 ('Westminster Abbey: A Thousand Years of History Behind One Gate',
 'Its twin towers rise majestically, and behind its walls lies the true story of England. Since 1066, kings and queens have been crowned here, and poets, scientists, and statesmen rest in its silence. Visit at dusk, when the lights glow on its stone walls and the square is nearly empty. It feels like walking into a history book.',
 'westminster-abbey.jpg', '2025-12-25 16:00:00', 'jialucuk'),
 ('Kensington Palace: A Red-Brick Palace Full of Stories',
 'Behind the green lawns and trees of Kensington Gardens stands a red-brick palace that has been a royal home since the 17th century. Not as grand as Buckingham, but warmer, more human, and easier to love. Bring a snack, sit on the grass, and let the afternoon move slowly. This is London at its calmest.',
 'kensington-palace.jpg', '2025-12-26 15:00:00', 'jialucuk'),
 ('Tower Bridge: The Bridge That Opens Its Doors for Ships',
 'Many people think this is London Bridge, but it is Tower Bridge, the blue-and-white Victorian bridge completed in 1894. Its signature trick: the middle section lifts to let large ships pass along the Thames. If your timing is right, you can watch it open in person. The best photo? From the riverbank, with a blue sky and thin clouds like this one.',
 'tower-bridge.jpg', '2025-12-25 10:30:00', 'jialucuk');

-- sample data: gallery
INSERT INTO gallery (gambar, tanggal, username) VALUES
('big-ben.jpg', '2025-12-25 14:30:00', 'jialucuk'),
('london-eye.jpg', '2025-12-29 15:00:00', 'jialucuk'),
('baker-street.jpg', '2025-12-26 10:00:00', 'jialucuk'),
('westminster-abbey.jpg', '2025-12-25 16:00:00', 'jialucuk'),
('kensington-palace.jpg', '2025-12-26 15:00:00', 'jialucuk'),
('tower-bridge.jpg', '2025-12-25 10:30:00', 'jialucuk');

-- sample data: destinasi tambahan
INSERT INTO article (judul, isi, gambar, tanggal, username) VALUES
('A Morning Watching the Guards at Buckingham Palace',
'Woke up early today because I did not want to miss the changing of the guard. I found a spot along the railings and waited, a little cold, a little sleepy, with a coffee that went lukewarm too fast. Then the band started to play and everything felt like a film scene. The red coats, the tall bearskin hats, the crowd holding up their phones all at once. I did not take that many photos in the end. I just stood there and watched. Some moments are better kept in your head.',
'buckingham-palace.jpg', '2025-12-24 10:15:00', 'jialucuk'),
('Lost for Hours Inside the British Museum',
'I told myself I would only stay for an hour. Four hours later I was still wandering between Egyptian mummies and Greek marble. The Rosetta Stone had a small crowd around it, so I waited my turn and finally stood close enough to see the tiny carved letters. It made me think about how many people have stood in this same spot, curious about the same thing. My feet hurt on the way out, but my head felt full in a good way.',
'british-museum.jpg', '2025-12-27 11:00:00', 'jialucuk'),
('Slow Afternoon in Hyde Park',
'No plan today, and that was the plan. I walked through Hyde Park with no destination, past runners, dog walkers, and families wrapped in thick scarves. I bought a sandwich, found a bench near the Serpentine, and watched the ducks argue over some bread. Nobody was in a hurry. For the first time this week, neither was I. The trees were bare and the winter light was soft. I think I needed this more than any museum.',
'hyde-park.jpg', '2025-12-24 12:30:00', 'jialucuk'),
('Camden Market and Too Much Good Food',
'Camden was loud, colorful, and a little chaotic, exactly how I hoped it would be. I wandered through narrow lanes of vintage jackets, handmade jewelry, and stalls selling food from every corner of the world. I could not decide what to eat, so I ate a bit of everything: dumplings, a warm pastry, and a sweet crepe I really did not need. I bought a small badge as a souvenir. It is now pinned to my bag, and it makes me smile every time I see it.',
'camden-market.jpg', '2025-12-26 12:00:00', 'jialucuk'),
('Street Musicians at Covent Garden',
'I stopped at Covent Garden just to look around and ended up staying until the evening. A violinist was playing under the old market roof, and the sound floated through the whole square. People slowed down, then stopped, then simply listened. A little girl danced beside her mother, and everyone around her smiled. I dropped a few coins into the case and went to find a hot chocolate. A very good ending to a very ordinary day.',
'covent-garden.jpg', '2025-12-24 16:00:00', 'jialucuk'),
('Climbing Up to the Dome of St Paul''s',
'There are 528 steps to the top of the dome, and I counted most of them out loud, which was a mistake. Halfway up I wanted to quit. The Whispering Gallery made me forget that, though. I whispered a silly sentence against the wall, and my friend heard it clearly from the other side. At the very top the wind was strong and the whole city lay below, grey rooftops and bright river. Worth every step, even the ones I complained about.',
'st-pauls-cathedral.jpg', '2025-12-29 10:00:00', 'jialucuk'),
('The Ravens and Old Stones of the Tower of London',
'The Tower of London is older and heavier than I imagined. Its walls hold stories of kings, prisoners, and secrets, and a guide in a dark uniform told them with such humor that I forgot to feel scared. I met the famous ravens too. One of them stared at me like I owed it money. According to legend, if the ravens ever leave, the kingdom will fall, so they are very well looked after. I left feeling a little small, in the best way.',
'tower-of-london.jpg', '2025-12-28 10:00:00', 'jialucuk'),
('Pigeons, Lions, and a Rainy Trafalgar Square',
'It started to drizzle just as I reached Trafalgar Square, but nobody seemed to mind. The big stone lions looked proud and unbothered, and children kept trying to climb up beside them. I sat on the edge of the fountain with my umbrella and watched the whole square go by: tourists, street artists, a few brave pigeons. The National Gallery glowed behind the rain. I stayed longer than I planned. Rainy London has its own kind of charm.',
'trafalgar-square.jpg', '2025-12-24 15:00:00', 'jialucuk'),
('Looking at Art I Did Not Fully Understand at Tate Modern',
'I will be honest: some of the pieces at Tate Modern confused me. A pile of bricks, a blank canvas with one line, a room full of mirrors. But I liked that it made me stop and ask myself what I felt, even when I did not know what I was supposed to feel. The old power station building is half the experience. I watched the river from the top floor with a cup of tea and decided that not understanding is allowed. Curiosity is enough.',
'tate-modern.jpg', '2025-12-28 15:30:00', 'jialucuk'),
('Evening Lights at Piccadilly Circus',
'I ended the day at Piccadilly Circus after dark, when the giant screens were glowing against the night sky. Pink, blue, yellow, everything flashing at once, and a crowd of people just standing and looking up. It is loud and bright and a little overwhelming, but I loved it. I sat on the steps near the fountain, tired, happy, and wishing the night would last a little longer. London, you have been very good to me so far.',
'piccadilly-circus.jpg', '2025-12-24 17:30:00', 'jialucuk');

INSERT INTO gallery (gambar, tanggal, username) VALUES
('buckingham-palace.jpg', '2025-12-24 10:15:00', 'jialucuk'),
('british-museum.jpg', '2025-12-27 11:00:00', 'jialucuk'),
('hyde-park.jpg', '2025-12-24 12:30:00', 'jialucuk'),
('camden-market.jpg', '2025-12-26 12:00:00', 'jialucuk'),
('covent-garden.jpg', '2025-12-24 16:00:00', 'jialucuk'),
('st-pauls-cathedral.jpg', '2025-12-29 10:00:00', 'jialucuk'),
('tower-of-london.jpg', '2025-12-28 10:00:00', 'jialucuk'),
('trafalgar-square.jpg', '2025-12-24 15:00:00', 'jialucuk'),
('tate-modern.jpg', '2025-12-28 15:30:00', 'jialucuk'),
('piccadilly-circus.jpg', '2025-12-24 17:30:00', 'jialucuk');

INSERT INTO article (judul, isi, gambar, tanggal, username) VALUES
('Crossing the Millennium Bridge with the River Below',
'I walked across the Millennium Bridge in the late morning, when the sun was bright and the river looked almost golden. It is a long, slim bridge that seems to float over the Thames, with St Paul''s waiting at one end and Tate Modern at the other. Halfway across I stopped and just leaned on the railing, watching boats drift by. A busker was playing guitar near the south end, and I caught myself humming along. Small moments like this are the ones I want to remember most.',
'millennium-bridge.jpg', '2025-12-25 11:45:00', 'jialucuk'),
('A Quiet Lunch Under the Glass Roof of Leadenhall Market',
'I almost walked past Leadenhall Market because the entrance is so tucked away. Inside, it felt like stepping into another century: painted ironwork, cobbled floors, and a glass roof that let in soft afternoon light. The street was calm after the noisy morning, so I found a small cafe, ordered soup and bread, and took my time. People in suits hurried by with their coffees while I sat there feeling gloriously unhurried. It was one of my favorite little discoveries of the trip.',
'leadenhall-market.jpg', '2025-12-29 12:00:00', 'jialucuk');

INSERT INTO gallery (gambar, tanggal, username) VALUES
('millennium-bridge.jpg', '2025-12-25 11:45:00', 'jialucuk'),
('leadenhall-market.jpg', '2025-12-29 12:00:00', 'jialucuk');

-- sample data: kuliner
INSERT INTO article (judul, isi, gambar, tanggal, username) VALUES
('Wandering Through Borough Market With an Empty Stomach',
'I arrived at Borough Market hungry on purpose, and it was the best decision of the day. The air smelled like fresh bread, grilled cheese, and roasting coffee all at once. I walked slowly through the stalls, tasting small samples of cheese and olives, then finally settled on a warm toastie that I ate standing up beside the old railway arches. It was messy, a little too hot, and completely perfect. I would come back just for that first bite.',
'borough-market.jpg', '2025-12-28 14:00:00', 'jialucuk'),
('Afternoon Tea, Pinky Up, at Fortnum & Mason',
'I could not visit London and skip afternoon tea, so I dressed up a little and went to Fortnum & Mason. The tea came in a tall pot with a tiny strainer, and the sandwiches were cut so neatly I almost felt bad eating them. The scones were warm, and I spent a serious minute deciding between cream first or jam first. The room was quiet and elegant, and for an hour I felt like a character in an old novel. Slow, sweet, and worth saving up for.',
'fortnum-and-mason.jpg', '2025-12-27 16:00:00', 'jialucuk'),
('A Colorful Corner Called Neal''s Yard',
'I found Neal''s Yard by accident while looking for a place to rest, and it turned out to be a hidden burst of color. Painted walls in yellow, blue, and pink, plants spilling from window boxes, and small cafes with benches in the sun. I ordered something warm to drink, sat on a stool by the wall, and just watched people pass. It is tiny, but it feels like a secret courtyard that belongs to everyone. A good place to pause between busy streets.',
'neals-yard.jpg', '2025-12-27 10:00:00', 'jialucuk'),
('One Good Cup of Coffee in Covent Garden',
'I am not usually picky about coffee, but this little shop on a quiet street made me pay attention. There was a small queue outside, everyone patient, everyone looking forward to the same thing. My flat white arrived with a warm, nutty smell, and I drank it on the pavement while people hurried by. Nothing fancy, just a simple cup done carefully. It reminded me that small pleasures can make a whole morning feel lighter.',
'monmouth-coffee.jpg', '2025-12-27 09:00:00', 'jialucuk'),
('Pie, Mash, and a Very Honest London Lunch',
'Pie and mash is not a pretty dish, but it is one of the most honest meals I have had here. A hot meat pie, a scoop of creamy mashed potato, and a green sauce poured over everything that I was not sure about until the first bite. The shop was simple, with tiled walls and long tables, and the people around me ate like they had done it a hundred times. Cheap, warm, and filling. It felt like tasting a small piece of old London.',
'pie-and-mash.jpg', '2025-12-28 12:30:00', 'jialucuk'),
('Stepping Into a Pub That Has Stood Since 1667',
'The doorway is narrow and the sign says the pub was rebuilt in 1667, which made me curious enough to duck inside. Low ceilings, dark wood, sawdust-colored floors, and little rooms that twist around each other like a maze. I sat at a small table with a drink and listened to the soft chatter of strangers. It is easy to imagine writers and travelers sitting in the same spot, centuries ago. I left slowly, as if I did not want to break the spell.',
'ye-olde-cheshire-cheese.jpg', '2025-12-27 18:30:00', 'jialucuk');

INSERT INTO gallery (gambar, tanggal, username) VALUES
('borough-market.jpg', '2025-12-28 14:00:00', 'jialucuk'),
('fortnum-and-mason.jpg', '2025-12-27 16:00:00', 'jialucuk'),
('neals-yard.jpg', '2025-12-27 10:00:00', 'jialucuk'),
('monmouth-coffee.jpg', '2025-12-27 09:00:00', 'jialucuk'),
('pie-and-mash.jpg', '2025-12-28 12:30:00', 'jialucuk'),
('ye-olde-cheshire-cheese.jpg', '2025-12-27 18:30:00', 'jialucuk');
