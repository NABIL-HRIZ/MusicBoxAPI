### README – MusicBoxAPI

MusicBoxAPI est une API RESTful Laravel pour gérer artistes, albums et chansons. La documentation interactive est fournie par L5-Swagger (Swagger UI). Le projet utilise Laravel Breeeze pour l'authentification et Laratrust (roles & permissions) pour les autorisations.

## Prérequis

- PHP 8+ (selon votre installation)
- Composer
- MySQL / MariaDB (ou autre SGBD supporté)
- Node (optionnel, pour front-end assets)

## Installation (backend)

1. Placez-vous dans le dossier backend :

```powershell
cd 'c:\Users\NABIL\Desktop\MusicApp\back_end'
```

2. Installez les dépendances PHP :

```powershell
composer install
```

3. Copiez le fichier d'environnement et générer la clé :

```powershell
copy .env.example .env
php artisan key:generate
```

4. Configurez `.env` (DB connection, app url, sanctum settings etc.) puis migrez/semez la base :

```powershell
php artisan migrate --seed
```

## Lancer l'application

```powershell
php artisan serve --host=127.0.0.1 --port=8000
```

## API & utilisation

- Base URL API : http://127.0.0.1:8000/api
- Swagger UI : http://127.0.0.1:8000/api/documentation (généré avec L5-Swagger)

## Endpoints utiles

# Authentification

# Si l'utilisateur connecté !

- POST /api/register → Inscription

- POST /api/login → Connexion

- POST /api/logout → Déconnexion (auth requise)

# Artists

- GET /api/artists → Liste paginée (filtres : genre, pays)

- GET /api/artists/{id} → Détail artiste

- POST /api/artists → Créer un artiste (admin)

- PUT /api/artists/{id} → Modifier un artiste (admin)

- DELETE /api/artists/{id} → Supprimer un artiste (admin)

- GET api/artists/{id}/albums -> List des artistes et ses albums

- GET api/artists/{id}/albums-chanson -> List des artistes et ses albums et ses chansons

# Albums

- GET /api/albums → Liste paginée (filtres : année, artiste)

- GET /api/albums/{id} → Détail album

- POST /api/albums → Créer un album (admin)

- PUT /api/albums/{id} → Modifier un album (admin)

- DELETE /api/albums/{id} → Supprimer un album (admin)

# Chansons

- GET /api/chansons → Liste paginée (filtres : durée, album)

- GET /api/chansons/search → Recherche par titre ou artiste

- GET /api/chansons/{id} → Détail chanson

- POST /api/chansons → Créer une chanson (admin)

- PUT /api/chansons/{id} → Modifier une chanson (admin)

- DELETE /api/chansons/{id} → Supprimer une chanson (admin)

- GET /api/albums/{id}/chansons' \_> List des chansons aprés un album

# Si l'utilisateur est juste un vésiteur

- GET /api/public/artists → Liste paginée (filtres : genre, pays)

- GET /api/public/artists/{id} → Détail artiste

- GET /api/public/albums → Liste paginée (filtres : année, artiste)

- GET /api/public/albums/{id} → Détail album

- GET api/public/artists/{id}/albums -> List des artistes et ses albums

- GET api/public/artists/{id}/albums-chanson -> List des artistes et ses albums et ses chansons

- GET /api/public/chansons → Liste paginée (filtres : durée, album)

- GET /api/public/chansons/search → Recherche par titre ou artiste

- GET /api/public/chansons/{id} → Détail chanson

- GET /api/public/albums/{id}/chansons' \_> List des chansons aprés un album

## Documentation Swagger

- Accessible à l’URL : /api/docs

- Générée automatiquement via Swagger-PHP et L5-Swagger

- Permet de tester les endpoints directement dans une interface interactive

## Optimisations

- Eager Loading pour réduire le nombre de requêtes (chargement artiste + albums + chansons).

- Pagination sur toutes les listes (artistes, albums, chansons).

- Filtres dynamiques (genre, pays).

## Modalités pédagogiques

- Travail : individuel

- Durée : 2 jours

- Lancement : Lundi 29/09/2025 à 10h

- Deadline : MERCREDI 30/09/2025 avant 10h

- Outils : GitHub (code source), Swagger (doc), Postman (tests), Revue de code collaborative

## Modalités d’évaluation

- Code source : propreté, structure, respect de la syntaxe Laravel

- Endpoints API : clarté, sécurité, réponses cohérentes

- Permissions & Auth : implémentation correcte de Sanctum Breeze et LaraTrust

- Documentation Swagger : complète, claire, utilisable par un tiers développeur

## Livrables

- Code source (GitHub)

- Documentation Swagger interactive (Swagger UI via /api/docs)

- README complet décrivant le projet

## Contact / Support

Open an issue on the repository or paste the failing request & response (headers + body) and I will help debug further.

---

Generated on: 2025-10-01
