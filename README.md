# ATELIER_geoquizz

## Initialisation du back-end quiz.geoquizz 

1) installation des dépendances 
```bash
  docker compose exec api.geo-quizz composer install
```

2) Création et peuplement de la BD
```bash
  docker compose exec api.geo-quizz php bin/console db:create
  docker compose exec api.geo-quizz php bin/console db:populate
``` 

