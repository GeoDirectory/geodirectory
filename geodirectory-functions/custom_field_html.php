<?php /* ====== Custom fields form  ======*/
global $post_type;

if(!isset($field_info->post_type)){
	$post_type = $_REQUEST['listing_type'];
}else
	$post_type = $field_info->post_type;


$nonce = wp_create_nonce( 'custom_fields_'.$result_str );

$field_admin_title = '';
if(isset($field_info->admin_title))
	$field_admin_title = $field_info->admin_title;

$default = isset($field_info->is_admin)	 ? $field_info->is_admin : '';
?>
<li class="text" id="licontainer_<?php echo $result_str;?>">
    <div class="title title<?php echo $result_str;?> gt-fieldset"   title="<?php _e('Double Click to toggle and drag-drop to sort',GEODIRECTORY_TEXTDOMAIN);?>" ondblclick="show_hide('field_frm<?php echo $result_str;?>')">
 <?php
 	
 	$nonce = wp_create_nonce( 'custom_fields_'.$result_str );
 ?>    
 
        <?php if($default):?>
        	<div title="<?php _e('Drag and drop to sort',GEODIRECTORY_TEXTDOMAIN);?>"  class="handlediv move"></div>
        <?php else: ?>    
        	<div title="<?php _e('Click to remove field',GEODIRECTORY_TEXTDOMAIN);?>" onclick="delete_field('<?php echo $result_str;?>', '<?php echo $nonce;?>')" class="handlediv close"></div>
     	<?php endif; 
		if($field_type=='fieldset')
		{
		?>
        
        <b style="cursor:pointer;" onclick="show_hide('field_frm<?php echo $result_str;?>')"><?php echo ucwords(__('Fieldset:',GEODIRECTORY_TEXTDOMAIN).' '.$field_admin_title);?></b>
         <?php
         }
		 else
		 {
		 ?>
         <b style="cursor:pointer;" onclick="show_hide('field_frm<?php echo $result_str;?>')"><?php echo ucwords(__('Field:',GEODIRECTORY_TEXTDOMAIN).' '.$field_admin_title.' ('.$field_type.')');?></b>
        <?php
        }
		?>
    </div>

    <div id="field_frm<?php echo $result_str;?>" class="field_frm" style="display:<?php if($field_ins_upd == 'submit'){echo 'block;';}else{echo 'none;';} ?>">
        <input type="hidden" name="_wpnonce" value="<?php echo $nonce; ?>" />
        <input type="hidden" name="listing_type" id="listing_type" value="<?php echo $post_type;?>" />
        <input type="hidden" name="field_type" id="field_type" value="<?php echo $field_type;?>" />
        <input type="hidden" name="field_id" id="field_id" value="<?php echo $result_str;?>" />
    	<input type="hidden" name="data_type" id="data_type" value="<?php if(isset($field_info->data_type)){ echo $field_info->data_type;}?>" />
        <input type="hidden" name="is_active" id="is_active" value="1" />
       
        <table class="widefat post fixed" border="0" style="width:100%;">
        <?php if($field_type != 'text' || $default){?>
	   
            <input type="hidden" name="data_type" id="data_type" value="<?php if(isset($field_info->data_type)){ echo $field_info->data_type;}?>" />
       
        <?php }else{?> 
        
            <tr>
            	<td width="30%"><strong><?php _e('Field Data Type ? :',GEODIRECTORY_TEXTDOMAIN);?></strong></td>
                <td align="left">
                   
                    <select name="data_type" id="data_type" onchange="javascript:gd_data_type_changed(this, '<?php echo $result_str;?>');">
                        <option value="VARCHAR" <?php if(isset($field_info->data_type) && $field_info->data_type=='VARCHAR'){ echo 'selected="selected"';}?>><?php _e('CHARACTER',GEODIRECTORY_TEXTDOMAIN);?></option>
                        <option value="INT" <?php if(isset($field_info->data_type) && $field_info->data_type=='INT'){ echo 'selected="selected"';}?>><?php _e('NUMBER',GEODIRECTORY_TEXTDOMAIN);?></option>
                        <option value="FLOAT" <?php if(isset($field_info->data_type) && $field_info->data_type=='FLOAT'){ echo 'selected="selected"';}?>><?php _e('DECIMAL',GEODIRECTORY_TEXTDOMAIN);?></option>
                    </select>
               		<br /> <span><?php _e('Select Custom Field type',GEODIRECTORY_TEXTDOMAIN);?></span>
                    
                </td>
            </tr>
			<tr class="decimal-point-wrapper" style="<?php echo (isset($field_info->data_type) && $field_info->data_type=='FLOAT') ? '' : 'display:none'?>">
				<td width="30%"><strong><?php _e( 'Select decimal point :', GEODIRECTORY_TEXTDOMAIN ); ?></strong></td>
				<td align="left">
				<select name="decimal_point" id="decimal_point" >
					<option value=""><?php echo _e( 'Select', GEODIRECTORY_TEXTDOMAIN );?></option>
					<?php for ( $i = 1; $i <= 10; $i++ ) { $decimal_point = isset( $field_info->decimal_point ) ? $field_info->decimal_point : ''; $selected = $i == $decimal_point ? 'selected="selected"' : ''; ?>
					<option value="<?php echo $i;?>" <?php echo $selected;?>><?php echo $i;?></option>
					<?php } ?>
				</select>
				<br /> <span><?php _e( 'Decimal point to display after point', GEODIRECTORY_TEXTDOMAIN );?></span>
				</td>
			</tr> 	
        
        <?php } ?>
        
            <tr>
                <td width="30%"><strong><?php _e('Admin title :' ,GEODIRECTORY_TEXTDOMAIN);?></strong></td>
                <td align="left">
                	<input type="text" name="admin_title" id="admin_title" value="<?php if(isset($field_info->admin_title)){ echo $field_info->admin_title;}?>" />
               		<br /><span><?php _e('Personal comment, it would not be displayed anywhere except in custom field settings',GEODIRECTORY_TEXTDOMAIN);?></span>
                </td>
            </tr>
            <tr>
                <td ><strong><?php _e('Frontend title :',GEODIRECTORY_TEXTDOMAIN);?></strong></td>
                <td align="left">
                	<input type="text" name="site_title" id="site_title" value="<?php if(isset($field_info->site_title)){ echo $field_info->site_title;}?>" />
                	<br /><span><?php _e('Section title which you wish to display in frontend',GEODIRECTORY_TEXTDOMAIN);?></span>
                </td>
            </tr>
            <tr>
                <td ><strong><?php _e('Frontend description :',GEODIRECTORY_TEXTDOMAIN);?></strong></td>
                <td align="left">
                	<input type="text" name="admin_desc" id="admin_desc" value="<?php if(isset($field_info->admin_desc)){ echo $field_info->admin_desc;}?>" />
                	<br /><span><?php _e('Section description which will appear in frontend',GEODIRECTORY_TEXTDOMAIN);?></span>
                </td>
            </tr>
        <?php   if($field_type != 'fieldset' && $field_type != 'taxonomy' )
				{?>
		
        	<tr>
                <td ><strong><?php _e('HTML variable name :',GEODIRECTORY_TEXTDOMAIN);?></strong></td>
                <td align="left">
                    <input type="text" name="htmlvar_name" id="htmlvar_name" value="<?php if(isset($field_info->htmlvar_name)){ echo preg_replace('/geodir_/', '', $field_info->htmlvar_name, 1);}?>" <?php if($default) { echo 'readonly="readonly"';}?> />
                    <br />    <span><?php _e('HTML variable name must not be blank',GEODIRECTORY_TEXTDOMAIN);?></span>
                    <br />    <span><?php _e('This should be a unique name',GEODIRECTORY_TEXTDOMAIN);?></span>
                    <br />    <span><?php _e('HTML variable name not use spaces, special characters',GEODIRECTORY_TEXTDOMAIN);?></span>
                </td>
            </tr>
          <?php }?>
            <tr>
                <td ><strong><?php _e('Admin label :',GEODIRECTORY_TEXTDOMAIN);?></strong></td>
                <td align="left"><input type="text" name="clabels" id="clabels" value="<?php if(isset($field_info->clabels)){ echo $field_info->clabels;}?>" />
                <br />    <span><?php _e('Section Title which will appear in backend',GEODIRECTORY_TEXTDOMAIN);?></span>
                </td>
            </tr>
         
          <?php   if($field_type != 'textarea' 
		  				&& $field_type != 'html' 
						&& $field_type != 'file'
						&& $field_type != 'fieldset'
						&& $field_type != 'taxonomy'
						&& $field_type != 'address')
						{
							
		 ?>
        	<tr >
                <td ><strong><?php _e('Default value :',GEODIRECTORY_TEXTDOMAIN);?></strong></td>
                <td align="left"><input type="text" name="default_value" id="default_value" value="<?php if(isset($field_info->default_value)){ echo $field_info->default_value;}?>" />
                <br />    <span><?php _e('Enter the default value (for "link" this will be used as the link text)',GEODIRECTORY_TEXTDOMAIN);?></span>
                </td>
            </tr>
            		<?php
                    	}?>
            <tr>
                <td ><strong><?php _e('Display order :',GEODIRECTORY_TEXTDOMAIN);?></strong></td>
                <td align="left"><input type="text" readonly="readonly" name="sort_order" id="sort_order"  value="<?php if(isset($field_info->sort_order)){ echo $field_info->sort_order;}?>" />
                <br />    <span><?php _e('Enter the display order of this field in backend. e.g. 5',GEODIRECTORY_TEXTDOMAIN);?></span>
                </td>
            </tr>
           
            <tr>
                <td ><strong><?php _e('Show in sidebar :',GEODIRECTORY_TEXTDOMAIN);?></strong></td>
                <td align="left">
                    <select name="is_default" id="is_default" >
                    <option value="0" <?php if(!isset($field_info->is_default) || $field_info->is_default=='0'){ echo 'selected="selected"';}?>><?php _e('No',GEODIRECTORY_TEXTDOMAIN);?></option>
                    <option value="1" <?php if( isset($field_info->is_default) && $field_info->is_default=='1'){ echo 'selected="selected"';}?>><?php _e('Yes',GEODIRECTORY_TEXTDOMAIN);?></option>
                    </select>
                    <br />    <span><?php _e('Select yes or no. If no is selected then the field will be displayed as main form field or additional field',GEODIRECTORY_TEXTDOMAIN);?></span>
                </td>
            </tr>
						
						<?php if($field_type == 'textarea' && isset($field_info->htmlvar_name) && $field_info->htmlvar_name == 'geodir_special_offers'){?>
						
							<tr>
								<td><strong><?php _e('Show advanced editor :',GEODIRECTORY_TEXTDOMAIN);?></strong></td>
								
								<td>
								
							<?php
								$selected = '';
								if(isset($field_info->extra_fields))
									$advanced_editor = unserialize($field_info->extra_fields);
								
								if(!empty($advanced_editor) && is_array($advanced_editor) && in_array('1', $advanced_editor))
									$selected = 'checked="checked"';
								?>
								
										 <input type="checkbox" name="advanced_editor[]" id="advanced_editor" value="1" <?php echo $selected; ?>/>
									<span><?php _e('Select if you want to show advanced editor.',GEODIRECTORY_TEXTDOMAIN);?></span>
								</td>
								
							</tr><?php
						}?>
           
            <?php
						
			$pricearr = array();
			if(isset($field_info->packages) && $field_info->packages != '')
			{ $pricearr = explode(',',trim($field_info->packages, ',')); }	
			else {
			$package_info = array() ;	
			
			$package_info = geodir_post_package_info($package_info, '', $post_type);
					$pricearr[] =$package_info->pid;  }
			
					ob_start()
					?>
		
		<select style="display:none" name="show_on_pkg[]" id="show_on_pkg" multiple="multiple">
		<?php
			if(!empty($pricearr)){
				foreach($pricearr as $val){
					?><option selected="selected" value="<?php echo $val; ?>" ><?php echo $val; ?></option><?php
				}
			}
		?>
		</select>
			
			<?php
				$html = ob_get_clean();
				
				echo $html = apply_filters('geodir_packages_list_on_custom_fields', $html, $field_info);

			?>

				<tr>
                    <td ><strong><?php _e('Is active :',GEODIRECTORY_TEXTDOMAIN);?></strong></td>
                    <td align="left">
                        <select name="is_active" id="is_active" >
                        <option value="1" <?php if(isset($field_info->is_active) && $field_info->is_active=='1'){ echo 'selected="selected"';}?>><?php _e('Yes',GEODIRECTORY_TEXTDOMAIN);?></option>
                        <option value="0" <?php if((isset($field_info->is_active) && $field_info->is_active=='0') || !isset($field_info->is_active)){ echo 'selected="selected"';}?>><?php _e('No',GEODIRECTORY_TEXTDOMAIN);?></option>
                        </select>
                        <br />    <span><?php _e('Select yes or no. If no is selected then the field will not be displayed anywhere',GEODIRECTORY_TEXTDOMAIN);?></span>
                    </td>
                </tr>
				<?php if ( !$default ) { /* field for admin use only */ $for_admin_use = isset( $field_info->for_admin_use ) && $field_info->for_admin_use == '1' ? true : false; ?>
				<tr>
					<td><strong><?php _e( 'For admin use only? :', GEODIRECTORY_TEXTDOMAIN ); ?></strong></td>
					<td align="left">
						<select name="for_admin_use" id="for_admin_use" >
							<option value="1" <?php echo ( $for_admin_use ? 'selected="selected"' : '' ); ?>><?php _e( 'Yes', GEODIRECTORY_TEXTDOMAIN ); ?></option>
							<option value="0" <?php echo ( !$for_admin_use ? 'selected="selected"' : '' ); ?>><?php _e( 'No', GEODIRECTORY_TEXTDOMAIN ); ?></option>
						</select>
						<br /><span><?php _e( 'Select yes or no. If yes is selected then only site admin can edit this field.', GEODIRECTORY_TEXTDOMAIN ); ?></span>
					</td>
				</tr>
				<?php } ?>
								
								<?php if($field_type != 'fieldset'){?>
                <tr>
                    <td ><strong><?php _e('Is required :',GEODIRECTORY_TEXTDOMAIN);?></strong></td>
                    <td align="left">
                        <select name="is_required" id="is_required">
                        <option value="1" <?php if(isset($field_info->is_required) && $field_info->is_required=='1'){ echo 'selected="selected"';}?>><?php _e('Yes',GEODIRECTORY_TEXTDOMAIN);?></option>
                        <option value="0" <?php if((isset($field_info->is_required) && $field_info->is_required=='0') || !isset($field_info->is_required)){ echo 'selected="selected"';}?>><?php _e('No',GEODIRECTORY_TEXTDOMAIN);?></option>
                        </select>
                        <br />    <span><?php _e('Select yes to set field as required',GEODIRECTORY_TEXTDOMAIN);?></span>
                    </td>
                </tr>
               <?php }?>
							  
                <tr>
                    <td ><strong><?php _e('Required message:',GEODIRECTORY_TEXTDOMAIN);?></strong></td>
                    <td align="left">
                    <input type="text" name="required_msg" id="required_msg" value="<?php if(isset($field_info->required_msg)){ echo $field_info->required_msg;}?>" />
                    <span>
                        <?php _e('Enter text for error message if field required and have not full fill requirment.',GEODIRECTORY_TEXTDOMAIN);?>
                    </span>
                    </td>
                    </td>
                </tr>
                
                <tr>
                    <td ><strong><?php _e('Show on listing page ? :',GEODIRECTORY_TEXTDOMAIN);?></strong></td>
                    <td align="left">
                        <select  name="show_on_listing" id="show_on_listing" >
                        <option value="1" <?php if(isset($field_info->show_on_listing) && $field_info->show_on_listing=='1'){ echo 'selected="selected"';}?>><?php _e('Yes',GEODIRECTORY_TEXTDOMAIN);?></option>                        
                        <option value="0" <?php if((isset($field_info->show_on_listing) && ($field_info->show_on_listing=='0' || $field_info->show_on_listing=='')) || !isset($field_info->show_on_listing)){ echo 'selected="selected"';}?>><?php _e('No',GEODIRECTORY_TEXTDOMAIN);?></option>
                        </select>
                        <br />    <span><?php _e('Want to show this on listing page ?',GEODIRECTORY_TEXTDOMAIN);?></span>
                    </td>
                </tr>
                <tr>
                    <td><strong><?php _e( 'Show in More Info tab on detail page? :', GEODIRECTORY_TEXTDOMAIN );?></strong></td>
                    <td align="left">
                        <select name="show_on_detail" id="show_on_detail" >
                        <option value="1" <?php if(isset($field_info->show_on_detail) && $field_info->show_on_detail=='1'){ echo 'selected="selected"';}?>><?php _e('Yes',GEODIRECTORY_TEXTDOMAIN);?></option>
                        <option value="0" <?php if((isset($field_info->show_on_detail) && ($field_info->show_on_detail=='0' || $field_info->show_on_detail=='')) || !isset($field_info->show_on_detail)){ echo 'selected="selected"';}?>><?php _e('No',GEODIRECTORY_TEXTDOMAIN);?></option>
                        </select>
                        <br />    <span><?php _e('Want to show this in More Info tab on detail page?',GEODIRECTORY_TEXTDOMAIN);?></span>
                    </td>
                </tr>
				<?php if ( !$default && in_array( $field_type, array( 'text', 'datepicker', 'textarea', 'time', 'phone', 'email', 'select', 'multiselect', 'url', 'html', 'fieldset', 'radio', 'checkbox', 'file' ) ) ) { ?>
				<tr>
					<td><strong><?php _e('Show as a Tab on detail page? :',GEODIRECTORY_TEXTDOMAIN);?></strong></td>
					<td align="left">
						<select name="show_as_tab" id="show_as_tab" >
							<option value="1" <?php if(isset($field_info->show_as_tab) && $field_info->show_as_tab=='1'){ echo 'selected="selected"';}?>><?php _e('Yes',GEODIRECTORY_TEXTDOMAIN);?></option>
							<option value="0" <?php if((isset($field_info->show_as_tab) && ($field_info->show_as_tab=='0' || $field_info->show_as_tab=='')) || !isset($field_info->show_as_tab)){ echo 'selected="selected"';}?>><?php _e('No',GEODIRECTORY_TEXTDOMAIN);?></option>
						</select>
						<br /><span><?php _e('Want to display this as a tab on detail page? If "Yes" then "Show on detail page?" must be Yes.',GEODIRECTORY_TEXTDOMAIN);?></span>
					</td>
				</tr>
				<?php } ?>
                
  <?php 
			
			switch($field_type):
				case 'taxonomy': 
				{              
              ?>  
                <tr>
                    <td ><strong><?php _e('Select taxonomy:',GEODIRECTORY_TEXTDOMAIN);?></strong></td>
                    <td align="left">
                        <select name="htmlvar_name" id="htmlvar_name">
                        <?php
                            $gd_taxonomy = geodir_get_taxonomies($post_type);
                            
                            foreach($gd_taxonomy as $gd_tax)
                            {
                                ?><option <?php if(isset($field_info->htmlvar_name) && $field_info->htmlvar_name == $gd_tax){echo 'selected="selected"';}?> id="<?php echo $gd_tax;?>"><?php echo $gd_tax;?></option><?php
                            }
                        ?>
                        </select>
                       
                        <br />    <span><?php _e('Selected taxonomy name use as field name index. ex:-( post_category[gd_placecategory] )',GEODIRECTORY_TEXTDOMAIN);?></span>
                    </td>
                </tr>
								
								<tr>
                    <td ><strong><?php _e('Category display type :',GEODIRECTORY_TEXTDOMAIN);?></strong></td>
                    <td align="left">
										
                        <select name="cat_display_type" id="cat_display_type">
														<option <?php if(isset($field_info->extra_fields) && unserialize($field_info->extra_fields) == 'ajax_chained'){ echo 'selected="selected"';}?> value="ajax_chained"><?php _e('Ajax Chained',GEODIRECTORY_TEXTDOMAIN);?></option>
                            <option <?php if(isset($field_info->extra_fields) && unserialize($field_info->extra_fields) == 'select'){ echo 'selected="selected"';}?> value="select"><?php _e('Select',GEODIRECTORY_TEXTDOMAIN);?></option>
                            <option <?php if(isset($field_info->extra_fields) && unserialize($field_info->extra_fields) == 'multiselect'){ echo 'selected="selected"';}?> value="multiselect"><?php _e('Multiselect',GEODIRECTORY_TEXTDOMAIN);?></option>
                            <option <?php if(isset($field_info->extra_fields) && unserialize($field_info->extra_fields) == 'checkbox'){ echo 'selected="selected"';}?> value="checkbox"><?php _e('Checkbox',GEODIRECTORY_TEXTDOMAIN);?></option>
                            <option <?php if(isset($field_info->extra_fields) && unserialize($field_info->extra_fields) == 'radio'){ echo 'selected="selected"';}?> value="radio"><?php _e('Radio',GEODIRECTORY_TEXTDOMAIN);?></option>
                        </select>
                     
                        <br />    <span><?php _e('Show categories list as select,multiselect,checkbox or radio',GEODIRECTORY_TEXTDOMAIN);?></span>
                    </td>
                </tr>
        <?php  } // end of additional field for taxonomy field type
				break;
			case 'address':
			{
				 if(isset($field_info->extra_fields) && $field_info->extra_fields != '')
	 			{	$address = unserialize($field_info->extra_fields); }
			?>
					<?php do_action('geodir_address_extra_admin_fields', $address, $field_info); ?>
						
            <tr>
                <td ><strong><?php _e('Display zip/post code :',GEODIRECTORY_TEXTDOMAIN);?></strong></td>
                <td align="left">
                	<input type="checkbox"  name="extra[show_zip]" id="show_zip"  value="1" <?php if(isset($address['show_zip']) && $address['show_zip']=='1'){ echo 'checked="checked"';}?>/>
                 	<span><?php _e('Select if you want to show zip/post code field in address section.',GEODIRECTORY_TEXTDOMAIN);?></span>
                </td>
            </tr>
            
            <tr>
                <td ><strong><?php _e('Zip/Post code label :',GEODIRECTORY_TEXTDOMAIN);?></strong></td>
                <td align="left">
                	<input type="text" name="extra[zip_lable]" id="zip_lable"  value="<?php if(isset($address['zip_lable'])){ echo $address['zip_lable'];}?>" />
                 	<span><?php _e('Enter zip/post code field label in address section.',GEODIRECTORY_TEXTDOMAIN);?></span>
                </td>
            </tr>
            
             <tr style="display:none;">
                <td ><strong><?php _e('Display map :',GEODIRECTORY_TEXTDOMAIN);?></strong></td>
                <td align="left">
                	<input type="checkbox"  name="extra[show_map]" id="show_map"  value="1" <?php if(isset($address['show_map']) && $address['show_map']=='1'){ echo 'checked="checked"';}?>/>
                 	<span><?php _e('Select if you want to `set address on map` field in address section.',GEODIRECTORY_TEXTDOMAIN);?></span>
                </td>
            </tr>
            
             <tr>
                <td ><strong><?php _e('Map button label :',GEODIRECTORY_TEXTDOMAIN);?></strong></td>
                <td align="left">
                	<input type="text"  name="extra[map_lable]" id="map_lable"  value="<?php if(isset($address['map_lable'])){ echo $address['map_lable'];}?>" />
                 	<span><?php _e('Enter text for  `set address on map` button in address section.',GEODIRECTORY_TEXTDOMAIN);?></span>
                </td>
            </tr>
            
            <tr>
                <td ><strong><?php _e('Use user zoom level:',GEODIRECTORY_TEXTDOMAIN);?></strong></td>
                <td align="left">
                	<input type="checkbox"  name="extra[show_mapzoom]" id="show_mapzoom"  value="1" <?php if(isset($address['show_mapzoom']) && $address['show_mapzoom']=='1'){ echo 'checked="checked"';}?>/>
                 	<span><?php _e('Select if you want to use the user defined map zoom level.',GEODIRECTORY_TEXTDOMAIN);?></span>
                </td>
            </tr>
            
            <tr>
                <td ><strong><?php _e('Display map view:',GEODIRECTORY_TEXTDOMAIN);?></strong></td>
                <td align="left">
                	<input type="checkbox"  name="extra[show_mapview]" id="show_mapview"  value="1" <?php if(isset($address['show_mapview']) && $address['show_mapview']=='1'){ echo 'checked="checked"';}?>/>
                 	<span><?php _e('Select if you want to `set default map` options in address section.',GEODIRECTORY_TEXTDOMAIN);?></span>
                </td>
            </tr>
            
                        
            <tr>
                <td ><strong><?php _e('Map view label :',GEODIRECTORY_TEXTDOMAIN);?></strong></td>
                <td align="left">
                	<input type="text" name="extra[mapview_lable]" id="mapview_lable"  value="<?php if(isset($address['mapview_lable'])){ echo $address['mapview_lable'];}?>" />
                 	<span><?php _e('Enter mapview field label in address section.',GEODIRECTORY_TEXTDOMAIN);?></span>
                </td>
            </tr>
			<tr>
                <td ><strong><?php _e('Show latitude and logatude from front-end :',GEODIRECTORY_TEXTDOMAIN);?></strong></td>
                <td align="left">
                	<input type="checkbox"  name="extra[show_latlng]" id="show_latlng"  value="1" <?php if(isset($address['show_latlng']) && $address['show_latlng']=='1'){ echo 'checked="checked"';}?>/>
                 	<span><?php _e('Select if you want to show latitude and logatude fields in address section from front-end.',GEODIRECTORY_TEXTDOMAIN);?></span>
                </td>
            </tr>	
		<?php } // end of extra fields for address field type
				break;
			case 'select':
			case 'multiselect':
			case 'radio' :
			{
							if($field_type == 'multiselect'){
								
								?>
								<tr>
                    <td ><strong><?php _e('Multiselect display type :',GEODIRECTORY_TEXTDOMAIN);?></strong></td>
                    <td align="left">
										
                        <select name="multi_display_type" id="multi_display_type">
                            <option <?php if(isset($field_info->extra_fields) && unserialize($field_info->extra_fields) == 'select'){ echo 'selected="selected"';}?> value="select"><?php _e('Select',GEODIRECTORY_TEXTDOMAIN);?></option>
                            <option <?php if(isset($field_info->extra_fields) && unserialize($field_info->extra_fields) == 'checkbox'){ echo 'selected="selected"';}?> value="checkbox"><?php _e('Checkbox',GEODIRECTORY_TEXTDOMAIN);?></option>
														<option <?php if(isset($field_info->extra_fields) && unserialize($field_info->extra_fields) == 'radio'){ echo 'selected="selected"';}?> value="radio"><?php _e('Radio',GEODIRECTORY_TEXTDOMAIN);?></option>
                        </select>
                     
                        <br />    <span><?php _e('Show multiselect list as multiselect,checkbox or radio',GEODIRECTORY_TEXTDOMAIN);?></span>
                    </td>
                </tr>
								<?php
							}
			?>
            	<tr >
                <td ><strong><?php _e('Option Values :',GEODIRECTORY_TEXTDOMAIN);?></strong></td>
                <td align="left">
                    <input type="text" name="option_values" id="option_values" value="<?php if(isset($field_info->option_values)){ echo $field_info->option_values;}?>" />
                    <br />    <span><?php _e('Option Values should be separated by comma.',GEODIRECTORY_TEXTDOMAIN);?></span>
                    <br />    <span><?php _e('If using for a "tick filter" place a / and then either a 1 for true or 0 for false',GEODIRECTORY_TEXTDOMAIN);?></span>
                    <br />    <span><?php _e('eg: "No Dogs Allowed/0,Dogs Allowed/1" (Select only, not multiselect)',GEODIRECTORY_TEXTDOMAIN);?></span>
					<?php if( $field_type == 'multiselect' || $field_type == 'select' ) { ?>
					<br />    <span><?php _e('- If using OPTGROUP tag to grouping options, use "{optgroup}OPTGROUP-LABEL|OPTION-1,OPTION-2{/optgroup}"',GEODIRECTORY_TEXTDOMAIN);?></span>
					<br />    <span><?php _e('eg: "{optgroup}Pets Allowed|No Dogs Allowed/0,Dogs Allowed/1{/optgroup},{optgroup}Sports|Cricket/Cricket,Football/Football,Hockey{/optgroup}"',GEODIRECTORY_TEXTDOMAIN);?></span>
					<?php } ?>
                </td>
            </tr>
            <?php 
			} // end of extra fields for select , multiselect and radio type fields
				break;
			case 'datepicker':
			{
				if(isset($field_info->extra_fields) && $field_info->extra_fields != '')
				{	$extra = unserialize($field_info->extra_fields); }
			?>
            	<tr>
                <td ><strong><?php _e('Date Format :',GEODIRECTORY_TEXTDOMAIN);?></strong></td>
                <td align="left" style="overflow:inherit;">
                    <input type="text" name="extra[date_format]" id="date_format" value="<?php if(isset($extra['date_format'])){echo $extra['date_format'];}?>" />
                    <div style="position:relative; cursor:pointer;">
                        <span onclick="jQuery('#show_dateformat').toggle();">
                            [Valid Date Formats]
                        </span>
        
                        <div id="show_dateformat" style=" background:#CCCCCC; height:auto;position:absolute; z-index:100; display:none;">
                        
                        <table class="doctable table">
                            <caption><strong><?php _e('Date Formats',GEODIRECTORY_TEXTDOMAIN) ; ?></strong></caption>
                        
                            <thead>
                            <tr>
                                <th><?php _e('Description',GEODIRECTORY_TEXTDOMAIN) ; ?></th>
                                <th><?php _e('Format',GEODIRECTORY_TEXTDOMAIN) ; ?></th>
                                <th><?php _e('Examples',GEODIRECTORY_TEXTDOMAIN) ; ?></th>
                            </tr>
                            </thead>
                        		
                            <tbody class="tbody">
                         		
                            <tr>
                                <td><?php _e('American month, day and year',GEODIRECTORY_TEXTDOMAIN) ; ?></td>
                                <td><em>mm</em> "/" <em>dd</em> "/" <em>y</em></td>
                                <td>"12/22/78", "1/17/2006", "1/17/6"</td>
                            </tr>
                            
                            <tr>
                                <td><?php _e('Four digit year, month and day with slashes',GEODIRECTORY_TEXTDOMAIN) ; ?></td>
                                <td><em>yy</em> "/" <em>mm</em> "/" <em>dd</em></td>
                                <td>"2008/6/30", "1978/12/22"</td>
                            </tr>
                            
                            <tr>
                                <td><?php _e('Year, month and day with dashes',GEODIRECTORY_TEXTDOMAIN) ; ?></td>
                                <td><em>y</em> "-" <em>mm</em> "-" <em>dd</em></td>
                                <td>"2008-6-30", "78-12-22", "8-6-21"</td>
                            </tr>
														
                            <tr>
                                <td><?php _e('Day, textual month and year',GEODIRECTORY_TEXTDOMAIN) ; ?></td>
                                <td><em>d</em> ([-])* <em>M</em> ([-])* <em>y</em></td>
                                <td>"30-June 2008", "22DEC78"</td>
                            </tr>
                            
                            <tr>
                                <td><?php _e('Textual month, day and year',GEODIRECTORY_TEXTDOMAIN) ; ?></td>
                                <td><em>M</em> ([-])* <em>dd</em> [,]* <em>yy</em></td>
                                <td>"July 1st, 2008", "April 17, 1790", "May.9,78"</td>
                            </tr>
                            
                            </tbody>
                        
                        </table>
                        
                        </div>
    
                    </div>
                    <br />    
                    <span><?php _e('Enter the date format.',GEODIRECTORY_TEXTDOMAIN);?></span>
                </td>
        	</tr>
            <?php 
			}
				break; 
			
		 endswitch; ?>
            <?php if($field_type!='fieldset')
			{
			?>
                            <tr>
                    <td colspan="2" align="left"><h3><?php echo __('Custom css',GEODIRECTORY_TEXTDOMAIN); ?></h3></td>
                </tr>
                            
                            <tr>
                    <td ><strong><?php _e('Upload icon:',GEODIRECTORY_TEXTDOMAIN);?></strong></td>
                    <td align="left">
                    <input type="text" name="field_icon" id="field_icon" value="<?php if(isset($field_info->field_icon)){ echo $field_info->field_icon;}?>" />
                    <span>
                        <?php _e('Upload icon using media and enter its url path, or enter <a href="http://fortawesome.github.io/Font-Awesome/icons/" target="_blank" >font awesome </a>class eg:"fa fa-home"',GEODIRECTORY_TEXTDOMAIN);?>
                    </span>
                    </td>
                    </td>
                </tr>
                            
                            <tr>
                    <td ><strong><?php _e('Css class:',GEODIRECTORY_TEXTDOMAIN);?></strong></td>
                    <td align="left">
                    <input type="text" name="css_class" id="css_class" value="<?php if(isset($field_info->css_class)){ echo $field_info->css_class;}?>" />
                    <span>
                        <?php _e('Enter custom css class for field custom style.',GEODIRECTORY_TEXTDOMAIN);?>
                    </span>
                    </td>
                    </td>
                </tr>
          <?php
          	}
		  ?>      
              	
                <?php 
			
			switch($field_type):
				case 'html':
				case 'file':
				case 'url':
				case 'fieldset':
					break;
				default:
				?>			
				
				<tr>
					<td colspan="2" align="left">
						<h3><?php 
						
						echo apply_filters('geodir_advance_custom_fields_heading',__('Posts sort options',GEODIRECTORY_TEXTDOMAIN), $field_type); 
						
						?></h3>
					</td>
				</tr>
				
				<?php if(!in_array($field_type,array('multiselect','textarea', 'taxonomy'))) { ?>	
				<tr>
					<td><?php _e('Include this field in sort option',GEODIRECTORY_TEXTDOMAIN);?></td>
						<td>:
                        <input type="checkbox"  name="cat_sort[]" id="cat_sort"  value="1" <?php if(isset($field_info->cat_sort[0]) && $field_info->cat_sort[0]=='1'){ echo 'checked="checked"';}?>/>
                 		<span><?php _e('Select if you want to show option in sort.',GEODIRECTORY_TEXTDOMAIN);?></span>
                		</td>
				</tr>
				<?php } ?>
				
				<?php do_action('geodir_advance_custom_fields', $field_info);?>
				
				<?php /*if(!in_array($field_type,array() )){?>
				<tr>
					<td><?php _e('Add category tick filter',GEODIRECTORY_TEXTDOMAIN);?></td>
					<td>:
                    	 <input type="checkbox"  name="cat_filter[]" id="cat_filter"  value="1" <?php if(isset($field_info->cat_filter[0])=='1'){ echo 'checked="checked"';}?>/>
                 		<span><?php _e('Select if you want to show option in filter.',GEODIRECTORY_TEXTDOMAIN);?></span>
					</td>
				</tr>
				<?php }*/ ?>
                
            <?php endswitch; ?>
						
        
            <tr>
                <td >&nbsp;</td>
                <td align="left">
                
                <input type="button" class="button" name="save" id="save" value="Save" onclick="save_field('<?php echo $result_str;?>')" /> 
                <?php if(!$default):?>
                <a href="javascript:void(0)"><input type="button" name="delete" value="Delete" onclick="delete_field('<?php echo $result_str;?>', '<?php echo $nonce;?>')" class="button_n" /></a>
								<?php endif;?>
                
                </td>
            </tr>
        </table>
    
    </div>
</li>