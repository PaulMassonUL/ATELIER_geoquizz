# GeoQuizz

GeoQuizz : Un jeu amusant où tu dois positionner une
photo sur la carte de ta ville sans te tromper et plus vite
que les autres !

## URL
- Dépôt git : https://github.com/PaulMassonUL/ATELIER_geoquizz
- Site : http://docketu.iutnc.univ-lorraine.fr:11116
- API : http://docketu.iutnc.univ-lorraine.fr:11111 + nom de la ressource (ex : /shows)
- Directus : http://docketu.iutnc.univ-lorraine.fr:11110/admin/login

### Prérequis

- PHP 8.1
- Composer
- sass
- nodejs
- npm
- docker

## Démarrage des containers

### Pour le back-end (API Slim)

Dans le dossier `backend\geoquizz.components` :

```bash
  docker compose up
```

### Pour le front-end (VueJS)

Dans le dossier `frontend` :

```bash
  npm run dev
```

## Initialisation du back-end quiz.geoquizz 

1) Installation des dépendances 
```bash
  docker compose exec api.geo-quizz composer install
```
```bash
  docker compose exec api.geo-auth composer install
```

2) Création de la base de données de geoquizz
```bash
  docker compose exec api.geo-quizz php bin/console db:create
```

## Fonctionnalités de base :

- [x] Une seule série : Nancy (par exemple)
- [x] Distance D pour le calcul des points identique pour toutes les parties
- [x] 10 photos par partie, la liste de photos et l'ordre de ces photos sont choisis aléatoirement dans la série
- [x] Backoffice : ajout /géolocalisation de photos à la série existante
- [ ] Notification d’événements à tous les clients connectés: démarrage d’une partie, fin et score d’une partie
- [x] Inscription et authentification
## Fonctionnalités étendues :
- [X] Plusieurs séries possibles : le choix de la série est fait à la création de la partie
- [x] Différents niveaux de jeu, qui se différencient par le nombre de photos à localiser dans la partie et par la distance D utilisée pour calculer les points
- [X] Profil de l’utilisateur : historique des parties créées et jouées, scores, high-scores par séries
- [X] Possibilité de rejouer une partie pour améliorer son score
- [X] Parties publiques : une partie peut être rendu publique par son créateur, elle peut être alors être jouée (à tout moment, pas de manière simultanée) par différents utilisateurs pour comparer les scores

## Utilisateur de test :
### GeoQuizz :
- URL : http://docketu.iutnc.univ-lorraine.fr:11116
- Utilisateur de test : mail@example.com
- Mot de passe : password

### Directus :
- URL : http://docketu.iutnc.univ-lorraine.fr:11110/admin/login
- Utilisateur administrateur : admin@example.com
- Mot de passe : d1r3ctu5
