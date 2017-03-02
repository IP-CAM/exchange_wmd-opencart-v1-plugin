<?php
ini_set('max_execution_time', 0);
ini_set("memory_limit", "-1");
require_once(DIR_SYSTEM . 'wmd.class.php');

class ControllerModuleExchangeWmd extends Controller {
    public function index(){
        if($this->config->get('exchange_wmd_status')){
            $this->load->model('module/exchange_wmd');
            if(((int)$this->config->get('exchange_wmd_last_update')+((int)$this->config->get('exchange_wmd_update_time')*60*60)) <= time()){
                if(!$this->config->get('exchange_wmd_update_categories')){
                    $this->UpdateCategories();
                    $this->cache->delete('category');
                    $this->model_module_exchange_wmd->setConfig('exchange_wmd_update_categories', 1);
                }else{
                    $offset = (int)$this->config->get('exchange_wmd_product_offset');
                    $count = $this->UpdateProducts($offset);
                    $this->cache->delete('manufacturer');
                    $this->cache->delete('product');
                    if($count < 50){
                        $this->model_module_exchange_wmd->setConfig('exchange_wmd_product_offset', 0);
                        $this->model_module_exchange_wmd->setConfig('exchange_wmd_update_categories', 0);
                        $this->model_module_exchange_wmd->setConfig('exchange_wmd_last_update', time());
                    }else{
                        $this->model_module_exchange_wmd->setConfig('exchange_wmd_product_offset', $offset+50);
                    }
                }
            }
            if(((int)$this->config->get('exchange_wmd_last_quantity_update')+((int)$this->config->get('exchange_wmd_quantity_update_time')*60*60)) <= time()){
                $offset = (int)$this->config->get('exchange_wmd_quantity_offset');
                $count = $this->UpdateQuantity($offset);
                if($count < 50){
                    $this->model_module_exchange_wmd->setConfig('exchange_wmd_quantity_offset', 0);
                    $this->model_module_exchange_wmd->setConfig('exchange_wmd_last_quantity_update', time());
                }else{
                   $this->model_module_exchange_wmd->setConfig('exchange_wmd_quantity_offset', $offset+50); 
                }
            }
            if(((int)$this->config->get('exchange_wmd_last_order_update')+((int)$this->config->get('exchange_wmd_order_update_time')*60*60)) <= time()){
                $exchange_wmd_order_status = $this->config->get('exchange_wmd_order_status');
                $order_statuses = $this->config->get('config_complete_status');
                $order_statuses[] = $exchange_wmd_order_status['cancel'];
                $offset = (int)$this->config->get('exchange_wmd_order_offset');
                $orders = $this->model_module_exchange_wmd->getOrders($order_statuses, $offset);
                if($orders){
                    $this->UpdateOrders($orders);
                    $this->model_module_exchange_wmd->setConfig('exchange_wmd_order_offset', $offset+count($orders)); 
                }else{
                    $this->model_module_exchange_wmd->setConfig('exchange_wmd_order_offset', 0);
                    $this->model_module_exchange_wmd->setConfig('exchange_wmd_last_order_update', time());
                }
            }
        }
    }
    
    private function UpdateCategories(){
        $wmd = new WMD($this->config->get('exchange_wmd_id_user'), $this->config->get('exchange_wmd_email'), $this->config->get('exchange_wmd_api_key'));
        
        $result = $wmd->GetCategories();
        
        if(isset($result['data'])){
            foreach($result['data'] as $category){
                $this->model_module_exchange_wmd->setCategory($category);
                
                if(isset($category['subcats'])){
                    foreach($category['subcats'] as $subcat){
                        $this->model_module_exchange_wmd->setCategory($subcat);
                    }
                }
            }
        }
    }
    
