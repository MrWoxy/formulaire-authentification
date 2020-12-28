<?php

use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Twig\Extension\DebugExtension;

require __DIR__.'/../vendor/autoload.php';

$loader = new FilesystemLoader(__DIR__.'/../templates');

$twig = new Environment($loader, [
    'debug' => true,
    'strict_variables' => true,
]);

$twig->addExtension(new DebugExtension());

dump($_POST);

$formData = [
    'login' => '',
    'password' => '',
];

$errors = [];

if ($_POST) {
    foreach($formData as $key => $value) {
        if(isset($_POST[$key])) {
            $formData[$key]=$_POST[$key];
        }
    }

    $minLengthLogin = 3;
    $maxLengthLogin = 190;

    if (empty($_POST['login'])) {
        $errors['login'] = 'Merci de remplir champ';
    }  elseif (strlen($_POST['login']) < 3 || (strlen($_POST['login']) > 190)) {
        $errors['login'] = "Merci de renseigner un login dont la longueur est comprise entre {$minLengthLogin} et {$maxLengthLogin} caractères inclus";
    }

    $minLengthPassword = 8;
    $maxLengthPassword = 32;

    if (empty($_POST['password'])) {
        $errors['password'] = 'Merci de renseigner ce champ';
    } elseif (strlen($_POST['password']) < 8 || strlen($_POST['password']) > 32) {
        $errors['password'] = "Merci de renseigner un password dont la longueur est comprise entre {$minLengthPassword} et {$maxLengthPassword} inclus";
    } elseif (preg_match('/[^A-Za-z]/', $_POST['password']) === 0 ){
        $errors['password'] = "Merci de renseigner un mot de passe composé de lettre d'alphabet sans accent";
    } elseif (preg_match('/[^A-Za-z0-9]/', $_POST['password']) === 0) {
        $errors['password'] = "Merci de renseigner un mot de passe avec minimum 1 caractère spécial";
    } elseif (preg_match('/[0-9]/', $_POST['password']) === 0 ) {
        $errors['password'] = "Merci de renseigner un mot de passe avec 1 chiffre";
    }
}

echo $twig->render('validation.html.twig', [
    'errors' => $errors,
    'formData' => $formData,
]);

// Il y a une incompréhension au niveau de l'énoncé par rapport au message d'erreur, tu nous dis de mettre un message générique mais en dessous un message détaillé.