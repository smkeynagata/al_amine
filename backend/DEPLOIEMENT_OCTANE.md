# Fix Healthcheck Railway - Octane FrankenPHP

## Changements effectués

### ✅ Installation de Laravel Octane
- Package `laravel/octane` installé
- Serveur FrankenPHP configuré (serveur web hautes performances)
- Binaire FrankenPHP téléchargé automatiquement

### ✅ Configuration optimisée pour Railway

#### 1. `railway.json`
```json
"startCommand": "php artisan octane:frankenphp --host=0.0.0.0 --port=$PORT --max-requests=500"
```
- Remplace `php artisan serve` (développement) par Octane (production)
- `--max-requests=500` : redémarre le worker après 500 requêtes pour éviter les fuites mémoire

#### 2. `nixpacks.toml` (nouveau)
Configuration de build optimisée pour Railway

#### 3. `Procfile` mis à jour
Utilise Octane FrankenPHP au lieu de php artisan serve

#### 4. `.railwayignore` (nouveau)
Optimise le déploiement en excluant les fichiers inutiles

#### 5. `public/health.php` (nouveau)
Healthcheck de secours indépendant de Laravel

### Routes healthcheck disponibles

1. `/api/health` - Route Laravel principale (déjà existante)
2. `/health.php` - Healthcheck de secours direct

## Déploiement

### Variables d'environnement Railway requises
```
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:...
OCTANE_SERVER=frankenphp
DB_CONNECTION=pgsql
DB_HOST=...
DB_PORT=5432
DB_DATABASE=...
DB_USERNAME=...
DB_PASSWORD=...
```

### Commandes de déploiement
```bash
git add .
git commit -m "Fix: Use Laravel Octane FrankenPHP for production deployment"
git push
```

## Avantages de cette solution

✅ **Performance** : Octane FrankenPHP est ~10x plus rapide que php artisan serve
✅ **Production-ready** : Serveur conçu pour la production
✅ **Léger** : Pas besoin d'extensions PHP supplémentaires
✅ **Fiable** : Gestion appropriée des workers et de la mémoire
✅ **Healthcheck** : Double système de healthcheck pour plus de fiabilité

## Test local (optionnel)

```bash
php artisan octane:start --port=8000
curl http://localhost:8000/api/health
```

## Problème résolu

❌ **Avant** : `php artisan serve` (serveur de dev) échouait au healthcheck
✅ **Après** : Octane FrankenPHP (serveur de prod) répond rapidement et de manière fiable
# Fix Healthcheck Railway - Octane FrankenPHP

## Changements effectués

### ✅ Installation de Laravel Octane
- Package `laravel/octane` installé
- Serveur FrankenPHP configuré (serveur web hautes performances)
- Binaire FrankenPHP téléchargé automatiquement

### ✅ Configuration optimisée pour Railway

#### 1. `railway.json`
```json
"startCommand": "php artisan octane:frankenphp --host=0.0.0.0 --port=$PORT --max-requests=500"
```
- Remplace `php artisan serve` (développement) par Octane (production)
- `--max-requests=500` : redémarre le worker après 500 requêtes pour éviter les fuites mémoire

#### 2. `nixpacks.toml` (nouveau)
Configuration de build optimisée pour Railway

#### 3. `Procfile` mis à jour
Utilise Octane FrankenPHP au lieu de php artisan serve

#### 4. `.railwayignore` (nouveau)
Optimise le déploiement en excluant les fichiers inutiles

#### 5. `public/health.php` (nouveau)
Healthcheck de secours indépendant de Laravel

### Routes healthcheck disponibles

1. `/api/health` - Route Laravel principale (déjà existante)
2. `/health.php` - Healthcheck de secours direct

## Déploiement

### Variables d'environnement Railway requises
```
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:...
OCTANE_SERVER=frankenphp
DB_CONNECTION=pgsql
DB_HOST=...
DB_PORT=5432
DB_DATABASE=...
DB_USERNAME=...
DB_PASSWORD=...
```

### Commandes de déploiement
```bash
git add .
git commit -m "Fix: Use Laravel Octane FrankenPHP for production deployment"
git push
```

## Avantages de cette solution

✅ **Performance** : Octane FrankenPHP est ~10x plus rapide que php artisan serve
✅ **Production-ready** : Serveur conçu pour la production
✅ **Léger** : Pas besoin d'extensions PHP supplémentaires
✅ **Fiable** : Gestion appropriée des workers et de la mémoire
✅ **Healthcheck** : Double système de healthcheck pour plus de fiabilité

## Test local (optionnel)

```bash
php artisan octane:start --port=8000
curl http://localhost:8000/api/health
```

## Problème résolu

