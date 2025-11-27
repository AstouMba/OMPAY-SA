# Guide de Test OmPay - Clients et Comptes

## Clients de Test Créés

Après avoir exécuté les seeders, vous aurez accès aux clients suivants pour tester les transactions :

### 1. **Astou Mbow**
- **Téléphone** : `+221781157773` (existant)
- **Email** : `astou.odc@gmail.com`
- **Solde** : 150 000 FCFA
- **Comptes** : OmPay activé

### 2. **Amadou Diallo**
- **Téléphone** : `+221701234567` (nouveau)
- **Email** : `amadou.diallo@gmail.com`
- **Solde** : 200 000 FCFA
- **Comptes** : OmPay activé

### 3. **Fatou Ndiaye**
- **Téléphone** : `+221702345678` (nouveau)
- **Email** : `fatou.ndiaye@gmail.com`
- **Solde** : 75 000 FCFA
- **Comptes** : OmPay activé

### 4. **Oumar Sarr**
- **Téléphone** : `+221703456789` (nouveau)
- **Email** : `oumar.sarr@gmail.com`
- **Solde** : 500 000 FCFA
- **Comptes** : OmPay activé

### 5. **Aisha Ba**
- **Téléphone** : `+221704567890` (nouveau)
- **Email** : `aisha.ba@gmail.com`
- **Solde** : 10 000 FCFA
- **Comptes** : OmPay activé

## Marchands de Test

Les marchands suivants sont également disponibles pour les tests de paiement :

1. **Magasin Mama Fall** - `+221700000001` (Code: M123456)
2. **Épicerie Diamono** - `+221700000002` (Code: M123457)
3. **Librairie Keur Serigne Touba** - `+221700000003` (Code: M123458)
4. **Restaurant Le Djolof** - `+221700000004` (Code: M123459)
5. **Pharmacie Gandhi** - `+221700000005` (Code: M123460)

## Comment Tester

### 1. Authentification
```bash
# Connexion Astou Mbow
curl -X POST "https://ompay-sa.onrender.com/api/v1/compte/login" \
  -H "Content-Type: application/json" \
  -d '{"telephone": "+221781157773"}'

# Connexion Amadou Diallo
curl -X POST "https://ompay-sa.onrender.com/api/v1/compte/login" \
  -H "Content-Type: application/json" \
  -d '{"telephone": "+221701234567"}'
```

### 2. Récupérer le Profil
```bash
# Obtenir le profil avec solde et QR code
curl -X GET "https://ompay-sa.onrender.com/api/v1/compte/me" \
  -H "Authorization: Bearer YOUR_TOKEN"
```

### 3. Effectuer des Transactions

#### Paiement vers un marchand
```bash
curl -X POST "https://ompay-sa.onrender.com/api/v1/compte/transactions/payment" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{"code_marchand": "M123456", "montant": 5000}'
```

#### Transfert entre clients
```bash
curl -X POST "https://ompay-sa.onrender.com/api/v1/compte/transactions/transfert" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{"numero_ompay": "+221701234567", "montant": 10000}'
```

## Exécuter les Seeders

Pour créer ces données de test, exécutez :

```bash
# Dans votre terminal
php artisan migrate:fresh --seed

# Ou individuellement
php artisan db:seed --class=ClientSeeder
php artisan db:seed --class=CompteSeeder
php artisan db:seed --class=MarchandSeeder
php artisan db:seed --class=TransactionSeeder
```

## Données de Test

Chaque client a des transactions de démonstration :
- Dépôts initiaux
- Retraits
- Paiements aux marchands
- Transferts entre clients

Ces transactions permettent de tester le calcul automatique du solde et l'affichage dans l'application Flutter.