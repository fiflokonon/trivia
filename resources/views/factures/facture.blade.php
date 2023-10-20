<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Facture - N° {{ $numero_panier }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
            line-height: 1.6;
        }
        .container {
            width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .invoice-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .invoice-info .info-item {
            flex: 1;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
        }
        .table th, .table td {
            border: 1px solid #ccc;
            padding: 8px;
        }
        .table th {
            background-color: #f2f2f2;
            text-align: left;
        }
        .total {
            margin-top: 20px;
            text-align: right;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <h1>Facture</h1>
        <p>N° de commande : {{ $numero_panier }}</p>
    </div>

    <div class="invoice-info">
        <div class="info-item">
            <strong>Expéditeur :</strong><br>
            TRIVIA<br>
            Adresse<br>
            Ville, Pays<br>
            Téléphone : +000000000000
        </div>
        <div class="info-item">
            <strong>Destinataire :</strong><br>
            {{$nom}}<br>
            {{ $contact}}<br>
            {{ $email }}
        </div>
    </div>

    <table class="table">
        <thead>
        <tr>
            <th>Produit</th>
            <th>Prix unitaire</th>
            <th>Quantité</th>
            <th>Total</th>
        </tr>
        </thead>
        <tbody>
        @php
            $produits = json_decode($produits, true);
        @endphp
        @foreach ($produits as $produit)
            <tr>
                <td>{{ $produit['nom_produit'] }}</td>
                <td>{{ $produit['prix'] }} €</td>
                <td>{{ $produit['quantite'] }}</td>
                <td>{{ $produit['prix'] * $produit['quantite'] }} €</td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <div class="total">
        <strong>Sous-total :</strong> {{ $sous_total}} €
    </div>
    <div class="total">
        <strong>Frais fournisseur :</strong> {{ $frais_fournisseur }} €
    </div>
    <div class="total">
        <strong>Frais TRIVIA :</strong> {{ $frais_trivia }} €
    </div>
    <div class="total">
        <strong>Total :</strong> {{ $sous_total + $frais_fournisseur + $frais_trivia }} €
    </div>
</div>
</body>
</html>

