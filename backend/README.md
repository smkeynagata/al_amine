# Hôpital Al-Amine - Système de Gestion de Rendez-vous Médicaux(pro)

## 📋 Description

Application web complète de gestion de rendez-vous médicaux pour l'Hôpital Al-Amine au Sénégal. Développée avec **Laravel 12**, **Blade**, **Tailwind CSS**, **Alpine.js** et **PostgreSQL**.

## 🚀 Technologies Utilisées

- **Backend**: Laravel 12
- **Base de données**: PostgreSQL
- **Frontend**: Blade Templates, Tailwind CSS, Alpine.js
- **Authentification**: Laravel Breeze
- **PDF**: DomPDF
- **Bibliothèques JS**: FullCalendar.js, Chart.js, SweetAlert2

## 👥 Rôles Utilisateurs

### 1. PATIENT
- Demander des rendez-vous en ligne
- Consulter ses demandes et rendez-vous
- Voir ses factures
- Payer en ligne (Wave, Orange Money, Free Money, Espèces, Carte bancaire)

### 2. SECRÉTAIRE
- Gérer la file d'attente des demandes
- Valider/Refuser les demandes de RDV
- Gérer les agendas des praticiens
- Générer les factures
- Encaisser les paiements

### 3. PRATICIEN
- Consulter son agenda
- Configurer ses disponibilités
- Réaliser des consultations
- Rédiger des ordonnances
- Voir ses statistiques

### 4. ADMIN
- Dashboard avec statistiques et graphiques
- CRUD des utilisateurs
- Voir tous les agendas
- Générer des rapports (activité, financier)
- Consulter l'audit trail

## 📊 Structure de la Base de Données

### Tables Principales

1. **users** - Utilisateurs (admin, patients, praticiens, secrétaires)
2. **patients** - Informations spécifiques aux patients
3. **praticiens** - Informations spécifiques aux praticiens
4. **secretaires** - Informations spécifiques aux secrétaires
5. **specialites** - Spécialités médicales
6. **services** - Services hospitaliers
7. **demande_rdvs** - Demandes de rendez-vous
8. **rendez_vous** - Rendez-vous confirmés
9. **consultations** - Consultations médicales
10. **ordonnances** - Ordonnances médicales
11. **factures** - Factures
12. **paiements** - Paiements
13. **disponibilites** - Disponibilités des praticiens
14. **audit_trails** - Traçabilité des actions

## 🔧 Installation

### Prérequis

- PHP 8.2+
- Composer
- PostgreSQL
- Node.js & NPM

### Étapes d'installation

```bash
# 1. Cloner le projet
cd /home/asspro/Téléchargements/l3/Al-amine/al-amine

# 2. Installer les dépendances PHP
composer install

# 3. Configurer l'environnement
cp .env.example .env
php artisan key:generate

# 4. Configurer la base de données dans .env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=al-amine
DB_USERNAME=postgres
DB_PASSWORD=1964

# 5. Exécuter les migrations et seeders
php artisan migrate:fresh --seed

# 6. Installer les dépendances frontend
npm install

# 7. Compiler les assets
npm run build

# 8. Lancer le serveur
php artisan serve
```

L'application sera accessible sur `http://localhost:8000`

## 👤 Comptes de Test

### Administrateur
- **Email**: admin@alamine.sn
- **Mot de passe**: password

### Secrétaire 1
- **Email**: secretaire1@alamine.sn
- **Mot de passe**: password

### Secrétaire 2
- **Email**: secretaire2@alamine.sn
- **Mot de passe**: password

### Praticiens
- **Email**: praticien1@alamine.sn (Médecine Générale)
- **Email**: praticien2@alamine.sn (Cardiologie)
- **Email**: praticien3@alamine.sn (Pédiatrie)
- **Email**: praticien4@alamine.sn (Dermatologie)
- **Email**: praticien5@alamine.sn (Gynécologie)
- **Mot de passe**: password

### Patients
- **Email**: patient1@example.sn à patient30@example.sn
- **Mot de passe**: password

## 💰 Spécialités et Tarifs

| Spécialité | Code | Tarif |
|------------|------|-------|
| Médecine Générale | MG | 10 000 FCFA |
| Cardiologie | CARDIO | 20 000 FCFA |
| Pédiatrie | PED | 15 000 FCFA |
| Dermatologie | DERM | 18 000 FCFA |
| Gynécologie | GYNO | 17 000 FCFA |

## 🏥 Services Hospitaliers

1. **Médecine Interne** - Bâtiment A, 1er étage
2. **Pédiatrie** - Bâtiment B, Rez-de-chaussée
3. **Cardiologie** - Bâtiment A, 2ème étage

