<?php
namespace docker {
    class AdminerCustomSort extends \Adminer\Plugin {
        function selectOrderPrint($order, $columns, $indexes) {
            // Si aucun ordre spécifié, utilise la clé primaire en DESC
            if (!$order) {
                foreach ($indexes as $index) {
                    if ($index["type"] == "PRIMARY") {
                        $primary_column = $index["columns"][0];
                        return "<a href='" . \h(\remove_from_uri("order")) . "&amp;order%5B0%5D=" . urlencode($primary_column) . "&amp;desc%5B0%5D=1'>" . \h($primary_column) . " ↓</a>";
                    }
                }
            }
            return parent::selectOrderPrint($order, $columns, $indexes);
        }
        
        function selectQuery($query, $start, $failed = false) {
            // Modifie la requête pour ajouter ORDER BY si pas présent
            if (!preg_match('/ORDER\s+BY/i', $query)) {
                // Trouve la table dans la requête
                if (preg_match('/FROM\s+`?(\w+)`?/i', $query, $matches)) {
                    $table = $matches[1];
                    $indexes = \indexes($table);
                    foreach ($indexes as $index) {
                        if ($index["type"] == "PRIMARY") {
                            $primary_column = $index["columns"][0];
                            $query = rtrim($query, ';') . " ORDER BY `$primary_column` DESC";
                            break;
                        }
                    }
                }
            }
            return parent::selectQuery($query, $start, $failed);
        }
    }

    // Retourne une instance du plugin pour le système de plugins d'Adminer
    return new AdminerCustomSort;
}