❌ **Avant** : `php artisan serve` (serveur de dev) échouait au healthcheck
✅ **Après** : Octane FrankenPHP (serveur de prod) répond rapidement et de manière fiable
# Fix Healthcheck Railway - Octane FrankenPHP

## Changements effectués

### ✅ Installation de Laravel Octane
- Package `laravel/octane` installé
- Serveur FrankenPHP configuré (serveur web hautes performances)
- Binaire FrankenPHP téléchargé automatiquement

### ✅ Configuration optimisée pour Railway

#### 1. `railway.json`
```json
"startCommand": "php artisan octane:frankenphp --host=0.0.0.0 --port=$PORT --max-requests=500"
```
- Remplace `php artisan serve` (développement) par Octane (production)
- `--max-requests=500` : redémarre le worker après 500 requêtes pour éviter les fuites mémoire

#### 2. `nixpacks.toml` (nouveau)
Configuration de build optimisée pour Railway

#### 3. `Procfile` mis à jour
Utilise Octane FrankenPHP au lieu de php artisan serve

#### 4. `.railwayignore` (nouveau)
Optimise le déploiement en excluant les fichiers inutiles

#### 5. `public/health.php` (nouveau)
Healthcheck de secours indépendant de Laravel

### Routes healthcheck disponibles

1. `/api/health` - Route Laravel principale (déjà existante)
2. `/health.php` - Healthcheck de secours direct

## Déploiement

### Variables d'environnement Railway requises
```
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:...
OCTANE_SERVER=frankenphp
DB_CONNECTION=pgsql
DB_HOST=...
DB_PORT=5432
DB_DATABASE=...
DB_USERNAME=...
DB_PASSWORD=...
```

### Commandes de déploiement
```bash
git add .
git commit -m "Fix: Use Laravel Octane FrankenPHP for production deployment"
git push
```

## Avantages de cette solution

✅ **Performance** : Octane FrankenPHP est ~10x plus rapide que php artisan serve
✅ **Production-ready** : Serveur conçu pour la production
✅ **Léger** : Pas besoin d'extensions PHP supplémentaires
✅ **Fiable** : Gestion appropriée des workers et de la mémoire
✅ **Healthcheck** : Double système de healthcheck pour plus de fiabilité

## Test local (optionnel)

```bash
php artisan octane:start --port=8000
curl http://localhost:8000/api/health
```

## Problème résolu

❌ **Avant** : `php artisan serve` (serveur de dev) échouait au healthcheck
✅ **Après** : Octane FrankenPHP (serveur de prod) répond rapidement et de manière fiable
# Fix Healthcheck Railway - Octane FrankenPHP

## Changements effectués

### ✅ Installation de Laravel Octane
- Package `laravel/octane` installé
- Serveur FrankenPHP configuré (serveur web hautes performances)
- Binaire FrankenPHP téléchargé automatiquement

### ✅ Configuration optimisée pour Railway

#### 1. `railway.json`
```json
"startCommand": "php artisan octane:frankenphp --host=0.0.0.0 --port=$PORT --max-requests=500"
```
- Remplace `php artisan serve` (développement) par Octane (production)
- `--max-requests=500` : redémarre le worker après 500 requêtes pour éviter les fuites mémoire

#### 2. `nixpacks.toml` (nouveau)
Configuration de build optimisée pour Railway

#### 3. `Procfile` mis à jour
Utilise Octane FrankenPHP au lieu de php artisan serve

#### 4. `.railwayignore` (nouveau)
Optimise le déploiement en excluant les fichiers inutiles

#### 5. `public/health.php` (nouveau)
Healthcheck de secours indépendant de Laravel

### Routes healthcheck disponibles

1. `/api/health` - Route Laravel principale (déjà existante)
2. `/health.php` - Healthcheck de secours direct

## Déploiement

### Variables d'environnement Railway requises
```
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:...
OCTANE_SERVER=frankenphp
DB_CONNECTION=pgsql
DB_HOST=...
DB_PORT=5432
DB_DATABASE=...
DB_USERNAME=...
DB_PASSWORD=...
```

### Commandes de déploiement
```bash
git add .
git commit -m "Fix: Use Laravel Octane FrankenPHP for production deployment"
git push
```

## Avantages de cette solution

✅ **Performance** : Octane FrankenPHP est ~10x plus rapide que php artisan serve
✅ **Production-ready** : Serveur conçu pour la production
✅ **Léger** : Pas besoin d'extensions PHP supplémentaires
✅ **Fiable** : Gestion appropriée des workers et de la mémoire
✅ **Healthcheck** : Double système de healthcheck pour plus de fiabilité

## Test local (optionnel)

```bash
php artisan octane:start --port=8000
curl http://localhost:8000/api/health
```

## Problème résolu

❌ **Avant** : `php artisan serve` (serveur de dev) échouait au healthcheck
✅ **Après** : Octane FrankenPHP (serveur de prod) répond rapidement et de manière fiable
# Fix Healthcheck Railway - Octane FrankenPHP

