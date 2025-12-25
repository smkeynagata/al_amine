# Railway Configuration - Variables d'environnement requises

## 🚨 IMPORTANT : Configurez ces variables sur Railway

Pour résoudre le problème "Mixed Content" (HTTP vs HTTPS), vous DEVEZ configurer ces variables d'environnement sur Railway :

### Variables à ajouter/modifier sur Railway :

```bash
APP_ENV=production
APP_DEBUG=false
APP_URL=https://alamine-production.up.railway.app
ASSET_URL=https://alamine-production.up.railway.app

# Force HTTPS (important !)
FORCE_HTTPS=true
```

### Si vous utilisez votre domaine personnalisé (al-amine.online) :

Une fois que le DNS sera propagé et validé par Railway, changez :

```bash
APP_URL=https://al-amine.online
ASSET_URL=https://al-amine.online
```

## Comment configurer sur Railway :

1. Allez sur https://railway.app
2. Ouvrez votre projet **al_amine**
3. Cliquez sur votre service (backend)
4. Allez dans **Variables**
5. Ajoutez/modifiez ces variables
6. Railway redéploiera automatiquement

## Vérification :

Après le redéploiement, ouvrez votre site et vérifiez la console (F12). Il ne devrait plus y avoir d'erreurs "Mixed Content".

## Note importante :

Le code a été modifié pour forcer HTTPS partout :
- ✅ AppServiceProvider force HTTPS
- ✅ TrustProxies configuré
- ✅ .htaccess redirige HTTP → HTTPS
- ✅ Toutes les URLs générées seront en HTTPS

Mais les **variables d'environnement** doivent absolument commencer par `https://` !

