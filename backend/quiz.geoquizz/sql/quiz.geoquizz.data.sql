-- Insertion de données dans la table `game`
INSERT INTO `game` (`id`, `token`, `id_serie`, `sequence`, `isPublic`, `level`, `id_user`, `created_at`, `updated_at`)
VALUES
    ('1', 'Token1', 1, '[{"id": 1, "url": "image1.jpg", "coordonnees": "12,34"}, {"id": 2, "url": "image2.jpg", "coordonnees": "56,78"}]', true, 1, 'AlixPerrot@free.fr', '2024-02-05 10:00:00', '2024-02-05 10:00:00'),
    ('2', 'Token2', 2, '[{"id": 3, "url": "image3.jpg", "coordonnees": "90,12"}, {"id": 4, "url": "image4.jpg", "coordonnees": "34,56"}]', false, 2, 'AlphonseFleury@sfr.fr', '2024-02-05 11:00:00', '2024-02-05 11:00:00');

-- Insertion de données dans la table `played`
INSERT INTO `played` (`id_game`, `id_user`, `score`, `date`)
VALUES
    ('1', 'User1', 100, '2024-02-05 10:00:00'),
    ('1', 'User2', 80, '2024-02-05 10:30:00'),
    ('2', 'User1', 120, '2024-02-05 11:00:00'),
    ('2', 'User2', 90, '2024-02-05 11:30:00');