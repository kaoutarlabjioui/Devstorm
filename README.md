## DevStorm

Ce projet consiste à développer une API RESTful permettant de gérer les éditions annuelles d'un hackathon dédié aux lycéens en programmation. L'API permettra aux organisateurs de créer et gérer différentes éditions du hackathon chaque année, d’inscrire des participants, de constituer des équipes et de soumettre des projets pour évaluation par un jury.

## Installation

### Étapes pour installer le projet

1. Clonez le repository dans votre répertoire local :

    ```bash
    git clone https://github.com/kaoutarlabjioui/Devstorm.git

    ```

2. Allez dans le répertoire du projet :

    ```bash
    cd DevStorm
    ```

3. Installez les dépendances via Composer :

    ```bash
    composer install
    ```

4. Créez une copie du fichier `.env.example` et renommez-le en `.env` :

    ```bash
    cp .env.example .env
    ```

5. Générez la clé d'application :

    ```bash
    php artisan key:generate
    ```

6. Configurez la base de données dans le fichier `.env`. Assurez-vous de bien indiquer les bonnes informations pour votre base de données (par exemple, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`).

7. Exécutez les migrations pour configurer la base de données :

    ```bash
    php artisan migrate
    ```

8. Démarrez le serveur de développement :
    ```bash
    php artisan serve
    ```

> Votre API devrait maintenant être accessible à `http://127.0.0.1:8000`.

## Points de terminaison API

### 1. Points de terminaison d'authentification des utilisateurs

#### inscription d'un nouvel utilisateur

Permet de créer un nouveau compte utilisateur dans le système.
**Endpoint**: POST /api/register
Permet d'enregistrer un nouvel utilisateur avec les informations suivantes :

-   `name` (obligatoire)
-   `email` (obligatoire, doit être unique)
-   `password` (obligatoire, minimum 8 caractères)
-   `role` (obligatoire)

Exemple de requête:

```bash
jsonCopier{
  "name": "Jean Dupont",
  "email": "jean.dupont@exemple.fr",
  "password": "motdepasse123",
  "role": "utilisateur"
}
```

Réponse réussie (Code 201 Created):

```bash
jsonCopier{
  "status": "success",
  "message": "Utilisateur créé avec succès",
  "data": {
    "user": {
      "id": 1,
      "name": "Jean Dupont",
      "email": "jean.dupont@exemple.fr",
      "role": "utilisateur",
      "created_at": "2025-03-17T12:00:00.000000Z",
      "updated_at": "2025-03-17T12:00:00.000000Z"
    },
 
    "jwt token": "abcdefghijklmnopqrstuvwxyz123456"
  }
}
```

#### Connexion de l'utilisateur

**Endpoint**: `POST /api/login`  
Permet à un utilisateur de se connecter avec les informations suivantes :

-   `email` (obligatoire)
-   `password` (obligatoire)

```bash
{
  "email": "jean.dupont@exemple.fr",
  "password": "motdepasse123"
}
```

```bash
{
  "status": "success",
  "message": "Connexion réussie",
  "data": {
    "user": {
      "id": 1,
      "name": "Jean Dupont",
      "email": "jean.dupont@exemple.fr",
      "role": "utilisateur",
      "created_at": "2025-03-17T12:00:00.000000Z",
      "updated_at": "2025-03-17T12:00:00.000000Z"
    },
    "jwt_token": "1abcdefghijklmnopqrstuvwxyz123456"
  }
}
```
