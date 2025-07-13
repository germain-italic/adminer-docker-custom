<?php
namespace docker {
    class AdminerCustomSort extends \Adminer\Plugin {
        function selectQuery($query, $start, $failed = false) {
            // Modifie la requête pour ajouter ORDER BY si pas présent
            if (!preg_match('/ORDER\s+BY/i', $query)) {
                // Trouve la table dans la requête
                if (preg_match('/FROM\s+`?(\w+)`?/i', $query, $matches)) {
                    $table = $matches[1];
                    
                    // Essaie de trouver la clé primaire via une requête
                    try {
                        $connection = connection();
                        if ($connection) {
                            $result = $connection->query("SHOW KEYS FROM `$table` WHERE Key_name = 'PRIMARY'");
                            if ($result && $row = $result->fetch_assoc()) {
                                $primary_column = $row['Column_name'];
                                $query = rtrim($query, ';') . " ORDER BY `$primary_column` DESC";
                            }
                        }
                    } catch (Exception $e) {
                        // Si on ne peut pas déterminer la clé primaire, on essaie avec 'id'
                        if (preg_match('/SELECT.*\bid\b/i', $query)) {
                            $query = rtrim($query, ';') . " ORDER BY `id` DESC";
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