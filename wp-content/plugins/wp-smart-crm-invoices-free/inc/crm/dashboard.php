<?php
if ( ! defined( 'ABSPATH' ) ) exit;
$view=isset($_GET["view"])? $_GET["view"] : "day";
$update_nonce= wp_create_nonce( "update_scheduler" );
$delete_nonce= wp_create_nonce( "delete_activity" );
$page="dashboard";
?>
<script type="text/javascript">

    jQuery(document).ready(function ($) {

			var $format = "<?php echo WPsCRM_DATETIMEFORMAT ?>";
          //update delle activity da modale
        $(document).on('click', '#save_activity_from_modal', function () {
            var id = $(this).data('id');
            $('.modal_loader').show();
            $.ajax({
                url: ajaxurl,
                method:'POST',
                data: {
                	'action': 'WPsCRM_scheduler_update',
                    'ID': id,
                    'fatto': $('input[type="radio"][name="fatto"]:checked').val(),
                    'esito': $('#esito').val(),
					'security':'<?php echo $update_nonce?>'
                },
            	success: function (result) {
					var _tgr
                    var t_Datasource = new kendo.data.DataSource({
                        transport: {
                            read: function (options) {
                                jQuery.ajax({
                                    url: ajaxurl,
                                    data: {
                                        'action': 'WPsCRM_get_scheduler',
                                        'type': 1,
                                        'view': '<?php echo $view?>',
										'self_client':'1'
                                    },
                                    success: function (result) {
                                    	console.log(result);
                                    	jQuery("#grid_todo").data("kendoGrid").dataSource.data(result.scheduler);
                                    },
                                    error: function (errorThrown) {
                                        console.log(errorThrown);
                                    }
                                })
                            }
                        },
                        sort: { field: "data_scadenza", dir: "desc" },
                        schema: {
                            model: {
                                id: "id_agenda",
                                fields: {
                                    tipo: { editable: false },
                                    oggetto: { editable: false },
                                    annotazioni: { editable: false },
                                    data_scadenza: { type: "date", editable: false },
                                    destinatari: { editable: false },
                                }
                            }
                        },
                        pageSize: 50,
                    });
                    var a_Datasource = new kendo.data.DataSource({
                        transport: {
                            read: function (options) {
                                jQuery.ajax({
                                    url: ajaxurl,
                                    data: {
                                    	'action': 'WPsCRM_get_scheduler',
                                        'type': 2,
                                        'view': '<?php echo $view?>'
                                    },
                                    success: function (result) {
                                        //console.log(result);
                                        a_grid.dataSource.data(result.scheduler);

                                    },
                                    error: function (errorThrown) {
                                        console.log(errorThrown);
                                    }
                                })
                            }
                        },
                        sort: { field: "data_scadenza", dir: "desc" },
                        schema: {
                            model: {
                                id: "id_agenda",
                                fields: {
                                    tipo: { editable: false },
                                    oggetto: { editable: false },
                                    annotazioni: { editable: false },
                                    data_scadenza: { type: "date", editable: false },
                                    destinatari: { editable: false },
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
                    }, 400);
                    //jQuery("#grid").data("kendoGrid").dataSource.data(result.scheduler);
                    //var a_grid = $("#grid_appuntamenti").data("kendoGrid").dataSource.data(result.scheduler);
                    //var t_grid = $("#grid_todo").data("kendoGrid").dataSource.data(result.scheduler);
            		//
                    var t_grid = $('#grid_todo').data("kendoGrid");
                    var a_grid = $('#grid_appuntamenti').data("kendoGrid");
                    t_grid.setDataSource(t_Datasource);
                    a_grid.setDataSource(a_Datasource);
                    setTimeout(function () {
                    	t_grid.dataSource.read();
                    	a_grid.dataSource.read();
                    }, 100);

                    setTimeout(function () {
                        t_grid.refresh();
                        a_grid.refresh()
                    }, 200);

                },
                error: function (errorThrown) {
                    console.log(errorThrown);
                }
            })

        })

        $(document).on('click', '._reset',function () {
            $('._modal').fadeOut('fast');
        });

        $("#grid_todo").kendoGrid({
		noRecords: {
			template: "<h4 style=\"text-align:center;padding:5%\"><?php _e('No TODO to show','wp-smart-crm-invoices-free')?></h4>"
    	},
        dataSource: {
          transport: {
            read: function (options) {
              $.ajax({
                url: ajaxurl,
                data: {
                	'action': 'WPsCRM_get_scheduler',
                  'type': 1,
                  'view': '<?php echo $view?>',
				  'self_client':"1"
                },
                success: function (result) {

                  $("#grid_todo").data("kendoGrid").dataSource.data(result.scheduler);

                },
                error: function (errorThrown) {
                  console.log(errorThrown);
                }
              })
            }
          },
          sort: { field: "data_scadenza", dir: "desc" },
          schema: {
            model: {
              id: "id_agenda",
              fields: {
                cliente: { editable: false },
                oggetto: { editable: false },
                annotazioni: { editable: false },
                data_scadenza: { type:"date", editable: false },
                destinatari: { editable: false },
              }
            }
          },
          pageSize: 50,
        },
        dataBound: loadCellsAttributesScheduler,
        groupable: true,
        sortable: true,
        serverPaging: true,
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
        	columns: [{ field: "id_agenda", title: "ID", hidden: true },
				{ field: "fk_utenti_ins", title: "Ins", hidden: true },
				{ field: "cliente", title: "<?php _e('Customer','wp-smart-crm-invoices-free')?>" },
				{ field: "oggetto", title: "<?php _e('Object','wp-smart-crm-invoices-free')?>" },
				{ field: "annotazioni", title: "<?php _e('Description','wp-smart-crm-invoices-free')?>" },
				{ field: "data_scadenza", title: "<?php _e('Expiration','wp-smart-crm-invoices-free')?>", template: '#= kendo.toString(kendo.parseDate(data_scadenza, "yyyy-MM-dd HH:mm:ss"), "' + $format + '") #' },
				{ field: "destinatari", title: "<?php _e('Recipients','wp-smart-crm-invoices-free')?>" },{field:"privileges",hidden:true},
        { command: [
          {
              name: "<?php _e('Open','wp-smart-crm-invoices-free')?>",
            click: function (e) {
              e.preventDefault();
              var position = $(e.target).offset();
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
                      $('#dialog-view').show().html(result)
                      $('.modal_inner').animate({
                          'top': position.top -320 +'px',
                      }, 1000);
                  },
                  error: function (errorThrown) {
                      console.log(errorThrown);
                  }
              })
            },
            className: "btn _flat"
          },
          {
          	name: "<?php _e('Delete','wp-smart-crm-invoices-free')?>",

          	click: function (e) {
            e.preventDefault();
            var tr = $(e.target).closest("tr"); // get the current table row (tr)
          // get the data bound to the current table row
          var data = this.dataItem(tr);

         if (!confirm("<?php _e('Confirm delete','wp-smart-crm-invoices-free') ?>?"))
            return false;
		 location.href="<?php echo admin_url('admin.php?page=smart-crm&p=scheduler/delete.php&ID=')?>"+data.id +"&ref=dashboard&security=<?php echo $delete_nonce?>";
         },
        className: "btn btn-danger _flat"
        }
        ],width:200
        }, { field: "esito", hidden: true }
		, { field: "status", title: "<?php _e('Status','wp-smart-crm-invoices-free')?>", width: 100 ,"filterable":false}
        , { field: "class", hidden: true }
        ],
        height: 500,
        editable:"popup"
        });

    	$("#grid_appuntamenti").kendoGrid({
		noRecords: {
			template: "<h4 style=\"text-align:center;padding:5%\"><?php _e('No APPOINTMENTS to show','wp-smart-crm-invoices-free')?></h4>"
    	},
        dataSource: {
          transport: {
            read: function (options) {
              $.ajax({
                url: ajaxurl,
                data: {
                	'action': 'WPsCRM_get_scheduler',
                  'type': 2,
                  'view': '<?php echo $view?>'
                },
                success: function (result) {
                  $("#grid_appuntamenti").data("kendoGrid").dataSource.data(result.scheduler);

                },
                error: function (errorThrown) {
                  console.log(errorThrown);
                }
              })
            }
          },
            sort: { field: "data_scadenza", dir: "desc" },
          schema: {
            model: {
              id: "id_agenda",
              fields: {
                cliente: { editable: false },
                oggetto: { editable: false },
                annotazioni: { editable: false },
                data_scadenza: { type:"date", editable: false },
                destinatari: { editable: false },
              }
            }
          },
          pageSize: 50,
        },
        dataBound: loadCellsAttributesScheduler,
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
    		columns: [{ field: "id_agenda", title: "ID", hidden: true },
				{ field: "fk_utenti_ins", title: "Ins", hidden: true },
				{ field: "cliente", title: "<?php _e('Customer','wp-smart-crm-invoices-free')?>" },
				{ field: "oggetto", title: "<?php _e('Object','wp-smart-crm-invoices-free')?>" },
				{ field: "annotazioni", title: "<?php _e('Description','wp-smart-crm-invoices-free')?>" },
				{ field: "data_scadenza", title: "<?php _e('Expiration','wp-smart-crm-invoices-free')?>", template: '#= kendo.toString(kendo.parseDate(data_scadenza, "yyyy-MM-dd HH:mm:ss"), "' + $format + '") #' },
				{ field: "destinatari", title: "<?php _e('Recipients','wp-smart-crm-invoices-free')?>" },{field:"privileges",hidden:true},
        { command: [
            {
            name: "<?php _e('Open','wp-smart-crm-invoices-free')?>",
            click: function (e) {
              e.preventDefault();
              var position = $(e.target).offset();
                console.log(position.top)
              var tr = $(e.target).closest("tr"); // get the current table row (tr)
              var _row = this.dataItem(tr);
                //location.href="?page=smart-crm&p=scheduler/view.php&ID="+data.id;
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
                      $('.modal_inner').animate({
                          'top': position.top - 320 + 'px',
                      }, 1000);

                  },
                  error: function (errorThrown) {
                      console.log(errorThrown);
                  }
              })
            },
               className: "btn _flat"
          },
          {
            name: "<?php _e('Delete','wp-smart-crm-invoices-free')?>",
            click: function (e) {
                e.preventDefault();
				var tr = $(e.target).closest("tr"); // get the current table row (tr)
          // get the data bound to the current table row
				var data = this.dataItem(tr);
				if (!confirm("<?php _e('Confirm delete','wp-smart-crm-invoices-free') ?>?"))
					return false;
				location.href="<?php echo admin_url('admin.php?page=smart-crm&p=scheduler/delete.php&ID=')?>"+data.id +"&ref=dashboard&security=<?php echo $delete_nonce?>";
			},
        className: "btn btn-danger _flat"
        }
        ],width:200
        }, { field: "esito", hidden: true }
		, { field: "status", title: "<?php _e('Status','wp-smart-crm-invoices-free')?>", width: 100 , "filterable": false}
        , { field: "class", hidden: true }
        ],
        height: 500,
        editable:"popup"
      });

});
    </script> 
