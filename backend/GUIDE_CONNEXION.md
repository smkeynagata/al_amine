# Guide de Connexion - Système Al-Amine

## Vue d'ensemble

Le système Al-Amine dispose de **2 points d'entrée** selon le type d'utilisateur :

### 1. Page d'accueil (Landing) - `/`
**Pour les nouveaux patients uniquement**

- **Bouton "Créer un Compte Patient"** : Permet aux nouveaux patients de s'inscrire
- Le formulaire crée automatiquement un compte avec le rôle `PATIENT`
- Après inscription, le patient peut se connecter via `/login`

### 2. Page de Connexion - `/login`
**Pour TOUS les utilisateurs existants (tous rôles)**

Cette page unique permet la connexion de :
- ✅ **Admin** - Gestion complète du système
- ✅ **Praticien** - Médecins et spécialistes
- ✅ **Secrétaire** - Personnel administratif
- ✅ **Patient** - Patients déjà inscrits

## Flux d'inscription et connexion

### Pour un nouveau patient
1. Aller sur la page d'accueil : `http://localhost:8000`
2. Cliquer sur **"Créer un Compte Patient"**
3. Remplir le formulaire d'inscription
4. Une fois inscrit, utiliser `/login` pour se connecter

### Pour les patients existants
1. Aller directement sur : `http://localhost:8000/login`
2. Entrer email et mot de passe
3. Redirection automatique vers le dashboard patient

### Pour le personnel (Praticien, Secrétaire, Admin)
1. Aller sur : `http://localhost:8000/login`
2. Entrer les identifiants fournis
3. Redirection automatique vers le dashboard correspondant

## Comptes de test disponibles

### Admin
- **Email** : `admin@alamine.sn`
- **Mot de passe** : `password`
- **Dashboard** : `/admin/dashboard`

### Praticiens (5 comptes disponibles)
- **Email** : `praticien1@alamine.sn` à `praticien5@alamine.sn`
- **Mot de passe** : `password`
- **Dashboard** : `/praticien/dashboard`

Exemple :
- Dr. Cheikh NDIAYE (Médecine Générale) : `praticien1@alamine.sn`
- Dr. Mariama SY (Cardiologie) : `praticien2@alamine.sn`
- Dr. Moussa BA (Pédiatrie) : `praticien3@alamine.sn`
- Dr. Aminata CISSE (Dermatologie) : `praticien4@alamine.sn`
- Dr. Fatima SECK (Gynécologie) : `praticien5@alamine.sn`

### Secrétaires (2 comptes disponibles)
- **Email** : `secretaire1@alamine.sn` ou `secretaire2@alamine.sn`
- **Mot de passe** : `password`
- **Dashboard** : `/secretaire/dashboard`

Exemple :
- Aïssatou FALL : `secretaire1@alamine.sn`
- Fatou SARR : `secretaire2@alamine.sn`

### Patients (30 comptes de test)
- **Email** : `patient1@example.sn` à `patient30@example.sn`
- **Mot de passe** : `password`
- **Dashboard** : `/patient/dashboard`

## Création de nouveaux comptes

### Patients
➡️ **Auto-inscription** via le formulaire sur la page d'accueil `/`

### Personnel (Praticien, Secrétaire, Admin)
➡️ **Créés par l'Administrateur** uniquement via le dashboard admin
- L'admin peut créer/gérer tous les comptes du personnel
- Ces comptes ne peuvent pas s'auto-inscrire pour des raisons de sécurité

## Redirection automatique après connexion

Le système redirige automatiquement selon le rôle :

```php
Route::get('/dashboard') → redirige vers :
├─ ADMIN      → /admin/dashboard
├─ PRATICIEN  → /praticien/dashboard
├─ SECRETAIRE → /secretaire/dashboard
└─ PATIENT    → /patient/dashboard
```

## Sécurité

- **Middleware `auth`** : Vérifie que l'utilisateur est connecté
- **Middleware `role:XXX`** : Vérifie que l'utilisateur a le bon rôle pour accéder à une section
- **CSRF Protection** : Tous les formulaires sont protégés contre les attaques CSRF
- **Session Database** : Les sessions sont stockées en base de données (PostgreSQL)

## Résolution de problèmes

### Erreur 419 (Page Expired)
```bash
php artisan optimize:clear
php artisan config:clear
php artisan cache:clear
```

### Les seeders n'ont pas créé les comptes
```bash
php artisan migrate:fresh --seed
```

### Impossible de se connecter
1. Vérifier que le serveur Laravel tourne : `php artisan serve`
2. Vérifier que la base de données PostgreSQL est accessible
3. Vérifier les identifiants dans le fichier `.env` :
   ```
   DB_CONNECTION=pgsql
   DB_HOST=127.0.0.1
   DB_PORT=5432
   DB_DATABASE=al-amine
   DB_USERNAME=postgres
   DB_PASSWORD=1964
   ```

## Notes importantes

1. **Pas de modal de connexion** : Le modal "Connexion Patient" sur la landing page a été remplacé par un lien direct vers `/login` pour éviter la confusion
2. **Un seul point de connexion** : `/login` accepte tous les types d'utilisateurs
3. **Inscription limitée aux patients** : Seuls les patients peuvent s'auto-inscrire
4. **Personnel créé par admin** : Les praticiens, secrétaires et admins sont créés uniquement par l'administrateur
