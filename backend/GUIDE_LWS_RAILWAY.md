# Guide pas à pas : Configuration DNS LWS pour Railway

## 🎯 Étape par étape avec l'interface LWS que vous avez

### 1️⃣ Dans l'interface LWS que vous voyez actuellement

Cliquez sur **"Zone DNS"** (l'icône avec le globe et les engrenages)

### 2️⃣ Sur la page Zone DNS

Vous allez voir une liste de vos enregistrements DNS actuels. Cherchez le bouton **"Ajouter un enregistrement"** ou **"+"**

### 3️⃣ Ajouter l'enregistrement CNAME

Remplissez le formulaire comme suit :

```
┌─────────────────────────────────────────────┐
│ Type d'enregistrement : CNAME               │
├─────────────────────────────────────────────┤
│ Nom (ou Sous-domaine) : api                 │
│  (ou alamine, backend, app, etc.)           │
├─────────────────────────────────────────────┤
│ Valeur (ou Cible) :                         │
│  alamine-production.up.railway.app          │
├─────────────────────────────────────────────┤
│ TTL : 3600                                  │
│  (ou laisser la valeur par défaut)          │
└─────────────────────────────────────────────┘
```

### 4️⃣ Cliquez sur "Enregistrer" ou "Ajouter"

### 5️⃣ Vérification immédiate sur LWS

Après enregistrement, vous devriez voir une nouvelle ligne dans votre Zone DNS :

```
Type    Nom/Sous-domaine    Valeur/Cible                          TTL
──────────────────────────────────────────────────────────────────────
CNAME   api                 alamine-production.up.railway.app     3600
```

## 🚀 Ensuite : Configuration sur Railway

### 1️⃣ Ouvrez Railway
- Allez sur https://railway.app
- Connectez-vous à votre compte
- Ouvrez le projet **al_amine**

### 2️⃣ Accédez aux paramètres du service
- Cliquez sur votre service **backend** (ou **web**)
- Cliquez sur l'onglet **"Settings"**
- Scrollez jusqu'à **"Networking"**

### 3️⃣ Ajoutez le domaine personnalisé
- Dans la section **"Domains"** ou **"Custom Domain"**
- Cliquez sur **"+ Custom Domain"** ou **"Add Domain"**
- Entrez : `api.votredomaine.com` (remplacez par votre vrai domaine)
- Cliquez sur **"Add"**

### 4️⃣ Attendez la validation
Railway va vérifier que le DNS pointe bien vers eux :
- ⏳ En cours... (cercle qui tourne)
- ⚠️ Pending (DNS pas encore propagé)
- ✅ Active (Tout est bon !)

**Temps d'attente typique : 5 à 30 minutes**

### 5️⃣ Mettez à jour les variables d'environnement
Une fois le domaine validé (✅) :

1. Toujours dans **Settings**, allez dans la section **"Variables"**
2. Modifiez ou ajoutez ces variables :

```
APP_URL=https://api.votredomaine.com
ASSET_URL=https://api.votredomaine.com
```

3. Railway va automatiquement redéployer l'application

## 🔍 Vérification finale

### Test 1 : Accès au site
Ouvrez votre navigateur et allez sur :
```
https://api.votredomaine.com/patient/dashboard
```

### Test 2 : Vérifier le certificat SSL
- Cliquez sur le cadenas 🔒 dans la barre d'adresse
- Vérifiez que le certificat est valide
- Émis par : Let's Encrypt

### Test 3 : Console du navigateur
- Appuyez sur F12 pour ouvrir la console
- Vérifiez qu'il n'y a **AUCUNE** erreur "Mixed Content"
- Les assets doivent tous charger en HTTPS

## 📋 Exemple complet avec un vrai domaine

Si votre domaine LWS est **`clinique-alamine.com`** :

### Sur LWS (Zone DNS) :
```
Type: CNAME
Nom: api
Valeur: alamine-production.up.railway.app
TTL: 3600
```

### Sur Railway (Custom Domain) :
```
Domaine: api.clinique-alamine.com
```

### Variables d'environnement Railway :
```
APP_URL=https://api.clinique-alamine.com
ASSET_URL=https://api.clinique-alamine.com
```

### Résultat :
Votre site sera accessible sur : **https://api.clinique-alamine.com**

## ❓ Problèmes courants

### "Domain not verified" sur Railway après 1 heure
**Solution :**
```bash
# Vérifiez que le DNS est propagé
dig api.votredomaine.com

# Ou utilisez un site web
https://www.whatsmydns.net
```

Si le DNS ne pointe pas vers Railway, revérifiez la configuration sur LWS

### Erreur "Too many redirects"
**Solution :**
- Vérifiez que `APP_URL` commence par `https://` (pas `http://`)
- Vérifiez qu'il n'y a pas de redirection forcée dans LWS (section "Redirection web")

### Le site charge mais CSS/JS ne fonctionne pas
**Solution :**
- Videz le cache : Ctrl + Shift + R
- Vérifiez que `ASSET_URL` est défini sur Railway
- Redéployez l'application sur Railway

## 🎉 Une fois terminé

Vous aurez :
- ✅ Un domaine professionnel (api.votredomaine.com)
- ✅ HTTPS automatique avec Let's Encrypt
- ✅ Plus d'erreur "Mixed Content"
- ✅ Certificat SSL renouvelé automatiquement

---

**Besoin d'aide ?**
Si vous bloquez à une étape, notez :
1. À quelle étape vous êtes bloqué
2. Le message d'erreur exact (si erreur)
3. Votre nom de domaine LWS

