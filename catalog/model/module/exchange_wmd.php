<?php
class ModelModuleExchangeWmd extends Model {
    private $attribute_group_id = 0;
    
    public function setConfig($key, $value){
        if (!is_array($value)) {
            $this->db->query("UPDATE " . DB_PREFIX . "setting SET `value` = '" . $this->db->escape($value) . "', serialized = '0'  WHERE `key` = '" . $this->db->escape($key) . "' AND store_id = '0'");
        }
    }
    
    public function getCategoryId($id_category){
       $query = $this->db->query("SELECT c.category_id FROM `" . DB_PREFIX . "category_to_wmd` c2w LEFT JOIN `" . DB_PREFIX . "category` c ON(c.category_id = c2w.category_id) WHERE c2w.id_category = '" . (int)$id_category . "'");
       
       if(isset($query->row['category_id'])){
           return $query->row['category_id'];
       }else{
           return false;
       }
    }
    
    public function getCategoryIds($id_category){
       $query = $this->db->query("SELECT c.category_id, c.parent_id FROM `" . DB_PREFIX . "category_to_wmd` c2w LEFT JOIN `" . DB_PREFIX . "category` c ON(c.category_id = c2w.category_id) WHERE c2w.id_category = '" . (int)$id_category . "'"); 
       
       $categoryes = array();
       if(isset($query->row['category_id'])){
           $categoryes[] = $query->row['category_id'];
           
           $parent_id = $query->row['parent_id'];
           while($parent_id > 0){
               $query = $this->db->query("SELECT parent_id FROM `" . DB_PREFIX . "category` WHERE category_id = '" . (int)$parent_id . "'");
               if(isset($query->row['parent_id'])){
                   $categoryes[] = $parent_id;
                   $parent_id = $query->row['parent_id'];
               }else{
                   $parent_id = 0;
               }
           }
       }
       
       return $categoryes;
    }
    
    public function setCategory($data){
        $query = $this->db->query("SELECT c.category_id FROM `" . DB_PREFIX . "category_to_wmd` c2w LEFT JOIN `" . DB_PREFIX . "category` c ON(c.category_id = c2w.category_id) WHERE c2w.id_category = '" . (int)$data['id_category'] . "'");
        
        if(!isset($query->row['category_id'])){
            $parent_id = 0;
            if(isset($data['id_parent'])){
                $query = $this->db->query("SELECT c.category_id FROM `" . DB_PREFIX . "category_to_wmd` c2w LEFT JOIN `" . DB_PREFIX . "category` c ON(c.category_id = c2w.category_id) WHERE c2w.id_category = '" . (int)$data['id_parent'] . "'");
                
                if(isset($query->row['category_id'])){
                    $parent_id = $query->row['category_id'];
                }
            }
            
            
            $this->db->query("INSERT INTO " . DB_PREFIX . "category SET parent_id = '" . (int)$parent_id . "', `top` = '0', `column` = '1', sort_order = '0', status = '1', date_modified = NOW(), date_added = NOW()");

            $category_id = $this->db->getLastId();
            
            $query = $this->db->query("SELECT language_id FROM " . DB_PREFIX . "language");
            
            foreach ($query->rows as $language) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "category_description SET category_id = '" . (int)$category_id . "', language_id = '" . (int)$language['language_id'] . "', name = '" . $this->db->escape($data['name']) . "'");
            }
            
            // MySQL Hierarchical Data Closure Table Pattern
            $level = 0;