## Changements effectués

### ✅ Installation de Laravel Octane
- Package `laravel/octane` installé
- Serveur FrankenPHP configuré (serveur web hautes performances)
- Binaire FrankenPHP téléchargé automatiquement

### ✅ Configuration optimisée pour Railway

#### 1. `railway.json`
```json
"startCommand": "php artisan octane:frankenphp --host=0.0.0.0 --port=$PORT --max-requests=500"
```
- Remplace `php artisan serve` (développement) par Octane (production)
- `--max-requests=500` : redémarre le worker après 500 requêtes pour éviter les fuites mémoire

#### 2. `nixpacks.toml` (nouveau)
Configuration de build optimisée pour Railway

#### 3. `Procfile` mis à jour
Utilise Octane FrankenPHP au lieu de php artisan serve

#### 4. `.railwayignore` (nouveau)
Optimise le déploiement en excluant les fichiers inutiles

#### 5. `public/health.php` (nouveau)
Healthcheck de secours indépendant de Laravel

### Routes healthcheck disponibles

1. `/api/health` - Route Laravel principale (déjà existante)
2. `/health.php` - Healthcheck de secours direct

## Déploiement

### Variables d'environnement Railway requises
```
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:...
OCTANE_SERVER=frankenphp
DB_CONNECTION=pgsql
DB_HOST=...
DB_PORT=5432
DB_DATABASE=...
DB_USERNAME=...
DB_PASSWORD=...
```

### Commandes de déploiement
```bash
git add .
git commit -m "Fix: Use Laravel Octane FrankenPHP for production deployment"
git push
```

## Avantages de cette solution

✅ **Performance** : Octane FrankenPHP est ~10x plus rapide que php artisan serve
✅ **Production-ready** : Serveur conçu pour la production
✅ **Léger** : Pas besoin d'extensions PHP supplémentaires
✅ **Fiable** : Gestion appropriée des workers et de la mémoire
✅ **Healthcheck** : Double système de healthcheck pour plus de fiabilité

## Test local (optionnel)

```bash
php artisan octane:start --port=8000
curl http://localhost:8000/api/health
```

## Problème résolu

❌ **Avant** : `php artisan serve` (serveur de dev) échouait au healthcheck
✅ **Après** : Octane FrankenPHP (serveur de prod) répond rapidement et de manière fiable
# Fix Healthcheck Railway - Octane FrankenPHP

## Changements effectués

### ✅ Installation de Laravel Octane
- Package `laravel/octane` installé
- Serveur FrankenPHP configuré (serveur web hautes performances)
- Binaire FrankenPHP téléchargé automatiquement

### ✅ Configuration optimisée pour Railway

#### 1. `railway.json`
```json
"startCommand": "php artisan octane:frankenphp --host=0.0.0.0 --port=$PORT --max-requests=500"
```
- Remplace `php artisan serve` (développement) par Octane (production)
- `--max-requests=500` : redémarre le worker après 500 requêtes pour éviter les fuites mémoire

#### 2. `nixpacks.toml` (nouveau)
Configuration de build optimisée pour Railway

#### 3. `Procfile` mis à jour
Utilise Octane FrankenPHP au lieu de php artisan serve

#### 4. `.railwayignore` (nouveau)
Optimise le déploiement en excluant les fichiers inutiles

#### 5. `public/health.php` (nouveau)
Healthcheck de secours indépendant de Laravel

### Routes healthcheck disponibles

1. `/api/health` - Route Laravel principale (déjà existante)
2. `/health.php` - Healthcheck de secours direct
# Fix Healthcheck Railway - Octane FrankenPHP
## Déploiement

### Variables d'environnement Railway requises
```
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:...
OCTANE_SERVER=frankenphp
DB_CONNECTION=mysql
DB_HOST=...
DB_PORT=3306
DB_DATABASE=...
DB_USERNAME=...
DB_PASSWORD=...
```

### Commandes de déploiement
```bash
git add .
git commit -m "Fix: Use Laravel Octane FrankenPHP for production deployment"
git push
```

## Avantages de cette solution

✅ **Performance** : Octane FrankenPHP est ~10x plus rapide que php artisan serve
✅ **Production-ready** : Serveur conçu pour la production
✅ **Léger** : Pas besoin d'extensions PHP supplémentaires
✅ **Fiable** : Gestion appropriée des workers et de la mémoire
✅ **Healthcheck** : Double système de healthcheck pour plus de fiabilité

## Test local (optionnel)

```bash
php artisan octane:start --port=8000
curl http://localhost:8000/api/health
```

## Problème résolu

❌ **Avant** : `php artisan serve` (serveur de dev) échouait au healthcheck
✅ **Après** : Octane FrankenPHP (serveur de prod) répond rapidement et de manière fiable

