#!/bin/bash

# Script de test IPN PayDunya
# Ce script simule un appel IPN de PayDunya vers votre serveur

# ========================================
# CONFIGURATION - À MODIFIER SELON VOTRE CAS
# ========================================

# URL de votre endpoint IPN (remplacez par votre URL ngrok)
IPN_URL="https://8339f9a45580.ngrok-free.app/paydunya/ipn"

# Vous pouvez aussi utiliser ngrok si démarré :
# IPN_URL="https://abcd1234.ngrok.io/paydunya/ipn"

# IDs à utiliser (modifiez selon vos besoins)
PAIEMENT_ID=20
DEMANDE_RDV_ID=33
PATIENT_ID=41
TRANSACTION_ID="TEST_$(date +%s)"

# ========================================
# TEST 1: Paiement COMPLÉTÉ (succès)
# ========================================
echo "📡 Test 1: Simulation IPN - Paiement COMPLÉTÉ"
echo "=============================================="
echo ""

curl -X POST "$IPN_URL" \
  -H "Content-Type: application/x-www-form-urlencoded" \
  -d "status=completed" \
  -d "transaction_id=$TRANSACTION_ID" \
  -d "custom_data={\"paiement_id\":$PAIEMENT_ID,\"demande_rdv_id\":$DEMANDE_RDV_ID,\"patient_id\":$PATIENT_ID}" \
  -v

echo ""
echo ""
echo "✅ Test 1 terminé. Vérifiez les logs avec: tail -f storage/logs/laravel.log"
echo ""
echo "Vérifiez aussi la base de données:"
echo "  - Paiement #$PAIEMENT_ID devrait avoir statut=PAYE"
echo "  - DemandeRdv #$DEMANDE_RDV_ID devrait avoir statut=EN_ATTENTE et paiement_effectue=true"
echo ""
read -p "Appuyez sur Entrée pour continuer avec le Test 2..."

# ========================================
# TEST 2: Paiement ANNULÉ
# ========================================
echo ""
echo "📡 Test 2: Simulation IPN - Paiement ANNULÉ"
echo "=============================================="
echo ""

TRANSACTION_ID_2="TEST_CANCELLED_$(date +%s)"

curl -X POST "$IPN_URL" \
  -H "Content-Type: application/x-www-form-urlencoded" \
  -d "status=cancelled" \
  -d "transaction_id=$TRANSACTION_ID_2" \
  -d "custom_data={\"paiement_id\":$PAIEMENT_ID,\"demande_rdv_id\":$DEMANDE_RDV_ID}" \
  -v

echo ""
echo ""
echo "✅ Test 2 terminé. Le paiement devrait être marqué comme ECHOUE"
echo ""
read -p "Appuyez sur Entrée pour continuer avec le Test 3..."

# ========================================
# TEST 3: Paiement ÉCHOUÉ
# ========================================
echo ""
echo "📡 Test 3: Simulation IPN - Paiement ÉCHOUÉ"
echo "=============================================="
echo ""

TRANSACTION_ID_3="TEST_FAILED_$(date +%s)"

curl -X POST "$IPN_URL" \
  -H "Content-Type: application/x-www-form-urlencoded" \
  -d "status=failed" \
  -d "transaction_id=$TRANSACTION_ID_3" \
  -d "custom_data={\"paiement_id\":$PAIEMENT_ID,\"demande_rdv_id\":$DEMANDE_RDV_ID}" \
  -v

echo ""
echo ""
echo "✅ Test 3 terminé. Le paiement devrait être marqué comme ECHOUE"
echo ""
echo "=========================================="
echo "📊 RÉSUMÉ DES TESTS"
echo "=========================================="
echo ""
echo "Vérifications à faire:"
echo "  1. Logs: tail -f storage/logs/laravel.log"
echo "  2. Base de données:"
echo "     php artisan tinker --execute=\"\\App\\Models\\Paiement::find($PAIEMENT_ID)\""
echo "     php artisan tinker --execute=\"\\App\\Models\\DemandeRdv::find($DEMANDE_RDV_ID)\""
echo ""
echo "🎯 Ce que vous devriez voir:"
echo "  - Test 1: Paiement PAYE, Demande EN_ATTENTE, paiement_effectue=true"
echo "  - Test 2 & 3: Paiement ECHOUE"
echo ""