<h4 class="page-header"><?php _e('Quick Menu','wp-smart-crm-invoices-free')?><!--<span class="crmHelp" data-help="quick-menu"></span>--></h4>
<div class="col-md-12" style="border-bottom:8px solid #337ab7;margin-bottom:30px">
<ul class="quick_menu" style="padding-bottom:10px;float: left;width: 100%;">
    <?php if($privileges ==null || $privileges['agenda'] ==2){?>
    <li onClick="location.href='<?php echo admin_url()?>?page=smart-crm&p=scheduler/form.php&tipo_agenda=1';return false;">
        <i class="glyphicon glyphicon-tag"></i><br /><b ><?php _e('New Todo','wp-smart-crm-invoices-free')?><small></small></b>
    </li>
    <li onClick="location.href='<?php echo admin_url('admin.php?page=smart-crm&p=scheduler/form.php&tipo_agenda=2')?>';return false;">
        <i class="glyphicon glyphicon-pushpin"></i><br /><b ><?php _e('New appointment','wp-smart-crm-invoices-free')?><small></small></b>
    </li>
    <?php } ?>
    <?php if($privileges ==null || $privileges['customer'] ==2){?>
	<li onclick="location.href='<?php echo admin_url('admin.php?page=smart-crm&p=clienti/form.php')?>';return false;">
        <i class="glyphicon glyphicon-user"></i><br /><b ><?php _e('New Customer','wp-smart-crm-invoices-free')?><small></small></b>
    </li>
    <?php } ?>
    <?php if($privileges ==null || $privileges['quote'] ==2){?>
    <li onClick="location.href='<?php echo admin_url('admin.php?page=smart-crm&p=documenti/form_quotation.php&type=1')?>';return false;">
		<i class="glyphicon glyphicon-circle-arrow-right"></i>
		<br />
		<b>
			<?php _e('New quotation','wp-smart-crm-invoices-free')?>
			<small></small>
		</b>
	</li>
    <?php } ?>
    <?php if($privileges ==null || $privileges['invoice'] ==2){?>
	<li onclick="location.href='<?php echo admin_url('admin.php?page=smart-crm&p=documenti/form_invoice.php&type=2')?>';return false;">
        <i class="glyphicon glyphicon-open-file"></i><br /><b ><?php _e('New Invoice','wp-smart-crm-invoices-free')?><small></small></b>
    </li>
    <?php } ?>
    <?php
	if(current_user_can('manage_options') ){
?>

	<li onclick="location.href='<?php echo admin_url('admin.php?page=smartcrm_settings&tab=CRM_general_settings')?>';return false;">
		<i class="glyphicon glyphicon-cog"></i>
		<br />
		<b>
			<?php _e('Settings','wp-smart-crm-invoices-free')?>
			<small></small>
		</b>
	</li>
	<?php }?>
