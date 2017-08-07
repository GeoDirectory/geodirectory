<?php
/**
 * Admin custom field form
 *
 * @since 1.0.0
 *
 * @package GeoDirectory
 */
 
/**
 * Displays the custom field form content.
 *
 * @since 1.0.0
 *
 * @global string $post_type Post type.
 */

global $post_type;

if (!isset($field_info->post_type)) {
    $post_type = sanitize_text_field($_REQUEST['listing_type']);
} else
    $post_type = $field_info->post_type;

//if(isset($_REQUEST['custom_type']) && $_REQUEST['custom_type']=='predefined'){
//    $cf_arr = geodir_custom_fields_predefined($post_type);
//}elseif(isset($_REQUEST['custom_type']) && $_REQUEST['custom_type']=='custom'){
//    $cf_arr = geodir_custom_fields_custom($post_type);
//}else{
//    $cf_arr = geodir_custom_fields($post_type);
//}

$cf_arr1 = geodir_custom_fields($post_type);
$cf_arr2 = geodir_custom_fields_predefined($post_type);
$cf_arr3 = geodir_custom_fields_custom($post_type);

$cf_arr = $cf_arr1 + $cf_arr2 + $cf_arr3; // this way defaults can't be overwritten

$cf = (isset($cf_arr[$field_type_key])) ? $cf_arr[$field_type_key] : '';


if(isset($field_info->extra_fields)){$extra_fields = $field_info->extra_fields;}
$field_info = stripslashes_deep($field_info); // strip slashes from labels
if(isset($field_info->extra_fields)){$field_info->extra_fields = $extra_fields;}


$nonce = wp_create_nonce('custom_fields_' . $result_str);

$field_admin_title = '';
if (isset($field_info->admin_title))
    $field_admin_title = $field_info->admin_title;

$default = isset($field_info->is_admin) ? $field_info->is_admin : '';

$display_on_listing = true;
// Remove Send Enquiry | Send To Friend from listings page
$htmlvar_name = isset($field_info->htmlvar_name) && $field_info->htmlvar_name != '' ? $field_info->htmlvar_name : '';
if ($htmlvar_name == 'geodir_email') {
	$field_info->show_on_listing = 0;
	$display_on_listing = false;
}
$field_data_type = isset($field_info->data_type) ? $field_info->data_type : '';

$field_display = $field_type == 'address' && $field_info->htmlvar_name == 'post' ? 'style="display:none"' : '';

$radio_id = (isset($field_info->htmlvar_name) && $field_info->htmlvar_name) ? $field_info->htmlvar_name : rand(5, 500);

//print_r($field_info);

if (isset($cf['icon']) && strpos($cf['icon'], 'fa fa-') !== false) {
    $field_icon = '<i class="'.$cf['icon'].'" aria-hidden="true"></i>';
}elseif(isset($cf['icon']) && $cf['icon']){
    $field_icon = '<b style="background-image: url("'.$cf['icon'].'")"></b>';
}else{
    $field_icon = '<i class="fa fa-cog" aria-hidden="true"></i>';
}

if(isset($cf['name']) && $cf['name']){
    $field_type_name = $cf['name'];
}else{
    $field_type_name = $field_type;
}

