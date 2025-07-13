<?php

/**
 * Adminer avec plugin DESC Sort intégré
 * 
 * Installation simple: remplacer le fichier index.php d'Adminer par ce fichier
 * 
 * @author italic
 * @version 1.0.0
 * @link https://github.com/germain-italic/adminer-docker-custom
 */

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

// Inclure Adminer
if (file_exists('adminer.php')) {
    include 'adminer.php';
} elseif (file_exists('adminer-4.8.1.php')) {
    include 'adminer-4.8.1.php';
} else {
    // Télécharger Adminer automatiquement si pas présent
    $adminer_url = 'https://github.com/vrana/adminer/releases/download/v4.8.1/adminer-4.8.1.php';
    $adminer_content = file_get_contents($adminer_url);
    if ($adminer_content) {
        file_put_contents('adminer-4.8.1.php', $adminer_content);
        include 'adminer-4.8.1.php';
    } else {
        die('Erreur: Impossible de charger Adminer. Téléchargez adminer.php manuellement.');
    }
}