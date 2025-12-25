# Configuration Railway pour HTTPS

## Variables d'environnement requises sur Railway

Assurez-vous que ces variables sont configurées dans Railway :

```
APP_ENV=production
APP_DEBUG=false
APP_URL=https://votre-domaine.com  # Remplacer par votre domaine LWS

# Force HTTPS
FORCE_HTTPS=true
ASSET_URL=https://votre-domaine.com  # Remplacer par votre domaine LWS
```

## Configuration d'un domaine personnalisé LWS avec Railway

### Étape 1 : Obtenir les informations de Railway

1. Connectez-vous à [Railway.app](https://railway.app)
2. Sélectionnez votre projet **al_amine**
3. Cliquez sur votre service (backend)
4. Allez dans l'onglet **Settings**
5. Scrollez jusqu'à la section **Networking** > **Custom Domain**
6. Cliquez sur **+ Custom Domain**
7. Entrez votre domaine (ex: `alamine.votredomaine.com` ou `votredomaine.com`)
8. Railway va afficher les enregistrements DNS à configurer :
   - **Type A** ou **CNAME** avec une valeur comme `xxxx.up.railway.app`

### Étape 2 : Configurer les DNS sur LWS

1. Connectez-vous à votre compte [LWS Panel](https://panel.lws.fr)
2. Allez dans **Domaines** > **Gestion DNS** ou **Zone DNS**
3. Sélectionnez votre domaine

#### Option A : Sous-domaine (Recommandé pour commencer)
Créez un enregistrement **CNAME** :
```
Type: CNAME
Nom: alamine (ou api, app, etc.)
Valeur: alamine-production.up.railway.app
TTL: 3600 (ou laisser par défaut)
```

#### Option B : Domaine racine
Créez un enregistrement **A** :
```
Type: A
Nom: @ (ou laisser vide pour le domaine racine)
Valeur: [L'adresse IP fournie par Railway]
TTL: 3600
```

OU créez un enregistrement **CNAME** avec **ALIAS/ANAME** (si LWS le supporte) :
```
Type: ALIAS ou ANAME
Nom: @ 
Valeur: alamine-production.up.railway.app
TTL: 3600
```

4. **Sauvegardez** les modifications DNS

### Étape 3 : Attendre la propagation DNS

⏱️ La propagation DNS peut prendre de **5 minutes à 48 heures** (généralement 15-30 minutes)

Vérifiez la propagation avec :
```bash
# Pour un sous-domaine
dig alamine.votredomaine.com

# Pour le domaine racine
dig votredomaine.com

# Ou via un site web
# https://www.whatsmydns.net
```

### Étape 4 : Vérifier sur Railway

1. Retournez sur Railway dans **Settings** > **Custom Domain**
2. Railway devrait automatiquement détecter la configuration DNS
3. Un certificat SSL sera automatiquement généré (Let's Encrypt)
4. Une fois activé, vous verrez un ✅ à côté de votre domaine

### Étape 5 : Mettre à jour les variables d'environnement

Sur Railway, mettez à jour ces variables :

```
APP_URL=https://alamine.votredomaine.com  # Votre domaine personnalisé
ASSET_URL=https://alamine.votredomaine.com
```

Puis **redéployez** l'application (Deploy > Redeploy)

### Étape 6 : Configuration supplémentaire (Optionnel)

#### Pour forcer www → non-www (ou vice versa)

Sur LWS, ajoutez une redirection :
```
Type: CNAME
Nom: www
Valeur: alamine.votredomaine.com
```

#### Pour activer le mode CAA (Sécurité)

Ajoutez un enregistrement CAA sur LWS :
```
Type: CAA
Nom: @
Valeur: 0 issue "letsencrypt.org"
```

## Exemple concret

Si votre domaine est **alamine.sn** et vous voulez utiliser **api.alamine.sn** :

### Sur LWS :
```
Type: CNAME
Nom: api
Valeur: alamine-production.up.railway.app
TTL: 3600
```

### Sur Railway :
```
Custom Domain: api.alamine.sn
```

### Variables d'environnement Railway :
```
APP_URL=https://api.alamine.sn
ASSET_URL=https://api.alamine.sn
```

## Vérification finale

Après le déploiement, vérifiez que :
1. ✅ Votre domaine personnalisé est accessible (https://votre-domaine.com)
2. ✅ Le certificat SSL est actif (cadenas dans le navigateur)
3. ✅ Toutes les URLs générées utilisent HTTPS
4. ✅ Les assets (CSS/JS) sont chargés en HTTPS
5. ✅ Les formulaires soumettent en HTTPS
6. ✅ Pas d'erreur "Mixed Content" dans la console

## Dépannage

### Le domaine ne fonctionne pas après 1 heure
- Vérifiez que l'enregistrement DNS est correct sur LWS
- Utilisez `dig` ou whatsmydns.net pour vérifier la propagation
- Vérifiez que Railway affiche le ✅ à côté du domaine

### Erreur SSL / Certificat non valide
- Attendez quelques minutes, Railway génère le certificat automatiquement
- Si après 30 min ça ne fonctionne pas, supprimez et réajoutez le domaine sur Railway

### Erreur "Mixed Content" persiste
- Vérifiez que `APP_URL` commence bien par `https://`
- Redéployez l'application après avoir modifié les variables d'environnement
- Videz le cache du navigateur (Ctrl + Shift + R)

## Configuration appliquée dans le code

- ✅ TrustProxies middleware configuré pour détecter les proxys HTTPS
- ✅ URL::forceScheme('https') activé en production
- ✅ Détection automatique du header X-Forwarded-Proto
- ✅ Support des domaines personnalisés avec SSL automatique

## Ressources utiles

- [Documentation Railway - Custom Domains](https://docs.railway.app/deploy/exposing-your-app#custom-domains)
- [LWS - Gestion DNS](https://aide.lws.fr/a/259)
- [Vérifier propagation DNS](https://www.whatsmydns.net)
- [SSL Labs - Test SSL](https://www.ssllabs.com/ssltest/)

