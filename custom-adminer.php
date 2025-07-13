<?php

/** @return AdminerPlugin */
function adminer_object() {
    include_once "./plugins/plugin.php";
    
    class AdminerCustomSort extends AdminerPlugin {
        function selectOrderPrint($order, $columns, $indexes) {
            return "";
        }
        
        function selectQuery($query, $start, $failed = false) {
            // Ajoute ORDER BY si pas présent
            if (!preg_match('/ORDER\s+BY/i', $query)) {
                // Cherche une colonne id
                if (preg_match('/SELECT.*\bid\b/i', $query)) {
                    $query = rtrim($query, ';') . " ORDER BY id DESC";
                }
                // Sinon cherche la première colonne
                else if (preg_match('/SELECT\s+(.+?)\s+FROM/i', $query, $matches)) {
                    $columns = trim($matches[1]);
                    if ($columns !== '*') {
                        $first_col = explode(',', $columns)[0];
                        $first_col = trim(str_replace('`', '', $first_col));
                        $query = rtrim($query, ';') . " ORDER BY `$first_col` DESC";
                    }
                }
            }
            return $query;
        }
    }
    
    return new AdminerCustomSort;
}

include "./adminer.php";