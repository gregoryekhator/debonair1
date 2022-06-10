<?php
if ( ! defined( 'ABSPATH' ) ) exit;
/**
 * Displays the grid in customer form activity tab
 * @return void
 */
function WPsCRM_JS_display_grid_customer_scheduler($delete_nonce){
	ob_start();?>
    function agenda_databound(){
<?php
    is_multisite() ? $filter=get_blog_option(get_current_blog_id(), 'active_plugins' ) : $filter=get_option('active_plugins' );
    if ( in_array( 'wp-smart-crm-agents/wp-smart-crm-agents.php', apply_filters( 'active_plugins', $filter) ) ) {
?>
        var gridRows = this.tbody.find("tr");
        console.log(privileges);
	    gridRows.each(function (e) {
		    var commandsCell = jQuery(this).find("td:last-child");
		    if(privileges.customer===1){
			    commandsCell.html('');
		    }
	    })


<?php

    }

?>}
$("#grid").kendoGrid({
			noRecords: {
					template: "<h4 style=\"text-align:center;padding:5%\"><?php _e('No activity for this customer','wp-smart-crm-invoices-free')?></h4>"
			},
			dataSource: _datasource,
            dataBound: loadCellsAttributes,
            filterable: true,
            sortable: true,
            pageable: true,
            groupable: {
                messages: {
                    empty: "<?php _e('Drag columns headers and drop it here to group by that column','wp-smart-crm-invoices-free') ?>"
                }
            },
        	columns: [{
        		field: "id_agenda", title: "ID", filterable: false, hidden: true },
				{ field: "fk_utenti_ins", title: "Ins",hidden:true },
				{ field: "tipo", title: "<?php _e('Type','wp-smart-crm-invoices-free')?>", width: 160 },
				{ field: "oggetto", title: "<?php _e('Subject','wp-smart-crm-invoices-free')?>", width: 240 },
				{ field: "annotazioni", title: "<?php _e('Description','wp-smart-crm-invoices-free')?>", groupable: false },
				{ field: "data_scadenza", title: "<?php _e('Exp. date','wp-smart-crm-invoices-free')?>",template: '#= kendo.toString(kendo.parseDate(data_scadenza, "yyyy-MM-dd HH:mm:ss"), "' + $format + '") #' },
        { command: [
          {
              name:"<?php _e('Open','wp-smart-crm-invoices-free')?>",
            click: function (e) {
              e.preventDefault();
              var position = $(e.target).offset();
              var tr = $(e.target).closest("tr"); // get the current table row (tr)
              var _row = this.dataItem(tr);
              $.ajax({
                  url: ajaxurl,
                  data: {
                  	'action': 'WPsCRM_view_activity_modal',
                      'id': _row.id
                  },
                  success: function (result) {
                      $('#dialog-view').show().html(result)
                      $('.modal_inner').animate({
                          'top': position.top - 320 + 'px',
                      }, 1000);
                  },
                  error: function (errorThrown) {
                      console.log(errorThrown);
                  }
              })

            },
			className: "btn btn-inverse _flat"
          },
          {
            name:"<?php _e('Delete','wp-smart-crm-invoices-free')?>",
            click: function (e) {
             if (!confirm("<?php _e('Confirm delete','wp-smart-crm-invoices-free') ?>?"))
                  return false;

            e.preventDefault();
              var tr = $(e.target).closest("tr"); // get the current table row (tr)
              var _row = this.dataItem(tr);
                //location.href="?page=smart-crm&p=scheduler/view.php&ID="+data.id;
              $.ajax({
                  url: ajaxurl,
                  data: {
                  	'action': 'WPsCRM_delete_activity',
                  	'id': _row.id,
                  	'security':'<?php echo $delete_nonce; ?>'
                  },
                  success: function (result) {
	                 var newDatasource = new kendo.data.DataSource({
                        transport: {
                            read: function (options) {
                                jQuery.ajax({
                                    url: ajaxurl,
                                    data: {
                                    	'action': 'WPsCRM_get_client_scheduler',
                                        id_cliente: '<?php if(isset($ID)) echo $ID?>'
                                    },
                                    success: function (result) {
                                        //console.log(result);
                                        jQuery("#grid").data("kendoGrid").dataSource.data(result.scheduler);

                                    },
                                    error: function (errorThrown) {
                                        console.log(errorThrown);
                                    }
                                })
                            }
                        },
                        schema: {
                            model: {
                                id: "id_agenda",
                                fields: {
                                    tipo: { editable: false },
                                    oggetto: { editable: false },
                                    annotazioni: { editable: false },
                                    data_scadenza: { type: "date", editable: false },
                                }
                            }
                        },
                        pageSize: 50,
                    });
                    setTimeout(function () {
                        $('.modal_loader').fadeOut('fast');
                    }, 300);
                    setTimeout(function () {
                        $('._modal').fadeOut('fast');
                    }, 500);

                    var grid = $('#grid').data("kendoGrid");
                    setTimeout(function () {
                        grid.setDataSource(newDatasource);
                        grid.dataSource.read();
                    }, 600);

                    setTimeout(function () { grid.refresh() }, 700);

	              },
                  error: function (errorThrown) {
                      console.log(errorThrown);
                  }
              })

            }
			, className: "btn btn-danger _flat"
          },

        ]
        }, { field: "esito", hidden: true }
		, { field: "status", title: "<?php _e('Status','wp-smart-crm-invoices-free')?>", width: 100 , "filterable":false}
        , { field: "class", hidden: true }
        ],
        height: 500,
        editable: "popup",
        autoSync: true,
        pageable: {
            pageSize: 10,
            refresh: true
        }
      });
<?php
	echo ob_get_clean();
	return;
}
add_action('WPsCRM_grid_customer_scheduler','WPsCRM_JS_display_grid_customer_scheduler',9,1);