    private function UpdateProducts($offset){
        $count = 0;

        $wmd = new WMD($this->config->get('exchange_wmd_id_user'), $this->config->get('exchange_wmd_email'), $this->config->get('exchange_wmd_api_key'));
        
        $data = array('offset' => $offset, 'limit' => 50);
        
        $result = $wmd->GetGoods($data);
        
        if(isset($result['data'])){
            $count = count($result['data']);
            foreach($result['data'] as $product){
                $product_id = $this->model_module_exchange_wmd->getProductId($product['id_good']);
                if((int)$product['deleted']){
                    if($product_id){
                        $this->model_module_exchange_wmd->offProduct($product_id);
                    }
                }else{
                    $product_data = array();
                    $isNew = false;
                    if(!$product_id){
                        $product_id = $this->model_module_exchange_wmd->addProduct($product['id_good']);
                        $isNew = true;
                    }
                
                    if($isNew){
                        $product_data['description']['name'] = $product['name'];
                        $product_data['description']['description'] = $product['description'];
                    }

                    $markup = $product['price'] * (intval($this->config->get('exchange_wmd_markup_size')) / 100);
                    $product_data['price'] = $product['price'] + $markup;
                    $product_data['model'] = $product['sku'];
                    $product_data['sku'] = $product['sku'];
                    $product_data['weight'] = isset($product['weight_netto']) ? $product['weight_netto'] : "";
                    
                    $quantity = 0;
                    $available = $wmd->GetGoodsAvailable($product['id_good'], 0, 1);
                    if(isset($available['data'])){
                        $quantity = (int)$available['data']['0']['available'];
                    }
                    
                    $product_data['quantity'] = $quantity;
                    
                    $product_data['image'] = "";
                    if($product['image']){
                        $image_explode = explode('/', $product['image']);
                        $image_name = array_pop($image_explode);
                        $img_path = 'data/wmd_import/' . $product_id . "_" . $image_name;
                        $load_image = $this->loadImageFromHost($product['image'], $img_path);
                        if($load_image){
                            $product_data['image'] = $img_path;
                        }
                    }
                    
                    $product_data['attributes'] = array();
                    
                    if(isset($product['size'])){
                        if($this->config->get('exchange_wmd_product_size') == 1){
                            $product_data['length'] = $product['size']['l'];
                            $product_data['width'] = $product['size']['w'];
                            $product_data['height'] = $product['size']['h'];
                        }elseif($this->config->get('exchange_wmd_product_size') == 2){
                            $product_data['attributes'][] = array(
                                'name'  => "Размеры товара",
                                'value' => $product['size']['l'] . " x " . $product['size']['w'] . " x " . $product['size']['h']
                            );
                        }
                    }
                    
                    if($this->config->get('exchange_wmd_parent_category')){
                        $product_data['categories'] = $this->model_module_exchange_wmd->getCategoryIds($product['id_category']);
                    }else{
                        $category_id = $this->model_module_exchange_wmd->getCategoryId($product['id_category']);
                        $product_data['categories'] = array( $category_id );
                    }
                    
                    if(isset($product['vendor_name'])){
                        if($this->config->get('exchange_wmd_manufacturer') == 1){
                            $product_data['manufacturer_id'] = $this->model_module_exchange_wmd->getManufacturerId($product['vendor_name']);
                        }else{
                            $product_data['attributes'][] = array(
                                'name'  => "Производитель",
                                'value' => $product['vendor_name']
                            );
                        }
                    }
                    
                    if($this->config->get('exchange_wmd_product_class') && isset($product['product_class'])){
                        $product_data['attributes'][] = array(
                            'name'  => "Класс продукта",
                            'value' => $product['product_class']
                        );
                    }
                    
                    if($this->config->get('exchange_wmd_untis') && isset($product['untis'])){
                        $product_data['attributes'][] = array(
                            'name'  => "Единица измерения товара",
                            'value' => $product['untis']
                        );
                    }
                    
                    if($this->config->get('exchange_wmd_warranty') && isset($product['warranty'])){
                        $warranty_period = ($product['warranty_period'] == "year") ? " г." : " мес.";
                        
                        $product_data['attributes'][] = array(
                            'name'  => "Гарантия",
                            'value' => $product['warranty'] . $warranty_period
                        );
                    }
                    
                    if($this->config->get('exchange_wmd_country_name') && isset($product['country_name'])){
                        $product_data['attributes'][] = array(
                            'name'  => "Страна изготовитель",
                            'value' => $product['country_name']
                        );
                    }

                    if($this->config->get('exchange_wmd_features') && isset($product['features'])){
                        foreach($product['features'] as $features){
                            $product_data['attributes'][] = array(
                                'name'  => $features['name'],
                                'value' => $features['value']
                            );
                        }
                    }

                    $this->model_module_exchange_wmd->updateProduct($product_id, $product_data);
                }
            }
        }
        
        return $count;
    }
    
    private function UpdateQuantity($offset){
        $count = 0;
        $wmd = new WMD($this->config->get('exchange_wmd_id_user'), $this->config->get('exchange_wmd_email'), $this->config->get('exchange_wmd_api_key'));
        
        $result = $wmd->GetGoodsAvailable('', $offset);
        if(isset($result['data'])){
            $count = count($result['data']);
            foreach($result['data'] as $good){
                $this->model_module_exchange_wmd->setQuantity($good['id_good'], $good['available']);
            }
        }
        
        return $count;
    }
    
    private function UpdateOrders($orders){
        $this->load->model('checkout/order');
        $exchange_wmd_order_status = $this->config->get('exchange_wmd_order_status');
        
        $store_orders = array();
        $orders_ids = array();
        foreach($orders as $order){
            $id_order = $order['id_order'];
            $orders_ids[] = $id_order;
            
            $store_orders[$id_order] = array(
                'order_id'        => $order['order_id'],
                'order_status_id' => $order['order_status_id']
            );
        }
        
        $wmd = new WMD($this->config->get('exchange_wmd_id_user'), $this->config->get('exchange_wmd_email'), $this->config->get('exchange_wmd_api_key'));
        $result = $wmd->GetOrdersStatus(implode(',', $orders_ids));
        
        if(isset($result['data'])){
            foreach($result['data'] as $order){
                $id_order = $order['id_order'];
                $status = $order['status'];
                if(isset($exchange_wmd_order_status[$status]) && $store_orders[$id_order]['order_status_id'] != $exchange_wmd_order_status[$status]){
                    $this->model_checkout_order->update($store_orders[$id_order]['order_id'], $exchange_wmd_order_status[$status], '', (int)$this->config->get('exchange_wmd_notify_order_status'));
                }
            }
        }
    }
    
    private function loadImageFromHost($link, $img_path){
        if(!file_exists(DIR_IMAGE . $img_path)){
            $fp = fopen(DIR_IMAGE . $img_path, "wb"); 
            chmod(DIR_IMAGE . $img_path, 0777);
            $image_content = file_get_contents($link, false);
                   
            if ($fp && $image_content) {
                file_put_contents(DIR_IMAGE . $img_path, $image_content);
            }elseif(!$image_content){
                unlink(DIR_IMAGE . $img_path);
            }
            
            return file_exists(DIR_IMAGE . $img_path);
        }else{
            return true;
        }
    }
}