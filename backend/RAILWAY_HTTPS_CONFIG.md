# Configuration Railway pour HTTPS

## Variables d'environnement requises sur Railway

Assurez-vous que ces variables sont configurées dans Railway :

```
APP_ENV=production
APP_DEBUG=false
APP_URL=https://alamine-production.up.railway.app

# Force HTTPS
FORCE_HTTPS=true
ASSET_URL=https://alamine-production.up.railway.app
```

## Vérification

Après le déploiement, vérifiez que :
1. Toutes les URLs générées utilisent HTTPS
2. Les assets (CSS/JS) sont chargés en HTTPS
3. Les formulaires soumettent en HTTPS

## Configuration appliquée

- ✅ TrustProxies middleware configuré pour détecter les proxys HTTPS
- ✅ URL::forceScheme('https') activé en production
- ✅ Détection automatique du header X-Forwarded-Proto

