<?php
if ( ! defined( 'ABSPATH' ) ) exit;
$active=$_SERVER['QUERY_STRING'];
$active = $active !="" ? explode('&',$active): null;
$menu = count($active) > 1  ? $active[1] : "";
$options=get_option('CRM_general_settings');
$current_user = wp_get_current_user();
?>
<div id="mainMenu">


<ul class="nav nav-pills">
    <li role="presentation" <?php echo ( $active[0]=="page=smart-crm" && count($active) ==0 || $active[0]=="page=smart-crm" && strstr($menu,"view") || $_SERVER['QUERY_STRING'] =="page=smart-crm" ) ? "class=\"active\"" :null  ?>>
        <a href="<?php echo admin_url('admin.php?page=smart-crm')?>"><i class="glyphicon glyphicon-home"></i> <?php _e('Dashboard','wp-smart-crm-invoices-free') ?></a>
	</li>
    <li role="presentation" <?php echo strstr($menu,"clienti") ? "class=\"active\"" :null  ?>>
        <?php if(!isset($privileges) || $privileges['customer'] >0){?>
        <a href="<?php echo admin_url('admin.php?page=smart-crm&p=clienti/list.php')?>"><i class="glyphicon glyphicon-user"></i> <?php _e('Customers','wp-smart-crm-invoices-free') ?></a>
        <?php } else{ ?>
        <a href="#" onclick="noPermission('customers');return false;">
            <i class="glyphicon glyphicon-user"></i><?php _e('Customers','wp-smart-crm-invoices-free') ?>
        </a>
        <?php } ?>
        <?php if(!isset($privileges) || $privileges['customer'] >0){?>
        <ul>

            <li role="presentation" <?php echo strstr($menu,"clienti") ? "class=\"active\"" :null  ?>>
                <a href="<?php echo admin_url('admin.php?page=smart-crm&p=clienti/list.php')?>">
                    <i class="glyphicon glyphicon-align-justify"></i>
                    <?php _e('LIST','wp-smart-crm-invoices-free')?>&raquo;
                </a>
            </li>

            <?php if(!isset($privileges) || $privileges['customer']==2){?>
            <li role="presentation" <?php echo strstr($menu,"documenti") ? "class=\"active\"" :null  ?>>
                <a href="<?php echo admin_url('admin.php?page=smart-crm&p=clienti/form.php')?>">
                    <i class="glyphicon  glyphicon-user"></i><?php _e('NEW CUSTOMER','wp-smart-crm-invoices-free')?>&raquo;
                </a>
            </li>
            <?php } ?>
        </ul>
    <?php } ?>		
	</li>
<?php if(isset($options['services']) &&$options['services'] ==1){?>
    <li role="presentation" <?php echo strstr($menu,"articoli") ? "class=\"active\"" :null  ?>>
        <a href="<?php echo admin_url('admin.php?page=smart-crm&p=articoli/list.php')?>"><i class="glyphicon glyphicon-star-empty"></i> <?php _e('Services','wp-smart-crm-invoices-free') ?></a>
	</li>
<?php } 
	is_multisite() ? $filter=get_blog_option(get_current_blog_id(), 'active_plugins' ) : $filter=get_option('active_plugins' );
	if ( ! in_array( 'wp-smart-crm-advanced/wp-smart-crm-advanced.php', apply_filters( 'active_plugins', $filter) ) ) {	  
	  ?>
    <li role="presentation" <?php echo strstr($menu,"scheduler") ? "class=\"active\"" :null  ?>>
        <?php if(!isset($privileges) || $privileges['agenda'] >0 ){?>
        <a href="<?php echo admin_url('admin.php?page=smart-crm&p=scheduler/list.php')?>">
            <i class="glyphicon  glyphicon-time"></i> <?php _e('Scheduler','wp-smart-crm-invoices-free') ?>
		</a>
        <?php } else{ ?>
        <a href="#" onclick="noPermission('agenda');return false;">
            <i class="glyphicon  glyphicon-time"></i><?php _e('Scheduler','wp-smart-crm-invoices-free') ?>
        </a>
        <?php } ?>
        <?php if(!isset($privileges) || $privileges['agenda'] >0){?>
        <ul>
            <li role="presentation" <?php echo strstr($menu,"scheduler") ? "class=\"active\"" :null  ?>>
                <a href="<?php echo admin_url('admin.php?page=smart-crm&p=scheduler/list.php')?>">
                    <i class="glyphicon glyphicon-align-justify"></i>
                    <?php _e('LIST','wp-smart-crm-invoices-free')?>&raquo;
                </a>
            </li>
            <?php if(!isset($privileges) || $privileges['agenda'] ==2){?>
            <li role="presentation" <?php echo strstr($menu,"scheduler") ? "class=\"active\"" :null  ?>>
                <a href="<?php echo admin_url('admin.php?page=smart-crm&p=scheduler/form.php&tipo_agenda=1')?>">
                    <i class="glyphicon  glyphicon-tag"></i>
                    <?php _e('NEW TODO','wp-smart-crm-invoices-free')?>&raquo;
                </a>
            </li>
            <li role="presentation" <?php echo strstr($menu,"scheduler") ? "class=\"active\"" :null  ?>>
                <a href="<?php echo admin_url('admin.php?page=smart-crm&p=scheduler/form.php&tipo_agenda=2')?>">
                    <i class="glyphicon  glyphicon-pushpin"></i>
                    <?php _e('NEW APPOINTMENT','wp-smart-crm-invoices-free') ?>&raquo;
                </a>
            </li>
            <?php } ?>
        </ul>
        <?php } ?>
	</li>
	<?php } ?>
    <li role="presentation" <?php echo strstr($menu,"documenti") ? "class=\"active\"" :null  ?>>
        <?php if(!isset($privileges) || $privileges['quote'] >0 || $privileges['invoice'] >0){?>
        <a href="<?php echo admin_url('admin.php?page=smart-crm&p=documenti/list.php')?>"><i class="glyphicon glyphicon-th-list"></i> <?php _e('Documents','wp-smart-crm-invoices-free') ?></a>
        <?php } else{ ?>
        <a href="#" onclick="noPermission('documents');return false;"><i class="glyphicon glyphicon-th-list"></i> <?php _e('Documents','wp-smart-crm-invoices-free') ?></a>
        <?php } ?>
        <?php if(!isset($privileges) || $privileges['quote'] >0 || $privileges['invoice'] >0){?>
		<ul>
			<li role="presentation" <?php echo strstr($menu,"documenti") ? "class=\"active\"" :null  ?>>
				<a href="<?php echo admin_url('admin.php?page=smart-crm&p=documenti/list.php')?>">
					<i class="glyphicon glyphicon-align-justify"></i>
					<?php _e('LIST','wp-smart-crm-invoices-free')?>&raquo;
				</a>
			</li>
            <?php } ?>
            <?php if(!isset($privileges) || $privileges['invoice'] ==2){?>
			<li role="presentation" <?php echo strstr($menu,"documenti") ? "class=\"active\"" :null  ?>>
				<a href="<?php echo admin_url('admin.php?page=smart-crm&p=documenti/form_invoice.php')?>">
					<i class="glyphicon  glyphicon-fire"></i>
				<?php _e('NEW INVOICE','wp-smart-crm-invoices-free')?>&raquo;
			</a>
		    </li>
            <?php } ?>
            <?php if(!isset($privileges) || $privileges['quote'] ==2){?>
		    <li role="presentation" <?php echo strstr($menu,"documenti") ? "class=\"active\"" :null  ?>>
				<a href="<?php echo admin_url('admin.php?page=smart-crm&p=documenti/form_quotation.php')?>">
					<i class="glyphicon  glyphicon-send"></i>
				<?php _e('NEW QUOTATION','wp-smart-crm-invoices-free') ?>&raquo;
				</a>
		    </li>

			<?php do_action('WPsCRM_add_submenu_documents',$menu)?>
		</ul>
        <?php } ?>
	</li>
