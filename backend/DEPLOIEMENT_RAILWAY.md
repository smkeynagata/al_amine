# 🚀 Guide de Déploiement sur Railway avec GitHub Actions

## 📋 Fichiers créés

✅ `.github/workflows/deploy.yml` - Workflow GitHub Actions
✅ `railway.json` - Configuration Railway
✅ `Procfile` - Commande de démarrage
✅ `.env.example` - Template des variables d'environnement

## 🔄 Comment ça fonctionne

### 1. Le Workflow GitHub Actions (`deploy.yml`)

```
Push sur ass_super ou main
    ↓
GitHub Actions démarre
    ↓
1. Checkout du code
2. Installation PHP 8.2
3. Installation Node.js 18
4. composer install --no-dev
5. npm install
6. npm run build
7. Installation Railway CLI
8. railway up (déploiement)
    ↓
Railway prend le relais
```

### 2. Ce que fait Railway (`railway.json`)

**Build** :
- `composer install --no-dev --optimize-autoloader`
- `npm install`
- `npm run build` (compile vos assets Vite)

**Deploy** (commandes exécutées au démarrage) :
- `php artisan config:cache` - Cache la config
- `php artisan route:cache` - Cache les routes
- `php artisan migrate --force` - Migrations automatiques
- `php artisan serve --host=0.0.0.0 --port=$PORT` - Démarre le serveur

## 📝 Étapes d'installation

### Étape 1 : Créer un compte Railway

