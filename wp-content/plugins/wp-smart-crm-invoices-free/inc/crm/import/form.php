<?php
	if ( ! defined( 'ABSPATH' ) ) exit;

	$import_nonce= wp_create_nonce( "import_customers" );
	$revert_nonce= wp_create_nonce( "revert_import" );
?>
<script type="text/javascript">

jQuery(document).ready(function ($) {
//	$("#file_csv").kendoUpload();
	$("#btn_save").click(function () {
		var formData = new FormData();
		var file = jQuery(document).find('input[type="file"]');
		var lists=[];
		$('.lists').each(function() {
			var list = $(this);
			if(list.is(':checked')) {
				lists[lists.length]=list.attr("id");
			}
		});
		if($("#override").is(':checked'))
            var chk="1";
        else
            var chk="0";
		if($("#newsletter_flag").is(':checked'))
            var newsletter_flag="1";
        else
            var newsletter_flag="0";
		var csv_file = file[0].files[0];
		formData.append("file", csv_file);
		formData.append("newsletter_flag", newsletter_flag);
		formData.append("override", chk);
		formData.append("lists", lists);
		formData.append('action', 'WPsCRM_import_customers');
		formData.append('security','<?php echo $import_nonce ?>');

		//console.log(formData);
            $.ajax({
                url: ajaxurl,
                data: formData,
                type: "POST",
                contentType: false,
                processData: false,
                success: function (response) {
                    console.log(response);
//                    var err_obj = JSON.parse(response);
//                    var err_obj = $.parseJSON(response);
                      var obj=response;
                    if (obj)
                    {
                        if (obj.mess_id==2)
                        {
                            var str_errore="<?php _e('The following errors occurred during import','wp-smart-crm-invoices-free')?>:<br />";
                            var err_obj=obj.errore;    
                            for(var i = 0; i < err_obj.length; i++) {
                                str_errore+="#"+err_obj[i].riga+": "+err_obj[i].errore+"<br />";
    }
                            $("#err").html(str_errore);
                        }
                        if (obj.mess_id==1 || obj.mess_id==3)
                        {
                            $("#err").html(obj.msg);
                        }
                    }
                }
            })
    });

});