/**
 * Displays the grid for contacts in customer form
 * @return void
 */
function WPsCRM_JS_grid_customer_contacts(){
	ob_start();?>
    function contact_databound(){
    <?php
        is_multisite() ? $filter=get_blog_option(get_current_blog_id(), 'active_plugins' ) : $filter=get_option('active_plugins' );
        if ( in_array( 'wp-smart-crm-agents/wp-smart-crm-agents.php', apply_filters( 'active_plugins', $filter) ) ) {
        ?>
        var gridRows = this.tbody.find("tr");
        console.log(privileges);
	    gridRows.each(function (e) {
		    var commandsCell = jQuery(this).find("td:last-child");
		    if(privileges.customer===1){
			    commandsCell.html('');
		    }
	    })


    <?php
    
    }

    ?>}
    $("#grid_contacts").kendoGrid({
        dataSource: _contacts,
        dataBound:contact_databound,
		noRecords: {
		template: "<h4 style=\"text-align:center;padding:5%\"><?php _e('No Contacts to show','wp-smart-crm-invoices-free')?></h4>"
    	},
        height: 550,
        groupable: {
        messages: {
            empty: "<?php _e('Drag columns headers and drop it here to group by that column','wp-smart-crm-invoices-free') ?>"
			}
		},
        sortable: true,
        serverPaging: true,
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
        filterable:
		{
        messages:
            {
                info: "<?php _e('Filter by','wp-smart-crm-invoices-free') ?> "
            },
        extra: false,
        operators:
            {
                string:
                    {
                        contains: "<?php _e('Contains','wp-smart-crm-invoices-free') ?> ",
                        startswith: "<?php _e('Starts with','wp-smart-crm-invoices-free') ?>",
                        eq: "<?php _e('Equal','wp-smart-crm-invoices-free') ?>",
                        neq: "<?php _e('Not equal','wp-smart-crm-invoices-free') ?>"
                    }
            }
        },
        toolbar: [{ name: "create", text: "<?php _e('Add new contact','wp-smart-crm-invoices-free') ?>" }],  
        columns: [
          {
            field: "id",
            title: "<?php _e('ID','wp-smart-crm-invoices-free') ?>"
          },
          {
            field: "nome",
            title: "<?php _e('Name','wp-smart-crm-invoices-free') ?>"
          },
          {
            field: "cognome",
            title: "<?php _e('Last Name','wp-smart-crm-invoices-free') ?>"
          },
          {
            field: "email",
            title: "<?php _e('Email','wp-smart-crm-invoices-free') ?>"
          },
          {
            field: "telefono",
            title: "<?php _e('Telephone','wp-smart-crm-invoices-free') ?>"
          },
          {
            field: "qualifica",
            title: "<?php _e('Qualification','wp-smart-crm-invoices-free') ?>"
          },
            { command: [
			    {
				    name:"edit",
				    text: {edit:"<?php _e('Edit','wp-smart-crm-invoices-free') ?>", update:"<?php _e('Update','wp-smart-crm-invoices-free') ?>", cancel:"<?php _e('Cancel','wp-smart-crm-invoices-free') ?>"}
			    }, 
			    {
				    name:"destroy",
				    text:"<?php _e('Delete','wp-smart-crm-invoices-free') ?>"
			    }
		    ], title: "&nbsp;", width: "250px" }],
            editable: {
			    confirmation: "<?php _e('Confirm delete','wp-smart-crm-invoices-free') ?>?",
			    mode: "inline"
		    },
        });
<?php
	echo ob_get_clean();
	return;
}
add_action('WPsCRM_grid_customer_contacts','WPsCRM_JS_grid_customer_contacts',9);

/**
 * display a tooltip for help in menu buttons
 * @return void
 */
function WPsCRM_JS_menu_tooltip(){
	ob_start();?>
    $("._tooltip").kendoTooltip({
    		//autoHide: false,
    		animation: {
				close: {
					duration: 1000,
				}
			},
			position:"top",
    		content: "<h4><?php _e('BUTTONS LEGEND','wp-smart-crm-invoices-free')?>:</h4>\n\
			<ul>\n\
				<li class=\"no-link\">\n\
					<span class=\"btn btn-info _flat\"><i class=\"glyphicon glyphicon-tag\"></i>= <?php _e('NEW TODO','wp-smart-crm-invoices-free')?></span>\n\
					<span class=\"btn btn_appuntamento_1 _flat\"><i class=\"glyphicon glyphicon-pushpin\"></i>= <?php _e('NEW APPOINTMENT','wp-smart-crm-invoices-free')?></span>\n\
					<span class=\"btn btn-primary _flat\"><i class=\"glyphicon glyphicon-option-horizontal\"></i>= <?php _e('NEW ACTIVITY','wp-smart-crm-invoices-free')?></span>\n\
				</li>\n\
			</ul>"
    	})
<?php
	echo ob_get_clean();
	return;
}
add_action('WPsCRM_menu_tooltip','WPsCRM_JS_menu_tooltip',9);
