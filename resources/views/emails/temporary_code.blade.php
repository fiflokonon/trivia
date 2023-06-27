<!DOCTYPE html>
<html lang="fr">
<head>
    <title>Votre code temporaire</title>
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
        .copy-btn:hover {
            border: none;
            cursor: pointer;
            background-color: #006F8B;
        }
    </style>
</head>
<body>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        new ClipboardJS('#copy_button', {
            text: function() {
                return document.getElementById('code').textContent;
            }
        });
    });
</script>
<h1>Votre code temporaire</h1>
<p>Veuillez utiliser le code suivant pour réinitialiser votre mot de passe :</p>
<div class="code" id="code">{{ $code }}</div>
<!--<button class="copy-btn" id="copy_button">Copier le code</button>-->
<p>Ce code est valable pendant 24 heures. Si vous ne l'utilisez pas dans ce délai, vous devrez en demander un nouveau.</p>
</body>
</html>