## 🌍 Fonctionnalités Spécifiques au Sénégal

- **Validation téléphone**: Format sénégalais (77/78/76/70/33 + 7 chiffres)
- **Validation CNI**: 13 chiffres
- **Quartiers de Dakar**: Plateau, Médina, Parcelles Assainies, Liberté 6, Mermoz, HLM Grand Yoff, etc.
- **Affichage montants**: Format FCFA avec espaces (ex: "15 000 FCFA")
- **Méthodes de paiement**: Wave, Orange Money, Free Money, Espèces, Carte bancaire

## 🔒 Sécurité

- Authentification via Laravel Breeze
- Middleware de vérification des rôles
- Audit trail pour tracer toutes les actions
- Hashage des mots de passe avec Bcrypt
- Protection CSRF

## 📱 Responsive Design

- **Desktop** (1400px+) : Sidebar 260px + Main area full
- **Laptop** (1000px-1400px) : Sidebar 220px réduit
- **Tablette** (768px-1000px) : Sidebar horizontal sticky top
- **Mobile** (320px-768px) : Sidebar collapsé en nav icons + cartes en grid simple
- **Tous écrans** : Navigation fluide, pas de débordement, scrollable adapté

## 🛠️ Helpers Functions

```php
formatCurrency($amount)           // Formate les montants en FCFA
formatPhone($phone)               // Formate les numéros de téléphone
calculateAge($date_naissance)     // Calcule l'âge
generateReference($prefix)        // Génère une référence unique
quartiersDatekar()               // Retourne la liste des quartiers de Dakar
getStatutBadgeClass($statut)     // Retourne la classe CSS pour les badges de statut
```

## 📈 Workflow Typique

1. **Patient** demande un RDV en ligne
2. **Secrétaire** valide la demande et crée le RDV confirmé
3. RDV confirmé apparaît dans l'agenda du **Praticien**
4. **Praticien** voit le RDV en section "Aujourd'hui" (cartes visuelles)
5. **Praticien** confirme → passé au statut "Confirmé"
6. **Praticien** termine → passé au statut "Terminé" avec date/heure
7. **Secrétaire** génère la facture basée sur tarif + consultation
8. **Patient** effectue le paiement en ligne (Wave, Orange Money, etc.)
9. Reçu généré automatiquement et archivé

### Workflow Praticien Détaillé
- ✅ **Dashboard aujourd'hui** : Voir tous les RDV du jour en cartes colorées
- ✅ **Actions rapides** : Confirmer, Terminer, Annuler directement depuis les cartes
- ✅ **Agenda 30j** : Vue chronologique complète tous les futurs RDV
- ✅ **Disponibilités** : Paramétrer plages horaires (lun-ven 9h-17h par défaut)
- ✅ **À venir** : Tableau pour anticiper consultations de la semaine
- ✅ **Historique** : Archives de tous les RDV (confirmés, terminés, annulés)

## 🎨 Design System

### Architecture UI/UX Praticien
- **Sidebar sombre** (gradient bleu nuit) avec navigation fluide
- **Section "Aujourd'hui"** en hero avec cartes RDV interactives
- **Topbar minimaliste** affichant date/heure et infos utilisateur
- **Sections emboîtées** : Agenda, Disponibilités, À venir, Historique
- **Cards RDV** avec bordure colorée par statut (left-border)
- **Tables modernes** avec hover effects et status chips
- **Responsive adaptatif** : Sidebar horizontal sur mobile

### Couleurs Principales
- **Bleu primaire**: #3B82F6 (actions, accents)
- **Bleu nuit**: #0f172a (sidebar, headers)
- **Vert succès**: #10B981 (confirmé)
- **Orange/Ambre**: #F59E0B (en attente)
- **Rouge erreur**: #EF4444 (annulé/refusé)
- **Vert foncé**: #047857 (terminé)

### Statuts et Couleurs
- `EN_ATTENTE` / `PLANIFIE` / `BROUILLON` → **Jaune (#F59E0B)**
- `CONFIRMEE` / `CONFIRME` / `VALIDE` / `EMISE` → **Vert (#10B981)**
- `EN_COURS` → **Bleu (#3B82F6)**
- `TERMINE` / `PAYEE` → **Vert foncé (#047857)**
- `REFUSEE` / `ANNULE` / `ECHOUE` → **Rouge (#EF4444)**

## 📝 Licence

Application développée pour l'Hôpital Al-Amine - Tous droits réservés © 2025

## 👨‍💻 Auteur

Développé pour une soutenance universitaire - Projet de gestion hospitalière au Sénégal

---

**Note**: Cette application est une version complète et fonctionnelle prête pour des captures d'écran professionnelles pour une soutenance universitaire.

