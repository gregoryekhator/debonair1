<?php
if (!defined('ABSPATH'))
  exit;
$delete_nonce = wp_create_nonce("delete_document");
$update_nonce = wp_create_nonce("update_document");
$scheduler_nonce = wp_create_nonce("update_scheduler");
global $document;
$generalOptions = get_option('CRM_general_settings');
$documentOptions = get_option('CRM_documents_settings');
$payOptions = get_option('CRM_documents_settings');
if (isset($payOptions['delayedPayments']))
  $arr_payments = maybe_unserialize($payOptions['delayedPayments']);
$def_iva = $documentOptions['default_vat'];
$accOptions = get_option("CRM_acc_settings");
if (isset($_GET["id_invoice"]) && ($ID = $_GET["id_invoice"])) {
  $plugin_dir = dirname(dirname(dirname(dirname(__FILE__))));
} else {
  $ID = isset($_REQUEST["ID"]) ? $_REQUEST["ID"] : 0;
  $a_table = WPsCRM_TABLE . "agenda";
  $d_table = WPsCRM_TABLE . "documenti";
  $dd_table = WPsCRM_TABLE . "documenti_dettaglio";
  $c_table = WPsCRM_TABLE . "clienti";
  $s_table = WPsCRM_TABLE . "subscriptionrules";
  if ($ID) {
    $sql = "select * from $d_table where id=$ID";
    $riga = $wpdb->get_row($sql, ARRAY_A);
    $type = $riga["tipo"];
    $data = WPsCRM_culture_date_format($riga["data"]);
    $payment = $riga["modalita_pagamento"];
    $data_scadenza = WPsCRM_culture_date_format($riga["data_scadenza"]);
    $giorni_pagamento = $riga["giorni_pagamento"];
    isset($riga["tempi_chiusura_dal"]) ? $tempi_chiusura_dal = WPsCRM_inverti_data($riga["tempi_chiusura_dal"]) : "";
    $oggetto = isset($riga["oggetto"]) ? $riga["oggetto"] : "";
    $iva = isset($riga["iva"]) ? $riga["iva"] : "";
    $tot_imp = sprintf("%01.2f", $riga["totale_imponibile"]);
    $tipo_sconto = $riga["tipo_sconto"];
    $totale_imposta = sprintf("%01.2f", $riga["totale_imposta"]);
    $tot_cassa = sprintf("%01.2f", $riga["tot_cassa_inps"]);
    $ritenuta_acconto = sprintf("%01.2f", $riga["ritenuta_acconto"]);
    $totale = $riga["totale"];
    $totale_netto = $riga["totale_netto"];

    if ($fk_clienti = $riga["fk_clienti"]) {
      $sql = "select ragione_sociale, nome, cognome, indirizzo, cap, localita, provincia, cod_fis, p_iva, tipo_cliente from $c_table where ID_clienti=" . $fk_clienti;
      $rigac = $wpdb->get_row($sql, ARRAY_A);
      $cliente = $rigac["ragione_sociale"] ? $rigac["ragione_sociale"] : $rigac["nome"] . " " . $rigac["cognome"];
      $cliente = stripslashes($cliente);
      $indirizzo = stripslashes($rigac["indirizzo"]);
      $cap = $rigac["cap"];
      $localita = stripslashes($rigac["localita"]);
      $provincia = $rigac["provincia"];
      $cod_fis = $rigac["cod_fis"];
      $p_iva = $rigac["p_iva"];
      $tipo_cliente = $rigac["tipo_cliente"];
    }
    $wpdb->update(
            $dd_table, array('eliminato' => 0), array('fk_documenti' => $ID), array('%d')
    );
    $sql = "select * from $dd_table where fk_documenti=$ID order by n_riga";
    $qd = $wpdb->get_results($sql, ARRAY_A);
    $sql = "select $s_table.* from $s_table, $a_table where fk_documenti=$ID and $s_table.ID =$a_table.fk_subscriptionrules and $a_table.fk_documenti_dettaglio=0";
    if ($record = $wpdb->get_row($sql)) {
      $steps = json_decode($record->steps);
      foreach ($steps as $step) {
        $users = $step->selectedUsers;
        $groups = $step->selectedGroups;
      }
    }
    is_multisite() ? $filter = get_blog_option(get_current_blog_id(), 'active_plugins') : $filter = get_option('active_plugins');
    if (in_array('wp-smart-crm-agents/wp-smart-crm-agents.php', apply_filters('active_plugins', $filter))) {
      $agent_obj = new AGsCRM_agent();
      $documentPrivileges = $agent_obj->getInvoicePrivileges($ID);
    } else
      $documentPrivileges = null;
  }
  else {
    $data = WPsCRM_culture_date_format(date("d-m-Y"));
    $iva = $documentOptions['default_vat'];
    $tempi_chiusura_dal = WPsCRM_culture_date_format(date("d-m-Y"));
    $FK_clienti = 0;
    $FK_contatti = 0;
    if (isset($documentOptions['invoice_noty_days']))
      $giorni_pagamento = $documentOptions['invoice_noty_days'];
    $documentPrivileges = null;
    $tipo_sconto = 0;
    $tot_imp = 0;
    $tot_cassa = 0;
    $totale_imposta = 0;
    $totale = 0;
    $ritenuta_acconto = 0;
    $totale_netto = 0;
  }
  ?>

  <style>
      #tabstrip div.k-content:not(first-child){padding-top:30px}
      h4.page-header{background:gainsboro;padding:10px 4px}
      ._forminvoice li{padding:2px!important}
      <?php if (isset($_GET['layout']) && $_GET['layout'] == "iframe") { ?>
        #wpadminbar, #adminmenumain, #mainMenu,.wrap h1,.btn-warning,.select-action:first-of-type {
            display: none;
        }
        #wpcontent, #wpfooter {
            margin-left: 0;
        }
      <?php } ?>
  </style>
  <script>
  <?php
  if (in_array('wp-smart-crm-agents/wp-smart-crm-agents.php', apply_filters('active_plugins', $filter))) {
    ?>
      privileges = <?php echo json_encode($documentPrivileges) ?>;
  <?php } else {
    ?>
      privileges = null;
  <?php } ?>
    var $format = "<?php echo WPsCRM_DATEFORMAT ?>";
    var $formatTime = "<?php echo WPsCRM_DATETIMEFORMAT ?>";
    var cliente = "<?php if (isset($cliente)) echo $cliente ?>";</script>
  <form name="form_insert" action="" method="post" id="form_insert">
      <input type="hidden" name="num_righe" id="num_righe" value="">
      <input type="hidden" name="ID" id="ID" value="<?php echo $ID ?>">
      <input type="hidden" name="type" id="type" value="2">
      <h1 style="text-align:center"><?php _e('CREATE/EDIT INVOICE', 'wp-smart-crm-invoices-free') ?> <i class="glyphicon glyphicon-fire"></i></h1>
      <div id="tabstrip">
          <ul>
              <li id="tab1"><?php _e('INVOICE', 'wp-smart-crm-invoices-free') ?></li>
              <li onclick="aggiornatot();"><?php _e('COMMENTS AND INTERNAL DATA', 'wp-smart-crm-invoices-free') ?></li>
          </ul>
          <div>
              <h4 class="page-header" style="margin: 10px 0 20px;float: left;padding: 6px 0;width:100%">
                  <span class="col-md-1" style="width:8px">
                      <span class="crmHelp" data-help="document-data" style="margin: 0 -10px;"></span>
                  </span>				
                  <span class="col-md-2" style="line-height:32px">
  <?php _e('INVOICE DATA', 'wp-smart-crm-invoices-free') ?>
                  </span>

                  <label class="control-label col-md-1"><?php _e('Issue Date', 'wp-smart-crm-invoices-free') ?></label>
  <?php if ($ID) { ?>
                    <span class="col-sm-1" style="min-width:110px">
                        <input name="data" id="data" class="form-control  _m" data-placement="bottom" title="<?php _e('Date', 'wp-smart-crm-invoices-free') ?>" value="<?php echo $data ?>" style="border:none" />
                    </span>
  <?php } else { ?>
                    <span class="col-sm-1" style="margin-top: -4px;min-width:110px">
                        <input name="data" id="data" class="form-control _m" data-placement="bottom" title="<?php _e('Date', 'wp-smart-crm-invoices-free') ?>" value="" style="border:none" />
                    </span>
  <?php } ?>

                  <label class="col-sm-1 control-label"><?php _e('Number', 'wp-smart-crm-invoices-free') ?></label>
                  <span class="col-sm-2">
                      <input name="progressivo" id="progressivo" class="form-control" data-placement="bottom" title="<?php _e('Number', 'wp-smart-crm-invoices-free') ?>" value="<?php if (isset($riga)) echo $riga["progressivo"] ?>" readonly disabled />
                  </span>

  <?php if ($ID) { ?>
                    <span style="/*float:right;*/font-size:.8em;text-decoration:underline;cursor:pointer;margin-top: 8px;" class="_edit_header col-md-1">
                        <i class="glyphicon glyphicon-pencil"></i> <?php _e('Edit', 'wp-smart-crm-invoices-free') ?>
                    </span>
  <?php } ?>
                  <div class="row" id="edit_warning" style="display:none;font-size:.8em;color:red;margin-top:20px"><div class="col-md-4 pull-right"><?php _e('WARNING: edit date and number may cause incongruences in your accounting', 'wp-smart-crm-invoices-free') ?></div></div>
              </h4>
              <div class="row form-group">
                  <label class="col-sm-1 control-label"><?php _e('Reference', 'wp-smart-crm-invoices-free') ?></label>
                  <div class="col-sm-3">
                      <input type="text" class="form-control" name="riferimento" id="riferimento" maxlength='55' value="<?php if (isset($riga)) echo $riga["riferimento"] ?>">
                  </div>
                  <label class="col-sm-2 control  -label"><?php _e('Notes', 'wp-smart-crm-invoices-free') ?></label>
                  <div class="col-sm-4">
                      <textarea class="_form-control col-md-12" id="annotazioni" name="annotazioni" rows="5"><?php if (isset($riga)) echo stripslashes($riga["annotazioni"]) ?></textarea><br />
                      <small><i>(<?php _e('Will be shown in the document', 'wp-smart-crm-invoices-free') ?>)</i></small>
                  </div>
              </div>
              <div class="row form-group">
                  <hr />
                  <label class="col-sm-1 control-label"><?php _e('Payment methods', 'wp-smart-crm-invoices-free') ?></label>
                  <div class="col-sm-2">

                      <select name="modalita_pagamento" id="modalita_pagamento" class="_form-control col-md-12">
                          <option value="0" <?php if (isset($riga) && $payment == 0) echo "selected" ?>><?php _e('Select', 'wp-smart-crm-invoices-free') ?></option>
                          <?php
                          if (isset($arr_payments))
                            foreach ($arr_payments as $pay) {
                              $pay_label = explode('~', $pay);
                              if (!empty($pay_label[1]))
                                $_pay_label = $pay_label[0] . " (" . $pay_label[1] . " " . __('dd', 'wp-smart-crm-invoices-free') . ")";
                              else
                                $_pay_label = $pay_label[0];
                              if (strstr($pay, $payment) && $payment != "0")
                                $selected = " selected";
                              else
                                $selected = "";
                              ?>
                              <option value="<?php echo str_replace("  ", " ", $pay) ?>" <?php echo $selected ?>><?php echo $_pay_label ?></option>
    <?php } ?>
                      </select>
                  </div>
                  <label class="control-label  col-md-1"><?php _e('Payment exp. Date', 'wp-smart-crm-invoices-free') ?></label>
                  <div class="col-sm-1" style="min-width:110px">
                      <input name="data_scadenza" id="data_scadenza" class="form-control _m" data-placement="bottom"  value="<?php if (isset($riga)) echo $data_scadenza ?>" style="border:none" />
                  </div>
                  <?php
                  if ($ID) {
                    ?>
                    <label class="control-label col-md-1" style="width:30px"><?php _e('Paid', 'wp-smart-crm-invoices-free') ?>?</label>
                    <div class="col-sm-1" style="width:30px;padding-right:10px">
                        <input type="checkbox" name="pagato" value="1" <?php if (isset($riga)) echo $riga["pagato"] ? "checked" : "" ?>>
                    </div>
                    <?php
                  }
                  ?>
                  <label class="control-label col-md-1" style="width:30px"><?php _e('Notify', 'wp-smart-crm-invoices-free') ?>? </label>
                  <div class="col-sm-1" style="width:30px">
                      <input  type="checkbox" name="notify_payment" id="notify_payment" value="1" <?php if (isset($riga)) echo $riga["notifica_pagamento"] ? "checked" : "" ?>>

                  </div>
                  <div class="col-sm-1"><span class="crmHelp crmHelp-dark" data-help="payment-notification"></span></div>
              </div>
              <section id="notifications" style="display:none!important">
                  <h4 class="page-header"><?php _e('Invoice payment reminder', 'wp-smart-crm-invoices-free') ?> </h4>

                  <div class="row form-group">
                      <label class="col-sm-1"><?php _e('Send to User', 'wp-smart-crm-invoices-free') ?></label><div class="col-sm-2"><input class="ruleActions" id="remindToUser" name="remindToUser" /></div>
                      <label class="col-sm-1"><?php _e('Send to Group', 'wp-smart-crm-invoices-free') ?></label><div class="col-sm-2"><input class="ruleActions" id="remindToGroup" name="remindToGroup"></div>
                      <label class="col-sm-1"><?php _e('Days after expiration', 'wp-smart-crm-invoices-free') ?></label><div class="col-sm-2"><input class="ruleActions" id="notificationDays" name="notificationDays" type="number" value="<?php if (isset($$giorni_pagamento)) echo $giorni_pagamento ?>"><small id="changeNoty"><a href="#" onclick="return false;"><?php _e('Edit default value', 'wp-smart-crm-invoices-free') ?>&raquo;</a></small></div>
                      <input type="hidden" id="selectedUsers" name="selectedUsers" class="ruleActions" value="" />
                      <input type="hidden" id="selectedGroups" name="selectedGroups" class="ruleActions" value="" />

                  </div>
              </section>

              <h4 class="page-header">
                  <?php _e('CUSTOMER DATA', 'wp-smart-crm-invoices-free') ?><span class="crmHelp" data-help="customer-data"></span>
                  <?php
                  if (isset($fk_clienti)) {
                    echo "<a href=\"" . admin_url('admin.php?page=smart-crm&p=clienti/form.php&ID=' . $fk_clienti) . "\" target=\"_blank\"><span class=\"header_customer\" >" . $cliente . "</span></a>";
                  }
                  ?>
                  <ul class="select-action _forminvoice" style="float:right;/*transform:scale(.8);*/background-color:transparent;margin:0!important;margin-top:-8px!important;width:inherit">
  <?php if ($ID) { ?>
                        <li class="btn _edit _white btn-sm _flat">
                            <i class="glyphicon glyphicon-pencil"></i>
                            <b> <?php _e('EDIT CUSTOMER DETAILS', 'wp-smart-crm-invoices-free') ?></b>
                        </li>
                        <li style="display:none" class="btn btn-danger _quitEdit btn-sm _flat">
                            <i class="glyphicon glyphicon-close"></i>
                            <b> <?php _e('QUIT EDITING', 'wp-smart-crm-invoices-free') ?></b>
                        </li>
                        <li class="btn"><i class="_tooltip glyphicon glyphicon-menu-right"></i></li>
                        <li class="btn btn-info btn-sm _flat btn_todo" style="margin-left:10px" title="<?php _e('NEW TODO', 'wp-smart-crm-invoices-free') ?>">
                            <i class="glyphicon glyphicon-tag"></i>
                            <b> </b>
                        </li>
                        <li class="btn  btn-sm _flat btn_appuntamento" title="<?php _e('NEW APPOINTMENT', 'wp-smart-crm-invoices-free') ?>">
                            <i class="glyphicon glyphicon-pushpin"></i>
                            <b> </b>
                        </li>
                        <li class="btn btn-primary btn-sm _flat btn_activity" title="<?php _e('NEW ANNOTATION', 'wp-smart-crm-invoices-free') ?>">
                            <i class="glyphicon glyphicon-option-horizontal"></i>
                            <b> </b>
                        </li>
                        <?php do_action('WpsCRM_advanced_document_buttons'); ?>
  <?php } ?>

                  </ul>
              </h4>
              <div class="customer_data_partial" data-customer="<?php if (isset($fk_clienti)) echo $fk_clienti ?>">
                  <input type="hidden" id="tipo_cliente" name="tipo_cliente" value="<?php if (isset($tipo_cliente)) echo $tipo_cliente ?>" data-value="<?php if (isset($tipo_cliente)) echo $tipo_cliente ?>" />
                  <div class="row form-group">
                      <label class="col-sm-1 control-label"><?php _e('Customer', 'wp-smart-crm-invoices-free') ?></label>
                      <div class="col-md-3 col-sm-2">
                          <?php
                          if (isset($fk_clienti)) {
                            $disabled = "disabled readonly";
                          } else
                            $disabled = "";
                          ?>
                          <select id="fk_clienti" name="fk_clienti"></select>
                          <input type="hidden" name="hidden_fk_clienti" id="hidden_fk_clienti" value="<?php if (isset($fk_clienti)) echo $fk_clienti ?>">

                      </div>
                      <div class="col-sm-2">
                          <input type="button" class="btn btn-sm btn-success _flat" id="save_client_data" name="save_client_data" value="<?php _e('Save', 'wp-smart-crm-invoices-free') ?>" style="display:none" />
                      </div>
                  </div>
                  <div class="row form-group">
                      <label class="col-sm-1 control-label"><?php _e('Address', 'wp-smart-crm-invoices-pro') ?></label>
                      <div class="col-sm-2 col-md-2 col-lg-3">

                          <input type="text" class="form-control _editable" name="indirizzo" id="indirizzo" maxlength='50' value="<?php if (isset($indirizzo)) echo $indirizzo ?>" <?php echo $disabled ?> data-value="<?php if (isset($indirizzo)) echo $indirizzo ?>" />

                      </div>
                      <label class="col-sm-1 control-label"><?php _e('ZIP code', 'wp-smart-crm-invoices-pro') ?></label>
                      <div class="col-sm-2">

                          <input type="text" class="form-control _editable" name="cap" id="cap" maxlength='10' value="<?php if (isset($cap)) echo $cap ?>" <?php echo $disabled ?> data-value="<?php if (isset($cap)) echo $cap ?>">

                      </div>
                      <label class="col-sm-1 control-label"><?php _e('C.F.', 'wp-smart-crm-invoices-pro') ?></label>
                      <div class="col-md-2">
                          <input type="text" class="form-control _editable" name="cod_fis" id="cod_fis" maxlength='20' value="<?php if (isset($cod_fis)) echo $cod_fis ?>" <?php echo $disabled ?> data-value="<?php if (isset($cod_fis)) echo $cod_fis ?>">
                      </div>

                  </div>
                  <div class="row form-group">
                      <label class="col-sm-1 control-label"><?php _e('Town', 'wp-smart-crm-invoices-pro') ?></label>
                      <div class="col-sm-2 col-md-2 col-lg-3">

                          <input type="text" class="form-control _editable" name="localita" id="localita" maxlength='50' value="<?php if (isset($localita)) echo $localita ?>" <?php echo $disabled ?> data-value="<?php if (isset($localita)) echo $localita ?>">

                      </div>
                      <label class="col-sm-1 control-label"><?php _e('State/Prov.', 'wp-smart-crm-invoices-pro') ?></label>
                      <div class="col-sm-2">

                          <input type="text" class="form-control _editable" name="provincia" id="provincia" maxlength='20' value="<?php if (isset($provincia)) echo $provincia ?>" <?php echo $disabled ?> data-value="<?php if (isset($provincia)) echo $provincia ?>">

                      </div>
                      <label class="col-sm-1 control-label"><?php _e('VAT code', 'wp-smart-crm-invoices-pro') ?></label>
                      <div class="col-md-2">
                          <input type="text" class="form-control _editable" name="p_iva" id="p_iva" maxlength='20' value="<?php if (isset($p_iva)) echo $p_iva ?>" <?php echo $disabled ?> data-value="<?php if (isset($p_iva)) echo $p_iva ?>">
                      </div>

                  </div>
              </div>
              <h4 class="page-header"><?php _e('Add Products to invoice', 'wp-smart-crm-invoices-free') ?><span class="crmHelp" data-help="invoice-products"></span>

  <?php do_action("WPsCRM_show_WOO_products"); ?>

              </h4>

              <?php
              $accontOptions = get_option("CRM_acc_settings");

              switch ($accontOptions['accountability']) {
                case 0:
                  include ('accountabilities/accountability_0.php');
                  break;
                case "1":
                  include (ACCsCRM_DIR . '/inc/crm/documenti/accountabilities/accountability_1.php');
                  break;
                case "2":
                  include (ACCsCRM_DIR . '/inc/crm/documenti/accountabilities/accountability_2.php');
                  break;
                case "3":
                  include (ACCsCRM_DIR . '/inc/crm/documenti/accountabilities/accountability_3.php');
                  break;
                case "4":
                  include (ACCsCRM_DIR . '/inc/crm/documenti/accountabilities/accountability_4.php');
                  break;
              }
              ?>


          </div>
          <!--fine primo tab -->
          <!-- inizio secondo tab -->
          <div>
              <div class="row form-group">
                  <label class="col-sm-2 control-label"><?php _e('Comments', 'wp-smart-crm-invoices-free') ?></label>
                  <div class="col-sm-6">
                      <textarea class="_form-control" id="commento" name="commento" rows="10" cols="50"><?php if (isset($riga)) echo stripslashes($riga["commento"]) ?></textarea>
                  </div>
              </div>
          </div>
          <!--fine secondo tab -->
      </div>
      <input name="check" style="visibility:hidden" />
      <input type="submit" style="display:none" />
      <ul class="select-action">
          <?php
          if (isset($riga["registrato"]) && $riga["registrato"] == 0 || !$ID) {
            ?>
            <li class="btn btn-sm btn-success _flat" id="_submit">
                <i class="glyphicon glyphicon-floppy-disk"></i>
                <b> <?php _e('Save', 'wp-smart-crm-invoices-free') ?></b>
            </li>
  <?php } ?>
          <li class="btn btn-warning btn-sm _flat" onClick="annulla(); return false;">
              <i class="glyphicon glyphicon-floppy-remove"></i>
              <b> <?php _e('Cancel', 'wp-smart-crm-invoices-free') ?></b>
          </li>
          <?php
          if ($ID) {
            //$upload_dir = wp_upload_dir();
            //$document = $upload_dir['baseurl'] . "/CRMdocuments/".$filename.".pdf";
            if (WPsCRM_advanced_print()) {
              ?>
              <li class="btn btn-sm btn-info _flat" onclick="location.replace('?page=smart-crm&p=documenti/advanced_print.php&id_invoice=<?php echo $ID ?>')">
                  <i class="glyphicon glyphicon-print"></i>
                  <b> <?php _e('Printable version', 'wp-smart-crm-invoices-free') ?></b>
              </li>
    <?php } else { ?>
              <li class="btn btn-sm btn-info _flat" onclick="location.replace('?page=smart-crm&p=documenti/document_print.php&id_invoice=<?php echo $ID ?>')">
                  <i class="glyphicon glyphicon-print"></i>
                  <b> <?php _e('Printable version', 'wp-smart-crm-invoices-free') ?></b>
              </li>

              <?php
            }
          }
          ?>
      </ul>
  </form> 
  <div id="dialog-view" style="display:none;margin: 0 auto; text-align: center; z-index: 1000; width: 100%; height: 100%; background: url('<?php echo str_replace("inc/crm/documenti/", "", plugin_dir_url(__FILE__)) ?>css/img/bg_w_tr.png');position: absolute;left: 0;top:0;"  class="_modal" data-from="documenti">
      <div class="col-md-6 panel panel-primary _flat modal_inner" style="border:1px solid #666;text-align:left;background:#fff;padding-bottom:20px;margin: 46px auto;float: none;padding:0;position:relative">
          <div class="panel-heading" style="padding: 3px 10px;">
              <h3 style="text-align:center;margin-top: 8px;"><?php _e('Change default days', 'wp-smart-crm-invoices-free') ?><span class="crmHelp" data-help="deafult-invoice-payment-noty"></span></h3>
          </div>
          <div class="panel-body" style="padding:50px">
              <label><?php _e('Change default value', 'wp-smart-crm-invoices-free') ?></label><input class="ruleActions" name="new_default_noty" id="new_default_noty" type="number" value="<?php if (isset($documentOptions['invoice_noty_days'])) echo $documentOptions['invoice_noty_days'] ?>">
              <span class="btn btn-success btn-sm _flat" id="notyConfirm"><?php _e('Confirm', 'wp-smart-crm-invoices-free') ?></span>
              <span class="btn btn-warning btn-sm _flat _reset" ><?php _e('Reset', 'wp-smart-crm-invoices-free') ?></span>
          </div>
      </div>
  </div>
  <div id="dialog_todo" style="display:none;" data-from="documenti" data-fkcliente="<?php if (isset($fk_clienti)) echo $fk_clienti ?>">
      <?php
      include ( WPsCRM_DIR . "/inc/crm/clienti/form_todo.php" )
      ?>
  </div>
  <?php
  include (WPsCRM_DIR . "/inc/crm/clienti/script_todo.php" )
  ?>
  <div id="dialog_appuntamento" style="display:none;" data-from="documenti" data-fkcliente="<?php if (isset($fk_clienti)) echo $fk_clienti ?>">
      <?php
      include (WPsCRM_DIR . "/inc/crm/clienti/form_appuntamento.php" )
      ?>
  </div>
  <?php
  include (WPsCRM_DIR . "/inc/crm/clienti/script_appuntamento.php" )
  ?>
  <div id="dialog_attivita" style="display:none;"  data-from="documenti" data-fkcliente="<?php if (isset($fk_clienti)) echo $fk_clienti ?>">
      <?php
      include (WPsCRM_DIR . "/inc/crm/clienti/form_attivita.php" )
      ?>
  </div>
  <?php
  include (WPsCRM_DIR . "/inc/crm/clienti/script_attivita.php" )
  ?>
  <div id="dialog_mail" style="display:none;" data-from="documenti" data-fkcliente="<?php if (isset($fk_clienti)) echo $fk_clienti ?>">
      <?php
      include (WPsCRM_DIR . "/inc/crm/clienti/form_mail.php" )
      ?>    
  </div>
  <?php
  include (WPsCRM_DIR . "/inc/crm/clienti/script_mail.php" )
  ?>
  <style>
      .customer_data_partial{padding-top:6px;padding-bottom:6px}
      .edit_active{border:1px dashed red;background:#ccc}
  </style>
  <div id="reverseCalculator">
      <div class="col-md-11">
          <label><?php _e('Input full amount for reverse calculation:', 'wp-smart-crm-invoices-free') ?></label><input class="form-control" type="number" id="reverseAmount" />
      </div>
      <div class="col-md-11">
          <label><?php _e('Input refund for reverse calculation:', 'wp-smart-crm-invoices-free') ?></label><input class="form-control" type="number" id="reverseRefund" />
      </div>
      <div class="col-md-11"><br />
          <input class="btn _flat btn-success" type="button" id="calculate" value="<?php _e('Calculate:', 'wp-smart-crm-invoices-free') ?>" />
      </div>
  </div>
  <script type="text/javascript">
    jQuery(document).ready(function ($) {
    sessionStorage.removeItem('tmp_amount');
    $('#reverseCalculator').kendoWindow({
    width: "400px",
            height: "300px",
            title: "<?php _e('Calculate from full amount:', 'wp-smart-crm-invoices-free') ?>",
            visible: false,
            modal: true,
            draggable: false,
            resizable:false,
            pinned: true,
            actions: [

                    "Close"
            ],
            close: function () {

            $('.modal_loader').hide();
            }
    })
            $('.reverseCalulator').on('click', function () {
    if (clientValidator.validate() && !$(this).hasClass('disabled'))
            $('#reverseCalculator').data('kendoWindow').center().open();
    })
            $("._tooltip").kendoTooltip({
    //autoHide: false,
    animation: {
    close: {
    duration: 1000,
    }
    },
            position:"top",
            content: "<h4><?php _e('BUTTONS LEGEND', 'wp-smart-crm-invoices-free') ?>:</h4>\n\
      <ul>\n\
          <li class=\"no-link\">\n\
              <span class=\"btn btn-info _flat\"><i class=\"glyphicon glyphicon-tag\"></i> = <?php _e('NEW TODO', 'wp-smart-crm-invoices-free') ?></span>\n\
              <span class=\"btn btn_appuntamento_1 _flat\"><i class=\"glyphicon glyphicon-pushpin\"></i> = <?php _e('NEW APPOINTMENT', 'wp-smart-crm-invoices-free') ?></span>\n\
              <span class=\"btn btn-primary _flat\"><i class=\"glyphicon glyphicon-option-horizontal\"></i> = <?php _e('NEW ACTIVITY', 'wp-smart-crm-invoices-free') ?></span>\n\
              <span class=\"btn btn-warning _flat\"><i class=\"glyphicon glyphicon-envelope\"></i> = <?php _e('NEW MAIL', 'wp-smart-crm-invoices-free') ?></span>\n\
          </li>\n\
      </ul>"
    });
    var mainValidator = jQuery("#form_insert").kendoValidator({
    rules: {
    hasClient: function (input) {
    if (input.is("[name=fk_clienti]")) {
    if (jQuery('input[name="fk_clienti"]').attr('type') != "hidden") {
    var kb = jQuery("#fk_clienti").data("kendoDropDownList").value();
    console.log(kb)
            if (kb.length == "") {
    jQuery.playSound("<?php echo WPsCRM_URL ?>inc/audio/double-alert-2");
    //jQuery('input[name="fk_clienti"]').focus();
    return false;
    }
    }
    return true;
    }
    return true;
    },
            hasNoty: function (input) {
            if (input.is("[name=remindToUser]")) {
            if (jQuery('input[name="notify_payment"]:checked').length) {
            var kb = jQuery("#remindToUser").data("kendoMultiSelect").value();
            var kb1 = jQuery("#remindToGroup").data("kendoMultiSelect").value();
            if (kb == "" && kb1 == "") {
            jQuery.playSound("<?php echo WPsCRM_URL ?>inc/audio/double-alert-2");
            return false;
            }
            }
            }
            if (input.is("[name=remindToGroup]")) {
            if (jQuery('input[name="notify_payment"]:checked').length) {
            var kb = jQuery("#remindToUser").data("kendoMultiSelect").value();
            var kb1 = jQuery("#remindToGroup").data("kendoMultiSelect").value();
            if (kb == "" && kb1 == "") {
            jQuery.playSound("<?php echo WPsCRM_URL ?>inc/audio/double-alert-2");
            return false;
            }
            }
            }
            return true;
            },
            hasRows: function (input) {
            if (input.is("[name=check]")) {
            console.log(jQuery('.riga').length)
                    if (jQuery('.riga').length == 0) {
            jQuery.playSound("<?php echo WPsCRM_URL ?>inc/audio/double-alert-2");
            return false;
            }
            }
            return true;
            },
            hasDescription: function (input) {
            if (input.hasClass("descriptive_row")) {

            if (input.val() == "") {
            jQuery.playSound("<?php echo WPsCRM_URL ?>inc/audio/double-alert-2");
            return false;
            }
            }
            return true;
            }
    },
            messages: {
            //hasExpiration: "<?php _e('You should select an expiration date; select today for a non-relevant ', 'wp-smart-crm-invoices-free') ?>",
            hasNoty: "<?php _e('You should select a user or a group of users to notify to', 'wp-smart-crm-invoices-free') ?>",
                    hasClient: "<?php _e('You should select a customer', 'wp-smart-crm-invoices-free') ?>",
                    hasRows: "<?php _e('You should add at least one row to this invoice', 'wp-smart-crm-invoices-free') ?>",
                    hasDescription:"<?php _e('Description is mandatory', 'wp-smart-crm-invoices-free') ?>"
            }
    }).data("kendoValidator");
    $('#_submit').on('click', function (e) {
    if (mainValidator.validate()) {
    jQuery("#progressivo").attr("disabled", false);
    showMouseLoader();
    jQuery('#mouse_loader').offset({ left: e.pageX, top: e.pageY });
    //var n_row = jQuery('#t_art > tbody > tr').length;
    if (jQuery('#t_art > tbody > tr').length){
        var last = jQuery('#t_art > tbody > tr').last().attr("id");
        var last_id=last.split("_");
        var n_row=parseInt(last_id[1]);
      }
      else{
        var n_row=0;
      }
    jQuery('#num_righe').val(n_row);
    //ajax save
    var form = jQuery('form');
    jQuery.ajax({
    url: ajaxurl,
            data: {
            action: 'WPsCRM_save_document',
                    fields:form.serialize(),
                    security:'<?php echo $update_nonce; ?>'

            },
            type: "POST",
            success: function (response) {
            console.log(response);
            jQuery("#progressivo").attr("disabled", 'disabled');
            if (response.indexOf('OK') != - 1) {
            var tmp = response.split("~");
            var id_cli = tmp[1];
            hideMouseLoader();
            noty({
            text: "<?php _e('Document has been saved', 'wp-smart-crm-invoices-free') ?>",
                    layout: 'center',
                    type: 'success',
                    template: '<div class="noty_message"><span class="noty_text"></span></div>',
                    //closeWith: ['button'],
                    timeout: 1000
            });
            jQuery("#ID").val(id_cli);
  <?php if (isset($_REQUEST["layout"]) && $_REQUEST["layout"] == "iframe") { ?>
              $(window.parent.document).find(".k-i-close").trigger("click");
  <?php } else if (!$ID) { ?>
              setTimeout(function () {
              location.href = "<?php echo admin_url('admin.php?page=smart-crm&p=documenti/form_invoice.php&ID=') ?>" + id_cli;
              }, 1000)
  <?php } ?>

            }
            else {
            noty({
            text: "<?php _e('Something was wrong', 'wp-smart-crm-invoices-free') ?>" + ": " + response,
                    layout: 'center',
                    type: 'error',
                    template: '<div class="noty_message"><span class="noty_text"></span></div>',
                    closeWith: ['button'],
                    //timeout: 1000
            });
            }

            }
    })
            //jQuery('#form_insert').find(':submit').click();
    }
    })
            //usato solo per i controlli sulle contabilit� avanzate che richiedono il tipo cliente per le ritenute d'acconto
            var clientValidator = jQuery("#form_insert").kendoValidator({
    rules: {
    hasClient: function (input) {
    if (input.is("[name=fk_clienti]")) {
    if (jQuery('input[name="fk_clienti"]').attr('type') != "hidden") {
    var kb = jQuery("#fk_clienti").data("kendoDropDownList").value();
    console.log(kb)
            if (kb.length == "") {
    jQuery.playSound("<?php echo WPsCRM_URL ?>inc/audio/double-alert-2");
    jQuery('html, body').animate({
    scrollTop: jQuery('select[name="fk_clienti"]').offset().top - 100
    }, 300);
    return false;
    }
    }
    return true;
    }
    return true;
    }
    },
            messages: {
            hasClient: "<?php _e('You should select a customer', 'wp-smart-crm-invoices-free') ?>",
            }
    }).data("kendoValidator");
    var todayDate = kendo.toString(new Date(), $format, localCulture);
    var todayAbsoluteDate = new Date();
    $("#data").kendoDatePicker({
  <?php if (!$ID) { ?>
      value: todayDate,
  <?php } ?>
    format: $format,
            width:110
    })
            var issuedate = $("#data").data("kendoDatePicker");
    $("#data_scadenza").kendoDatePicker({
  <?php if ($data_scadenza == "") { ?>
      value: todayDate,
  <?php } ?>
    format: $format,
            width:110
    })
            issuedate.bind("change", function () {
            var dateToBind = $('#data_scadenza').data('kendoDatePicker');
            var $date = $('#modalita_pagamento').val();
            $date = $date.split('~');
            $date[1] != undefined ? bindPayToExpiration($date[1], 'data_scadenza', $format) : null;
            });
    if ($('input[name="notify_payment"]:checked').length) {
    $('#notifications').show();
    }
    else {
    $('#notifications').hide();
    }
    $('#modalita_pagamento').on('change', function () {
    var $date = $(this).val();
    $date = $date.split('~');
    $date[1] != undefined ? bindPayToExpiration($date[1], 'data_scadenza', $format) : null;
    })
            function bindPayToExpiration(days, el, format) {
            var dateToBind = $('#' + el).data('kendoDatePicker');
            var $date = new Date(kendo.parseDate($('#data').val(), format, localCulture)).getTime();
            //console.log($date);
            var $future = new Date(parseInt($date + days * 86400000))
                    var futureDate = kendo.toString(new Date($future));
            dateToBind.value(futureDate);
            }
    //var datepicker = $('#data').data("kendoDatePicker");
  <?php if ($ID) { ?> issuedate.readonly(true);<?php } ?>
    $('._edit_header').on('click', function () {
    toggle_read('data', 'edit_warning', true);
    toggle_dis('progressivo', 'edit_warning')
            toggle_read('progressivo', 'edit_warning')
    });
    function toggle_dis(el, msg) {
    if ($('#' + el).attr('disabled') == 'disabled') {
    $('#' + el).attr('disabled', false)
            $('#' + msg).show()
    }
    else {
    $('#' + el).attr('disabled', 'disabled')
            $('#' + msg).hide()
    }
    }
    function toggle_read(el, msg, k_el) {
    if ($('#' + el).attr('readonly') == 'readonly') {
    $('#' + el).attr('readonly', false)
            $('#' + msg).show()
            if (k_el) {
    //var datepicker = $('#' + el).data("kendoDatePicker");
    issuedate.enable(true);
    issuedate.readonly(false)
    }
    }
    else {
    $('#' + el).attr('readonly', 'readonly')
            $('#' + msg).hide()
            if (k_el) {
    //var datepicker = $('#' + el).data("kendoDatePicker");
    issuedate.enable(false)
            issuedate.readonly(true)
    }
    }

    }
  <?php if ($ID) { ?>
      $("#fk_clienti").kendoDropDownList({
      enable: false
      });
  <?php } ?>

    $('._edit').on('click', function () {
    var $this = $(this);
    $this.hide();
    $('._quitEdit').show();
    var dropdownlist = $("#fk_clienti").data("kendoDropDownList");
    //dropdownlist.enable(true);
    $('._editable').attr('readonly', false).attr('disabled', false);
    $('#_submit').css('visibility', 'hidden');
    $('#save_client_data').show();
    $('#save_client_data').parent().append("<br><small class=\"_notice notice notice-error \"><?php _e("You're editing the master data for this customer", 'wp-smart-crm-invoices-free') ?></small>")
            $('.customer_data_partial').addClass('edit_active');
    });
    $('._quitEdit').on('click', function () {
    var dropdownlist = $("#fk_clienti").data("kendoDropDownList");
    var $this = $(this);
    $this.hide();
    $('._notice').hide().remove();
    $('._edit').show();
    $('._editable').attr('readonly', 'readonly').attr('disabled', 'disabled');
    $('._editable').each(function (e) {
    $(this).val('');
    $(this).val($(this).data('value'));
    })
            $('#_submit').css('visibility', 'visible');
    $('#save_client_data').hide();
    $('.customer_data_partial').removeClass('edit_active');
    });
    $('#save_client_data').on('click', function () {
    var inputs = $('.customer_data_partial :input').serialize();
    $.ajax({
    url: ajaxurl,
            method: 'POST',
            data: {
            action: 'WPsCRM_save_client_partial',
                    values: inputs,
                    security: '<?php echo $update_nonce ?>'
            },
            success: function (result) {
            console.log(result);
            noty({
            text: "<?php _e('Data has been saved', 'wp-smart-crm-invoices-free') ?>",
                    layout: 'center',
                    type: 'success',
                    template: '<div class="noty_message"><span class="noty_text"></span></div>',
                    //closeWith: ['button'],
                    timeout: 1000
            });
            setTimeout(function () {
            $('._quitEdit').hide();
            $('._notice').hide().remove();
            $('._edit').show();
            $('._editable').attr('readonly', 'readonly').attr('disabled', 'disabled');
            $('#_submit').css('visibility', 'visible');
            $('#save_client_data').hide();
            $('.customer_data_partial').removeClass('edit_active');
            }, 200)
                    $('.customer_data_partial :input').each(function () {
            $(this).attr('data-value', $(this).val())
            })
            },
            error: function (errorThrown) {
            console.log(errorThrown);
            }
    })
    });
    var _clients = new kendo.data.DataSource({
    transport: {
    read: function (options) {
    $.ajax({
    url: ajaxurl,
            data: {
            'action': 'WPsCRM_get_clients2'
            },
            success: function (result) {

            $("#fk_clienti").data("kendoDropDownList").dataSource.data(result.clients);
            },
            error: function (errorThrown) {
            console.log(errorThrown);
            }
    })
    }
    }
    });
    var clienti = $('#fk_clienti').kendoDropDownList({
    placeholder: "<?php _e('Select Customer', 'wp-smart-crm-invoices-free') ?>...",
            dataTextField: "ragione_sociale",
            dataValueField: "ID_clienti",
            filter: "contains",
            autoBind: false,
            minLength: 3,
            dataSource: _clients,
            change: function () {
            id_clienti = this.value();
            if (id_clienti != null && id_clienti != "" && id_clienti != undefined) {
            $.ajax({
            url: ajaxurl,
                    data: {
                    'action': 'WPsCRM_get_client_info',
                            'id_clienti': id_clienti
                    },
                    success: function (result) {
                    console.log(result.info);
                    var parseData = result.info;
                    JSON.stringify(parseData);
                    $("#indirizzo").val(parseData[0].indirizzo);
                    $("#cap").val(parseData[0].cap);
                    $("#localita").val(parseData[0].localita);
                    $("#provincia").val(parseData[0].provincia);
                    $("#cod_fis").val(parseData[0].cod_fis);
                    $("#p_iva").val(parseData[0].p_iva);
                    $("#tipo_cliente").val(parseData[0].tipo_cliente);
                    },
                    error: function (errorThrown) {
                    console.log(errorThrown);
                    }
            })
            }
            },
    }).data('kendoDropDownList');
    $('#fk_clienti').data('kendoDropDownList').value([<?php if (isset($fk_clienti)) echo $fk_clienti ?>]);
    //t_users.value([<?php echo wp_get_current_user()->ID ?>]);
  <?php if (isset($_GET['cliente'])) { ?>
      $('#fk_clienti').data('kendoDropDownList').value(<?php echo $_GET['cliente'] ?>)
              $('#fk_clienti').data('kendoDropDownList').trigger("change");
  <?php } ?>
    var userSource = new kendo.data.DataSource({
    transport: {
    read: function (options) {
    $.ajax({
    url: ajaxurl,
            data: {
            'action': 'WPsCRM_get_CRM_users',
            },
            success: function (result) {
            //console.log(result);
            $("#remindToUser").data("kendoMultiSelect").dataSource.data(result);
            },
            error: function (errorThrown) {
            console.log(errorThrown);
            }
    })
    }
    }
    });
    var roleSource = new kendo.data.DataSource({
    transport: {
    read: function (options) {
    $.ajax({
    url: ajaxurl,
            data: {
            'action': 'WPsCRM_get_registered_roles',
            },
            success: function (result) {
            console.log(result);
            $("#remindToGroup").data("kendoMultiSelect").dataSource.data(result.roles);
            },
            error: function (errorThrown) {
            console.log(errorThrown);
            }
    })
    }
    }
    });
    $('#remindToUser').kendoMultiSelect({
    placeholder: "<?php _e('Select User', 'wp-smart-crm-invoices-free') ?>...",
            dataTextField: "display_name",
            dataValueField: "ID",
            autoBind: false,
            dataSource: userSource,
            change: function (e) {
            var selectedUsers = (this.value()).clean("");
            $('#selectedUsers').val(selectedUsers)
            },
            dataBound: function (e) {
            var selectedUsers = (this.value()).clean("");
            $('#selectedUsers').val(selectedUsers)
            }
    })

            $('#remindToGroup').kendoMultiSelect({
    placeholder: "<?php _e('Select Role', 'wp-smart-crm-invoices-free') ?>...",
            dataTextField: "name",
            dataValueField: "role",
            autoBind: false,
            dataSource: roleSource,
            change: function (e) {
            var selectedGroups = (this.value()).clean("");
            $('#selectedGroups').val(selectedGroups)
            },
            dataBound: function (e) {
            var selectedGroups = (this.value()).clean("");
            $('#selectedGroups').val(selectedGroups)
            }
    });
    if (users = '<?php if (isset($users)) echo $users ?>')
    {
    users = users.split(",");
    $("#remindToUser").data('kendoMultiSelect').value(users);
    }
    if (groups = '<?php if (isset($groups)) echo $groups ?>')
    {
    groups = groups.split(",");
    $("#remindToGroup").data('kendoMultiSelect').value(groups);
    }
    /*
     if ($("#remindToUser").data('kendoMultiSelect').value() || $("#remindToGroup").data('kendoMultiSelect').value())
     {
     $('#notify_payment').trigger("click");
     $('#notifications').fadeToggle();
     }*/

    $('#notify_payment').on('click', function () {
    $('#notifications').is(':visible') ? $('#notifications').fadeOut(200) : $('#notifications').fadeIn(200)
    })
            $("#tabstrip").kendoTabStrip({
    animation:
    {
    close: {
    duration: 500,
            effects: "fadeOut"
    },
            open: {
            duration: 500,
                    effects: "fadeIn"
            }
    }
    });
    var tabToActivate = $("#tab1");
    $("#tabstrip").kendoTabStrip().data("kendoTabStrip").activateTab(tabToActivate);
    $('#changeNoty').on('click', function (e) {
    var position = $(e.target).offset();
    $('#dialog-view').show();
    $('.modal_inner').animate({
    'top': position.top - 320 + 'px',
    }, 1000);
    })
            $(document).on('click', '#notyConfirm', function () {
    $.ajax({
    url: ajaxurl,
            method: 'POST',
            data: {
            action: 'WPsCRM_update_options_modal',
                    option_section: 'CRM_documents_settings',
                    option: 'invoice_noty_days',
                    val: $('#new_default_noty').val(),
                    security:'<?php echo $update_nonce ?>'
            },
            success: function (result) {
            $('#dialog-view').slideToggle();
            if (isNaN(result) == false) {
            $('#notificationDays').val(result);
            noty({
            text: "<?php _e('Option Saved', 'wp-smart-crm-invoices-free') ?>",
                    layout: 'center',
                    type: 'success',
                    template: '<div class="noty_message"><span class="noty_text"></span><span class="noty_close glyphicons gypicons-close"></span></div>',
                    //closeWith: ['button'],
                    timeout: 1500
            });
            }
            else {
            noty({
            text: "<?php _e('An error occurred', 'wp-smart-crm-invoices-free') ?>",
                    layout: 'center',
                    type: 'error',
                    template: '<div class="noty_message"><span class="noty_text"></span><span class="noty_close glyphicons gypicons-close"></span></div>',
                    closeWith: ['button'],
                    //timeout: 1500
            });
            }
            },
            error: function (errorThrown) {
            console.log(errorThrown);
            }
    })
    })

            var _dateEnd = $("#data_scadenza").data('kendoDatePicker');
    _dateEnd.setOptions({
    value: new Date(),
            width: 200
    });
    setTimeout(function () {
    $('.modal_loader').hide()
    }, 200);
    //aggiornatot();


    if ($('.reverse_row').length) {
    jQuery('#btn_manual').addClass('disabled').attr('title', 'Questa fattura e\' stata calcolata con valori scorporati e non e\' possibile aggiungere altre righe'); ;
    jQuery('.reverseCalulator').addClass('disabled').attr('title', 'Questa funzione e\' disponibile solo se non vi sono altre righe in fattura');
    jQuery('#btn_refund').addClass('disabled').attr('title', 'Questa funzione e\' disponibile solo se non vi sono altre righe in fattura');
    }
    if ($('.manual_row').length) {
    jQuery('.reverseCalulator').addClass('disabled').attr('title', 'Questa funzione e\' disponibile solo se non vi sono altre righe in fattura');
    }
    });

  </script>
<?php } ?>

