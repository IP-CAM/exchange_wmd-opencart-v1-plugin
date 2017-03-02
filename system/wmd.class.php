<?php
class WMD {
    protected $v;
    protected $date;
    protected $user_id;
    protected $user_email;
    protected $api_key;
    protected $hash;
    protected $url = 'http://api.wmd.ru/';
    
    public function __construct($user_id, $user_email, $api_key, $v = 1) {
        $this->v = $v;
        $this->user_id = $user_id;
        $this->user_email = $user_email;
        $this->api_key = $api_key;
        $this->date = date('Ymd');
        $this->hash = md5($this->user_id . ":" . $this->date . ":" . $this->user_email . ":" . $this->api_key);
        $this->url .= "v" . $this->v . "/";
        
    }
    
    public function GetCategories(){
        $data = http_build_query(
            array(
                'id_user' => $this->user_id,
                'hash'    => $this->hash
            )
        );
         
        $result = file_get_contents($this->url . 'categories/?' . $data, false);
        return json_decode($result, true);
    }
    
    public function GetChildCategories($id_cat){
        $data = http_build_query(
            array(
                'id_user' => $this->user_id,
                'hash'    => $this->hash,
                'id_cat'  => $id_cat,
            )
        );
         
        $result = file_get_contents($this->url . 'categories/?' . $data, false);
        return json_decode($result, true);
    }
    
    public function GetGoods($data = array()){
        $data['id_user'] = $this->user_id;
        $data['hash'] = $this->hash;
         
        $result = file_get_contents($this->url . 'goods/?' . http_build_query($data), false);
        return json_decode($result, true);
    }
    
    public function GetGoodsAvailable($ids = "", $offset=0, $limit=50){
        $data = array(
                'id_user' => $this->user_id,
                'hash'    => $this->hash,
                'offset'  => $offset,
                'limit'   => $limit
        );
        
        if($ids){
            $data['ids'] = $ids;
        }
         
        $result = file_get_contents($this->url . 'goods/available?' . http_build_query($data), false);
        return json_decode($result, true);
    }
    
    public function AddOrder($goods = array()){
        $data = http_build_query(
            array(
                'id_user'      => $this->user_id,
                'hash'         => $this->hash,
                'request_type' => 'post',
                'goods'        => json_encode($goods)
            )
        );

        $opts = array('http' =>
            array(
                'method'  => 'POST',
                'header'  => 'Content-type: application/x-www-form-urlencoded',
                'content' => $data
            )
        );

        $context  = stream_context_create($opts);
        
        $result = file_get_contents($this->url . 'orders/add', false, $context);
        return json_decode($result, true);
    }
    
    public function GetOrdersStatus($orders_ids){
        $data = http_build_query(
            array(
                'id_user'    => $this->user_id,
                'hash'       => $this->hash,
                'orders_ids' => $orders_ids
            )
        );
         
        $result = file_get_contents($this->url . 'orders/status?' . $data, false);
        return json_decode($result, true);
    }
    
    public function OrdersCancel($orders = array()){
        $data = http_build_query(
            array(
                'id_user'      => $this->user_id,
                'hash'         => $this->hash,
                'request_type' => 'put',
                'orders'       => json_encode($orders)
            )
        );

        $opts = array('http' =>
            array(
                'method'  => 'PUT',
                'header'  => "Content-Length: " . strlen($data) . "\r\n" . "Content-Type: application/json\r\n",
                'content' => $data
            )
        );

        $context  = stream_context_create($opts);
        
        $result = file_get_contents($this->url . 'orders/cancel?' . $data, false, $context);
        return json_decode($result, true);
    }
}