<?php

/**
 * Sets the datasource used in scheduler grid
 */
function WPsCRM_JS_schedulerList_datasource(){
	ob_start();
?>
    var dataSource = new kendo.data.DataSource(
			{
				transport: {
					read: function (options) {
						$.ajax({
							url: ajaxurl,
							data: {
								action: 'WPsCRM_get_scheduler',
								self_client:1
							},
							success: function (result) {
								options.success(result);
								//$("#grid").data("kendoGrid").dataSource.data(result.scheduler);

							},
							error: function (errorThrown) {
								console.log(errorThrown);
							}
						})
					}
				},
				group: { field: "tipo_agenda", dir: "asc" },
				schema: {
					data: function (response) {
						return response.scheduler
					},total: function (response) {
						return response.scheduler.length;
					},
					model: {
						id: "id_agenda",
						fields: {
							tipo_agenda: { editable: false },
							cliente: { editable: false },
							oggetto: { editable: false },
							annotazioni: { editable: false },
							esito: { editable: false },
							data_scadenza: {
								type: "date", editable: false,
								filterable: {
									cell: {
										template: '#= kendo.toString(kendo.parseDate(datao, "yyyy-MM-dd"), "' + $format + '") #'
									}
								}
							},
							destinatari: { editable: false },
						}
					}
				},
				pageSize: 50,
			}
		)

<?php
	echo ob_get_clean();
	return;
}
add_action('WPsCRM_scheduler_datasource','WPsCRM_JS_schedulerList_datasource',9);

/**
 *display scheduler list  grid
 **/
function WPsCRM_JS_display_schedulerGrid($delete_nonce){
	ob_start();
        $current_user = wp_get_current_user();
        $user_id=$current_user->ID;
        $options=get_option('CRM_general_settings');
?>
    var c_user=parseInt(<?php echo $user_id?>);
    var del_priv="<?php echo isset($options['deletion_privileges'])&& $options['deletion_privileges']=="1" ?>";
    var is_admin="<?php echo current_user_can('administrator')?>";
    var grid = $("#grid").kendoGrid({
    	toolbar: kendo.template($("#gridHeader").html()),
    	dataSource: dataSource,
		noRecords: {
			template: "<h4 style=\"text-align:center;padding:5%\"><?php _e('No ACTIVITIES to show; you can add new todo or new appointments, please be sure to have some customers available or create a new customer from the main menu','wp-smart-crm-invoices-free')?></h4>"
		},
        groupable: {
            messages: {
                empty: "<?php _e('Drag columns headers and drop it here to group by that column','wp-smart-crm-invoices-free') ?>"
            }
        },
		pageable:
        {
            pageSizes: [20, 50, 100],
            messages:
                {
                    display: "<?php _e('Showing','wp-smart-crm-invoices-free') ?> {0}-{1}  <?php _e('of','wp-smart-crm-invoices-free') ?> {2} <?php _e('total','wp-smart-crm-invoices-free') ?>",
                    of: "<?php _e('of','wp-smart-crm-invoices-free') ?> {0}",
                    itemsPerPage: "<?php _e('Posts per page','wp-smart-crm-invoices-free') ?>",
                    first: "<?php _e('First page','wp-smart-crm-invoices-free') ?>",
                    last: "<?php _e('Last page','wp-smart-crm-invoices-free') ?>",
                    next: "<?php _e('Next','wp-smart-crm-invoices-free') ?>",
                    previous: "<?php _e('Prev.','wp-smart-crm-invoices-free') ?>",
                    refresh: "<?php _e('Reload','wp-smart-crm-invoices-free') ?>",
                    morePages: "<?php _e('More','wp-smart-crm-invoices-free') ?>"
                },
        },
    	sortable: true,
		filterable:true,
        serverPaging: true,
        dataBound: loadCellsAttributesScheduler,
        columns: [{ field: "id_agenda", title: "ID", hidden: true },
				  { field: "fk_utenti_ins", title: "Ins", hidden: true },
				  { field: "tipo_agenda", title: "<?php _e('Type','wp-smart-crm-invoices-free') ?>", width: 150 },
				  { field: "cliente", title: "<?php _e('Customer','wp-smart-crm-invoices-free') ?>" },
				  { field: "oggetto", title: "<?php _e('Object','wp-smart-crm-invoices-free') ?>" },
				  { field: "data_scadenza", title: "<?php _e('Expiration','wp-smart-crm-invoices-free') ?>", template: '#= kendo.toString(kendo.parseDate(data_scadenza, "yyyy-MM-dd HH:mm:ss"), "' + $format + '") #' ,
				  	filterable: {
				  		ui: function (element) {
				  			element.kendoDateTimePicker({
				  				format: $format
				  			});
				  		}
				  	}
				  },
				{ field: "destinatari", title: "<?php _e('Recipients','wp-smart-crm-invoices-free')?>" },
                {field:"privileges",hidden:true},
				{ command: [

				{
                name: "<?php _e('Open','wp-smart-crm-invoices-free') ?>",
                click: function (e) {
                    e.preventDefault();
                    var tr = $(e.target).closest("tr"); // get the current table row (tr)
                    var _row = this.dataItem(tr);
                    $.ajax({
                        url: ajaxurl,
                        data: {
                        	'action': 'WPsCRM_view_activity_modal',
                            'id': _row.id,
                            'report':$(e.currentTarget).data('report')
                        },
                        success: function (result) {
                            //console.log(result);
                            $('#dialog-view').show().html(result)
                            //$("#grid").data("kendoGrid").dataSource.data(result.scheduler);

                        },
                        error: function (errorThrown) {
                            console.log(errorThrown);
                        }
                    })

                },
                className: "btn btn-inverse _flat"
            },
            {
            name: "<?php _e('Delete','wp-smart-crm-invoices-free') ?>",
            click: function (e) {
                var tr = $(e.target).closest("tr");
                var data = this.dataItem(tr);
                if ((is_admin=="" && del_priv==1) || (is_admin=="" && del_priv==""  && c_user!=parseInt(data.fk_utenti_ins))) {
                    alert("<?php _e('You do not have the privilege to delete this event.','wp-smart-crm-invoices-free') ?>")
                    return false;
                }
                if (!confirm("<?php _e('Confirm delete','wp-smart-crm-invoices-free') ?>?"))
                    return false;
                e.preventDefault();
                location.href="<?php echo admin_url('admin.php?page=smart-crm&p=scheduler/delete.php&ID=')?>"+data.id +"ref=scheduler&security=<?php echo $delete_nonce?>";
            },
           className: "btn btn-danger _flat"
           }
        ], width: 200
        }, { field: "esito", hidden: true }
		, { field: "status", title: "<?php _e('Status','wp-smart-crm-invoices-free')?>", width: 100, "filterable":false }
        , { field: "class", hidden: true }

        ],
        height: 500,
        editable:"popup"
      });


<?php
	echo ob_get_clean();
	return;
}
add_action('WPsCRM_schedulerGrid','WPsCRM_JS_display_schedulerGrid',9,1);