</ul>
</div>
<div class="col-md-12" style="background:#fafafa;padding:15px">
    <h4 class="page-header"><?php _e('Your current notifications','wp-smart-crm-invoices-free')?><span class="crmHelp" data-help="home-notifications"></span> 
		<div id="week_menu" style="float: right;margin-right: 50px;margin-top: -6px;">
			<ul class="nav nav-pills">
				<li role="presentation" <?php echo  (strstr($menu,"day") || !isset($_GET['view']) ) ? "class=\"active\"" :null  ?>><a href="<?php echo admin_url()?>?page=smart-crm&view=day"><?php _e('Daily View','wp-smart-crm-invoices-free')?></a></li>
				<li role="presentation" <?php echo  strstr($menu,"week") ? "class=\"active\"" :null  ?>><a href="<?php echo admin_url()?>?page=smart-crm&view=week"><?php _e('Weekly View','wp-smart-crm-invoices-free')?></a></li>

			</ul>
		</div>
	</h4>
    <script id="tooltipTemplate" type="text/x-kendo-template">
        <div style="background-color:rgba(57,57,57,.8);border:2px solid rgb(204,204,204);color:rgb(250,250,250);border-radius:6px;display:block;width:240px;height:100px">#=target.data('title')#</div>
    </script>
    <?php if($privileges==null || $privileges['agenda'] >0){ ?>
<h3 style="margin:0 20px"><?php _e('Todo','wp-smart-crm-invoices-free')?>
	<ul class="select-action _llegend pull-right" style="width:initial">
		<span style="float:right;font-size:.6em;background: none!important;">
			<li class="no-link" style="margin-top:4px">
				<?php _e('Legend','wp-smart-crm-invoices-free') ?>:
			</li>
			<li class="no-link">
				<i class="glyphicon glyphicon-ok" style="color:green;font-size:1.3em"></i><?php _e('Done','wp-smart-crm-invoices-free') ?>
			</li>
			<li class="no-link">
				<i class="glyphicon glyphicon-bookmark  " style="color:black;font-size:1.3em"></i><?php _e('To be done','wp-smart-crm-invoices-free') ?>
			</li>
			<li class="no-link">
				<i class="glyphicon glyphicon-remove" style="color:red;font-size:1.3em"></i><?php _e('Canceled','wp-smart-crm-invoices-free') ?>
			</li>
			<li class="no-link">
				<span class="tipped" style="width:13px;height:13px;display:inline-flex" title="<?php _e('Mouse over to display info','wp-smart-crm-invoices-free')?>"></span>Info tooltip
			</li>
		</span>
	</ul>



</h3>
<div id="grid_todo" class="datagrid" style="margin-bottom:24px"></div>
	
<h3><?php _e('Appointments','wp-smart-crm-invoices-free')?>
	<ul class="select-action _llegend pull-right" style="width:initial">
		<span style="float:right;font-size:.6em;background: none!important;">
			<li class="no-link" style="margin-top:4px">
				<?php _e('Legend','wp-smart-crm-invoices-free') ?>:
			</li>
			<li class="no-link">
				<i class="glyphicon glyphicon-ok" style="color:green;font-size:1.3em"></i><?php _e('Done','wp-smart-crm-invoices-free') ?>
			</li>
			<li class="no-link">
				<i class="glyphicon glyphicon-bookmark  " style="color:black;font-size:1.3em"></i><?php _e('To be done','wp-smart-crm-invoices-free') ?>
			</li>
			<li class="no-link">
				<i class="glyphicon glyphicon-remove" style="color:red;font-size:1.3em"></i><?php _e('Canceled','wp-smart-crm-invoices-free') ?>
			</li>
			<li class="no-link">
				<span class="tipped" style="width:13px;height:13px;display:inline-flex" title="<?php _e('Mouse over to display info','wp-smart-crm-invoices-free')?>"></span><?php _e('Info tooltip','wp-smart-crm-invoices-free')?>
			</li>
		</span>
	</ul>
	</h3>
<div id="grid_appuntamenti" class="datagrid" style="margin-bottom:24px"></div> 
</div>
<?php } else{?> 
<h3 style="color:crimson"><?php _e("You don't have permission to access the notification area","wp-smart-crm-invoices-free") ?></h3>
<?php } ?>
<div id="dialog-view" style="display:none;margin: 0 auto; text-align: center; z-index: 1000; width: 100%; height: 100%; position: absolute;left: 0;top:0;"  class="_modal">
</div>