?>
<li class="text" id="licontainer_<?php echo $result_str; ?>">
    <div class="title title<?php echo $result_str; ?> gt-fieldset"
         title="<?php _e('Double Click to toggle and drag-drop to sort', 'geodirectory'); ?>"
         ondblclick="show_hide('field_frm<?php echo $result_str; ?>')">
        <?php

        $nonce = wp_create_nonce('custom_fields_' . $result_str);
        ?>

        <?php if (!$default): ?>
            <div title="<?php _e('Click to remove field', 'geodirectory'); ?>"
                 onclick="delete_field('<?php echo $result_str; ?>', '<?php echo $nonce; ?>')"
                 class="handlediv close"><i class="fa fa-times" aria-hidden="true"></i></div>
        <?php endif;
        if ($field_type == 'fieldset') {
            ?>
            <i class="fa fa-long-arrow-left " aria-hidden="true"></i>
            <i class="fa fa-long-arrow-right " aria-hidden="true"></i>
            <b style="cursor:pointer;"
               onclick="show_hide('field_frm<?php echo $result_str;?>')"><?php echo geodir_ucwords(__('Fieldset:', 'geodirectory') . ' ' . $field_admin_title);?></b>
        <?php
        } else {echo $field_icon;
            ?>
            <b style="cursor:pointer;"
               onclick="show_hide('field_frm<?php echo $result_str;?>')"><?php echo geodir_ucwords(' ' . $field_admin_title . ' (' . $field_type_name . ')');?></b>
        <?php
        }
        ?>
    </div>

    <form><!-- we need to wrap in a fom so we can use radio buttons with same name -->
    <div id="field_frm<?php echo $result_str; ?>" class="field_frm"
         style="display:<?php if ($field_ins_upd == 'submit') {
             echo 'block;';
         } else {
             echo 'none;';
         } ?>">
        <input type="hidden" name="_wpnonce" value="<?php echo esc_attr($nonce); ?>"/>
        <input type="hidden" name="listing_type" id="listing_type" value="<?php echo $post_type; ?>"/>
        <input type="hidden" name="field_type" id="field_type" value="<?php echo $field_type; ?>"/>
        <input type="hidden" name="field_type_key" id="field_type_key" value="<?php echo $field_type_key; ?>"/>
        <input type="hidden" name="field_id" id="field_id" value="<?php echo esc_attr($result_str); ?>"/>
        <input type="hidden" name="data_type" id="data_type" value="<?php if (isset($field_info->data_type)) {
            echo $field_info->data_type;
        } ?>"/>
        <input type="hidden" name="is_active" id="is_active" value="1"/>

        <input type="hidden" name="is_default" value="<?php echo isset($field_info->is_default) ? $field_info->is_default : '';?>" /><?php // show in sidebar value?>
        <input type="hidden" name="show_on_listing" value="<?php echo isset($field_info->show_on_listing) ? $field_info->show_on_listing : '';?>" />
        <input type="hidden" name="show_on_detail" value="<?php echo isset($field_info->show_on_listing) ? $field_info->show_on_listing : '';?>" />
        <input type="hidden" name="show_as_tab" value="<?php echo isset($field_info->show_as_tab) ? $field_info->show_as_tab : '';?>" />

        <ul class="widefat post fixed" border="0" style="width:100%;">

            <?php

            // data_type
            if(has_filter("geodir_cfa_data_type_{$field_type}")){

                echo apply_filters("geodir_cfa_data_type_{$field_type}",'',$result_str,$cf,$field_info);

            }else{
                $value = '';
                if (isset($field_info->data_type)) {
                    $value = esc_attr($field_info->data_type);
                }elseif(isset($cf['defaults']['data_type']) && $cf['defaults']['data_type']){
                    $value = $cf['defaults']['data_type'];
                }
                ?>
                <input type="hidden" name="data_type" id="data_type" value="<?php echo $value;?>"/>
            <?php
            }


            // admin_title
            if(has_filter("geodir_cfa_admin_title_{$field_type}")){

                echo apply_filters("geodir_cfa_admin_title_{$field_type}",'',$result_str,$cf,$field_info);

            }else{
                $value = '';
                if (isset($field_info->admin_title)) {
                    $value = esc_attr($field_info->admin_title);
                }elseif(isset($cf['defaults']['admin_title']) && $cf['defaults']['admin_title']){
                    $value = $cf['defaults']['admin_title'];
                }
                ?>
                <li>
                    <label for="admin_title" class="gd-cf-tooltip-wrap">
                        <i class="fa fa-info-circle" aria-hidden="true"></i> <?php _e('Admin title :', 'geodirectory'); ?>
                        <div class="gdcf-tooltip">
                            <?php _e('This is used as the field setting name here in the backend only.', 'geodirectory'); ?>
                        </div>
                    </label>
                    <div class="gd-cf-input-wrap">
                        <input type="text" name="admin_title" id="admin_title"
                               value="<?php echo $value;?>"/>
                    </div>
                </li>
                <?php
            }


            // site_title
            if(has_filter("geodir_cfa_site_title_{$field_type}")){

                echo apply_filters("geodir_cfa_site_title_{$field_type}",'',$result_str,$cf,$field_info);

            }else{
                $value = '';
                if (isset($field_info->site_title)) {
                    $value = esc_attr($field_info->site_title);
                }elseif(isset($cf['defaults']['site_title']) && $cf['defaults']['site_title']){
                    $value = $cf['defaults']['site_title'];
                }
                ?>
                <li>
                    <label for="site_title" class="gd-cf-tooltip-wrap"> <i class="fa fa-info-circle" aria-hidden="true"></i> <?php _e('Frontend title :', 'geodirectory'); ?>
                        <div class="gdcf-tooltip">
                            <?php _e('This will be the title for the field on the frontend.', 'geodirectory'); ?>
                        </div>
                    </label>
                    <div class="gd-cf-input-wrap">
                        <input type="text" name="site_title" id="site_title"
                               value="<?php echo $value; ?>"/>
                    </div>
                </li>
                <?php
            }


            // admin_desc
            if(has_filter("geodir_cfa_admin_desc_{$field_type}")){

                echo apply_filters("geodir_cfa_admin_desc_{$field_type}",'',$result_str,$cf,$field_info);

            }else{
                $value = '';
                if (isset($field_info->admin_desc)) {
                    $value = esc_attr($field_info->admin_desc);
                }elseif(isset($cf['defaults']['admin_desc']) && $cf['defaults']['admin_desc']){
                    $value = $cf['defaults']['admin_desc'];
                }
                ?>
                <li>
                    <label for="admin_desc" class="gd-cf-tooltip-wrap"><i class="fa fa-info-circle" aria-hidden="true"></i> <?php _e('Frontend description :', 'geodirectory'); ?>
                        <div class="gdcf-tooltip">
                            <?php _e('This will be shown below the field on the add listing form.', 'geodirectory'); ?>
                        </div>
                    </label>
                    <div class="gd-cf-input-wrap">
                        <input type="text" name="admin_desc" id="admin_desc" value="<?php echo $value;?>"/>
                    </div>
                </li>
                <?php
            }



            // htmlvar_name
            if(has_filter("geodir_cfa_htmlvar_name_{$field_type}")){

                echo apply_filters("geodir_cfa_htmlvar_name_{$field_type}",'',$result_str,$cf,$field_info);

            }else{
                $value = '';
                if (isset($field_info->htmlvar_name)) {
                    $value = esc_attr($field_info->htmlvar_name);
                }elseif(isset($cf['defaults']['htmlvar_name']) && $cf['defaults']['htmlvar_name']){
                    $value = $cf['defaults']['htmlvar_name'];
                }
                ?>
                <li>
                    <label for="htmlvar_name" class="gd-cf-tooltip-wrap"><i class="fa fa-info-circle" aria-hidden="true"></i> <?php _e('HTML variable name :', 'geodirectory');?>
                        <div class="gdcf-tooltip">
                            <?php _e('This is a unique identifier used in the HTML, it MUST NOT contain spaces or special characters.', 'geodirectory'); ?>
                        </div>
                    </label>
                    <div class="gd-cf-input-wrap">
                        <input type="text" name="htmlvar_name" id="htmlvar_name" pattern="[a-zA-Z0-9]+" title="<?php _e('Must not contain spaces or special characters', 'geodirectory');?>"
                               value="<?php if ($value) {
                                   echo preg_replace('/geodir_/', '', $value, 1);
                               }?>" <?php if ($default) {
                            echo 'readonly="readonly"';
                        }?> />
                    </div>
                </li>
                <?php
            }


            // is_active
            if(has_filter("geodir_cfa_is_active_{$field_type}")){

                echo apply_filters("geodir_cfa_is_active_{$field_type}",'',$result_str,$cf,$field_info);

            }else{
                $value = '';
                if (isset($field_info->is_active)) {
                    $value = esc_attr($field_info->is_active);
                }elseif(isset($cf['defaults']['is_active']) && $cf['defaults']['is_active']){
                    $value = $cf['defaults']['is_active'];
                }
                ?>
                <li <?php echo $field_display; ?>>
                    <label for="is_active" class="gd-cf-tooltip-wrap"><i class="fa fa-info-circle" aria-hidden="true"></i> <?php _e('Is active :', 'geodirectory'); ?>
                        <div class="gdcf-tooltip">
                            <?php _e('If no is selected then the field will not be displayed anywhere.', 'geodirectory'); ?>
                        </div>
                    </label>
                    <div class="gd-cf-input-wrap gd-switch">

                        <input type="radio" id="is_active_yes<?php echo $radio_id;?>" name="is_active" class="gdri-enabled"  value="1"
                            <?php if ($value == '1') {
                                echo 'checked';
                            } ?>/>
                        <label for="is_active_yes<?php echo $radio_id;?>" class="gdcb-enable"><span><?php _e('Yes', 'geodirectory'); ?></span></label>

                        <input type="radio" id="is_active_no<?php echo $radio_id;?>" name="is_active" class="gdri-disabled" value="0"
                            <?php if ($value == '0' || !$value) {
                                echo 'checked';
                            } ?>/>
                        <label for="is_active_no<?php echo $radio_id;?>" class="gdcb-disable"><span><?php _e('No', 'geodirectory'); ?></span></label>

                    </div>
                </li>
                <?php
            }


            // for_admin_use
            if(has_filter("geodir_cfa_for_admin_use_{$field_type}")){

                echo apply_filters("geodir_cfa_for_admin_use_{$field_type}",'',$result_str,$cf,$field_info);

            }else{
                $value = '';
                if (isset($field_info->for_admin_use)) {
                    $value = esc_attr($field_info->for_admin_use);
                }elseif(isset($cf['defaults']['for_admin_use']) && $cf['defaults']['for_admin_use']){
                    $value = $cf['defaults']['for_admin_use'];
                }
                ?>
                <li>
                    <label for="for_admin_use" class="gd-cf-tooltip-wrap"><i class="fa fa-info-circle" aria-hidden="true"></i> <?php _e('For admin use only? :', 'geodirectory'); ?>
                        <div class="gdcf-tooltip">
                            <?php _e('If yes is selected then only site admin can see and edit this field.', 'geodirectory'); ?>
                        </div>
                    </label>
                    <div class="gd-cf-input-wrap gd-switch">

                        <input type="radio" id="for_admin_use_yes<?php echo $radio_id;?>" name="for_admin_use" class="gdri-enabled"  value="1"
                            <?php if ($value == '1') {
                                echo 'checked';
                            } ?>/>
                        <label for="for_admin_use_yes<?php echo $radio_id;?>" class="gdcb-enable"><span><?php _e('Yes', 'geodirectory'); ?></span></label>

                        <input type="radio" id="for_admin_use_no<?php echo $radio_id;?>" name="for_admin_use" class="gdri-disabled" value="0"
                            <?php if ($value == '0' || !$value) {
                                echo 'checked';
                            } ?>/>
                        <label for="for_admin_use_no<?php echo $radio_id;?>" class="gdcb-disable"><span><?php _e('No', 'geodirectory'); ?></span></label>

                    </div>
                </li>
                <?php
            }


            // default_value
            if(has_filter("geodir_cfa_default_value_{$field_type}")){

                echo apply_filters("geodir_cfa_default_value_{$field_type}",'',$result_str,$cf,$field_info);

            }else{
                $value = '';
                if (isset($field_info->default_value)) {
                    $value = esc_attr($field_info->default_value);
                }elseif(isset($cf['defaults']['default_value']) && $cf['defaults']['default_value']){
                    $value = $cf['defaults']['default_value'];
                }
                ?>
                <li>
                    <label for="default_value" class="gd-cf-tooltip-wrap"><i class="fa fa-info-circle" aria-hidden="true"></i> <?php _e('Default value :', 'geodirectory');?>
                        <div class="gdcf-tooltip">
                            <?php
                            if ($field_type == 'checkbox') {
                                _e('Should the checkbox be checked by default?', 'geodirectory');
                            } else if ($field_type == 'email') {
                                _e('A default value for the field, usually blank. Ex: info@mysite.com', 'geodirectory');
                            } else {
                                _e('A default value for the field, usually blank. (for "link" this will be used as the link text)', 'geodirectory');
                            }
                            ?>
                        </div>
                    </label>
                    <div class="gd-cf-input-wrap">
                        <?php if ($field_type == 'checkbox') { ?>
                            <select name="default_value" id="default_value">
                                <option value=""><?php _e('Unchecked', 'geodirectory'); ?></option>
                                <option value="1" <?php selected(true, (int)$value === 1);?>><?php _e('Checked', 'geodirectory'); ?></option>
                            </select>
                        <?php } else if ($field_type == 'email') { ?>
                            <input type="email" name="default_value" placeholder="<?php _e('info@mysite.com', 'geodirectory') ;?>" id="default_value" value="<?php echo esc_attr($value);?>" /><br/>
                        <?php } else { ?>
                            <input type="text" name="default_value" id="default_value" value="<?php echo esc_attr($value);?>" /><br/>
                        <?php } ?>
                    </div>
                </li>
                <?php
            }


            // show_in
            if(has_filter("geodir_cfa_show_in_{$field_type}")){

                echo apply_filters("geodir_cfa_show_in_{$field_type}",'',$result_str,$cf,$field_info);

            }else{
                $value = '';
                if (isset($field_info->show_in)) {
                    $value = esc_attr($field_info->show_in);
                }elseif(isset($cf['defaults']['show_in']) && $cf['defaults']['show_in']){
                    $value = esc_attr($cf['defaults']['show_in']);
                }
                ?>
                <li>
                    <label for="show_in" class="gd-cf-tooltip-wrap"><i class="fa fa-info-circle" aria-hidden="true"></i> <?php _e('Show in what locations?:', 'geodirectory'); ?>
                        <div class="gdcf-tooltip">
                            <?php _e('Select in what locations you want to display this field.', 'geodirectory'); ?>
                        </div>
                    </label>
                    <div class="gd-cf-input-wrap">

                        <?php

                        /*
						 * We wrap the key values in [] so we can search the DB easier with a LIKE query.
						 */
                        $show_in_locations = array(
                            "[detail]" => __("Details page sidebar", 'geodirectory'),
                            "[moreinfo]" => __("More info tab", 'geodirectory'),
                            "[listing]" => __("Listings page", 'geodirectory'),
                            "[owntab]" => __("Details page own tab", 'geodirectory'),
                            "[mapbubble]" => __("Map bubble", 'geodirectory'),
                        );

                        /**
                         * Filter the locations array for where to display custom fields.
                         *
                         * @since 1.6.6
                         * @param array $show_in_locations The array of locations and descriptions.
                         * @param object $field_info The field being displayed info.
                         * @param string $field_info The type of field.
                         */
                        $show_in_locations = apply_filters('geodir_show_in_locations',$show_in_locations,$field_info,$field_type);


                        // remove some locations for some field types

                        // don't show new tab option for some types
                        if (in_array($field_type, array('text', 'datepicker', 'textarea', 'time', 'phone', 'email', 'select', 'multiselect', 'url', 'html', 'fieldset', 'radio', 'checkbox', 'file','address','taxonomy'))) {
                        }else{
                            unset($show_in_locations['[owntab]']);
                        }

                        if(!$display_on_listing){
                            unset($show_in_locations['[listings]']);
                        }

                        ?>

                        <select multiple="multiple" name="show_in[]"
                                id="show_in"
                                style="min-width:300px;"
                                class="chosen_select"
                                data-placeholder="<?php _e('Select locations', 'geodirectory'); ?>"
                                option-ajaxchosen="false">
                            <?php

                            $show_in_values = explode(',',$value);

                            foreach( $show_in_locations as $key => $val){
                                $selected = '';

                                if(is_array($show_in_values) && in_array($key,$show_in_values ) ){
                                    $selected = 'selected';
                                }

                                ?>
                                <option  value="<?php echo $key;?>" <?php echo $selected;?>><?php echo $val;?></option>
                                <?php
                            }
                            ?>
                        </select>
                    </div>
                </li>
                <?php
            }


            // advanced_editor
            if(has_filter("geodir_cfa_advanced_editor_{$field_type}")){

                echo apply_filters("geodir_cfa_advanced_editor_{$field_type}",'',$result_str,$cf,$field_info);

            }




            ?>


            <?php // @todo this does not seem to be used for anything, it can be removed or replaced ?>
            <input type="hidden" name="clabels" id="clabels" value="<?php if (isset($field_info->clabels)) { echo esc_attr($field_info->clabels);} ?>"/>

            <?php // we dont need to show the sort order ?>
            <input type="hidden" readonly="readonly" name="sort_order" id="sort_order" value="<?php if (isset($field_info->sort_order)) { echo esc_attr($field_info->sort_order);} ?>"/>



            <?php

            $pricearr = array();
            if (isset($field_info->packages) && $field_info->packages != '') {
                $pricearr = explode(',', trim($field_info->packages, ','));
            } else {
                $package_info = array();

                $package_info = geodir_post_package_info($package_info, '', $post_type);
                $pricearr[] = $package_info->pid;
            }

            ob_start()
            ?>

            <select style="display:none" name="show_on_pkg[]" id="show_on_pkg" multiple="multiple">
                <?php
                if (!empty($pricearr)) {
                    foreach ($pricearr as $val) {
                        ?>
                        <option selected="selected" value="<?php echo esc_attr($val); ?>" ><?php echo $val; ?></option><?php
                    }
                }
                ?>
            </select>

            <?php
            $html = ob_get_clean();

			/**
			 * Filter the price packages list.
			 *
			 * Filter the price packages list in custom field form in admin
             * custom fields settings.
			 *
			 * @since 1.0.0
			 *
			 * @param string $html The price packages content.
			 * @param object $field_info Current field object.
			 */
			echo $html = apply_filters('geodir_packages_list_on_custom_fields', $html, $field_info);

            ?>



            <?php

            // is_required
            if(has_filter("geodir_cfa_is_required_{$field_type}")){

                echo apply_filters("geodir_cfa_is_required_{$field_type}",'',$result_str,$cf,$field_info);

            }else{
                $value = '';
                if (isset($field_info->is_required)) {
                    $value = esc_attr($field_info->is_required);
                }elseif(isset($cf['defaults']['is_required']) && $cf['defaults']['is_required']){
                    $value = $cf['defaults']['is_required'];
                }
                ?>
                <li>
                    <label for="is_required" class="gd-cf-tooltip-wrap"><i class="fa fa-info-circle" aria-hidden="true"></i> <?php _e('Is required :', 'geodirectory'); ?>
                        <div class="gdcf-tooltip">
                            <?php _e('Select yes to set field as required', 'geodirectory'); ?>
                        </div>
                    </label>

                    <div class="gd-cf-input-wrap gd-switch">

                        <input type="radio" id="is_required_yes<?php echo $radio_id;?>" name="is_required" class="gdri-enabled"  value="1"
                            <?php if ($value == '1') {
                                echo 'checked';
                            } ?>/>
                        <label onclick="show_hide_radio(this,'show','cf-is-required-msg');" for="is_required_yes<?php echo $radio_id;?>" class="gdcb-enable"><span><?php _e('Yes', 'geodirectory'); ?></span></label>

                        <input type="radio" id="is_required_no<?php echo $radio_id;?>" name="is_required" class="gdri-disabled" value="0"
                            <?php if ($value == '0' || !$value) {
                                echo 'checked';
                            } ?>/>
                        <label onclick="show_hide_radio(this,'hide','cf-is-required-msg');" for="is_required_no<?php echo $radio_id;?>" class="gdcb-disable"><span><?php _e('No', 'geodirectory'); ?></span></label>

                    </div>

                </li>

                <?php
            }

            // required_msg
            if(has_filter("geodir_cfa_required_msg_{$field_type}")){

                echo apply_filters("geodir_cfa_required_msg_{$field_type}",'',$result_str,$cf,$field_info);

            }else{
                $value = '';
                if (isset($field_info->required_msg)) {
                    $value = esc_attr($field_info->required_msg);
                }elseif(isset($cf['defaults']['required_msg']) && $cf['defaults']['required_msg']){
                    $value = $cf['defaults']['required_msg'];
                }
                ?>
                <li class="cf-is-required-msg" <?php if ((isset($field_info->is_required) && $field_info->is_required == '0') || !isset($field_info->is_required)) {echo "style='display:none;'";}?>>
                    <label for="required_msg" class="gd-cf-tooltip-wrap">
                        <i class="fa fa-info-circle" aria-hidden="true"></i> <?php _e('Required message:', 'geodirectory'); ?>
                        <div class="gdcf-tooltip">
                            <?php _e('Enter text for the error message if the field is required and has not fulfilled the requirements.', 'geodirectory'); ?>
                        </div>
                    </label>
                    <div class="gd-cf-input-wrap">
                        <input type="text" name="required_msg" id="required_msg"
                               value="<?php echo esc_attr($value); ?>"/>
                    </div>
                </li>
                <?php
            }


            // required_msg
            if(has_filter("geodir_cfa_validation_pattern_{$field_type}")){

                echo apply_filters("geodir_cfa_validation_pattern_{$field_type}",'',$result_str,$cf,$field_info);

            }


            // extra_fields
            if(has_filter("geodir_cfa_extra_fields_{$field_type}")){

                echo apply_filters("geodir_cfa_extra_fields_{$field_type}",'',$result_str,$cf,$field_info);

            }


            // field_icon
            if(has_filter("geodir_cfa_field_icon_{$field_type}")){

                echo apply_filters("geodir_cfa_field_icon_{$field_type}",'',$result_str,$cf,$field_info);

            }else{
                $value = '';
                if (isset($field_info->field_icon)) {
                    $value = esc_attr($field_info->field_icon);
                }elseif(isset($cf['defaults']['field_icon']) && $cf['defaults']['field_icon']){
                    $value = $cf['defaults']['field_icon'];
                }
                ?>
                <li>
                    <h3><?php echo __('Custom css', 'geodirectory'); ?></h3>


                    <label for="field_icon" class="gd-cf-tooltip-wrap">
                        <i class="fa fa-info-circle" aria-hidden="true"></i> <?php _e('Upload icon :', 'geodirectory'); ?>
                        <div class="gdcf-tooltip">
                            <?php _e('Upload icon using media and enter its url path, or enter <a href="http://fortawesome.github.io/Font-Awesome/icons/" target="_blank" >font awesome </a>class eg:"fa fa-home"', 'geodirectory');?>
                        </div>
                    </label>
                    <div class="gd-cf-input-wrap">
                        <input type="text" name="field_icon" id="field_icon"
                               value="<?php echo $value;?>"/>
                    </div>

                </li>
                <?php
            }


            // css_class
            if(has_filter("geodir_cfa_css_class_{$field_type}")){

                echo apply_filters("geodir_cfa_css_class_{$field_type}",'',$result_str,$cf,$field_info);

            }else{
                $value = '';
                if (isset($field_info->css_class)) {
                    $value = esc_attr($field_info->css_class);
                }elseif(isset($cf['defaults']['css_class']) && $cf['defaults']['css_class']){
                    $value = $cf['defaults']['css_class'];
                }
                ?>
                <li>

                    <label for="css_class" class="gd-cf-tooltip-wrap">
                        <i class="fa fa-info-circle" aria-hidden="true"></i> <?php _e('Css class :', 'geodirectory'); ?>
                        <div class="gdcf-tooltip">
                            <?php _e('Enter custom css class for field custom style.', 'geodirectory');?>
                            <?php if($field_type=='multiselect'){_e('(Enter class `gd-comma-list` to show list as comma separated)', 'geodirectory');}?>
                        </div>
                    </label>
                    <div class="gd-cf-input-wrap">
                        <input type="text" name="css_class" id="css_class"
                               value="<?php if (isset($field_info->css_class)) {
                                   echo esc_attr($field_info->css_class);
                               }?>"/>
                    </div>
                </li>
                <?php
            }


            // cat_sort
            if(has_filter("geodir_cfa_cat_sort_{$field_type}")){

                echo apply_filters("geodir_cfa_cat_sort_{$field_type}",'',$result_str,$cf,$field_info);

            }else{
                $value = '';
                $hide_cat_sort  ='';
                if (isset($field_info->cat_sort)) {
                    $value = esc_attr($field_info->cat_sort);
                }elseif(isset($cf['defaults']['cat_sort']) && $cf['defaults']['cat_sort']){
                    $value = $cf['defaults']['cat_sort'];
                    $hide_cat_sort = ($value===false) ? "style='display:none;'" : '';
                }

                $hide_cat_sort = (isset($cf['defaults']['cat_sort']) && $cf['defaults']['cat_sort']===false) ? "style='display:none;'" : '';
                ?>
                <li <?php echo $hide_cat_sort ;?>>
                    <h3><?php
                        /**
                         * Filter the section title.
                         *
                         * Filter the section title in custom field form in admin
                         * custom fields settings.
                         *
                         * @since 1.0.0
                         *
                         * @param string $title Title of the section.
                         * @param string $field_type Current field type.
                         */
                        echo apply_filters('geodir_advance_custom_fields_heading', __('Posts sort options', 'geodirectory'), $field_type);

                        ?></h3>
                    <label for="cat_sort" class="gd-cf-tooltip-wrap">
                        <i class="fa fa-info-circle" aria-hidden="true"></i> <?php _e('Include this field in sorting options :', 'geodirectory'); ?>
                        <div class="gdcf-tooltip">
                            <?php _e('Lets you use this filed as a sorting option, set from sorting options above.', 'geodirectory');?>
                        </div>
                    </label>

                    <div class="gd-cf-input-wrap gd-switch">

                        <input type="radio" id="cat_sort_yes<?php echo $radio_id;?>" name="cat_sort" class="gdri-enabled"  value="1"
                            <?php if ($value == '1') {
                                echo 'checked';
                            } ?>/>
                        <label for="cat_sort_yes<?php echo $radio_id;?>" class="gdcb-enable"><span><?php _e('Yes', 'geodirectory'); ?></span></label>

                        <input type="radio" id="cat_sort_no<?php echo $radio_id;?>" name="cat_sort" class="gdri-disabled" value="0"
                            <?php if (!$value) {
                                echo 'checked';
                            } ?>/>
                        <label for="cat_sort_no<?php echo $radio_id;?>" class="gdcb-disable"><span><?php _e('No', 'geodirectory'); ?></span></label>

                    </div>
                </li>
                <?php
            }



            switch ($field_type):
                case 'html':
                case 'file':
                case 'url':
                case 'fieldset':
                    break;
                default:

                    /**
                     * Called at the end of the advanced custom fields settings page loop.
                     *
                     * Can be used to add or deal with different settings types.
                     *
                     * @since 1.0.0
                     * @since 1.6.6 $cf param added.
                     * @param object $field_info The current fields info.
                     * @param array $cf The custom field settings
                     */
                    do_action('geodir_advance_custom_fields', $field_info,$cf);?>


                <?php endswitch; ?>


            <li>

                <label for="save" class="gd-cf-tooltip-wrap">
                    <h3></h3>
                </label>
                <div class="gd-cf-input-wrap">
                    <input type="button" class="button button-primary" name="save" id="save" value="<?php echo esc_attr(__('Save','geodirectory'));?>"
                           onclick="save_field('<?php echo esc_attr($result_str); ?>')"/>
                    <?php if (!$default): ?>
                        <a href="javascript:void(0)"><input type="button" name="delete" value="<?php echo esc_attr(__('Delete','geodirectory'));?>"
                                                            onclick="delete_field('<?php echo esc_attr($result_str); ?>', '<?php echo $nonce; ?>')"
                                                            class="button"/></a>
                    <?php endif; ?>
                </div>
            </li>
        </ul>
    </div>
    </form>
</li>