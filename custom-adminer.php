<?php
namespace docker {
    class AdminerCustomSort extends \Adminer\Plugin {
        function selectQuery($query, $start, $failed = false) {
            // Modifie la requête pour ajouter ORDER BY si pas présent
            if (!preg_match('/ORDER\s+BY/i', $query)) {
                // Essaie de détecter une colonne 'id' dans la requête
                if (preg_match('/SELECT.*\bid\b/i', $query)) {
                    $query = rtrim($query, ';') . " ORDER BY `id` DESC";
                }
                // Sinon, essaie de trouver la première colonne qui ressemble à une clé primaire
                else if (preg_match('/SELECT\s+(.+?)\s+FROM/i', $query, $matches)) {
                    $columns = $matches[1];
                    // Cherche des patterns de clés primaires communes
                    if (preg_match('/\b(\w*id\w*)\b/i', $columns, $id_match)) {
                        $query = rtrim($query, ';') . " ORDER BY `" . $id_match[1] . "` DESC";
                    }
                    // Fallback : utilise la première colonne
                    else if (preg_match('/`?(\w+)`?/', $columns, $first_col)) {
                        $query = rtrim($query, ';') . " ORDER BY `" . $first_col[1] . "` DESC";
                    }
                }
            }
            return parent::selectQuery($query, $start, $failed);
        }
    }

    // Retourne une instance du plugin pour le système de plugins d'Adminer
    return new AdminerCustomSort;
}