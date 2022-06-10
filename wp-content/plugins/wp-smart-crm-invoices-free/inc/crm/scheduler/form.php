<?php
if ( ! defined( 'ABSPATH' ) ) exit;
$update_nonce= wp_create_nonce( "update_scheduler" );

$tipo_agenda=$_GET["tipo_agenda"];
$a_table=WPsCRM_TABLE."agenda";
$c_table=WPsCRM_TABLE."clienti";
$s_table=WPsCRM_TABLE."subscriptionrules";
$data_scadenza=date("d-m-Y");

$where="1";
switch ($tipo_agenda)
{
    case 1:
        
        $icon='<i class="glyphicon glyphicon-tag"></i> '.__('New TODO','wp-smart-crm-invoices-free');
        break;
    case 2:
        $icon='<i class="glyphicon glyphicon-pushpin"></i> '.__('New Appointment','wp-smart-crm-invoices-free');
        break;
    case 3:
        $icon='<i class="glyphicon glyphicon-option-horizontal"></i> '.__('New Activity','wp-smart-crm-invoices-free');
        break;
    default:
        $tipo="";
        break;
}
?>
<script type="text/javascript">
	jQuery(document).ready(function ($) {
		var $format = "<?php echo WPsCRM_DATETIMEFORMAT ?>";
		var _clients = new kendo.data.DataSource({
			transport: {
				read: function (options) {
					$.ajax({
						url: ajaxurl,
						data: {
							'action': 'WPsCRM_get_clients2'
						<?php if($tipo_agenda ==1) {?>, 'self_client': 1 <?php } ?>
						},
						success: function (result) {
							console.log(result);
							$("#fk_clienti").data("kendoDropDownList").dataSource.data(result.clients);

						},
						error: function (errorThrown) {
							console.log(errorThrown);
						}
					})
				}
			}
		});
		$('#fk_clienti').kendoDropDownList({
			optionLabel : "<?php _e('Select Customer','wp-smart-crm-invoices-free') ?>...",
			dataSource: _clients,
			dataTextField: "ragione_sociale",
			dataValueField: "ID_clienti",
			//filter: "contains",
			autoBind: true,
			minLength: 3,

	}).data('kendoDropDownList');

		var _users = new kendo.data.DataSource({
			transport: {
				read: function (options) {
					$.ajax({
						url: ajaxurl,
						data: {
							'action': 'WPsCRM_get_CRM_users'
						},
						success: function (result) {
							console.log(result);
							$("#remindToUser").data("kendoMultiSelect").dataSource.data(result);

						},
						error: function (errorThrown) {
							console.log(errorThrown);
						}
					})
				}
			}
		});
		var users=$('#remindToUser').kendoMultiSelect({
			placeholder: "<?php _e('Select User','wp-smart-crm-invoices-free') ?>...",
			dataTextField: "display_name",
			dataValueField: "ID",
			autoBind: true,
			dataSource: _users,
			change: function (e) {
				var selectedUsers = (this.value()).clean("");
				$('#selectedUsers').val(selectedUsers)
			},

		}).data("kendoMultiSelect");
		//$("#remindToUser").focus();

		var roleSource = new kendo.data.DataSource({

			transport: {
				read: function (options) {
					$.ajax({
						url: ajaxurl,
						data: {
							'action': 'WPsCRM_get_registered_roles',
						},
						success: function (result) {
							//console.log(result);
							$("#remindToGroup").data("kendoMultiSelect").dataSource.data(result.roles);
						},
						error: function (errorThrown) {
							console.log(errorThrown);
						}
					})
				}
			}
		});
		$('#remindToGroup').kendoMultiSelect({
			placeholder: "<?php _e('Select Role','wp-smart-crm-invoices-free') ?>...",
			dataTextField: "name",
			dataValueField: "role",
			autoBind: true,
			dataSource: roleSource,
			change: function (e) {
				var selectedGroups = (this.value()).clean("");
				$('#selectedGroups').val(selectedGroups)
			},

		});


		var validator = $("form").kendoValidator({
			rules: {
				hasDays: function (input) {
					if (input.is("[name=ruleStep]")) {

						if (jQuery('#ruleStep').val() == "") {
								jQuery.playSound("<?php echo WPsCRM_URL?>inc/audio/double-alert-2")
            					return false;
            				}

            			}

            			return true;
            		},

				hasClients: function (input) {
					if (input.is("[name=fk_clienti]")) {

						var kb = $("#fk_clienti").data("kendoDropDownList").value();
						if (kb.length == "") {
							jQuery.playSound("<?php echo WPsCRM_URL?>inc/audio/double-alert-2");
							$("#fk_clienti").focus();
							return false;
						}
					}

					return true;
				},
				hasObject: function (input) {
					if (input.is("[name=oggetto]")) {
						var kb = $("#oggetto").val();
						if (kb == "") {
							jQuery.playSound("<?php echo WPsCRM_URL?>inc/audio/double-alert-2");
							return false;
						}
					}
					return true;
				},
				hasNoty: function (input) {
	            	if (input.is("[name=remindToUser]") ) {
	            			var kb = jQuery("#remindToUser").data("kendoMultiSelect").value();
	            			var kb1 = jQuery("#remindToGroup").data("kendoMultiSelect").value();

	            			if (kb == "" && kb1 == "") {
								jQuery.playSound("<?php echo WPsCRM_URL?>inc/audio/double-alert-2");

	            				return false;
	            			}

	            	}

					if (input.is("[name=remindToGroup]") ) {
	            			var kb = jQuery("#remindToUser").data("kendoMultiSelect").value();
	            			var kb1 = jQuery("#remindToGroup").data("kendoMultiSelect").value();

	            			if (kb == "" && kb1 == "") {
                            	jQuery.playSound("<?php echo WPsCRM_URL?>inc/audio/double-alert-2");

	            				return false;
	            			}

	            	}

	            	return true;
	            },

			},

			messages: {
				hasDays:"<?php _e('You should select how many days in advance to activate the notification','wp-smart-crm-invoices-free')?>",
				hasNoty:"<?php _e('You should select a user or a group of users to notify to','wp-smart-crm-invoices-free')?>",
				hasClients: "<?php _e('You should select a customer','wp-smart-crm-invoices-free')."."; $tipo_agenda==1 ? "<br /> "._e('To send an internal communication select you company','wp-smart-crm-invoices-free') :null ?>",
				hasObject: "<?php _e('You should type a subject for this Eevent','wp-smart-crm-invoices-free')?>",
				//hasUsers: "<?php _e('You should select at least one user','wp-smart-crm-invoices-free')?>",
			}
		}).data("kendoValidator");

		$("input", users.wrapper).on("blur", function () {
			validator.validate();
		});
		$("form").validate({
			submitHandler: function () {
				showMouseLoader();
				$('#btn_save b').html("<?php _e('Saving...','wp-smart-crm-invoices-free')?>");
				id_cliente = $("#fk_clienti").data("kendoDropDownList").value();
				tipo_agenda = '<?php echo $tipo_agenda?>';
				scadenza_inizio = $("#data_scadenza_inizio").val();
				if ($("#data_scadenza_fine").length)
					scadenza_fine = $("#data_scadenza_fine").val();
				else
					scadenza_fine = $("#data_scadenza_inizio").val();
				scadenzaTimestamp = $("#data_scadenza_inizio").data('kendoDateTimePicker').value();

				annotazioni = $("#annotazioni").val();
				oggetto = $("#oggetto").val();
				priorita = $("#priorita").val();
				if ($('#instantNotification').prop('checked'))
					instantNotification = 1;
				else
					instantNotification = 0;
				mailToRecipients = $("#mailToRecipients").prop('checked');
				//alert(mailToRecipients);return false;
				users = $("#selectedUsers").val();
				groups = $("#selectedGroups").val();
				//alert(users); return false;
				days = $("#ruleStep").val();
				var s = "[";
				s += '{"ruleStep":"' + days + '" ,"remindToCustomer":';
				if ($('#remindToCustomer').prop('checked'))
					s += '"on"';
				else
					s += '""';
				s += ',"selectedUsers":"' + users + '"';
				s += ',"selectedGroups":"' + groups + '"';
				s += ',"userDashboard":';
				if ($('#userDashboard').prop('checked'))
					s += '"on"';
				else
					s += '""';
				s += ',"groupDashboard":';
				if ($('#groupDashboard').prop('checked'))
					s += '"on"';
				else
					s += '""';
				s += ',"mailToRecipients":';
				if ($('#mailToRecipients').prop('checked'))
					s += '"on"';
				else
					s += '""';
				s += '}'
				s += ']';
				$.ajax({
					url: ajaxurl,
					data: {
						action: 'WPsCRM_save_todo',

						id_cliente: id_cliente,
						tipo_agenda: tipo_agenda,
						scadenza_inizio: scadenza_inizio,
						scadenza_fine: scadenza_fine,
						scadenza_timestamp: scadenzaTimestamp,
						annotazioni: annotazioni,
						oggetto: oggetto,
						priorita: priorita,
						mail_destinatari: mailToRecipients,
						steps: encodeURIComponent(s),
						instantNotification:instantNotification,
						security:'<?php echo $update_nonce?>'
					},
					type: "POST",
					success: function (response) {
						console.log(response)
						window.location.href = "<?php echo admin_url('admin.php?page=smart-crm&p=scheduler/list.php')?>";
					}
				})
			}
		});


		$("#btn_save").click(function () {
			//validator.validate();
			$('form').find(':submit').click();


		});
		users.value([<?php echo wp_get_current_user()->ID ?>]);
		$('#selectedUsers').val(users.value());

<?php if($tipo_agenda==2) {?>

		var _dateIni = $("#data_scadenza_inizio").kendoDateTimePicker({
			value: new Date(),
			format: $format,
			change:set_end_min
		}).data("kendoDateTimePicker");

		if ($("#data_scadenza_fine").length)
		{

		}
		var _dateEnd = $("#data_scadenza_fine").kendoDateTimePicker({
			value: new Date(),
			format: $format,
			width: 300
		}).data("kendoDateTimePicker");
		function set_end_min() {
			//alert();
			var iniDate = _dateIni.value();
			console.log(iniDate);
			console.log(_dateEnd.value());
			_dateEnd.min(kendo.parseDate(iniDate, "yyyy-MM-dd HH:mm"), $format);
			_dateEnd.value(iniDate)
		}
<?php } else {?>
		var _dateIni = $("#data_scadenza_inizio").kendoDateTimePicker({
			value: new Date(),
			format: $format,
			width: 300
		});

		if ($("#data_scadenza_fine").length) {
			var _dateEnd = $("#data_scadenza_fine").kendoDateTimePicker({
				value: new Date(),
				format: $format,
				width: 300
			});
			//_dateEnd.setOptions({
			//	value: new Date(),
			//	format: $format,
			//	width: 200
			//});
		}
		<?php } ?>
});
	//function annulla()
	//{
	//	location.href="index2.php?page=todo/mostra.php";
	//}