            $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "category_path` WHERE category_id = '" . (int)$parent_id . "' ORDER BY `level` ASC");

            foreach ($query->rows as $result) {
                $this->db->query("INSERT INTO `" . DB_PREFIX . "category_path` SET `category_id` = '" . (int)$category_id . "', `path_id` = '" . (int)$result['path_id'] . "', `level` = '" . (int)$level . "'");

                $level++;
            }

            $this->db->query("INSERT INTO `" . DB_PREFIX . "category_path` SET `category_id` = '" . (int)$category_id . "', `path_id` = '" . (int)$category_id . "', `level` = '" . (int)$level . "'");
            
            $this->db->query("INSERT INTO " . DB_PREFIX . "category_to_store SET category_id = '" . (int)$category_id . "', store_id = '0'");
            
            $this->db->query("INSERT INTO " . DB_PREFIX . "category_to_wmd SET category_id = '" . (int)$category_id . "', id_category = '" . (int)$data['id_category'] . "'");
        }
    }
    
    public function getProductId($id_good){
        $query = $this->db->query("SELECT p.product_id FROM `" . DB_PREFIX . "product_to_wmd` p2w LEFT JOIN `" . DB_PREFIX . "product` p ON(p.product_id = p2w.product_id) WHERE p2w.id_good = '" . (int)$id_good . "'");
        
        if(isset($query->row['product_id'])){
            return $query->row['product_id'];
        }
        
        return false;
    }
    
    public function addProduct($id_good){
        $this->db->query("INSERT INTO " . DB_PREFIX . "product SET minimum = '1', date_available = '" . date('Y-m-d') . "', tax_class_id = '0', shipping = '1', subtract = '1', sort_order = '1', status = '1', date_added = NOW()");
        $product_id = $this->db->getLastId();
        $this->db->query("INSERT INTO " . DB_PREFIX . "product_to_store SET product_id = '" . (int)$product_id . "', store_id = '0'");
        $this->db->query("INSERT INTO " . DB_PREFIX . "product_to_wmd SET product_id = '" . (int)$product_id . "', id_good = '" . (int)$id_good . "'");
            
        return $product_id;
    }
    
    public function offProduct($product_id){
        $this->db->query("UPDATE " . DB_PREFIX . "product SET status = '0' WHERE product_id = '" . (int)$product_id . "'");
    }
    
    public function updateProduct($product_id, $data){
        $query_language = $this->db->query("SELECT language_id FROM " . DB_PREFIX . "language");
        
        $sql = "UPDATE " . DB_PREFIX . "product SET stock_status_id = '" . (int)$this->config->get('exchange_wmd_stock_status_id') . "', weight_class_id = '" . (int)$this->config->get('exchange_wmd_weight_class_id') . "', length_class_id = '" . (int)$this->config->get('exchange_wmd_length_class_id') . "', price = '" . (float)$data['price'] . "', model = '" . $this->db->escape($data['model']) . "', sku = '" . $this->db->escape($data['sku']) . "', weight = '" . (float)$data['weight'] . "', quantity = '" . (int)$data['quantity'] . "'";
        
        if($data['image']){
            $sql .= ", image = '" . $this->db->escape(html_entity_decode($data['image'], ENT_QUOTES, 'UTF-8')) . "'";
        }
        
        if(isset($data['manufacturer_id'])){
            $sql .= ", manufacturer_id = '" . (int)$data['manufacturer_id'] . "'";
        }
        
        if(isset($data['length']) && isset($data['width']) && isset($data['height'])){
            $sql .= ", length = '" . (float)$data['length'] . "', width = '" . (float)$data['width'] . "', height = '" . (float)$data['height'] . "'";
        }
        
        $sql .= " WHERE product_id = '" . (int)$product_id . "'";
        
        $this->db->query($sql);
        
        if(isset($data['description'])){
            foreach ($query_language->rows as $language) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "product_description SET product_id = '" . (int)$product_id . "', language_id = '" . (int)$language['language_id'] . "', name = '" . $this->db->escape($data['description']['name']) . "', description = '" . $this->db->escape($data['description']['description']) . "'");
            }
        }
        
        if($data['categories']){
            $categories = array();
            $query = $this->db->query("SELECT category_id FROM " . DB_PREFIX . "product_to_category WHERE product_id = '" . (int)$product_id . "'");
            foreach($query->rows as $category){
                $data['categories'][] = $category['category_id'];
            }
            
            $this->db->query("DELETE FROM " . DB_PREFIX . "product_to_category WHERE product_id = '" . (int)$product_id . "'");
                
            foreach ($data['categories'] as $category_id){
                if($category_id > 0 && !in_array($category_id, $categories)){
                    $this->db->query("INSERT INTO " . DB_PREFIX . "product_to_category SET product_id = '" . (int)$product_id . "', category_id = '" . (int)$category_id . "'");
                    $categories[] = $category_id;
                }
            } 
        }
        
        if($data['attributes']){
            $attributes = array();
            foreach($data['attributes'] as $attribute){
                if(trim($attribute['value'])){
                    $attribute_id = $this->getAttributeId($attribute['name']);
                    foreach($query_language->rows as $language){
                        $language_id = $language['language_id'];
                        $attributes[$language_id][$attribute_id] = trim($attribute['value']);
                    }
                }
            }
            
            $query = $this->db->query("SELECT attribute_id, language_id, text FROM " . DB_PREFIX . "product_attribute WHERE product_id = '" . (int)$product_id . "'");
            
            foreach($query->rows as $attribute){
                $language_id = $attribute['language_id'];
                $attribute_id = $attribute['attribute_id'];
                $attributes[$language_id][$attribute_id] = $attribute['text'];
            }
            
            $this->db->query("DELETE FROM " . DB_PREFIX . "product_attribute WHERE product_id = '" . (int)$product_id . "'");
            
            foreach($attributes as $language_id => $attribute){
                foreach($attribute as $attribute_id => $text){
                    $this->db->query("INSERT INTO " . DB_PREFIX . "product_attribute SET product_id = '" . (int)$product_id . "', attribute_id = '" . (int)$attribute_id . "', language_id = '" . (int)$language_id . "', text = '" .  $this->db->escape($text) . "'");
                }
            }
        }
    }
    
    public function addManufacturer($name){
        $this->db->query("INSERT INTO " . DB_PREFIX . "manufacturer SET name = '" . $this->db->escape(trim($name)) . "', sort_order = '0'");

        $manufacturer_id = $this->db->getLastId();

        $this->db->query("INSERT INTO " . DB_PREFIX . "manufacturer_to_store SET manufacturer_id = '" . (int)$manufacturer_id . "', store_id = '0'");
        
        if($this->config->get('exchange_wmd_manufacturer_description')){
            $query_language = $this->db->query("SELECT language_id FROM " . DB_PREFIX . "language");
            foreach ($query_language->rows as $language) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "manufacturer_description SET manufacturer_id = '" . (int)$manufacturer_id . "', language_id = '" . (int)$language['language_id'] . "'");
            }
        }
        
        return $manufacturer_id;
    }
    
    public function getManufacturerId($name){
        $query = $this->db->query("SELECT manufacturer_id FROM `" . DB_PREFIX . "manufacturer` WHERE LOWER(name) = '" . $this->db->escape(utf8_strtolower(trim($name))) . "'");
        
        if(isset($query->row['manufacturer_id'])){
            return $query->row['manufacturer_id'];
        }elseif(trim($name)){
            return $this->addManufacturer($name);
        }
    }
    
    public function getAttributeId($name){
        $query = $this->db->query("SELECT a.attribute_id FROM `" . DB_PREFIX . "attribute` a LEFT JOIN `" . DB_PREFIX . "attribute_description` ad ON(a.attribute_id = ad.attribute_id) WHERE LOWER(ad.name) = '" . $this->db->escape(utf8_strtolower(trim($name))) . "' LIMIT 1");
        
        if(isset($query->row['attribute_id'])){
            return $query->row['attribute_id'];
        }elseif(trim($name)){
            return $this->addAttributeId($name);
        }
    }
    
    public function addAttributeId($name){
        $query_language = $this->db->query("SELECT language_id FROM " . DB_PREFIX . "language");
        
        if(!$this->attribute_group_id){
            $query = $this->db->query("SELECT ag.attribute_group_id FROM `" . DB_PREFIX . "attribute_group` ag LEFT JOIN `" . DB_PREFIX . "attribute_group_description` agd ON(ag.attribute_group_id = agd.attribute_group_id) WHERE LOWER(agd.name) = '" . $this->db->escape(utf8_strtolower("Характеристики")) . "' LIMIT 1");
            
            if(isset($query->row['attribute_group_id'])){
                $this->attribute_group_id = $query->row['attribute_group_id'];
            }else{
                $this->db->query("INSERT INTO " . DB_PREFIX . "attribute_group SET sort_order = '0'");

                $this->attribute_group_id = $this->db->getLastId();

                foreach ($query_language->rows as $language) {
                    $this->db->query("INSERT INTO " . DB_PREFIX . "attribute_group_description SET attribute_group_id = '" . (int)$this->attribute_group_id . "', language_id = '" . (int)$language['language_id'] . "', name = '" . $this->db->escape("Характеристики") . "'");
                }
            }
        }
        
        $this->db->query("INSERT INTO " . DB_PREFIX . "attribute SET attribute_group_id = '" . (int)$this->attribute_group_id . "', sort_order = '0'");

        $attribute_id = $this->db->getLastId();

        foreach ($query_language->rows as $language) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "attribute_description SET attribute_id = '" . (int)$attribute_id . "', language_id = '" . (int)(int)$language['language_id'] . "', name = '" . $this->db->escape(trim($name)) . "'");
        }
        
        return $attribute_id;
    }
    
    public function setQuantity($id_good, $quantity){
        $product_id = $this->getProductId($id_good);
        if($product_id){
            $this->db->query("UPDATE " . DB_PREFIX . "product SET quantity = '" . (int)$quantity . "' WHERE product_id = '" . (int)$product_id . "'");
        }
    }
    
    public function getGoodId($product_id){
        $query = $this->db->query("SELECT p2w.id_good FROM `" . DB_PREFIX . "product_to_wmd` p2w LEFT JOIN `" . DB_PREFIX . "product` p ON(p.product_id = p2w.product_id) WHERE p.product_id = '" . (int)$product_id . "'");
        
        if(isset($query->row['id_good'])){
            return $query->row['id_good'];
        }
        
        return false;
    }
    
    public function getOrders($order_statuses, $start = 0, $limit = 10){
        $query = $this->db->query("SELECT o.order_id, o2w.id_order, o.order_status_id FROM `" . DB_PREFIX . "order_to_wmd` o2w LEFT JOIN `" . DB_PREFIX . "order` o ON(o.order_id = o2w.order_id) WHERE o.order_status_id NOT IN (" . implode(",", $order_statuses) . ") LIMIT " . (int)$start . ", " . (int)$limit);
        
        return $query->rows;
    }
}