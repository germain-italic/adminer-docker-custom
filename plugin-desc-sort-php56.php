<?php

/**
 * Adminer Plugin: Default DESC Sort - PHP 5.6 Compatible
 * 
 * Plugin compatible PHP 5.6+ pour forcer le tri DESC par défaut sur la colonne 'id'
 * Version simplifiée sans namespaces ni fonctionnalités PHP 7+
 * 
 * @author italic
 * @version 1.1.0
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
            header("Location: " . $new_url);
            exit;
        }
    }
}

// Le reste du code Adminer sera inclus après