<?php
namespace Adminer;

// Inclut Adminer pour charger les classes
include "./adminer.php";

class AdminerCustomSort extends Plugin {
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

// Configuration des plugins
$plugins = [new AdminerCustomSort()];