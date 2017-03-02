<?php
class ControllerModuleExchangeWmd extends Controller {
    private $error = array();

    public function index() {
        $this->load->language('module/exchange_wmd');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('setting/setting');
        $this->load->model('module/exchange_wmd');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            $this->model_setting_setting->editSetting('exchange_wmd', $this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

            $this->redirect($this->url->link('module/exchange_wmd', 'token=' . $this->session->data['token'], 'SSL'));
        }

        $this->data['heading_title'] = $this->language->get('heading_title');

        $this->data['text_edit'] = $this->language->get('text_edit');
        $this->data['text_enabled'] = $this->language->get('text_enabled');
        $this->data['text_disabled'] = $this->language->get('text_disabled');
        $this->data['text_yes'] = $this->language->get('text_yes');
        $this->data['text_no'] = $this->language->get('text_no');
        $this->data['text_attribute'] = $this->language->get('text_attribute');
        $this->data['text_attributes'] = $this->language->get('text_attributes');
        $this->data['text_update_settings'] = $this->language->get('text_update_settings');
        $this->data['text_hour'] = $this->language->get('text_hour');
        $this->data['text_any'] = $this->language->get('text_any');
        $this->data['text_help_stock_status'] = $this->language->get('text_help_stock_status');
        $this->data['text_module_name'] = $this->language->get('text_module_name');
        $this->data['text_module_version'] = $this->language->get('text_module_version');
        $this->data['text_support'] = $this->language->get('text_support');
        $this->data['text_docs_link'] = $this->language->get('text_docs_link');
        $this->data['text_contact_description'] = $this->language->get('text_contact_description');
        $this->data['text_get_api_key'] = $this->language->get('text_get_api_key');
        $this->data['entry_status'] = $this->language->get('entry_status');
        $this->data['entry_markup_size'] = $this->language->get('entry_markup_size');
        $this->data['entry_id_user'] = $this->language->get('entry_id_user');
        $this->data['entry_email'] = $this->language->get('entry_email');
        $this->data['entry_api_key'] = $this->language->get('entry_api_key');
        $this->data['entry_parent_category'] = $this->language->get('entry_parent_category');
        $this->data['entry_manufacturer'] = $this->language->get('entry_manufacturer');
        $this->data['entry_product_size'] = $this->language->get('entry_product_size');
        $this->data['entry_product_class'] = $this->language->get('entry_product_class');
        $this->data['entry_warranty'] = $this->language->get('entry_warranty');
        $this->data['entry_untis'] = $this->language->get('entry_untis');
        $this->data['entry_country_name'] = $this->language->get('entry_country_name');
        $this->data['entry_features'] = $this->language->get('entry_features');
        $this->data['entry_send_status'] = $this->language->get('entry_send_status');
        $this->data['entry_status_wait'] = $this->language->get('entry_status_wait');
        $this->data['entry_status_checking'] = $this->language->get('entry_status_checking');
        $this->data['entry_status_processing'] = $this->language->get('entry_status_processing');
        $this->data['entry_status_pay'] = $this->language->get('entry_status_pay');
        $this->data['entry_status_sent'] = $this->language->get('entry_status_sent');
        $this->data['entry_status_packing'] = $this->language->get('entry_status_packing');
        $this->data['entry_status_moving'] = $this->language->get('entry_status_moving');
        $this->data['entry_status_ready_for_delivering'] = $this->language->get('entry_status_ready_for_delivering');
        $this->data['entry_status_delivering'] = $this->language->get('entry_status_delivering');
        $this->data['entry_status_ready_for_pick_up'] = $this->language->get('entry_status_ready_for_pick_up');
        $this->data['entry_status_complete'] = $this->language->get('entry_status_complete');
        $this->data['entry_status_cancel'] = $this->language->get('entry_status_cancel');
        $this->data['entry_cron_link'] = $this->language->get('entry_cron_link');
        $this->data['entry_update_time'] = $this->language->get('entry_update_time');
        $this->data['entry_quantity_update_time'] = $this->language->get('entry_quantity_update_time');
        $this->data['entry_order_update_time'] = $this->language->get('entry_order_update_time');
        $this->data['entry_stock_status'] = $this->language->get('entry_stock_status');
        $this->data['entry_weight_class'] = $this->language->get('entry_weight_class');
        $this->data['entry_length_class'] = $this->language->get('entry_length_class');
        $this->data['entry_notify_order'] = $this->language->get('entry_notify_order');
        
        $this->data['tab_general'] = $this->language->get('tab_general');
        $this->data['tab_contacts'] = $this->language->get('tab_contacts');
        $this->data['tab_product_data'] = $this->language->get('tab_product_data');
        $this->data['tab_order_statuses'] = $this->language->get('tab_order_statuses');

        $this->data['button_save'] = $this->language->get('button_save');
        $this->data['button_cancel'] = $this->language->get('button_cancel');

        if (isset($this->error['warning'])) {
            $this->data['error_warning'] = $this->error['warning'];
        } else {
            $this->data['error_warning'] = '';
        }
        
        if (isset($this->error['id_user'])) {
            $this->data['error_id_user'] = $this->error['id_user'];
        } else {
            $this->data['error_id_user'] = '';
        }
        
        if (isset($this->error['email'])) {
            $this->data['error_email'] = $this->error['email'];
        } else {
            $this->data['error_email'] = '';
        }
        
        if (isset($this->error['api_key'])) {
            $this->data['error_api_key'] = $this->error['api_key'];
        } else {
            $this->data['error_api_key'] = '';
        }
        
        if (isset($this->error['update_time'])) {
            $this->data['error_update_time'] = $this->error['update_time'];
        } else {
            $this->data['error_update_time'] = '';
        }
        
        if (isset($this->error['quantity_update_time'])) {
            $this->data['error_quantity_update_time'] = $this->error['quantity_update_time'];
        } else {
            $this->data['error_quantity_update_time'] = '';
        }
        
        if (isset($this->error['order_update_time'])) {
            $this->data['error_order_update_time'] = $this->error['order_update_time'];
        } else {
            $this->data['error_order_update_time'] = '';
        }
        
        if (isset($this->session->data['success'])) {
            $this->data['success'] = $this->session->data['success'];

            unset($this->session->data['success']);
        } else {
            $this->data['success'] = '';
        }

        $this->data['breadcrumbs'] = array();

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => false
        );

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_module'),
            'href' => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => ' :: '
        );

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('module/exchange_wmd', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => ' :: '
        );

        $this->data['action'] = $this->url->link('module/exchange_wmd', 'token=' . $this->session->data['token'], 'SSL');
        $this->data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');

        if (isset($this->request->post['exchange_wmd_status'])) {
            $this->data['exchange_wmd_status'] = $this->request->post['exchange_wmd_status'];
        } else {
            $this->data['exchange_wmd_status'] = $this->config->get('exchange_wmd_status');
        }
        
        if (isset($this->request->post['exchange_wmd_id_user'])) {
            $this->data['exchange_wmd_id_user'] = $this->request->post['exchange_wmd_id_user'];
        } else {
            $this->data['exchange_wmd_id_user'] = $this->config->get('exchange_wmd_id_user');
        }
        
        if (isset($this->request->post['exchange_wmd_email'])) {
            $this->data['exchange_wmd_email'] = $this->request->post['exchange_wmd_email'];
        } else {
            $this->data['exchange_wmd_email'] = $this->config->get('exchange_wmd_email');
        }
        
        if (isset($this->request->post['exchange_wmd_api_key'])) {
            $this->data['exchange_wmd_api_key'] = $this->request->post['exchange_wmd_api_key'];
        } else {
            $this->data['exchange_wmd_api_key'] = $this->config->get('exchange_wmd_api_key');
        }
        
        if (isset($this->request->post['exchange_wmd_update_time'])) {
            $this->data['exchange_wmd_update_time'] = $this->request->post['exchange_wmd_update_time'];
        } elseif((int)$this->config->get('exchange_wmd_update_time')) {
            $this->data['exchange_wmd_update_time'] = $this->config->get('exchange_wmd_update_time');
        } else {
            $this->data['exchange_wmd_update_time'] = 24;
        }
        
        if (isset($this->request->post['exchange_wmd_quantity_update_time'])) {
            $this->data['exchange_wmd_quantity_update_time'] = $this->request->post['exchange_wmd_quantity_update_time'];
        } elseif((int)$this->config->get('exchange_wmd_quantity_update_time')) {
            $this->data['exchange_wmd_quantity_update_time'] = $this->config->get('exchange_wmd_quantity_update_time');
        } else {
            $this->data['exchange_wmd_quantity_update_time'] = 1;
        }
        
        if (isset($this->request->post['exchange_wmd_order_update_time'])) {
            $this->data['exchange_wmd_order_update_time'] = $this->request->post['exchange_wmd_order_update_time'];
        } elseif((int)$this->config->get('exchange_wmd_order_update_time')) {
            $this->data['exchange_wmd_order_update_time'] = $this->config->get('exchange_wmd_order_update_time');
        } else {
            $this->data['exchange_wmd_order_update_time'] = 1;
        }
        
        if (isset($this->request->post['exchange_wmd_parent_category'])) {
            $this->data['exchange_wmd_parent_category'] = $this->request->post['exchange_wmd_parent_category'];
        } else {
            $this->data['exchange_wmd_parent_category'] = $this->config->get('exchange_wmd_parent_category');
        }

        if (isset($this->request->post['exchange_wmd_manufacturer'])) {
            $this->data['exchange_wmd_manufacturer'] = $this->request->post['exchange_wmd_manufacturer'];
        } else {
            $this->data['exchange_wmd_manufacturer'] = $this->config->get('exchange_wmd_manufacturer');
        }

        if (isset($this->request->post['exchange_wmd_markup_size'])) {
            $this->data['exchange_wmd_markup_size'] = $this->request->post['exchange_wmd_markup_size'];
        } else {
            $this->data['exchange_wmd_markup_size'] = $this->config->get('exchange_wmd_markup_size');
        }
        
        if (isset($this->request->post['exchange_wmd_product_size'])) {
            $this->data['exchange_wmd_product_size'] = $this->request->post['exchange_wmd_product_size'];
        } else {
            $this->data['exchange_wmd_product_size'] = $this->config->get('exchange_wmd_product_size');
        }
        
        if (isset($this->request->post['exchange_wmd_product_class'])) {
            $this->data['exchange_wmd_product_class'] = $this->request->post['exchange_wmd_product_class'];
        } else {
            $this->data['exchange_wmd_product_class'] = $this->config->get('exchange_wmd_product_class');
        }
        
        if (isset($this->request->post['exchange_wmd_warranty'])) {
            $this->data['exchange_wmd_warranty'] = $this->request->post['exchange_wmd_warranty'];
        } else {
            $this->data['exchange_wmd_warranty'] = $this->config->get('exchange_wmd_warranty');
        }
        
        if (isset($this->request->post['exchange_wmd_untis'])) {
            $this->data['exchange_wmd_untis'] = $this->request->post['exchange_wmd_untis'];
        } else {
            $this->data['exchange_wmd_untis'] = $this->config->get('exchange_wmd_untis');
        }
        
        if (isset($this->request->post['exchange_wmd_country_name'])) {
            $this->data['exchange_wmd_country_name'] = $this->request->post['exchange_wmd_country_name'];
        } else {
            $this->data['exchange_wmd_country_name'] = $this->config->get('exchange_wmd_country_name');
        }
        
        if (isset($this->request->post['exchange_wmd_features'])) {
            $this->data['exchange_wmd_features'] = $this->request->post['exchange_wmd_features'];
        } else {
            $this->data['exchange_wmd_features'] = $this->config->get('exchange_wmd_features');
        }
        
        if (isset($this->request->post['exchange_wmd_order_status'])) {
            $this->data['exchange_wmd_order_status'] = $this->request->post['exchange_wmd_order_status'];
        } else {
            $this->data['exchange_wmd_order_status'] = $this->config->get('exchange_wmd_order_status');
        }
        
        if (isset($this->request->post['exchange_wmd_last_update'])) {
            $this->data['exchange_wmd_last_update'] = $this->request->post['exchange_wmd_last_update'];
        } else {
            $this->data['exchange_wmd_last_update'] = $this->config->get('exchange_wmd_last_update');
        }
              
        if (isset($this->request->post['exchange_wmd_last_quantity_update'])) {
            $this->data['exchange_wmd_last_quantity_update'] = $this->request->post['exchange_wmd_last_quantity_update'];
        } else {
            $this->data['exchange_wmd_last_quantity_update'] = $this->config->get('exchange_wmd_last_quantity_update');
        }
              
        if (isset($this->request->post['exchange_wmd_last_order_update'])) {
            $this->data['exchange_wmd_last_order_update'] = $this->request->post['exchange_wmd_last_order_update'];
        } else {
            $this->data['exchange_wmd_last_order_update'] = $this->config->get('exchange_wmd_last_order_update');
        }
              
        if (isset($this->request->post['exchange_wmd_update_categories'])) {
            $this->data['exchange_wmd_update_categories'] = $this->request->post['exchange_wmd_update_categories'];
        } else {
            $this->data['exchange_wmd_update_categories'] = $this->config->get('exchange_wmd_update_categories');
        }
              
        if (isset($this->request->post['exchange_wmd_product_offset'])) {
            $this->data['exchange_wmd_product_offset'] = $this->request->post['exchange_wmd_product_offset'];
        } else {
            $this->data['exchange_wmd_product_offset'] = $this->config->get('exchange_wmd_product_offset');
        }
        
        if (isset($this->request->post['exchange_wmd_quantity_offset'])) {
            $this->data['exchange_wmd_quantity_offset'] = $this->request->post['exchange_wmd_quantity_offset'];
        } else {
            $this->data['exchange_wmd_quantity_offset'] = $this->config->get('exchange_wmd_quantity_offset');
        }
              
        if (isset($this->request->post['exchange_wmd_order_offset'])) {
            $this->data['exchange_wmd_order_offset'] = $this->request->post['exchange_wmd_order_offset'];
        } else {
            $this->data['exchange_wmd_order_offset'] = $this->config->get('exchange_wmd_order_offset');
        }
              
        if (isset($this->request->post['exchange_wmd_notify_order_status'])) {
            $this->data['exchange_wmd_notify_order_status'] = $this->request->post['exchange_wmd_notify_order_status'];
        } else {
            $this->data['exchange_wmd_notify_order_status'] = $this->config->get('exchange_wmd_notify_order_status');
        }
              
        $this->data['stock_statuses'] = $this->model_module_exchange_wmd->getStockStatuses();
        
        if (isset($this->request->post['exchange_wmd_stock_status_id'])) {
            $this->data['exchange_wmd_stock_status_id'] = $this->request->post['exchange_wmd_stock_status_id'];
        } else {
            $this->data['exchange_wmd_stock_status_id'] = $this->config->get('exchange_wmd_stock_status_id');
        }

        if (isset($this->request->post['exchange_wmd_send_status'])) {
            $this->data['exchange_wmd_send_status'] = $this->request->post['exchange_wmd_send_status'];
        } else {
            $this->data['exchange_wmd_send_status'] = $this->config->get('exchange_wmd_send_status');
        }
              
        $this->data['weight_classes'] = $this->model_module_exchange_wmd->getWeightClasses();
        
        if (isset($this->request->post['exchange_wmd_weight_class_id'])) {
            $this->data['exchange_wmd_weight_class_id'] = $this->request->post['exchange_wmd_weight_class_id'];
        } elseif($this->config->get('exchange_wmd_weight_class_id')) {
            $this->data['exchange_wmd_weight_class_id'] = $this->config->get('exchange_wmd_weight_class_id');
        }else{
            $this->data['exchange_wmd_weight_class_id'] = $this->config->get('config_weight_class_id');
        }
              
        $this->data['length_classes'] = $this->model_module_exchange_wmd->getLengthClasses();
        
        if (isset($this->request->post['exchange_wmd_length_class_id'])) {
            $this->data['exchange_wmd_length_class_id'] = $this->request->post['exchange_wmd_length_class_id'];
        } elseif($this->config->get('exchange_wmd_length_class_id')) {
            $this->data['exchange_wmd_length_class_id'] = $this->config->get('exchange_wmd_length_class_id');
        }else{
            $this->data['exchange_wmd_weight_class_id'] = $this->config->get('config_weight_class_id');
        }
        
        if (isset($this->request->post['exchange_wmd_manufacturer_description'])) {
            $this->data['exchange_wmd_manufacturer_description'] = $this->request->post['exchange_wmd_manufacturer_description'];
        } elseif($this->config->get('exchange_wmd_manufacturer_description') != "") {
            $this->data['exchange_wmd_manufacturer_description'] = $this->config->get('exchange_wmd_manufacturer_description');
        }else{
            $this->data['exchange_wmd_manufacturer_description'] = $this->model_module_exchange_wmd->check_db_manufacturer_description();
        }

        $this->data['order_statuses'] = $this->model_module_exchange_wmd->getOrderStatuses();
        $this->data['cron_link'] = HTTP_CATALOG . "index.php?route=module/exchange_wmd";

        $this->children = array(
            'common/header',
            'common/footer'
        );

        $this->template = 'module/exchange_wmd.tpl';
        $this->response->setOutput($this->render());

    }

    public function install(){
        $this->load->model('module/exchange_wmd');
        $this->model_module_exchange_wmd->createDB();
        $this->redirect($this->url->link('module/exchange_wmd', 'token=' . $this->session->data['token'], 'SSL'));
    }
    
    protected function validate() {
        if (!$this->user->hasPermission('modify', 'module/exchange_wmd')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }
        
        if((int)$this->request->post['exchange_wmd_id_user'] <= 0){
            $this->error['id_user'] = $this->language->get('error_id_user');
        }
        
        if(!trim($this->request->post['exchange_wmd_email'])){
            $this->error['email'] = $this->language->get('error_email');
        }
        
        if(!trim($this->request->post['exchange_wmd_api_key'])){
            $this->error['api_key'] = $this->language->get('error_api_key');
        }
        
        if((int)$this->request->post['exchange_wmd_update_time'] <= 0){
            $this->error['update_time'] = $this->language->get('error_time');
        }
        
        if((int)$this->request->post['exchange_wmd_quantity_update_time'] <= 0){
            $this->error['quantity_update_time'] = $this->language->get('error_time');
        }
        
        if((int)$this->request->post['exchange_wmd_order_update_time'] <= 0){
            $this->error['order_update_time'] = $this->language->get('error_time');
        }
        
        if($this->error && !isset($this->error['warning'])){
            $this->error['warning'] = $this->language->get('error_warning');
        }

        return !$this->error;
    }
}