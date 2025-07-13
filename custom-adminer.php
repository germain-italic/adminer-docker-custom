<?php
// Plugin Adminer pour tri DESC par défaut
class AdminerCustomSort extends AdminerPlugin {
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
?>