<?php
	if(current_user_can('manage_options') ){
?>
    <li role="presentation"  <?php if(strstr($menu,"settings")) {?> class="active" <?php } ?>><a href="#" onclick="return false;"><i class="glyphicon  glyphicon-wrench"></i> <?php _e('Utilities','wp-smart-crm-invoices-free') ?></a>
        <ul>
            <li role="presentation" <?php if(strstr($menu,"settings")) {?> class="active" <?php } ?>><a href="<?php echo admin_url('admin.php?page=smartcrm_settings&tab=CRM_documents_settings')?>"><i class="glyphicon glyphicon-cog"></i> <?php _e('SETTINGS','wp-smart-crm-invoices-free') ?>&raquo;</a></li>
			<li role="presentation" ><a href="<?php echo admin_url('admin.php?page=smart-crm&p=register_invoices/form.php')?>"><i class="glyphicon glyphicon-transfer"></i> <?php _e('REGISTER INVOICES','wp-smart-crm-invoices-free') ?>&raquo;</a></li>
			<li role="presentation" ><a href="<?php echo admin_url('admin.php?page=smart-crm&p=import/form.php')?>"><i class="glyphicon glyphicon-import"></i> <?php _e('IMPORT CUSTOMERS','wp-smart-crm-invoices-free') ?>&raquo;</a></li>
			<?php do_action('WPsCRM_add_options_in_menu')?>
	    </ul>

    </li>
    <li role="presentation" <?php if(strstr($active[0],"subscription")) {?> class="active" <?php } ?>>
        <a href="<?php echo admin_url('admin.php?page=smartcrm_subscription-rules')?>">
            <i class="glyphicon glyphicon-bell"></i>
            <?php _e('Subscription/Notification rules','wp-smart-crm-invoices-free') ?>
        </a>
    </li>
	<?php } ?>
    <li role="presentation" <?php if(strstr($active[0],"subscription")) {?> class="active" <?php } ?>>
        <a href="#" onclick="return false;" style="color:firebrick">
            <i class="glyphicon glyphicon-asterisk"></i><?php _e('Upgrade to PRO version','wp-smart-crm-invoices-free') ?>
        </a>
        <ul>
            <li role="presentation" >
                <a href="http://crm.softrade.it" target="_blank" style="color:firebrick">
                    DEMO &raquo;
                </a><br />
                <small>
                    user:demo
                    <br />password: demowpsmartcrm
                </small>
            </li>
            <li role="presentation">
 
                <a href="https://softrade.it/servizi/wp-smart-crm-pro-plugin/" target="_blank" style="color:firebrick">
                   <?php _e('BUY Pro version','wp-smart-crm-invoices-free') ?> &raquo;
                </a>
            </li>
        </ul>
    </li>
    <?php do_action('add_menu_items_b') //add custom menu items through file functions.php of your theme using hook 'add_menu_items_b'?>
</ul>

</div>
       
<script>
    function noPermission(type) {
        setTimeout(function(){
            noty({
            text: "<?php _e('You don\'t have permission to view this section','wp-smart-crm-invoices-free')?>",
            layout: 'topRight',
            type: 'error',
            template: '<div class="noty_message"><span class="noty_text"></span></div>',
            timeout: 2000,
            container:"#mainMenu"
            });
        },200);
    }
</script>