</script>
<form name="form_insert">
    <div style="margin-top:14px;background-color: #fafafa;" class="col-md-12">
        <h3><?php echo $icon?></h3>
    <!-- TAB 1 -->
 
        <div id="d_anagrafica">

            <div class="row form-group">
                <label class="col-sm-2 control-label"><?php _e('Customer','wp-smart-crm-invoices-free')?> *</label>
                
                <div class="col-md-4">
	                  <select id="fk_clienti" name="fk_clienti" class="form-control" ></select>
                </div>                   

            </div>
            <?php if ($tipo_agenda==2) {?>
            <div class="row form-group">
                <label class="col-sm-4 control-label"><?php _e('Start', 'wp-smart-crm-invoices-free'); ?>
                    <input name="data_scadenza_inizio" id='data_scadenza_inizio'  value="<?php echo $data_scadenza?>" class="" required validationMessage="<?php _e('You should select a start date/time for this appointment','wp-smart-crm-invoices-free')?>">
                </label> 
                <label class="col-sm-4 control-label"><?php _e('End', 'wp-smart-crm-invoices-free'); ?>
                    <input name="data_scadenza_fine" id='data_scadenza_fine'  value="<?php echo $data_scadenza?>" class="" required validationMessage="<?php _e('You should select an end date/time for this appointment','wp-smart-crm-invoices-free')?>">
                </label>
            </div>
            <?php } ?>
            <?php if ($tipo_agenda==1) { ?>
            <div class="row form-group">
                <label class="col-sm-2 control-label"><?php _e('TODO Date','wp-smart-crm-invoices-free')?> *</label>
                <div class="col-sm-4">
                    <input type="text" name="data_scadenza_inizio" id='data_scadenza_inizio' value="<?php echo $data_scadenza?>"  required validationMessage="<?php _e('You should select an expiration date for this event','wp-smart-crm-invoices-free')?>">
                </div>
            </div>
            <?php } ?>
        <h4 class="page-header" style="background:#e2e2e2;padding:15px"><?php _e('Contents','wp-smart-crm-invoices-free')?></h4>
            <div class="row form-group">
	            <label class="col-sm-2 control-label"><?php _e('Subject','wp-smart-crm-invoices-free')?> *</label>
	            <div class="col-sm-4">
                    <input type="text" value="<?php if(isset($oggetto)) echo $oggetto?>" name="oggetto" id="oggetto" class="form-control  k-textbox _m" placeholder="<?php _e('Type a subject for this Event','wp-smart-crm-invoices-free')?>" >
	            </div>
	        
            </div>
            <div class="row form-group">
                <label class="col-sm-2 control-label"><?php _e('Description','wp-smart-crm-invoices-free')?></label>
	            <div class="col-sm-4">
                    <textarea  class="col-md-12" id="annotazioni" name="annotazioni" rows="5" cols="50"><?php if(isset($annotazioni)) echo $annotazioni?></textarea>
	            </div>
            </div>
            <div class="row form-group">
                <label class="col-sm-2 control-label"><?php _e('Priority','wp-smart-crm-invoices-free')?></label>
                <div class="col-sm-4">
                <?php if(isset($riga["priorita"])) WPsCRM_priorita($riga["priorita"]); else WPsCRM_priorita()?> 
                </div>
            </div>

            <h4 class="page-header" style="background:#e2e2e2;padding:15px"><?php _e('Notification rules','wp-smart-crm-invoices-free')?><span class="crmHelp" data-help="notification-rules"></span></h4>


            <div class="row form group" style="padding-bottom:20px;border-bottom:1px solid #ccc">
               <label class="col-sm-2 control-label"><?php _e('Days in advance','wp-smart-crm-invoices-free')?> *</label>
                <div class="col-sm-4">
                    <select class="form-control ruleActions _m k-dropdown _flat" style="width:150px" id="ruleStep" name="ruleStep">
                        <option value=""><?php _e("Sel","wp-smart-crm-invoices-free")?></option><?php for($k=0;$k<31;$k++){echo '<option value="'.$k.'">'.$k.'</option>'; } ?>

                    </select>
                </div>
                <label class="col-sm-2 control-label"><?php _e('Send also instant notification','wp-smart-crm-invoices-free')?></label>
                <div class="col-sm-4">
                    <input type="checkbox" class="ruleActions " id="instantNotification" name="instantNotification" />
                    <small style="line-height:.8em"><?php _e('An email will be sent immediately to all selected users/groups if the option "send mail to recipients" below is active','wp-smart-crm-invoices-free');?></small>
                </div>
            </div>
            <!--<div class="row form group" style="padding-bottom:20px;border-bottom:1px solid #ccc">
               
            </div>-->
            <div class="row form-group" style="border:1px solid red;line-height: 3.2em;<?php echo $tipo_agenda==1 ? "display:none" : false ?>">
                <label class="col-sm-2 control-label" style="font-size:1.2em"><?php _e('Send email to customer','wp-smart-crm-invoices-free')?></label>
                <div class="col-md-4">
                <input type="checkbox" class="ruleActions " id="remindToCustomer" name="remindToCustomer"/> 
                </div>
            </div>
            <div class="row for-group">
              <label class="col-sm-2 control-label"  style="line-height:20px"><?php _e('Send mail to recipients','wp-smart-crm-invoices-free')?></label>
                <div class="col-md-4">
                <input type="checkbox" class="ruleActions " id="mailToRecipients" name="mailToRecipients"/>
                </div>
            </div>
            <div class="row form-group" style="margin-top:10px">
                <label class="col-sm-2 control-label" style="line-height:20px"><?php if($tipo_agenda==2) _e('Select Account for this appointment','wp-smart-crm-invoices-free'); else _e('Send to User','wp-smart-crm-invoices-free')?></label>
                <div class="col-md-4">
                    <input class="ruleActions" id="remindToUser" name="remindToUser" />
                </div>

                 <label class="col-sm-2 control-label" style="line-height:20px"><?php if($tipo_agenda==2) _e('Publish on Account dashboard','wp-smart-crm-invoices-free'); else _e('Publish on User dashboard','wp-smart-crm-invoices-free')?>?</label> 
                 <div class="col-md-4">
                <input type="checkbox" class="ruleActions" name="userDashboard" id="userDashboard" />
                 </div>
            </div>
            <div class="row form-group"  <?php if($tipo_agenda==2) echo ' style="display:none"'?> >
                <label class="col-sm-2 control-label"><?php _e('Send to Group','wp-smart-crm-invoices-free')?></label>
                <div class="col-md-4">
                    <input class="ruleActions" id="remindToGroup" name="remindToGroup">
                </div>
                    <label class="col-sm-2 control-label"><?php _e('Publish on Groups dashboard','wp-smart-crm-invoices-free')?>?</label>
                <div class="col-md-4">
                    <input type="checkbox" class="ruleActions" name="groupDashboard" id="groupDashboard"/>
                </div>
            </div>
                <input type="hidden" id="selectedUsers" name="selectedUsers"  class="ruleActions"value=""/>
                <input type="hidden" id="selectedGroups" name="selectedGroups"  class="ruleActions"value=""/>
                
             <div class="row form-group">
                 <ul class="select-action" style="margin-left:8px">
                    <li class="btn btn-success btn-sm _flat" id="btn_save"><i class="glyphicon glyphicon-floppy-disk"></i> 
                        <b onClick="return false;"> <?php _e('Save','wp-smart-crm-invoices-free')?></b>
                    </li>
                    <li class="btn btn-warning btn-sm _flat"><i class="glyphicon glyphicon-floppy-remove"></i>
                        <b onClick="window.location.replace('<?php echo admin_url('admin.php?page=smart-crm&p=scheduler/list.php')?>');return false;"> <?php _e('Reset','wp-smart-crm-invoices-free')?></b>
                    </li>
                     
                </ul>
		    </div>      
	    </div>

    </div>
<input type="submit"  id="submit_form" style="display:none"/>
</form>
