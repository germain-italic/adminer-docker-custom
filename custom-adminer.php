<?php
// Plugin Adminer pour tri DESC par défaut
function adminer_object() {
    include_once "adminer.php";
    
    class AdminerCustom extends Adminer {
        function selectOrderProcess($fields, $indexes) {
            $order = parent::selectOrderProcess($fields, $indexes);
            
            // Si aucun ordre spécifié, utilise la clé primaire en DESC
            if (!$order) {
                foreach ($indexes as $index) {
                    if ($index["type"] == "PRIMARY") {
                        return array($index["columns"][0] => "DESC");
                    }
                }
            }
            
            return $order;
        }
    }
    
    return new AdminerCustom;
}
?>