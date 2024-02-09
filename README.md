# ATELIER_geoquizz

## Initialisation du back-end quiz.geoquizz 

1) installation des dépendances 
```bash
  docker compose exec api.geo-quizz composer install
```

2) Création de la BD de geoquizz
```bash
  docker compose exec api.geo-quizz php bin/console db:create
``` 


