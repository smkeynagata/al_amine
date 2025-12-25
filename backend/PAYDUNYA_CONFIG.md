# Configuration PayDunya - Al-Amine Medical Center

## 🔗 URLs à configurer dans PayDunya Dashboard

Votre tunnel de développement : `https://gzp8qzp9-8000.uks1.devtunnels.ms`

### 1. IPN URL (Instant Payment Notification)
```
https://gzp8qzp9-8000.uks1.devtunnels.ms/paydunya/ipn
```
**Type:** POST  
**Description:** PayDunya enverra les notifications de paiement à cette URL

### 2. Return URL (Page de retour après paiement réussi)
```
https://gzp8qzp9-8000.uks1.devtunnels.ms/paydunya/return
```
**Type:** GET  
**Description:** Le patient sera redirigé ici après un paiement réussi

### 3. Cancel URL (Page de retour après annulation)
```
https://gzp8qzp9-8000.uks1.devtunnels.ms/paydunya/cancel
```
**Type:** GET  
**Description:** Le patient sera redirigé ici s'il annule le paiement

---

## 🔑 Variables d'environnement à ajouter dans .env

Ajoutez ces lignes dans votre fichier `.env` :

```env
# PayDunya Configuration
PAYDUNYA_MODE=test
PAYDUNYA_MASTER_KEY=votre-master-key
PAYDUNYA_PRIVATE_KEY=votre-private-key
PAYDUNYA_PUBLIC_KEY=votre-public-key
PAYDUNYA_TOKEN=votre-token

# URLs de callback
PAYDUNYA_CALLBACK_URL=https://gzp8qzp9-8000.uks1.devtunnels.ms/paydunya/ipn
PAYDUNYA_RETURN_URL=https://gzp8qzp9-8000.uks1.devtunnels.ms/paydunya/return
PAYDUNYA_CANCEL_URL=https://gzp8qzp9-8000.uks1.devtunnels.ms/paydunya/cancel

# Informations du marchand
PAYDUNYA_STORE_NAME="Al-Amine Medical Center"
PAYDUNYA_POSTAL_ADDRESS="Dakar, Sénégal"
PAYDUNYA_PHONE="+221 XX XXX XX XX"
```

---

## 📝 Comment obtenir vos clés API PayDunya

1. Connectez-vous à https://paydunya.com
2. Allez dans **Settings > API Keys**
3. Copiez vos clés :
   - Master Key
   - Private Key
   - Public Key
   - Token

4. En mode **TEST** :
   - Utilisez les clés de test fournies
   - Tous les paiements sont simulés
   
5. En mode **LIVE** (Production) :
   - Changez `PAYDUNYA_MODE=live`
   - Utilisez vos vraies clés API
   - Mettez à jour les URLs avec votre vrai domaine

---

## 🧪 Tester l'intégration

### 1. Vérifier que les routes sont accessibles

```bash
# Vérifier les routes PayDunya
php artisan route:list --path=paydunya
```

Résultat attendu :
```
POST   paydunya/ipn     → PaydunyaWebhookController@handleIPN
GET    paydunya/return  → PaydunyaWebhookController@paymentReturn
GET    paydunya/cancel  → PaydunyaWebhookController@paymentCancel
```

### 2. Tester l'URL IPN manuellement

Utilisez Postman ou curl pour envoyer un POST à :
```
https://gzp8qzp9-8000.uks1.devtunnels.ms/paydunya/ipn
```

Body (JSON) :
```json
{
    "status": "completed",
    "transaction_id": "TEST123",
    "custom_data": "{\"paiement_id\":1,\"demande_rdv_id\":1}"
}
```

### 3. Vérifier les logs

Les logs se trouvent dans `storage/logs/laravel.log`

Recherchez les entrées :
```
[2025-11-08] PayDunya IPN received
[2025-11-08] PayDunya IPN: Payment successful
```

---

## 🔄 Flux de paiement

1. **Patient demande un RDV** et choisit "Paiement en ligne"
2. **Système crée une invoice** via PaydunyaService
3. **Patient est redirigé** vers la page de paiement PayDunya
4. **Patient effectue le paiement** (Wave, Orange Money, Carte)
5. **PayDunya envoie une notification** à `/paydunya/ipn`
6. **Système met à jour** le statut du paiement
7. **Patient reçoit une notification** email + database
8. **Patient est redirigé** vers `/paydunya/return`

---

## 🛠️ Dépannage

### L'IPN ne reçoit pas les notifications

1. Vérifiez que le tunnel est actif :
   ```bash
   curl https://gzp8qzp9-8000.uks1.devtunnels.ms/paydunya/ipn
   ```

2. Vérifiez les logs Laravel :
   ```bash
   tail -f storage/logs/laravel.log
   ```

3. Vérifiez que l'URL IPN est bien configurée dans PayDunya Dashboard

### Erreur CSRF Token Mismatch

✅ **RÉSOLU** - Les routes `paydunya/*` sont exclues du CSRF dans `bootstrap/app.php`

### Le paiement ne se met pas à jour

1. Vérifiez la table `paiements` :
   ```sql
   SELECT * FROM paiements ORDER BY created_at DESC LIMIT 5;
   ```

2. Vérifiez que le `paiement_id` dans `custom_data` correspond

---

## 📞 Support

- Documentation PayDunya : https://paydunya.com/developers
- Support PayDunya : support@paydunya.com
- Logs système : `storage/logs/laravel.log`

---

## ✅ Checklist de mise en production

- [ ] Obtenir les clés API de production
- [ ] Changer `PAYDUNYA_MODE=live`
- [ ] Remplacer le tunnel par le vrai domaine dans toutes les URLs
- [ ] Tester un paiement réel en mode live
- [ ] Configurer les emails de notification
- [ ] Activer les sauvegardes de la base de données
- [ ] Mettre en place la surveillance des logs
