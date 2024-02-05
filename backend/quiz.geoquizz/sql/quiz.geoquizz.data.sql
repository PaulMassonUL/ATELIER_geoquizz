-- Insertion de données dans la table `partie`
INSERT INTO `game` (`id`, `name`, `city`, `sequence`)
VALUES
    ('1', 'Partie 1', 'Ville A', '[{"id": 1, "url": "image1.jpg", "coordonnees": "12,34"}, {"id": 2, "url": "image2.jpg", "coordonnees": "56,78"}]'),
    ('2', 'Partie 2', 'Ville B', '[{"id": 3, "url": "image3.jpg", "coordonnees": "90,12"}, {"id": 4, "url": "image4.jpg", "coordonnees": "34,56"}]');

-- Insertion de données dans la table `jouer`
INSERT INTO `played` (`id_game`, `id_user`, `score`, `state`, `date`)
VALUES
    ('1', 'User1', 100, 1, '2024-02-05 10:00:00'),
    ('1', 'User2', 80, 1, '2024-02-05 10:30:00'),
    ('2', 'User1', 120, 1, '2024-02-05 11:00:00'),
    ('2', 'User2', 90, 1, '2024-02-05 11:30:00');
