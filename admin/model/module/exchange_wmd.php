<?php
class ModelModuleExchangeWmd extends Model {
    
    public function createDB(){
        $this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "category_to_wmd` (
            `category_id` int(11) NOT NULL,
            `id_category` int(11) NOT NULL,
            PRIMARY KEY (`category_id`,`id_category`)
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;");
            
        $this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "product_to_wmd` (
            `product_id` int(11) NOT NULL,
            `id_good` int(11) NOT NULL,
            PRIMARY KEY (`product_id`,`id_good`)
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;");
            
        $this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "order_to_wmd` (
            `order_id` int(11) NOT NULL,
            `id_order` int(11) NOT NULL,
            PRIMARY KEY (`order_id`,`id_order`)
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;");
    }
    
    public function check_db_manufacturer_description()
    {
        $query = $this->db->query("SHOW TABLES LIKE '" . DB_PREFIX . "manufacturer_description'");
        
        return $query->num_rows;
    }
    
    public function getOrderStatuses() {
        $order_status_data = $this->cache->get('order_status.' . (int)$this->config->get('config_language_id'));

        if (!$order_status_data) {
            $query = $this->db->query("SELECT order_status_id, name FROM " . DB_PREFIX . "order_status WHERE language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY name");

            $order_status_data = $query->rows;

            $this->cache->set('order_status.' . (int)$this->config->get('config_language_id'), $order_status_data);
        }

        return $order_status_data;
    }
    
    public function getStockStatuses() {
        $stock_status_data = $this->cache->get('stock_status.' . (int)$this->config->get('config_language_id'));

        if (!$stock_status_data) {
            $query = $this->db->query("SELECT stock_status_id, name FROM " . DB_PREFIX . "stock_status WHERE language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY name");

            $stock_status_data = $query->rows;

            $this->cache->set('stock_status.' . (int)$this->config->get('config_language_id'), $stock_status_data);
        }

        return $stock_status_data;
    }
    
    public function getWeightClasses() {
        $weight_class_data = $this->cache->get('weight_class.' . (int)$this->config->get('config_language_id'));

        if (!$weight_class_data) {
            $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "weight_class wc LEFT JOIN " . DB_PREFIX . "weight_class_description wcd ON (wc.weight_class_id = wcd.weight_class_id) WHERE wcd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

            $weight_class_data = $query->rows;

            $this->cache->set('weight_class.' . (int)$this->config->get('config_language_id'), $weight_class_data);
        }

        return $weight_class_data;
    }
    
    public function getLengthClasses() {
        $length_class_data = $this->cache->get('length_class.' . (int)$this->config->get('config_language_id'));

        if (!$length_class_data) {
            $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "length_class lc LEFT JOIN " . DB_PREFIX . "length_class_description lcd ON (lc.length_class_id = lcd.length_class_id) WHERE lcd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

            $length_class_data = $query->rows;

            $this->cache->set('length_class.' . (int)$this->config->get('config_language_id'), $length_class_data);
        }

        return $length_class_data;
    }
}