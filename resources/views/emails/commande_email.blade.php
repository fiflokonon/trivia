<!DOCTYPE html>
<html lang="fr">
<head>
    <title>Votre code temporaire</title>
    <!-- Notez que vous devez inclure la bibliothèque ClipboardJS pour utiliser le bouton de copie -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/2.0.8/clipboard.min.js"></script>
    <style>
        body {
            font-family: sans-serif;
            color: #444;
            line-height: 1.4;
        }
        h1 {
            font-size: 24px;
            margin-bottom: 20px;
        }
        p {
            font-size: 16px;
            margin-bottom: 20px;
        }
        .code {
            display: inline-block;
            background-color: #f1f1f1;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 20px;
            font-weight: bold;
        }
        .copy-btn {
            display: inline-block;
            background-color: #008CBA;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s ease-in-out;
            margin-left: 20px;
        }
    </style>
</head>
<body>
<h1>Facture de votre commande passée sur <b>TRIVIA</b></h1>
<p>Voici les détails de la commande</p>
<div>
    <h3 style="text-decoration: underline">Numéro de la commande:</h3> <span>{{$code}}</span><br>
    <p>Ci-jointe la facture de la commande</p>
</div>
</body>
</html>



