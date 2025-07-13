<?php

/**
 * Adminer Plugin: Default DESC Sort - Debug Version
 */

class AdminerDescSort {
    
    function name() {
        return "Default DESC Sort";
    }
    
    function selectQueryBuild($select, $where, $group, $order, $limit, $page) {
        // Debug: Log what we receive
        error_log("AdminerDescSort DEBUG - Input order: " . print_r($order, true));
        
        // Si il y a déjà un ORDER BY, on ne fait rien
        if (!empty($order)) {
            error_log("AdminerDescSort DEBUG - Order already exists, skipping");
            return array($select, $where, $group, $order, $limit, $page);
        }
        
        // Pour l'instant, on ne fait RIEN d'autre
        error_log("AdminerDescSort DEBUG - No order, but not adding any for now");
        return array($select, $where, $group, $order, $limit, $page);
    }
}