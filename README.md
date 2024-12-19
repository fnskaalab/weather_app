# Laravel Weather Product Suggestion API

## Prérequis

Assurez-vous d'avoir installé les outils suivants avant de commencer :

- [PHP](https://www.php.net/) (version 8.0 ou supérieure)
- [Composer](https://getcomposer.org/)
- [Laravel](https://laravel.com/docs)
- [SQLite](https://www.sqlite.org/)

## Installation

### Étapes pour cloner et configurer le projet

Clonez le repository dans votre environnement local :

```bash
git clone git@github.com:fnskaalab/weather_app.git
cd weather_app
```

Copiez le fichier .env.example vers .env :

```bash
cp .env.example .env
```
Ouvrez le fichier .env et ajoutez votre clé d'API pour la météo :

```dotenv
WEATHER_API_TOKEN=<votre_clé_api_météo>
```
Générez une clé d'application Laravel :

```bash
php artisan key:generate
```
Créez le fichier de base de données SQLite :

```bash
touch database/database.sqlite
```
Exécutez les migrations et le peuplement de la base de données :

```bash
php artisan migrate:fresh --seed
```
Générez la documentation Swagger :

```bash
php artisan l5-swagger:generate
```
Démarrez le serveur de développement Laravel :

```bash
php artisan serve
```
Le serveur sera accessible à l'URL suivante : http://127.0.0.1:8000/api.

### Accéder à la documentation Swagger
Une fois le serveur démarré, vous pouvez accéder à la documentation de l'API Swagger à l'URL suivante :

```arduino
http://127.0.0.1:8000/api/documentation
```
### Tests

Pour faire les tests 

```bash
php artisan test
```
