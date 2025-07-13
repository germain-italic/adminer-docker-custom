<?php

// Force DESC par défaut si aucun ordre spécifié
if (!isset($_GET["order"]) && isset($_GET["select"])) {
    // Redirige avec ordre DESC sur la première colonne
    if (!headers_sent()) {
        $current_url = $_SERVER['REQUEST_URI'];
        if (strpos($current_url, 'order') === false) {
            // Ajoute order[0]=id&desc[0]=1 à l'URL
            $separator = strpos($current_url, '?') !== false ? '&' : '?';
            $new_url = $current_url . $separator . 'order%5B0%5D=id&desc%5B0%5D=1';
            header("Location: $new_url");
            exit;
        }
    }
}

// Inclut Adminer pour charger les classes
include "./adminer.php";

class AdminerCustomSort extends \Adminer\Plugin {
    // Plugin vide pour l'instant, la logique est dans le code ci-dessus
}

// Configuration des plugins
$plugins = [new AdminerCustomSort()];