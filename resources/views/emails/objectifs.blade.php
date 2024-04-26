<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
</head>
<body>
<h2>Objectif atteint</h2>

<p>L'utilisateur ci-dessous a atteint son objectif de {{$objectif}} parrainages: </p>

<ul>
    <li><strong>Id de l'utilisateur</strong> : {{ $user->id  }}</li>
    <li><strong>Téléphone</strong> : {{ $user->email }}</li>
    <li><strong>Commune</strong> : {{ $user->commune }}</li>
    <li><strong>Code de parrainage</strong> : {{ $user->code_affiliation }}</li>
</ul>

</body>
</html>