1. Allez sur [railway.app](https://railway.app)
2. Connectez-vous avec GitHub
3. Créez un nouveau projet

### Étape 2 : Lier votre repository GitHub

1. Dans Railway : **New Project** → **Deploy from GitHub repo**
2. Sélectionnez `al-amine`
3. Railway détecte automatiquement Laravel

### Étape 3 : Ajouter PostgreSQL

1. Dans votre projet Railway : **New** → **Database** → **PostgreSQL**
2. Railway génère automatiquement :
   - `DATABASE_URL`
   - `PGHOST`, `PGPORT`, `PGDATABASE`, `PGUSER`, `PGPASSWORD`

### Étape 4 : Configurer les variables d'environnement sur Railway

Dans Railway → Variables, ajoutez :

```env
APP_NAME=Al-Amine
APP_ENV=production
APP_KEY=base64:zHekAVk9wY2D3xzWspva9iGXriPJidBh1Wux/IGCwm4=
APP_DEBUG=false
APP_URL=https://votre-app.up.railway.app

DB_CONNECTION=pgsql
DB_HOST=${{ PGHOST }}
DB_PORT=${{ PGPORT }}
DB_DATABASE=${{ PGDATABASE }}
DB_USERNAME=${{ PGUSER }}
DB_PASSWORD=${{ PGPASSWORD }}

SESSION_DRIVER=database
CACHE_STORE=database

MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=birakanembodj01@gmail.com
MAIL_PASSWORD="clok bcet gtjf rvyn"
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=birakanembodj01@gmail.com

# PayDunya (utilisez vos clés LIVE en production)
PAYDUNYA_MODE=live
PAYDUNYA_MASTER_KEY=votre_master_key_live
PAYDUNYA_PRIVATE_KEY=votre_private_key_live
PAYDUNYA_PUBLIC_KEY=votre_public_key_live
PAYDUNYA_TOKEN=votre_token_live
PAYDUNYA_CURRENCY=XOF

# Stripe (utilisez vos clés LIVE en production)
STRIPE_PUBLIC_KEY=pk_live_xxxxx
STRIPE_SECRET_KEY=sk_live_xxxxx
STRIPE_WEBHOOK_SECRET=whsec_xxxxx

# Reverb (WebSocket)
REVERB_APP_ID=alamine-chat
REVERB_APP_KEY=votre_reverb_key
REVERB_APP_SECRET=votre_reverb_secret
REVERB_HOST=votre-app.up.railway.app
REVERB_SCHEME=https
```

**Important** : Railway utilise des variables avec `${{ }}` pour référencer d'autres variables.

### Étape 5 : Obtenir le Railway Token

1. Dans Railway : Settings → **Tokens**
2. Cliquez sur **Create Token**
3. Copiez le token généré

### Étape 6 : Ajouter le token dans GitHub

1. Allez sur votre repo GitHub
2. **Settings** → **Secrets and variables** → **Actions**
3. Cliquez **New repository secret**
4. Nom : `RAILWAY_TOKEN`
5. Valeur : Collez le token Railway
6. Cliquez **Add secret**

### Étape 7 : Pousser le code

```bash
cd /home/asspro/Documents/al-amine/backend

# Ajoutez les nouveaux fichiers
git add .github/ railway.json Procfile .env.example

# Commitez
git commit -m "🚀 Ajout du workflow de déploiement Railway"

# Poussez sur ass_super
git push origin ass_super
```

### Étape 8 : Vérifier le déploiement

1. **GitHub** : Actions → Vous verrez le workflow en cours
2. **Railway** : Deployments → Vous verrez le build et le déploiement
3. Une fois terminé, cliquez sur l'URL générée par Railway

## 🔐 Sécurité importante

⚠️ **ATTENTION** :

1. **Ne committez JAMAIS le fichier `.env`** avec vos vraies clés
2. Le `.env` est déjà dans `.gitignore`
3. Utilisez les clés **LIVE** (pas test) pour PayDunya et Stripe en production
4. Changez `APP_DEBUG=false` en production
5. Générez une nouvelle `APP_KEY` pour la production :
   ```bash
   php artisan key:generate --show
   ```

## 🎯 Déploiement automatique vs Manuel

### Option 1 : Via GitHub Actions (recommandé)
- Push sur `ass_super` ou `main`
- GitHub Actions build et déploie automatiquement
- Contrôle total du processus

### Option 2 : Déploiement automatique Railway
- Railway surveille directement votre repo GitHub
- Déploie à chaque push (toutes branches)
- Plus simple mais moins de contrôle

Pour activer l'option 2 :
1. Railway → Settings → **Watch Paths** : `backend/**`
2. Railway déploie sans GitHub Actions

## 🐛 Résolution de problèmes

### Erreur de build
```bash
# Vérifiez les logs dans Railway → Deployments → Build Logs
```

### Migrations échouent
```bash
# Railway → Service → Shell
php artisan migrate:fresh --force
```

### Assets non compilés
```bash
# Vérifiez package.json contient :
"scripts": {
  "build": "vite build"
}
```

### Variables d'environnement manquantes
- Railway → Variables → Vérifiez toutes les variables requises
- Redéployez : Railway → Deployments → Redeploy

## 📊 Monitoring

Railway fournit :
- **Metrics** : CPU, RAM, Network
- **Logs** : Logs en temps réel
- **Deployments** : Historique des déploiements
- **Health Checks** : Vérification automatique

## 💰 Coûts

Railway offre :
- **$5 de crédit gratuit/mois** (Hobby plan)
- Suffisant pour petits projets
- Pay-as-you-go au-delà

## 🔄 Workflow de développement

```
Développement local (ass_super)
    ↓
git push origin ass_super
    ↓
GitHub Actions teste et build
    ↓
Railway déploie automatiquement
    ↓
Testez sur l'URL Railway
    ↓
Merge dans main quand stable
```

## 📝 Commandes utiles

```bash
# Installer Railway CLI localement
npm i -g @railway/cli

# Se connecter
railway login

# Lier le projet
railway link

# Voir les logs
railway logs

# Ouvrir le dashboard
railway open

# Déployer manuellement
railway up

# Exécuter une commande
railway run php artisan migrate
```

## ✅ Checklist avant le premier déploiement

- [ ] Compte Railway créé
- [ ] Projet Railway créé et lié au repo GitHub
- [ ] PostgreSQL ajouté sur Railway
- [ ] Toutes les variables d'environnement configurées
- [ ] `RAILWAY_TOKEN` ajouté dans GitHub Secrets
- [ ] Clés PayDunya LIVE configurées
- [ ] Clés Stripe LIVE configurées
- [ ] `APP_ENV=production` et `APP_DEBUG=false`
- [ ] Nouvelle `APP_KEY` générée
- [ ] URLs callback PayDunya mises à jour avec URL Railway
- [ ] Webhooks Stripe configurés avec URL Railway

## 🎉 C'est prêt !

Maintenant, à chaque push sur `ass_super`, votre application sera automatiquement déployée sur Railway ! 🚀

