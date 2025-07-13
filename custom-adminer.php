<?php
namespace docker {
    class AdminerCustomSort extends \Adminer\Plugin {
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
}