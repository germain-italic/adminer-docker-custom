<?php

/**
 * Adminer Plugin: Default DESC Sort
 * 
 * Plugin universel pour forcer le tri DESC par défaut sur la colonne 'id'
 * Compatible avec Docker et installations vanilla
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

/**
 * Plugin class pour Adminer (optionnel)
 */
class AdminerDescSort {
    
    function name() {
        return "Default DESC Sort";
    }
    
    function version() {
        return "1.0.0";
    }
    
    function description() {
        return "Automatically sorts table data in DESC order on 'id' column by default";
    }
}

// Retourner une instance du plugin si utilisé avec le système de plugins d'Adminer
if (class_exists('Adminer')) {
    return new AdminerDescSort;
}