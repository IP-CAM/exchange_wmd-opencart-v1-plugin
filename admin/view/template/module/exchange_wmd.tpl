<?php echo $header; ?>
<div id="content">
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <?php if ($error_warning) { ?>
  <div class="warning"><?php echo $error_warning; ?></div>
  <?php } ?>
  <?php if ($success) { ?>
  <div class="success"><?php echo $success; ?></div>
  <?php } ?>
  <div class="box">
    <div class="heading">
      <h1><img src="view/image/module.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons"><a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a><a href="<?php echo $cancel; ?>" class="button"><?php echo $button_cancel; ?></a></div>
    </div>
    <div class="content">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
          <input type="hidden" name="exchange_wmd_last_update" value="<?php echo $exchange_wmd_last_update; ?>">
          <input type="hidden" name="exchange_wmd_last_quantity_update" value="<?php echo $exchange_wmd_last_quantity_update; ?>">
          <input type="hidden" name="exchange_wmd_last_order_update" value="<?php echo $exchange_wmd_last_order_update; ?>">
          <input type="hidden" name="exchange_wmd_update_categories" value="<?php echo $exchange_wmd_update_categories; ?>">
          <input type="hidden" name="exchange_wmd_product_offset" value="<?php echo $exchange_wmd_product_offset; ?>">
          <input type="hidden" name="exchange_wmd_quantity_offset" value="<?php echo $exchange_wmd_quantity_offset; ?>">
          <input type="hidden" name="exchange_wmd_order_offset" value="<?php echo $exchange_wmd_order_offset; ?>">
          <input type="hidden" name="exchange_wmd_manufacturer_description" value="<?php echo $exchange_wmd_manufacturer_description; ?>">
          <div id="tabs" class="htabs">
            <a href="#tab-general"><?php echo $tab_general; ?></a>
            <a href="#tab-data"><?php echo $tab_product_data; ?></a>
            <a href="#tab-statuses"><?php echo $tab_order_statuses; ?></a>
            <a href="#tab-contacts"><?php echo $tab_contacts; ?></a>
          </div>
          <div id="tab-general">
            <table class="form">
              <tr>
                <td><?php echo $entry_status; ?></td>
                <td>
                  <select name="exchange_wmd_status">
                    <?php if ($exchange_wmd_status) { ?>
                    <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                    <option value="0"><?php echo $text_disabled; ?></option>
                    <?php } else { ?>
                    <option value="1"><?php echo $text_enabled; ?></option>
                    <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                    <?php } ?>
                  </select>
                </td>
              </tr>
              <tr>
                <td><span class="required">*</span> <?php echo $entry_id_user; ?></td>
                <td>
                  <input type="text" name="exchange_wmd_id_user" value="<?php echo $exchange_wmd_id_user; ?>" />
                  <?php if ($error_id_user) { ?>
                  <span class="error"><?php echo $error_id_user; ?></span>
                  <?php } ?>
                </td>
              </tr>
              <tr>
                <td><span class="required">*</span> <?php echo $entry_email; ?></td>
                <td>
                  <input type="text" name="exchange_wmd_email" value="<?php echo $exchange_wmd_email; ?>" />
                  <?php if ($error_email) { ?>
                  <span class="error"><?php echo $error_email; ?></span>
                  <?php } ?>
                </td>
              </tr>
              <tr>
                <td><span class="required">*</span> <?php echo $entry_api_key; ?></td>
                <td>
                  <input type="text" name="exchange_wmd_api_key" value="<?php echo $exchange_wmd_api_key; ?>" />
                  <?php if ($error_api_key) { ?>
                  <span class="error"><?php echo $error_api_key; ?></span>
                  <?php } ?>
                  <br><a target="_blank" href="https://www.wmd.ru/registration.html"><?php echo $text_get_api_key; ?></a>
                </td>
              </tr>
              <tr>
                <td><?php echo $entry_notify_order; ?></td>
                <td>
                  <select name="exchange_wmd_notify_order_status">
                    <?php if ($exchange_wmd_notify_order_status) { ?>
                    <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                    <option value="0"><?php echo $text_disabled; ?></option>
                    <?php } else { ?>
                    <option value="1"><?php echo $text_enabled; ?></option>
                    <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                    <?php } ?>
                  </select>
                </td>
              </tr>
              <tr>
                <td><?php echo $entry_send_status; ?></td>
                <td>
                  <select name="exchange_wmd_send_status" id="input-send_status" class="form-control">
                    <option value="0"><?php echo $text_any; ?></option>
                    <?php foreach ($order_statuses as $status): ?>
                        <option
                            <?php echo ($status['order_status_id'] == $exchange_wmd_send_status) ? 'selected="selected"' : '' ?>
                            value="<?php echo $status['order_status_id'] ?>"><?php echo $status['name'] ?></option>
                    <?php endforeach; ?>
                  </select>
                </td>
              </tr>
              <tr>
                <td><?php echo $entry_cron_link; ?></td>
                <td><?php echo $cron_link; ?></td>
              </tr>
            </table>
            <h2><?php echo $text_update_settings; ?></h2>
            <table class="form">
              <tr>
                <td><span class="required">*</span> <?php echo $entry_update_time; ?></td>
                <td>
                  <input type="text" name="exchange_wmd_update_time" value="<?php echo $exchange_wmd_update_time; ?>" size="2" /> <span><?php echo $text_hour; ?></span>
                  <?php if ($error_update_time) { ?>
                  <span class="error"><?php echo $error_update_time; ?></span>
                  <?php } ?>
                </td>
              </tr>
              <tr>
                <td><span class="required">*</span> <?php echo $entry_quantity_update_time; ?></td>
                <td>
                  <input type="text" name="exchange_wmd_quantity_update_time" value="<?php echo $exchange_wmd_quantity_update_time; ?>" size="2" /> <span><?php echo $text_hour; ?></span>
                  <?php if ($error_quantity_update_time) { ?>
                  <span class="error"><?php echo $error_quantity_update_time; ?></span>
                  <?php } ?>
                </td>
              </tr>
              <tr>
                <td><span class="required">*</span> <?php echo $entry_order_update_time; ?></td>
                <td>
                  <input type="text" name="exchange_wmd_order_update_time" value="<?php echo $exchange_wmd_order_update_time; ?>" size="2" /> <span><?php echo $text_hour; ?></span>
                  <?php if ($error_order_update_time) { ?>
                  <span class="error"><?php echo $error_order_update_time; ?></span>
                  <?php } ?>
                </td>
              </tr>
            </table>
          </div>
          <div id="tab-data">
            <table class="form">
              <tr>
                <td><?php echo $entry_parent_category; ?></td>
                <td>
                    <?php if ($exchange_wmd_parent_category) { ?>
                    <input type="radio" name="exchange_wmd_parent_category" value="1" checked="checked" />
                    <?php echo $text_yes; ?>
                    <input type="radio" name="exchange_wmd_parent_category" value="0" />
                    <?php echo $text_no; ?>
                    <?php } else { ?>
                    <input type="radio" name="exchange_wmd_parent_category" value="1" />
                    <?php echo $text_yes; ?>
                    <input type="radio" name="exchange_wmd_parent_category" value="0" checked="checked" />
                    <?php echo $text_no; ?>
                    <?php } ?>
                </td>
              </tr>
              <tr>
                <td><?php echo $entry_stock_status; ?><br><span class="help"><?php echo $text_help_stock_status; ?></span></td>
                <td>
                  <select name="exchange_wmd_stock_status_id">
                    <?php foreach ($stock_statuses as $stock_status) { ?>
                    <?php if ($stock_status['stock_status_id'] == $exchange_wmd_stock_status_id) { ?>
                    <option value="<?php echo $stock_status['stock_status_id']; ?>" selected="selected"><?php echo $stock_status['name']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $stock_status['stock_status_id']; ?>"><?php echo $stock_status['name']; ?></option>
                    <?php } ?>
                    <?php } ?>
                  </select>
                </td>
              </tr>
              <tr>
                <td><?php echo $entry_weight_class; ?></td>
                <td>
                  <select name="exchange_wmd_weight_class_id">
                    <?php foreach ($weight_classes as $weight_class) { ?>
                    <?php if ($weight_class['weight_class_id'] == $exchange_wmd_weight_class_id) { ?>
                    <option value="<?php echo $weight_class['weight_class_id']; ?>" selected="selected"><?php echo $weight_class['title']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $weight_class['weight_class_id']; ?>"><?php echo $weight_class['title']; ?></option>
                    <?php } ?>
                    <?php } ?>
                  </select>
                </td>
              </tr>
              <tr>
                <td><?php echo $entry_length_class; ?></td>
                <td>
                  <select name="exchange_wmd_length_class_id">
                    <?php foreach ($length_classes as $length_class) { ?>
                    <?php if ($length_class['length_class_id'] == $exchange_wmd_length_class_id) { ?>
                    <option value="<?php echo $length_class['length_class_id']; ?>" selected="selected"><?php echo $length_class['title']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $length_class['length_class_id']; ?>"><?php echo $length_class['title']; ?></option>
                    <?php } ?>
                    <?php } ?>
                  </select>
                </td>
              </tr>
              <tr>
                <td><?php echo $entry_manufacturer; ?></td>
                <td>
                  <select name="exchange_wmd_manufacturer">
                    <option value="0"><?php echo $text_no; ?></option>
                    <option value="1" <?php if ($exchange_wmd_manufacturer == 1) { ?> selected="selected"<?php } ?>><?php echo $text_yes; ?></option>
                    <option value="2" <?php if ($exchange_wmd_manufacturer == 2) { ?> selected="selected"<?php } ?>><?php echo $text_attribute; ?></option>
                  </select>
                </td>
              </tr>
              <tr>
                <td><?php echo $entry_product_size; ?></td>
                <td>
                  <select name="exchange_wmd_product_size">
                    <option value="0"><?php echo $text_no; ?></option>
                    <option value="1" <?php if ($exchange_wmd_product_size == 1) { ?> selected="selected"<?php } ?>><?php echo $text_yes; ?></option>
                    <option value="2" <?php if ($exchange_wmd_product_size == 2) { ?> selected="selected"<?php } ?>><?php echo $text_attribute; ?></option>
                  </select>
                </td>
              </tr>
              <tr>
                <td><?php echo $entry_markup_size; ?></td>
                <td><input type="text" name="exchange_wmd_markup_size" value="<?php echo $exchange_wmd_markup_size; ?>" size="3" /></td>
              </tr>
            </table>
            <h2><?php echo $text_attributes; ?></h2>
            <table class="form">
              <tr>
                <td><?php echo $entry_product_class; ?></td>
                <td>
                    <?php if ($exchange_wmd_product_class) { ?>
                    <input type="radio" name="exchange_wmd_product_class" value="1" checked="checked" />
                    <?php echo $text_yes; ?>
                    <input type="radio" name="exchange_wmd_product_class" value="0" />
                    <?php echo $text_no; ?>
                    <?php } else { ?>
                    <input type="radio" name="exchange_wmd_product_class" value="1" />
                    <?php echo $text_yes; ?>
                    <input type="radio" name="exchange_wmd_product_class" value="0" checked="checked" />
                    <?php echo $text_no; ?>
                    <?php } ?>
                </td>
              </tr>
              <tr>
                <td><?php echo $entry_warranty; ?></td>
                <td>
                    <?php if ($exchange_wmd_warranty) { ?>
                    <input type="radio" name="exchange_wmd_warranty" value="1" checked="checked" />
                    <?php echo $text_yes; ?>
                    <input type="radio" name="exchange_wmd_warranty" value="0" />
                    <?php echo $text_no; ?>
                    <?php } else { ?>
                    <input type="radio" name="exchange_wmd_warranty" value="1" />
                    <?php echo $text_yes; ?>
                    <input type="radio" name="exchange_wmd_warranty" value="0" checked="checked" />
                    <?php echo $text_no; ?>
                    <?php } ?>
                </td>
              </tr>
              <tr>
                <td><?php echo $entry_untis; ?></td>
                <td>
                    <?php if ($exchange_wmd_untis) { ?>
                    <input type="radio" name="exchange_wmd_untis" value="1" checked="checked" />
                    <?php echo $text_yes; ?>
                    <input type="radio" name="exchange_wmd_untis" value="0" />
                    <?php echo $text_no; ?>
                    <?php } else { ?>
                    <input type="radio" name="exchange_wmd_untis" value="1" />
                    <?php echo $text_yes; ?>
                    <input type="radio" name="exchange_wmd_untis" value="0" checked="checked" />
                    <?php echo $text_no; ?>
                    <?php } ?>
                </td>
              </tr>
              <tr>
                <td><?php echo $entry_country_name; ?></td>
                <td>
                    <?php if ($exchange_wmd_country_name) { ?>
                    <input type="radio" name="exchange_wmd_country_name" value="1" checked="checked" />
                    <?php echo $text_yes; ?>
                    <input type="radio" name="exchange_wmd_country_name" value="0" />
                    <?php echo $text_no; ?>
                    <?php } else { ?>
                    <input type="radio" name="exchange_wmd_country_name" value="1" />
                    <?php echo $text_yes; ?>
                    <input type="radio" name="exchange_wmd_country_name" value="0" checked="checked" />
                    <?php echo $text_no; ?>
                    <?php } ?>
                </td>
              </tr>
              <tr>
                <td><?php echo $entry_features; ?></td>
                <td>
                    <?php if ($exchange_wmd_features) { ?>
                    <input type="radio" name="exchange_wmd_features" value="1" checked="checked" />
                    <?php echo $text_yes; ?>
                    <input type="radio" name="exchange_wmd_features" value="0" />
                    <?php echo $text_no; ?>
                    <?php } else { ?>
                    <input type="radio" name="exchange_wmd_features" value="1" />
                    <?php echo $text_yes; ?>
                    <input type="radio" name="exchange_wmd_features" value="0" checked="checked" />
                    <?php echo $text_no; ?>
                    <?php } ?>
                </td>
              </tr>
            </table>
          </div>
          <div id="tab-statuses">
            <table class="form">
              <tr>
                <td><?php echo $entry_status_wait; ?></td>
                <td>
                  <select name="exchange_wmd_order_status[wait]">
                    <?php foreach($order_statuses as $order_status){ ?>
                      <?php if(isset($exchange_wmd_order_status['wait']) && $exchange_wmd_order_status['wait'] == $order_status['order_status_id']){ ?>
                        <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                      <?php }else{ ?>
                        <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                      <?php } ?>
                    <?php } ?>
                  </select>
                </td>
              </tr>
              <tr>
                <td><?php echo $entry_status_checking; ?></td>
                <td>
                  <select name="exchange_wmd_order_status[checking]">
                    <?php foreach($order_statuses as $order_status){ ?>
                      <?php if(isset($exchange_wmd_order_status['checking']) && $exchange_wmd_order_status['checking'] == $order_status['order_status_id']){ ?>
                        <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                      <?php }else{ ?>
                        <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                      <?php } ?>
                    <?php } ?>
                  </select>
                </td>
              </tr>
              <tr>
                <td><?php echo $entry_status_processing; ?></td>
                <td>
                  <select name="exchange_wmd_order_status[processing]">
                    <?php foreach($order_statuses as $order_status){ ?>
                      <?php if(isset($exchange_wmd_order_status['processing']) && $exchange_wmd_order_status['processing'] == $order_status['order_status_id']){ ?>
                        <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                      <?php }else{ ?>
                        <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                      <?php } ?>
                    <?php } ?>
                  </select>
                </td>
              </tr>
              <tr>
                <td><?php echo $entry_status_pay; ?></td>
                <td>
                  <select name="exchange_wmd_order_status[pay]">
                    <?php foreach($order_statuses as $order_status){ ?>
                      <?php if(isset($exchange_wmd_order_status['pay']) && $exchange_wmd_order_status['pay'] == $order_status['order_status_id']){ ?>
                        <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                      <?php }else{ ?>
                        <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                      <?php } ?>
                    <?php } ?>
                  </select>
                </td>
              </tr>
              <tr>
                <td><?php echo $entry_status_sent; ?></td>
                <td>
                  <select name="exchange_wmd_order_status[sent]">
                    <?php foreach($order_statuses as $order_status){ ?>
                      <?php if(isset($exchange_wmd_order_status['sent']) && $exchange_wmd_order_status['sent'] == $order_status['order_status_id']){ ?>
                        <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                      <?php }else{ ?>
                        <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                      <?php } ?>
                    <?php } ?>
                  </select>
                </td>
              </tr>
              <tr>
                <td><?php echo $entry_status_packing; ?></td>
                <td>
                  <select name="exchange_wmd_order_status[packing]">
                    <?php foreach($order_statuses as $order_status){ ?>
                      <?php if(isset($exchange_wmd_order_status['packing']) && $exchange_wmd_order_status['packing'] == $order_status['order_status_id']){ ?>
                        <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                      <?php }else{ ?>
                        <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                      <?php } ?>
                    <?php } ?>
                  </select>
                </td>
              </tr>
              <tr>
                <td><?php echo $entry_status_moving; ?></td>
                <td>
                  <select name="exchange_wmd_order_status[moving]">
                    <?php foreach($order_statuses as $order_status){ ?>
                      <?php if(isset($exchange_wmd_order_status['moving']) && $exchange_wmd_order_status['moving'] == $order_status['order_status_id']){ ?>
                        <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                      <?php }else{ ?>
                        <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                      <?php } ?>
                    <?php } ?>
                  </select>
                </td>
              </tr>
              <tr>
                <td><?php echo $entry_status_ready_for_delivering; ?></td>
                <td>
                  <select name="exchange_wmd_order_status[ready_for_delivering]">
                    <?php foreach($order_statuses as $order_status){ ?>
                      <?php if(isset($exchange_wmd_order_status['ready_for_delivering']) && $exchange_wmd_order_status['ready_for_delivering'] == $order_status['order_status_id']){ ?>
                        <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                      <?php }else{ ?>
                        <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                      <?php } ?>
                    <?php } ?>
                  </select>
                </td>
              </tr>
              <tr>
                <td><?php echo $entry_status_delivering; ?></td>
                <td>
                  <select name="exchange_wmd_order_status[delivering]">
                    <?php foreach($order_statuses as $order_status){ ?>
                      <?php if(isset($exchange_wmd_order_status['delivering']) && $exchange_wmd_order_status['delivering'] == $order_status['order_status_id']){ ?>
                        <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                      <?php }else{ ?>
                        <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                      <?php } ?>
                    <?php } ?>
                  </select>
                </td>
              </tr>
              <tr>
                <td><?php echo $entry_status_ready_for_pick_up; ?></td>
                <td>
                  <select name="exchange_wmd_order_status[ready_for_pick_up]">
                    <?php foreach($order_statuses as $order_status){ ?>
                      <?php if(isset($exchange_wmd_order_status['ready_for_pick_up']) && $exchange_wmd_order_status['ready_for_pick_up'] == $order_status['order_status_id']){ ?>
                        <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                      <?php }else{ ?>
                        <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                      <?php } ?>
                    <?php } ?>
                  </select>
                </td>
              </tr>
              <tr>
                <td><?php echo $entry_status_complete; ?></td>
                <td>
                  <select name="exchange_wmd_order_status[complete]">
                    <?php foreach($order_statuses as $order_status){ ?>
                      <?php if(isset($exchange_wmd_order_status['complete']) && $exchange_wmd_order_status['complete'] == $order_status['order_status_id']){ ?>
                        <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                      <?php }else{ ?>
                        <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                      <?php } ?>
                    <?php } ?>
                  </select>
                </td>
              </tr>
              <tr>
                <td><?php echo $entry_status_cancel; ?></td>
                <td>
                  <select name="exchange_wmd_order_status[cancel]">
                    <?php foreach($order_statuses as $order_status){ ?>
                      <?php if(isset($exchange_wmd_order_status['cancel']) && $exchange_wmd_order_status['cancel'] == $order_status['order_status_id']){ ?>
                        <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                      <?php }else{ ?>
                        <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                      <?php } ?>
                    <?php } ?>
                  </select>
                </td>
              </tr>
            </table>
          </div>
          <div id="tab-contacts">
            <p>
                <div><?php echo $text_module_version; ?>: 1.0</div>
                <div><?php echo $text_support; ?>: <a href="mailto:sale@wmd.ru">sale@wmd.ru</a>, <a href="mailto:payment@wmd.ru">payment@wmd.ru</a></div>
                <div><?php echo $text_docs_link; ?>: <a href="http://www.wmd.ru/affiliates.html">www.wmd.ru/affiliates.html</a></div>
            </p>
            <?php echo $text_contact_description; ?>
          </div>
      </form>
    </div>
  </div>
</div>
<script type="text/javascript"><!--
$('#tabs a').tabs(); 
//--></script> 
<?php echo $footer; ?>
