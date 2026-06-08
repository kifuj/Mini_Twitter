-- ============================================
--  Base de données : mini_twitter
--  Projet BTS SIO SLAM - 1ère année
--  Objectif : apprentissage connexion BDD
-- ============================================

CREATE DATABASE IF NOT EXISTS mini_twitter
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE mini_twitter;

-- --------------------------------------------
--  Table : membre
-- --------------------------------------------
CREATE TABLE IF NOT EXISTS membre (
    id_membre   INT             NOT NULL AUTO_INCREMENT,
    identifiant VARCHAR(50)     NOT NULL UNIQUE,
    mdp         VARCHAR(255)    NOT NULL,          -- stocker un hash (ex: SHA2 / bcrypt)
    photo       VARCHAR(255)    DEFAULT NULL,      -- chemin ou nom du fichier image
    PRIMARY KEY (id_membre)
) ENGINE=InnoDB;

-- --------------------------------------------
--  Table : tweet
-- --------------------------------------------
CREATE TABLE IF NOT EXISTS tweet (
    id_tweet    INT             NOT NULL AUTO_INCREMENT,
    contenu     TEXT   NOT NULL,          -- limite classique d'un tweet
    date_tweet  DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    image       VARCHAR(255)    DEFAULT NULL AFTER contenu,
    id_membre   INT             NOT NULL,          -- clé étrangère vers membre
    PRIMARY KEY (id_tweet),
    CONSTRAINT fk_tweet_membre
        FOREIGN KEY (id_membre)
        REFERENCES membre(id_membre)
        ON DELETE CASCADE
        ON UPDATE CASCADE
) ENGINE=InnoDB;

-- --------------------------------------------
-- Table : like tweet
-- --------------------------------------------
CREATE TABLE IF NOT EXISTS like_tweet (
    id_like INT AUTO_INCREMENT PRIMARY KEY,
    id_membre INT NOT NULL,
    id_tweet INT NOT NULL,

    UNIQUE(id_membre, id_tweet),

    FOREIGN KEY (id_membre) REFERENCES membre(id_membre)
        ON DELETE CASCADE,

    FOREIGN KEY (id_tweet) REFERENCES tweet(id_tweet)
        ON DELETE CASCADE
);

-- --------------------------------------------
-- Table : follow
-- --------------------------------------------
CREATE TABLE IF NOT EXISTS follow (
    id_follow INT AUTO_INCREMENT PRIMARY KEY,

    follower_id INT NOT NULL,
    followed_id INT NOT NULL,

    UNIQUE(follower_id, followed_id),

    FOREIGN KEY (follower_id)
        REFERENCES membre(id_membre)
        ON DELETE CASCADE,

    FOREIGN KEY (followed_id)
        REFERENCES membre(id_membre)
        ON DELETE CASCADE
);

-- ============================================
--  Données de test (optionnel)
-- ============================================

-- Insertion de membres (mdp = hash SHA2-256 de "motdepasse")
INSERT INTO membre (identifiant, mdp, photo) VALUES
    ('alice',   SHA2('motdepasse', 256), 'alice.jpg'),
    ('bob',     SHA2('motdepasse', 256), 'bob.jpg'),
    ('charlie', SHA2('motdepasse', 256), NULL);

-- Insertion de tweets
INSERT INTO tweet (contenu, id_membre) VALUES
    ('Bonjour tout le monde ! Mon premier tweet', 1),
    ('Le BTS SIO c''est vraiment cool',            2),
    ('PHP + MySQL = combo gagnant !',              1),
    ('Vive le developpement web !',                3);