</script>
<div id="err"></div>
<form name="form_insert" method="post" class="form" role="form" enctype="multipart/form-data">
    <div style="margin-top:14px;background-color: #fafafa;" class="col-md-12">

    <!-- TAB 1 -->
 
        <div id="d_anagrafica">
        <h4 class="page-header" style="background:#e2e2e2;padding:15px"><?php _e('Import customers from csv file','wp-smart-crm-invoices-free')?><span class="crmHelp crmHelp-dark" data-help="import-customers"></span></h4>
        <p><?php _e('Select a cvs file to import your customers. Note that the first row of the file must contain the following headers (named exactly as they are):','wp-smart-crm-invoices-free')?></p>
            <label> firstname ; lastname ; company ; address ; zip ; city ; province ; vat ; taxcode ; phone1 ; mobile ; fax ; email ; website ; skype; birthdate ; birthplace ; country ; invoiceable ; type ; categories ; interests ; origins</label>
		<p>
			<?php _e('The fields must be separated by semicolon (;).','wp-smart-crm-invoices-free')?>
		</p>
        <p><?php _e('The only mandatory fields are: "firstname"+"lastname" or alternatively "company".','wp-smart-crm-invoices-free')?></p>
        <p><?php _e('birthdate must be in yyyy-mm-dd format; invoiceable can be 1 or 0 (for yes/no); type can be 1 or 2 (for private/business); categories, interests and origins can contain string separated by commas (see example file) ','wp-smart-crm-invoices-free')?></p>
        <?php
			is_multisite() ? $filter=get_blog_option(get_current_blog_id(), 'active_plugins' ) : $filter=get_option('active_plugins' );
			if ( in_array( 'newsletter/plugin.php', apply_filters( 'active_plugins', $filter) ) ) 
			{?>
		<p><?php _e('Optionally you can import newsletter subscribers into one or more lists. To this purpose, you have to add one column to your csv file named "newsletter" (with values 1/0) and optionally check the list(s) in which  you want to import','wp-smart-crm-invoices-free')?></p>
		<?php }?>
		<p><?php _e('A report will show your import errors. You can find error logs in wp-content/plugins/wp-smart-crm-invoices-free/logs','wp-smart-crm-invoices-free')?></p>
        <p style="color:red">***<?php _e('If you select the overwrite option, the existing records will be deleted. Be careful if you have done operations on your customers: all the activities previously saved will loose their customer association.','wp-smart-crm-invoices-free')?></p>
			<p></p>
			<p><a href="<?php echo WPsCRM_URL."examples/import.csv"?>"><?php _e('Download a csv example file','wp-smart-crm-invoices-free')?></a></p>
            <div class="row form-group">
                <label class="col-sm-3 control-label"><?php _e('Upload csv','wp-smart-crm-invoices-free')?> *</label>
                <div class="col-sm-4">
                    <input type="file" name="file_csv" id='file_csv' class="_m form-control">
                </div>
            </div>
            <div class="row form-group">
                <label class="col-sm-3 control-label" ><?php _e('Overwrite existing records','wp-smart-crm-invoices-free')?> </label>
                <div class="col-sm-4">
                    <input type="checkbox" name="override" value="1" id='override' class="_m form-control">
					<small style="color:red">
						***<?php _e('Warning: read above','wp-smart-crm-invoices-free')?>
					</small>
                </div>
            </div>
			<?php
			is_multisite() ? $filter=get_blog_option(get_current_blog_id(), 'active_plugins' ) : $filter=get_option('active_plugins' );
			if ( in_array( 'newsletter/plugin.php', apply_filters( 'active_plugins', $filter) ) ) 
			{?>
			<div class="row form-group">
				<label class="col-sm-3 control-label"><?php _e('Insert in Newsletter','wp-smart-crm-invoices-free')?>?</label>
				<div class="col-sm-1">
					<input type="checkbox" name="newsletter_flag" id="newsletter_flag" value="1">
				</div>
			</div>
			<?php
			$options_profile = get_option('newsletter_profile');
			echo '<div id="listCheck" style="display:none"><label class="col-sm-3 control-label"><small>'.__('Optionally select the list(s) where you want to import records','wp-smart-crm-invoices-free').'</small></label><div class="newsletter-preferences-group col-sm-4">';

			for ($i = 1; $i <= 20; $i++) {
				if (empty($options_profile['list_' . $i])) {
					continue;
				}
				?>
				<div class="newsletter-preferences-item">
					<label>
					<input type="checkbox" class="lists" id="list_<?php echo $i?>" name="list_<?php echo $i?>" value="1">
						<?php echo esc_html($options_profile['list_' . $i])?>
					</label>
				</div>
			<?php
			}
			echo '<div style="clear: both"></div></div></div>';
			}
			?>
             <div class="row form-group">
                 <ul class="select-action" style="margin-left:8px">
                    <li class="btn btn-success btn-sm _flat" id="btn_save"><i class="glyphicon glyphicon-import"></i> 
                        <b onClick="return false;"> <?php _e('Import','wp-smart-crm-invoices-free')?></b>
                    </li>
                    <li class="btn btn-warning btn-sm _flat"><i class="glyphicon glyphicon-floppy-remove"></i>
						<b onclick="window.location.replace('<?php echo admin_url( 'admin.php?page=smart-crm&p=scheduler/list.php' )?>');return false;">
							<?php _e('Reset','wp-smart-crm-invoices-free')?>
						</b>
                    </li>
                     
                </ul>
		    </div>      
	    </div>

    </div>
<input type="submit"  id="submit_form" style="display:none"/>

</form>
<script>
	jQuery(document).ready(function ($) {
		$('#newsletter_flag').on('click', function () {
			$('#listCheck').toggle()
		})
	})
</script>
<?php 
	do_action('WPsCRM_users_import',$import_nonce, $revert_nonce);
?>