## Changements effectués

### ✅ Installation de Laravel Octane
- Package `laravel/octane` installé
- Serveur FrankenPHP configuré (serveur web hautes performances)
- Binaire FrankenPHP téléchargé automatiquement

### ✅ Configuration optimisée pour Railway

#### 1. `railway.json`
```json
"startCommand": "php artisan octane:frankenphp --host=0.0.0.0 --port=$PORT --max-requests=500"
```
- Remplace `php artisan serve` (développement) par Octane (production)
- `--max-requests=500` : redémarre le worker après 500 requêtes pour éviter les fuites mémoire

#### 2. `nixpacks.toml` (nouveau)
Configuration de build optimisée pour Railway

#### 3. `Procfile` mis à jour
Utilise Octane FrankenPHP au lieu de php artisan serve

#### 4. `.railwayignore` (nouveau)
Optimise le déploiement en excluant les fichiers inutiles

#### 5. `public/health.php` (nouveau)
Healthcheck de secours indépendant de Laravel

### Routes healthcheck disponibles

1. `/api/health` - Route Laravel principale (déjà existante)
2. `/health.php` - Healthcheck de secours direct
# Fix Healthcheck Railway - Octane FrankenPHP
## Déploiement

### Variables d'environnement Railway requises
```
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:...
OCTANE_SERVER=frankenphp
DB_CONNECTION=mysql
DB_HOST=...
DB_PORT=3306
DB_DATABASE=...
DB_USERNAME=...
DB_PASSWORD=...
```

### Commandes de déploiement
```bash
git add .
git commit -m "Fix: Use Laravel Octane FrankenPHP for production deployment"
git push
```

## Avantages de cette solution

✅ **Performance** : Octane FrankenPHP est ~10x plus rapide que php artisan serve
✅ **Production-ready** : Serveur conçu pour la production
✅ **Léger** : Pas besoin d'extensions PHP supplémentaires
✅ **Fiable** : Gestion appropriée des workers et de la mémoire
✅ **Healthcheck** : Double système de healthcheck pour plus de fiabilité

## Test local (optionnel)

```bash
php artisan octane:start --port=8000
curl http://localhost:8000/api/health
```

## Problème résolu

❌ **Avant** : `php artisan serve` (serveur de dev) échouait au healthcheck
✅ **Après** : Octane FrankenPHP (serveur de prod) répond rapidement et de manière fiable

## Changements effectués

### ✅ Installation de Laravel Octane
- Package `laravel/octane` installé
- Serveur FrankenPHP configuré (serveur web hautes performances)
- Binaire FrankenPHP téléchargé automatiquement

### ✅ Configuration optimisée pour Railway

#### 1. `railway.json`
```json
"startCommand": "php artisan octane:frankenphp --host=0.0.0.0 --port=$PORT --max-requests=500"
```
- Remplace `php artisan serve` (développement) par Octane (production)
- `--max-requests=500` : redémarre le worker après 500 requêtes pour éviter les fuites mémoire

#### 2. `nixpacks.toml` (nouveau)
Configuration de build optimisée pour Railway

#### 3. `Procfile` mis à jour
Utilise Octane FrankenPHP au lieu de php artisan serve

#### 4. `.railwayignore` (nouveau)
Optimise le déploiement en excluant les fichiers inutiles

#### 5. `public/health.php` (nouveau)
Healthcheck de secours indépendant de Laravel

### Routes healthcheck disponibles

1. `/api/health` - Route Laravel principale (déjà existante)
2. `/health.php` - Healthcheck de secours direct

## Déploiement

### Variables d'environnement Railway requises
```
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:...
OCTANE_SERVER=frankenphp
DB_CONNECTION=pgsql
DB_HOST=...
DB_PORT=5432
DB_DATABASE=...
DB_USERNAME=...
DB_PASSWORD=...
```

### Commandes de déploiement
```bash
git add .
git commit -m "Fix: Use Laravel Octane FrankenPHP for production deployment"
git push
```

## Avantages de cette solution

✅ **Performance** : Octane FrankenPHP est ~10x plus rapide que php artisan serve
✅ **Production-ready** : Serveur conçu pour la production
✅ **Léger** : Pas besoin d'extensions PHP supplémentaires
✅ **Fiable** : Gestion appropriée des workers et de la mémoire
✅ **Healthcheck** : Double système de healthcheck pour plus de fiabilité

## Test local (optionnel)

```bash
php artisan octane:start --port=8000
curl http://localhost:8000/api/health
```

## Problème résolu

❌ **Avant** : `php artisan serve` (serveur de dev) échouait au healthcheck
✅ **Après** : Octane FrankenPHP (serveur de prod) répond rapidement et de manière fiable

