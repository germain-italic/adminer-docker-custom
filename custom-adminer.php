<?php

// Inclut Adminer pour charger les classes
include "./adminer.php";

class AdminerCustomSort extends \Adminer\Plugin {
    
    function selectOrderPrint($order, $columns, $indexes) {
        // Si aucun ordre spécifié, force DESC sur la clé primaire
        if (!$order) {
            // Trouve la clé primaire
            foreach ($indexes as $index) {
                if ($index["type"] == "PRIMARY") {
                    $primary_key = $index["columns"][0];
                    echo "<a href='" . h(remove_from_uri("order") . "&order%5B0%5D=" . urlencode($primary_key) . "&desc%5B0%5D=1") . "'>$primary_key</a> DESC";
                    return;
                }
            }
            // Si pas de clé primaire, utilise la première colonne
            if ($columns) {
                $first_column = array_keys($columns)[0];
                echo "<a href='" . h(remove_from_uri("order") . "&order%5B0%5D=" . urlencode($first_column) . "&desc%5B0%5D=1") . "'>$first_column</a> DESC";
                return;
            }
        }
        
        // Sinon, comportement par défaut
        parent::selectOrderPrint($order, $columns, $indexes);
    }
    
    function selectQuery($query, $start, $failed = false) {
        // Ajoute ORDER BY DESC si aucun ordre spécifié
        if (!preg_match('/ORDER\s+BY/i', $query) && !$_GET["order"]) {
            // Cherche une colonne id ou clé primaire
            if (preg_match('/SELECT.*\b(id|.*_id)\b/i', $query, $matches)) {
                $id_column = trim($matches[1]);
                $query = rtrim($query, ';') . " ORDER BY `$id_column` DESC";
            }
            // Sinon première colonne
            else if (preg_match('/SELECT\s+(.+?)\s+FROM/i', $query, $matches)) {
                $columns = trim($matches[1]);
                if ($columns !== '*') {
                    $first_col = explode(',', $columns)[0];
                    $first_col = trim(str_replace(['`', ' '], '', $first_col));
                    if ($first_col && $first_col !== '*') {
                        $query = rtrim($query, ';') . " ORDER BY `$first_col` DESC";
                    }
                }
            }
        }
        return $query;
    }
}

// Configuration des plugins
$plugins = [new AdminerCustomSort()];