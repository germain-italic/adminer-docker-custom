<?php
namespace docker {
    class AdminerCustomSort extends \Adminer\Plugin {
        function selectQueryBuild($select, $where, $group, $order, $limit, $page) {
            // Si aucun ordre spécifié, ajoute un tri DESC sur la première colonne qui ressemble à un ID
            if (!$order) {
                // Essaie de trouver une colonne ID dans les colonnes sélectionnées
                foreach ($select as $key => $val) {
                    if (preg_match('/\bid\b/i', $key) || preg_match('/\w*id$/i', $key)) {
                        $order = array($key => true); // true = DESC
                        break;
                    }
                }
                // Si pas d'ID trouvé, utilise la première colonne
                if (!$order && $select) {
                    $first_column = array_keys($select)[0];
                    $order = array($first_column => true); // true = DESC
                }
            }
            return parent::selectQueryBuild($select, $where, $group, $order, $limit, $page);
        }
    }

    // Retourne une instance du plugin pour le système de plugins d'Adminer
    return new AdminerCustomSort;
}