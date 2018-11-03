<?php
/**
 * Custom fields input functions
 *
 * @since 1.6.6
 * @package GeoDirectory
 */



/**
 * Get the html input for the custom field: fieldset
 *
 * @param string $html The html to be filtered.
 * @param array $cf The custom field array details.
 * @since 1.6.6
 *
 * @return string The html to output for the custom field.
 */
function geodir_cfi_fieldset($html,$cf){

    $html_var = $cf['htmlvar_name'];

    // Check if there is a custom field specific filter.
    if(has_filter("geodir_custom_field_input_fieldset_{$html_var}")){
        /**
         * Filter the fieldset html by individual custom field.
         *
         * @param string $html The html to filter.
         * @param array $cf The custom field array.
         * @since 1.6.6
         */
        $html = apply_filters("geodir_custom_field_input_fieldset_{$html_var}",$html,$cf);
    }

    // If no html then we run the standard output.
    if(empty($html)) {

        ob_start(); // Start  buffering;
        ?>
        <h5 id="geodir_fieldset_<?php echo (int) $cf['id']; ?>" class="geodir-fieldset-row"
            gd-fieldset="<?php echo (int) $cf['id']; ?>"><?php echo __( $cf['site_title'], 'geodirectory' ); ?>
            <?php if ( $cf['desc'] != '' ) {
                echo '<small>( ' . __( $cf['desc'], 'geodirectory' ) . ' )</small>';
            } ?></h5>
        <?php
        $html = ob_get_clean();
    }

    return $html;
}
add_filter('geodir_custom_field_input_fieldset','geodir_cfi_fieldset',10,2);



/**
 * Get the html input for the custom field: text
 *
 * @param string $html The html to be filtered.
 * @param array $cf The custom field array details.
 * @since 1.6.6
 *
 * @return string The html to output for the custom field.
 */
function geodir_cfi_text($html,$cf){

    $html_var = $cf['htmlvar_name'];

    // Check if there is a custom field specific filter.
    if(has_filter("geodir_custom_field_input_text_{$html_var}")){
        /**
         * Filter the text html by individual custom field.
         *
         * @param string $html The html to filter.
         * @param array $cf The custom field array.
         * @since 1.6.6
         */
        $html = apply_filters("geodir_custom_field_input_text_{$html_var}",$html,$cf);
    }

    // If no html then we run the standard output.
    if(empty($html)) {

        ob_start(); // Start  buffering;

        $value = geodir_get_cf_value($cf);
        $type = $cf['type'];
        //number and float validation $validation_pattern
        if(isset($cf['data_type']) && $cf['data_type']=='INT'){$type = 'number';}
        elseif(isset($cf['data_type']) && $cf['data_type']=='FLOAT'){$type = 'float';}

        //validation
        if(isset($cf['validation_pattern']) && $cf['validation_pattern']){
            $validation = 'pattern="'.$cf['validation_pattern'].'"';
        }else{$validation='';}

        // validation message
        if(isset($cf['validation_msg']) && $cf['validation_msg']){
            $validation_msg = 'title="'.$cf['validation_msg'].'"';
        }else{$validation_msg='';}
        ?>

        <div id="<?php echo $cf['name'];?>_row"
             class="<?php if ($cf['is_required']) echo 'required_field';?> geodir_form_row clearfix gd-fieldset-details">
            <label>
                <?php $site_title = __($cf['site_title'], 'geodirectory');
                echo (trim($site_title)) ? $site_title : '&nbsp;'; ?>
                <?php if ($cf['is_required']) echo '<span>*</span>';?>
            </label>
            <input field_type="<?php echo $type;?>" name="<?php echo $cf['name'];?>" id="<?php echo $cf['name'];?>"
                   value="<?php echo esc_attr(stripslashes($value));?>" type="<?php echo $type;?>" class="geodir_textfield" <?php echo $validation;echo $validation_msg;?> />
            <span class="geodir_message_note"><?php _e($cf['desc'], 'geodirectory');?></span>
            <?php if ($cf['is_required']) { ?>
                <span class="geodir_message_error"><?php _e($cf['required_msg'], 'geodirectory'); ?></span>
            <?php } ?>
        </div>

        <?php
        $html = ob_get_clean();
    }

    return $html;
}
add_filter('geodir_custom_field_input_text','geodir_cfi_text',10,2);


/**
 * Get the html input for the custom field: email
 *
 * @param string $html The html to be filtered.
 * @param array $cf The custom field array details.
 * @since 1.6.6
 *
 * @return string The html to output for the custom field.
 */
function geodir_cfi_email($html,$cf){

    $html_var = $cf['htmlvar_name'];

    // Check if there is a custom field specific filter.
    if(has_filter("geodir_custom_field_input_email_{$html_var}")){
        /**
         * Filter the email html by individual custom field.
         *
         * @param string $html The html to filter.
         * @param array $cf The custom field array.
         * @since 1.6.6
         */
        $html = apply_filters("geodir_custom_field_input_email_{$html_var}",$html,$cf);
    }

    // If no html then we run the standard output.
    if(empty($html)) {

        ob_start(); // Start  buffering;
        $value = geodir_get_cf_value($cf);

        if ($value == $cf['default']) {
            $value = '';
        }?>

        <div id="<?php echo $cf['name'];?>_row"
             class="<?php if ($cf['is_required']) echo 'required_field';?> geodir_form_row clearfix gd-fieldset-details">
            <label>
                <?php $site_title = __($cf['site_title'], 'geodirectory');
                echo (trim($site_title)) ? $site_title : '&nbsp;'; ?>
                <?php if ($cf['is_required']) echo '<span>*</span>';?>
            </label>
            <input field_type="<?php echo $cf['type'];?>" name="<?php  echo $cf['name'];?>" id="<?php echo $cf['name'];?>"
                   value="<?php echo esc_attr(stripslashes($value));?>" type="email" class="geodir_textfield"/>
            <span class="geodir_message_note"><?php _e($cf['desc'], 'geodirectory');?></span>
            <?php if ($cf['is_required']) { ?>
                <span class="geodir_message_error"><?php _e($cf['required_msg'], 'geodirectory'); ?></span>
            <?php } ?>
        </div>

        <?php
        $html = ob_get_clean();
    }

    return $html;
}
add_filter('geodir_custom_field_input_email','geodir_cfi_email',10,2);



/**
 * Get the html input for the custom field: phone
 *
 * @param string $html The html to be filtered.
 * @param array $cf The custom field array details.
 * @since 1.6.6
 *
 * @return string The html to output for the custom field.
 */
function geodir_cfi_phone($html,$cf){

    $html_var = $cf['htmlvar_name'];

    // Check if there is a custom field specific filter.
    if(has_filter("geodir_custom_field_input_phone_{$html_var}")){
        /**
         * Filter the phone html by individual custom field.
         *
         * @param string $html The html to filter.
         * @param array $cf The custom field array.
         * @since 1.6.6
         */
        $html = apply_filters("geodir_custom_field_input_phone_{$html_var}",$html,$cf);
    }

    // If no html then we run the standard output.
    if(empty($html)) {

        ob_start(); // Start  buffering;
        $value = geodir_get_cf_value($cf);

        if ($value == $cf['default']) {
            $value = '';
        }?>

        <div id="<?php echo $cf['name'];?>_row"
             class="<?php if ($cf['is_required']) echo 'required_field';?> geodir_form_row clearfix gd-fieldset-details">
            <label>
                <?php $site_title = __($cf['site_title'], 'geodirectory');
                echo (trim($site_title)) ? $site_title : '&nbsp;'; ?>
                <?php if ($cf['is_required']) echo '<span>*</span>';?>
            </label>
            <input field_type="<?php echo $cf['type'];?>" name="<?php  echo $cf['name'];?>" id="<?php echo $cf['name'];?>"
                   value="<?php echo esc_attr(stripslashes($value));?>" type="tel" class="geodir_textfield"/>
            <span class="geodir_message_note"><?php _e($cf['desc'], 'geodirectory');?></span>
            <?php if ($cf['is_required']) { ?>
                <span class="geodir_message_error"><?php _e($cf['required_msg'], 'geodirectory'); ?></span>
            <?php } ?>
        </div>

        <?php
        $html = ob_get_clean();
    }

    return $html;
}
add_filter('geodir_custom_field_input_phone','geodir_cfi_phone',10,2);



/**
 * Get the html input for the custom field: url
 *
 * @param string $html The html to be filtered.
 * @param array $cf The custom field array details.
 * @since 1.6.6
 *
 * @return string The html to output for the custom field.
 */
function geodir_cfi_url($html,$cf){

    $html_var = $cf['htmlvar_name'];

    // Check if there is a custom field specific filter.
    if(has_filter("geodir_custom_field_input_url_{$html_var}")){
        /**
         * Filter the url html by individual custom field.
         *
         * @param string $html The html to filter.
         * @param array $cf The custom field array.
         * @since 1.6.6
         */
        $html = apply_filters("geodir_custom_field_input_url_{$html_var}",$html,$cf);
    }

    // If no html then we run the standard output.
    if(empty($html)) {

        ob_start(); // Start  buffering;
        $value = geodir_get_cf_value($cf);

        if ($value == $cf['default']) {
            $value = '';
        }?>

        <div id="<?php echo $cf['name'];?>_row"
             class="<?php if ($cf['is_required']) echo 'required_field';?> geodir_form_row clearfix gd-fieldset-details">
            <label>
                <?php $site_title = __($cf['site_title'], 'geodirectory');
                echo (trim($site_title)) ? $site_title : '&nbsp;'; ?>
                <?php if ($cf['is_required']) echo '<span>*</span>';?>
            </label>
            <input field_type="<?php echo $cf['type'];?>" name="<?php echo $cf['name'];?>" id="<?php echo $cf['name'];?>"
                   value="<?php echo esc_attr(stripslashes($value));?>" type="url" class="geodir_textfield"
                   oninvalid="setCustomValidity('<?php _e('Please enter a valid URL including http://', 'geodirectory'); ?>')"
                   onchange="try{setCustomValidity('')}catch(e){}"
            />
            <span class="geodir_message_note"><?php _e($cf['desc'], 'geodirectory');?></span>
            <?php if ($cf['is_required']) { ?>
                <span class="geodir_message_error"><?php _e($cf['required_msg'], 'geodirectory'); ?></span>
            <?php } ?>
        </div>

        <?php
        $html = ob_get_clean();
    }

    return $html;
}
add_filter('geodir_custom_field_input_url','geodir_cfi_url',10,2);


/**
 * Get the html input for the custom field: radio
 *
 * @param string $html The html to be filtered.
 * @param array $cf The custom field array details.
 * @since 1.6.6
 *
 * @return string The html to output for the custom field.
 */
function geodir_cfi_radio($html,$cf){

    $html_var = $cf['htmlvar_name'];

    // Check if there is a custom field specific filter.
    if(has_filter("geodir_custom_field_input_radio_{$html_var}")){
        /**
         * Filter the radio html by individual custom field.
         *
         * @param string $html The html to filter.
         * @param array $cf The custom field array.
         * @since 1.6.6
         */
        $html = apply_filters("geodir_custom_field_input_radio_{$html_var}",$html,$cf);
    }

    // If no html then we run the standard output.
    if(empty($html)) {

        ob_start(); // Start  buffering;
        $value = geodir_get_cf_value($cf);

        ?>
        <div id="<?php echo $cf['name'];?>_row"
             class="<?php if ($cf['is_required']) echo 'required_field';?> geodir_form_row clearfix gd-fieldset-details">
            <label>
                <?php $site_title = __($cf['site_title'], 'geodirectory');
                echo (trim($site_title)) ? $site_title : '&nbsp;'; ?>
                <?php if ($cf['is_required']) echo '<span>*</span>';?>
            </label>
            <?php if ($cf['option_values']) {
                $option_values = geodir_string_values_to_options($cf['option_values'], true);

                if (!empty($option_values)) {
                    foreach ($option_values as $option_value) {
                        if (empty($option_value['optgroup'])) {
                            ?>
                            <span class="gd-radios"><input name="<?php echo $cf['name'];?>" id="<?php echo $cf['name'];?>" <?php checked(stripslashes($value), $option_value['value']);?> value="<?php echo esc_attr($option_value['value']); ?>" class="gd-checkbox" field_type="<?php echo $cf['type'];?>" type="radio" /><?php echo $option_value['label']; ?></span>
                            <?php
                        }
                    }
                }
            }
            ?>
            <span class="geodir_message_note"><?php _e($cf['desc'], 'geodirectory');?></span>
            <?php if ($cf['is_required']) { ?>
                <span class="geodir_message_error"><?php _e($cf['required_msg'], 'geodirectory'); ?></span>
            <?php } ?>
        </div>

        <?php
        $html = ob_get_clean();
    }

    return $html;
}
add_filter('geodir_custom_field_input_radio','geodir_cfi_radio',10,2);


/**
 * Get the html input for the custom field: checkbox
 *
 * @param string $html The html to be filtered.
 * @param array $cf The custom field array details.
 * @since 1.6.6
 *
 * @return string The html to output for the custom field.
 */
function geodir_cfi_checkbox($html,$cf){

    $html_var = $cf['htmlvar_name'];

    // Check if there is a custom field specific filter.
    if(has_filter("geodir_custom_field_input_checkbox_{$html_var}")){
        /**
         * Filter the checkbox html by individual custom field.
         *
         * @param string $html The html to filter.
         * @param array $cf The custom field array.
         * @since 1.6.6
         */
        $html = apply_filters("geodir_custom_field_input_checkbox_{$html_var}",$html,$cf);
    }

    // If no html then we run the standard output.
    if(empty($html)) {

        ob_start(); // Start  buffering;
        $value = geodir_get_cf_value($cf);


        if ($value == '' && $cf['default']) {
            $value = '1';
        }
        ?>

        <div id="<?php echo $cf['name'];?>_row"
             class="<?php if ($cf['is_required']) echo 'required_field';?> geodir_form_row clearfix gd-fieldset-details">
            <label>
                <?php $site_title = __($cf['site_title'], 'geodirectory');
                echo (trim($site_title)) ? $site_title : '&nbsp;'; ?>
                <?php if ($cf['is_required']) echo '<span>*</span>';?>
            </label>
            <?php if ($value != '1') {
                $value = '0';
            }?>
            <input type="hidden" name="<?php echo $cf['name'];?>" id="<?php echo $cf['name'];?>" value="<?php echo esc_attr($value);?>"/>
            <input  <?php if ($value == '1') {
                echo 'checked="checked"';
            }?>  value="1" class="gd-checkbox" field_type="<?php echo $cf['type'];?>" type="checkbox"
                 onchange="if(this.checked){jQuery('#<?php echo $cf['name'];?>').val('1');} else{ jQuery('#<?php echo $cf['name'];?>').val('0');}"/>
            <span class="geodir_message_note"><?php _e($cf['desc'], 'geodirectory');?></span>
            <?php if ($cf['is_required']) { ?>
                <span class="geodir_message_error"><?php _e($cf['required_msg'], 'geodirectory'); ?></span>
            <?php } ?>
        </div>

        <?php
        $html = ob_get_clean();
    }

    return $html;
}
add_filter('geodir_custom_field_input_checkbox','geodir_cfi_checkbox',10,2);


/**
 * Get the html input for the custom field: textarea
 *
 * @param string $html The html to be filtered.
 * @param array $cf The custom field array details.
 * @since 1.6.6
 *
 * @return string The html to output for the custom field.
 */
function geodir_cfi_textarea($html,$cf){

    $html_var = $cf['htmlvar_name'];

    // Check if there is a custom field specific filter.
    if(has_filter("geodir_custom_field_input_textarea_{$html_var}")){
        /**
         * Filter the textarea html by individual custom field.
         *
         * @param string $html The html to filter.
         * @param array $cf The custom field array.
         * @since 1.6.6
         */
        $html = apply_filters("geodir_custom_field_input_textarea_{$html_var}",$html,$cf);
    }

    // If no html then we run the standard output.
    if(empty($html)) {

        ob_start(); // Start  buffering;
        $value = geodir_get_cf_value($cf);

        $extra_fields = unserialize($cf['extra_fields']);
        ?>

        <div id="<?php echo $cf['name'];?>_row"
             class="<?php if ($cf['is_required']) echo 'required_field';?> geodir_form_row clearfix gd-fieldset-details">
            <label>
                <?php $site_title = __($cf['site_title'], 'geodirectory');
                echo (trim($site_title)) ? $site_title : '&nbsp;'; ?>
                <?php if ($cf['is_required']) echo '<span>*</span>';?>
            </label><?php


            if (is_array($extra_fields) && in_array('1', $extra_fields)) {

                $editor_settings = array('media_buttons' => false, 'textarea_rows' => 10);?>

            <div class="editor" field_id="<?php echo $cf['name'];?>" field_type="editor">
                <?php wp_editor(stripslashes($value), $cf['name'], $editor_settings); ?>
                </div><?php

            } else {

                ?><textarea field_type="<?php echo $cf['type'];?>" class="geodir_textarea" name="<?php echo $cf['name'];?>"
                            id="<?php echo $cf['name'];?>"><?php echo stripslashes($value);?></textarea><?php

            }?>


            <span class="geodir_message_note"><?php _e($cf['desc'], 'geodirectory');?></span>
            <?php if ($cf['is_required']) { ?>
                <span class="geodir_message_error"><?php _e($cf['required_msg'], 'geodirectory'); ?></span>
            <?php } ?>
        </div>

        <?php
        $html = ob_get_clean();
    }

    return $html;
}
add_filter('geodir_custom_field_input_textarea','geodir_cfi_textarea',10,2);


/**
 * Get the html input for the custom field: select
 *
 * @param string $html The html to be filtered.
 * @param array $cf The custom field array details.
 * @since 1.6.6
 *
 * @return string The html to output for the custom field.
 */
function geodir_cfi_select($html,$cf){

    $html_var = $cf['htmlvar_name'];

    // Check if there is a custom field specific filter.
    if(has_filter("geodir_custom_field_input_select_{$html_var}")){
        /**
         * Filter the select html by individual custom field.
         *
         * @param string $html The html to filter.
         * @param array $cf The custom field array.
         * @since 1.6.6
         */
        $html = apply_filters("geodir_custom_field_input_select_{$html_var}",$html,$cf);
    }

    // If no html then we run the standard output.
    if(empty($html)) {

        ob_start(); // Start  buffering;
        $value = geodir_get_cf_value($cf);

        ?>
        <div id="<?php echo $cf['name'];?>_row"
             class="<?php if ($cf['is_required']) echo 'required_field';?> geodir_form_row geodir_custom_fields clearfix gd-fieldset-details">
            <label>
                <?php $site_title = __($cf['site_title'], 'geodirectory');
                echo (trim($site_title)) ? $site_title : '&nbsp;'; ?>
                <?php if ($cf['is_required']) echo '<span>*</span>';?>
            </label>
            <?php
            $option_values_arr = geodir_string_values_to_options($cf['option_values'], true);
            $select_options = '';
            if (!empty($option_values_arr)) {
                foreach ($option_values_arr as $option_row) {
                    if (isset($option_row['optgroup']) && ($option_row['optgroup'] == 'start' || $option_row['optgroup'] == 'end')) {
                        $option_label = isset($option_row['label']) ? $option_row['label'] : '';

                        $select_options .= $option_row['optgroup'] == 'start' ? '<optgroup label="' . esc_attr($option_label) . '">' : '</optgroup>';
                    } else {
                        $option_label = isset($option_row['label']) ? $option_row['label'] : '';
                        $option_value = isset($option_row['value']) ? $option_row['value'] : '';
                        $selected = $option_value == stripslashes($value) ? 'selected="selected"' : '';

                        $select_options .= '<option value="' . esc_attr($option_value) . '" ' . $selected . '>' . $option_label . '</option>';
                    }
                }
            }
            ?>
            <select field_type="<?php echo $cf['type'];?>" name="<?php echo $cf['name'];?>" id="<?php echo $cf['name'];?>"
                    class="geodir_textfield textfield_x chosen_select"
                    data-placeholder="<?php echo __('Choose', 'geodirectory') . ' ' . $site_title . '&hellip;';?>"
                    option-ajaxchosen="false"><?php echo $select_options;?></select>
            <span class="geodir_message_note"><?php _e($cf['desc'], 'geodirectory');?></span>
            <?php if ($cf['is_required']) { ?>
                <span class="geodir_message_error"><?php _e($cf['required_msg'], 'geodirectory'); ?></span>
            <?php } ?>
        </div>

        <?php
        $html = ob_get_clean();
    }

    return $html;
}
add_filter('geodir_custom_field_input_select','geodir_cfi_select',10,2);


/**
 * Get the html input for the custom field: multiselect
 *
 * @param string $html The html to be filtered.
 * @param array $cf The custom field array details.
 * @since 1.6.6
 *
 * @return string The html to output for the custom field.
 */
function geodir_cfi_multiselect($html,$cf){

    $html_var = $cf['htmlvar_name'];

    // Check if there is a custom field specific filter.
    if(has_filter("geodir_custom_field_input_multiselect_{$html_var}")){
        /**
         * Filter the multiselect html by individual custom field.
         *
         * @param string $html The html to filter.
         * @param array $cf The custom field array.
         * @since 1.6.6
         */
        $html = apply_filters("geodir_custom_field_input_multiselect_{$html_var}",$html,$cf);
    }

    // If no html then we run the standard output.
    if(empty($html)) {

        ob_start(); // Start  buffering;
        $value = geodir_get_cf_value($cf);

        $multi_display = 'select';
        if (!empty($cf['extra_fields'])) {
            $multi_display = unserialize($cf['extra_fields']);
        }
        ?>
        <div id="<?php echo $cf['name']; ?>_row"
             class="<?php if ($cf['is_required']) echo 'required_field'; ?> geodir_form_row clearfix gd-fieldset-details">
            <label>
                <?php $site_title = __($cf['site_title'], 'geodirectory');
                echo (trim($site_title)) ? $site_title : '&nbsp;'; ?>
                <?php if ($cf['is_required']) echo '<span>*</span>'; ?>
            </label>
            <input type="hidden" name="gd_field_<?php echo $cf['name']; ?>" value="1"/>
            <?php if ($multi_display == 'select') { ?>
            <div class="geodir_multiselect_list">
                <select field_type="<?php echo $cf['type']; ?>" name="<?php echo $cf['name']; ?>[]" id="<?php echo $cf['name']; ?>"
                        multiple="multiple" class="geodir_textfield textfield_x chosen_select"
                        data-placeholder="<?php _e('Select', 'geodirectory'); ?>"
                        option-ajaxchosen="false">
                    <?php
                    } else {
                        echo '<ul class="gd_multi_choice">';
                    }

                    $option_values_arr = geodir_string_values_to_options($cf['option_values'], true);
                    $select_options = '';
                    if (!empty($option_values_arr)) {
                        foreach ($option_values_arr as $option_row) {
                            if (isset($option_row['optgroup']) && ($option_row['optgroup'] == 'start' || $option_row['optgroup'] == 'end')) {
                                $option_label = isset($option_row['label']) ? $option_row['label'] : '';

                                if ($multi_display == 'select') {
                                    $select_options .= $option_row['optgroup'] == 'start' ? '<optgroup label="' . esc_attr($option_label) . '">' : '</optgroup>';
                                } else {
                                    $select_options .= $option_row['optgroup'] == 'start' ? '<li>' . $option_label . '</li>' : '';
                                }
                            } else {
                                if (!is_array($value) && $value != '') {
                                    $value = trim($value);
                                }
                                
                                $option_label = isset($option_row['label']) ? $option_row['label'] : '';
                                $option_value = isset($option_row['value']) ? $option_row['value'] : '';
                                $selected = $option_value == $value ? 'selected="selected"' : '';
                                $selected = '';
                                $checked = '';

                                if ((!is_array($value) && trim($value) != '') || (is_array($value) && !empty($value))) {
                                    if (!is_array($value)) {
                                        $value_array = explode(',', $value);
                                    } else {
                                        $value_array = $value;
                                    }
                                    
                                    $value_array = stripslashes_deep($value_array);

                                    if (is_array($value_array)) {
                                        $value_array = array_map('trim', $value_array);
                                        
                                        if (in_array($option_value, $value_array)) {
                                            $selected = 'selected="selected"';
                                            $checked = 'checked="checked"';
                                        }
                                    }
                                }

                                if ($multi_display == 'select') {
                                    $select_options .= '<option value="' . esc_attr($option_value) . '" ' . $selected . '>' . $option_label . '</option>';
                                } else {
                                    $select_options .= '<li><input name="' . $cf['name'] . '[]" ' . $checked . ' value="' . esc_attr($option_value) . '" class="gd-' . $multi_display . '" field_type="' . $multi_display . '" type="' . $multi_display . '" />&nbsp;' . $option_label . ' </li>';
                                }
                            }
                        }
                    }
                    echo $select_options;

                    if ($multi_display == 'select') { ?></select></div>
        <?php } else { ?></ul><?php } ?>
            <span class="geodir_message_note"><?php _e($cf['desc'], 'geodirectory'); ?></span>
            <?php if ($cf['is_required']) { ?>
                <span class="geodir_message_error"><?php _e($cf['required_msg'], 'geodirectory'); ?></span>
            <?php } ?>
        </div>
        <?php
        $html = ob_get_clean();
    }

    return $html;
}
add_filter('geodir_custom_field_input_multiselect','geodir_cfi_multiselect',10,2);


/**
 * Get the html input for the custom field: html
 *
 * @param string $html The html to be filtered.
 * @param array $cf The custom field array details.
 * @since 1.6.6
 *
 * @return string The html to output for the custom field.
 */
function geodir_cfi_html($html,$cf){

    $html_var = $cf['htmlvar_name'];

    // Check if there is a custom field specific filter.
    if(has_filter("geodir_custom_field_input_html_{$html_var}")){
        /**
         * Filter the html html by individual custom field.
         *
         * @param string $html The html to filter.
         * @param array $cf The custom field array.
         * @since 1.6.6
         */
        $html = apply_filters("geodir_custom_field_input_html_{$html_var}",$html,$cf);
    }

    // If no html then we run the standard output.
    if(empty($html)) {

        ob_start(); // Start  buffering;
        $value = geodir_get_cf_value($cf);
        ?>

        <div id="<?php echo $cf['name']; ?>_row"
             class="<?php if ($cf['is_required']) echo 'required_field'; ?> geodir_form_row clearfix gd-fieldset-details">
            <label>
                <?php $site_title = __($cf['site_title'], 'geodirectory');
                echo (trim($site_title)) ? $site_title : '&nbsp;'; ?>
                <?php if ($cf['is_required']) echo '<span>*</span>'; ?>
            </label>

            <?php $editor_settings = array('media_buttons' => false, 'textarea_rows' => 10); ?>

            <div class="editor" field_id="<?php echo $cf['name']; ?>" field_type="editor">
                <?php wp_editor(stripslashes($value), $cf['name'], $editor_settings); ?>
            </div>

            <span class="geodir_message_note"><?php _e($cf['desc'], 'geodirectory'); ?></span>
            <?php if ($cf['is_required']) { ?>
                <span class="geodir_message_error"><?php _e($cf['required_msg'], 'geodirectory'); ?></span>
            <?php } ?>

        </div>

        <?php
        $html = ob_get_clean();
    }

    return $html;
}
add_filter('geodir_custom_field_input_html','geodir_cfi_html',10,2);



/**
 * Get the html input for the custom field: datepicker
 *
 * @param string $html The html to be filtered.
 * @param array $cf The custom field array details.
 * @since 1.6.6
 *
 * @return string The html to output for the custom field.
 */
function geodir_cfi_datepicker($html,$cf){

    $html_var = $cf['htmlvar_name'];

    // Check if there is a custom field specific filter.
    if(has_filter("geodir_custom_field_input_datepicker_{$html_var}")){
        /**
         * Filter the datepicker html by individual custom field.
         *
         * @param string $html The html to filter.
         * @param array $cf The custom field array.
         * @since 1.6.6
         */
        $html = apply_filters("geodir_custom_field_input_datepicker_{$html_var}",$html,$cf);
    }

    // If no html then we run the standard output.
    if(empty($html)) {

        ob_start(); // Start  buffering;
        $value = geodir_get_cf_value($cf);

        $extra_fields = unserialize($cf['extra_fields']);
        $name = $cf['name'];

        if ($extra_fields['date_format'] == '')
            $extra_fields['date_format'] = 'yy-mm-dd';

        $date_format = $extra_fields['date_format'];
        $jquery_date_format  = $date_format;


        // check if we need to change the format or not
        $date_format_len = strlen(str_replace(' ', '', $date_format));
        if($date_format_len>5){// if greater then 5 then it's the old style format.

            $search = array('dd','d','DD','mm','m','MM','yy'); //jQuery UI datepicker format
            $replace = array('d','j','l','m','n','F','Y');//PHP date format

            $date_format = str_replace($search, $replace, $date_format);
        }else{
            $jquery_date_format = geodir_date_format_php_to_jqueryui( $jquery_date_format );
        }

        if($value=='0000-00-00'){$value='';}//if date not set, then mark it empty
        if($value && !isset($_REQUEST['backandedit'])) {
            //$time = strtotime($value);
            //$value = date_i18n($date_format, $time);
        }
        $value = geodir_date($value, 'Y-m-d', $date_format);

        ?>
        <script type="text/javascript">

            jQuery(function () {

                jQuery("#<?php echo $cf['name'];?>").datepicker({changeMonth: true, changeYear: true <?php
                    /**
                     * Used to add extra option to datepicker per custom field.
                     *
                     * @since 1.5.7
                     * @param string $name The custom field name.
                     */
                    echo apply_filters("gd_datepicker_extra_{$name}",'');?>});

                jQuery("#<?php echo $name;?>").datepicker("option", "dateFormat", '<?php echo $jquery_date_format;?>');

                <?php if(!empty($value)){?>
                jQuery("#<?php echo $name;?>").datepicker("setDate", '<?php echo $value;?>');
                <?php } ?>

            });

        </script>
        <div id="<?php echo $name;?>_row"
             class="<?php if ($cf['is_required']) echo 'required_field';?> geodir_form_row clearfix gd-fieldset-details">
            <label>

                <?php $site_title = __($cf['site_title'], 'geodirectory');
                echo (trim($site_title)) ? $site_title : '&nbsp;'; ?>
                <?php if ($cf['is_required']) echo '<span>*</span>';?>
            </label>

            <input field_type="<?php echo $cf['type'];?>" name="<?php echo $name;?>" id="<?php echo $name;?>"
                   value="<?php echo esc_attr($value);?>" type="text" class="geodir_textfield"/>

            <span class="geodir_message_note"><?php _e($cf['desc'], 'geodirectory');?></span>
            <?php if ($cf['is_required']) { ?>
                <span class="geodir_message_error"><?php _e($cf['required_msg'], 'geodirectory'); ?></span>
            <?php } ?>
        </div>

        <?php
        $html = ob_get_clean();
    }

    return $html;
}
add_filter('geodir_custom_field_input_datepicker','geodir_cfi_datepicker',10,2);


/**
 * Get the html input for the custom field: time
 *
 * @param string $html The html to be filtered.
 * @param array $cf The custom field array details.
 * @since 1.6.6
 *
 * @return string The html to output for the custom field.
 */
function geodir_cfi_time($html,$cf){

    $html_var = $cf['htmlvar_name'];

    // Check if there is a custom field specific filter.
    if(has_filter("geodir_custom_field_input_time_{$html_var}")){
        /**
         * Filter the time html by individual custom field.
         *
         * @param string $html The html to filter.
         * @param array $cf The custom field array.
         * @since 1.6.6
         */
        $html = apply_filters("geodir_custom_field_input_time_{$html_var}",$html,$cf);
    }

    // If no html then we run the standard output.
    if(empty($html)) {

        ob_start(); // Start  buffering;
        $value = geodir_get_cf_value($cf);

        $name = $cf['name'];

        if ($value != '')
            $value = date('H:i', strtotime($value));
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function () {

                jQuery('#<?php echo $name;?>').timepicker({
                    showPeriod: true,
                    showLeadingZero: true,
                    showPeriod: true,
                });
            });
        </script>
        <div id="<?php echo $name;?>_row"
             class="<?php if ($cf['is_required']) echo 'required_field';?> geodir_form_row clearfix gd-fieldset-details">
            <label>

                <?php $site_title = __($cf['site_title'], 'geodirectory');
                echo (trim($site_title)) ? $site_title : '&nbsp;'; ?>
                <?php if ($cf['is_required']) echo '<span>*</span>';?>
            </label>
            <input readonly="readonly" field_type="<?php echo $cf['type'];?>" name="<?php echo $name;?>"
                   id="<?php echo $name;?>" value="<?php echo esc_attr($value);?>" type="text" class="geodir_textfield"/>

            <span class="geodir_message_note"><?php _e($cf['desc'], 'geodirectory');?></span>
            <?php if ($cf['is_required']) { ?>
                <span class="geodir_message_error"><?php _e($cf['required_msg'], 'geodirectory'); ?></span>
            <?php } ?>
        </div>
        <?php
        $html = ob_get_clean();
    }

    return $html;
}
add_filter('geodir_custom_field_input_time','geodir_cfi_time',10,2);


/**
 * Get the html input for the custom field: address
 *
 * @param string $html The html to be filtered.
 * @param array $cf The custom field array details.
 * @since 1.6.6
 *
 * @return string The html to output for the custom field.
 */
function geodir_cfi_address($html,$cf){

    $html_var = $cf['htmlvar_name'];

    // Check if there is a custom field specific filter.
    if(has_filter("geodir_custom_field_input_address_{$html_var}")){
        /**
         * Filter the address html by individual custom field.
         *
         * @param string $html The html to filter.
         * @param array $cf The custom field array.
         * @since 1.6.6
         */
        $html = apply_filters("geodir_custom_field_input_address_{$html_var}",$html,$cf);
    }

    // If no html then we run the standard output.
    if(empty($html)) {

        global $gd_session;
        ob_start(); // Start  buffering;
        $value = geodir_get_cf_value($cf);
        $name = $cf['name'];
        $type = $cf['type'];
        $admin_desc = $cf['desc'];
        $is_required = $cf['is_required'];
        $required_msg = $cf['required_msg'];
        $site_title = $cf['site_title'];
        $is_admin = $cf['is_admin'];
        $extra_fields = stripslashes_deep(unserialize($cf['extra_fields']));
        $prefix = $name . '_';

        ($site_title != '') ? $address_title = $site_title : $address_title = geodir_ucwords($prefix . ' address');
        ($extra_fields['zip_lable'] != '') ? $zip_title = $extra_fields['zip_lable'] : $zip_title = geodir_ucwords($prefix . ' zip/post code ');
        ($extra_fields['map_lable'] != '') ? $map_title = $extra_fields['map_lable'] : $map_title = geodir_ucwords('set address on map');
        ($extra_fields['mapview_lable'] != '') ? $mapview_title = $extra_fields['mapview_lable'] : $mapview_title = geodir_ucwords($prefix . ' mapview');

        $address = '';
        $zip = '';
        $mapview = '';
        $mapzoom = '';
        $lat = '';
        $lng = '';

        if (isset($_REQUEST['backandedit']) && $_REQUEST['backandedit'] && $gd_ses_listing = $gd_session->get('listing')) {
            $post = $gd_ses_listing;
            $address = $post[$prefix . 'address'];
            $zip = isset($post[$prefix . 'zip']) ? $post[$prefix . 'zip'] : '';
            $lat = isset($post[$prefix . 'latitude']) ? $post[$prefix . 'latitude'] : '';
            $lng = isset($post[$prefix . 'longitude']) ? $post[$prefix . 'longitude'] : '';
            $mapview = isset($post[$prefix . 'mapview']) ? $post[$prefix . 'mapview'] : '';
            $mapzoom = isset($post[$prefix . 'mapzoom']) ? $post[$prefix . 'mapzoom'] : '';
        } else if (isset($_REQUEST['pid']) && $_REQUEST['pid'] != '' && $post_info = geodir_get_post_info($_REQUEST['pid'])) {
            $post_info = (array)$post_info;

            $address = $post_info[$prefix . 'address'];
            $zip = isset($post_info[$prefix . 'zip']) ? $post_info[$prefix . 'zip'] : '';
            $lat = isset($post_info[$prefix . 'latitude']) ? $post_info[$prefix . 'latitude'] : '';
            $lng = isset($post_info[$prefix . 'longitude']) ? $post_info[$prefix . 'longitude'] : '';
            $mapview = isset($post_info[$prefix . 'mapview']) ? $post_info[$prefix . 'mapview'] : '';
            $mapzoom = isset($post_info[$prefix . 'mapzoom']) ? $post_info[$prefix . 'mapzoom'] : '';
        }

        $location = geodir_get_default_location();
        if (empty($city)) $city = isset($location->city) ? $location->city : '';
        if (empty($region)) $region = isset($location->region) ? $location->region : '';
        if (empty($country)) $country = isset($location->country) ? $location->country : '';

        $lat_lng_blank = false;
        if (empty($lat) && empty($lng)) {
            $lat_lng_blank = true;
        }

        if (empty($lat)) $lat = isset($location->city_latitude) ? $location->city_latitude : '';
        if (empty($lng)) $lng = isset($location->city_longitude) ? $location->city_longitude : '';

        /**
         * Filter the default latitude.
         *
         * @since 1.0.0
         *
         * @param float $lat Default latitude.
         * @param bool $is_admin For admin use only?.
         */
        $lat = apply_filters('geodir_default_latitude', $lat, $is_admin);
        /**
         * Filter the default longitude.
         *
         * @since 1.0.0
         *
         * @param float $lat Default longitude.
         * @param bool $is_admin For admin use only?.
         */
        $lng = apply_filters('geodir_default_longitude', $lng, $is_admin);

        $locate_me = !empty($extra_fields['show_map']) && geodir_map_name() != 'none' ? true : false;
        $locate_me_class = $locate_me ? ' gd-form-control' : '';
        ?>
        <div id="geodir_<?php echo $prefix . 'address';?>_row"
             class="<?php if ($is_required) echo 'required_field';?> geodir_form_row clearfix gd-fieldset-details">
            <label><?php _e($address_title, 'geodirectory'); ?> <?php if ($is_required) echo '<span>*</span>';?></label>
            <?php if ($locate_me) { ?>
            <div class="gd-input-group gd-locate-me">
            <?php } ?>
                <input type="text" field_type="<?php echo $type;?>" name="<?php echo $prefix . 'address';?>" id="<?php echo $prefix . 'address';?>" class="geodir_textfield<?php echo $locate_me_class;?>" value="<?php echo esc_attr(stripslashes($address)); ?>" />
                <?php if ($locate_me) { ?>
                <span onclick="gdGeoLocateMe(this, 'add-listing');" class="gd-locate-me-btn gd-input-group-addon" title="<?php esc_attr_e('My location', 'geodirlocation'); ?>"><i class="fas fa-crosshairs fa-fw" aria-hidden="true"></i></span>
            </div>
            <?php } ?>
            <span class="geodir_message_note"><?php _e($admin_desc, 'geodirectory');?></span>
            <?php if ($is_required) { ?>
                <span class="geodir_message_error"><?php _e($required_msg, 'geodirectory'); ?></span>
            <?php } ?>
        </div>
        
        <?php
        /**
         * Called after the address input on the add listings.
         *
         * This is used by the location manage to add further locations info etc.
         *
         * @since 1.0.0
         * @param array $cf The array of setting for the custom field. {@see geodir_custom_field_save()}.
         */
        do_action('geodir_address_extra_listing_fields', $cf);

        if (isset($extra_fields['show_zip']) && $extra_fields['show_zip']) { ?>

            <div id="geodir_<?php echo $prefix . 'zip'; ?>_row"
                 class="<?php /*if($is_required) echo 'required_field';*/ ?> geodir_form_row clearfix gd-fieldset-details">
                <label>
                    <?php _e($zip_title, 'geodirectory'); ?>
                    <?php /*if($is_required) echo '<span>*</span>';*/ ?>
                </label>
                <input type="text" field_type="<?php echo $type; ?>" name="<?php echo $prefix . 'zip'; ?>"
                       id="<?php echo $prefix . 'zip'; ?>" class="geodir_textfield autofill"
                       value="<?php echo esc_attr(stripslashes($zip)); ?>"/>
                <?php /*if($is_required) {?>
					<span class="geodir_message_error"><?php echo _e($required_msg,'geodirectory');?></span>
					<?php }*/ ?>
            </div>
        <?php } ?>

        <?php if (isset($extra_fields['show_map']) && $extra_fields['show_map']) { ?>

            <div id="geodir_<?php echo $prefix . 'map'; ?>_row" class="geodir_form_row clearfix gd-fieldset-details">
                <?php
                /**
                 * Contains add listing page map functions.
                 *
                 * @since 1.0.0
                 */
                include(geodir_plugin_path() . "/geodirectory-functions/map-functions/map_on_add_listing_page.php");
                if ($lat_lng_blank) {
                    $lat = '';
                    $lng = '';
                }
                ?>
                <span class="geodir_message_note"><?php echo stripslashes(GET_MAP_MSG); ?></span>
            </div>
            <?php
            /* show lat lng */
            $style_latlng = ((isset($extra_fields['show_latlng']) && $extra_fields['show_latlng']) || is_admin()) ? '' : 'style="display:none"'; ?>
            <div id="geodir_<?php echo $prefix . 'latitude'; ?>_row"
                 class="<?php if ($is_required) echo 'required_field'; ?> geodir_form_row clearfix gd-fieldset-details" <?php echo $style_latlng; ?>>
                <label>
                    <?php echo PLACE_ADDRESS_LAT; ?>
                    <?php if ($is_required) echo '<span>*</span>'; ?>
                </label>
                <input type="text" field_type="<?php echo $type; ?>" name="<?php echo $prefix . 'latitude'; ?>"
                       id="<?php echo $prefix . 'latitude'; ?>" class="geodir_textfield"
                       value="<?php echo esc_attr(stripslashes($lat)); ?>" size="25"/>
                <span class="geodir_message_note"><?php echo GET_LATITUDE_MSG; ?></span>
                <?php if ($is_required) { ?>
                    <span class="geodir_message_error"><?php _e($required_msg, 'geodirectory'); ?></span>
                <?php } ?>
            </div>

            <div id="geodir_<?php echo $prefix . 'longitude'; ?>_row"
                 class="<?php if ($is_required) echo 'required_field'; ?> geodir_form_row clearfix gd-fieldset-details" <?php echo $style_latlng; ?>>
                <label>
                    <?php echo PLACE_ADDRESS_LNG; ?>
                    <?php if ($is_required) echo '<span>*</span>'; ?>
                </label>
                <input type="text" field_type="<?php echo $type; ?>" name="<?php echo $prefix . 'longitude'; ?>"
                       id="<?php echo $prefix . 'longitude'; ?>" class="geodir_textfield"
                       value="<?php echo esc_attr(stripslashes($lng)); ?>" size="25"/>
                <span class="geodir_message_note"><?php echo GET_LOGNGITUDE_MSG; ?></span>
                <?php if ($is_required) { ?>
                    <span class="geodir_message_error"><?php _e($required_msg, 'geodirectory'); ?></span>
                <?php } ?>
            </div>
        <?php } ?>

        <?php if (isset($extra_fields['show_mapview']) && $extra_fields['show_mapview']) { ?>
            <div id="geodir_<?php echo $prefix . 'mapview'; ?>_row" class="geodir_form_row clearfix gd-fieldset-details">
                <label><?php _e($mapview_title, 'geodirectory'); ?></label>


                    <span class="geodir_user_define"><input field_type="<?php echo $type; ?>" type="radio"
                                                            class="gd-checkbox"
                                                            name="<?php echo $prefix . 'mapview'; ?>"
                                                            id="<?php echo $prefix . 'mapview'; ?>" <?php if ($mapview == 'ROADMAP' || $mapview == '') {
                            echo 'checked="checked"';
                        } ?>  value="ROADMAP" size="25"/> <?php _e('Default Map', 'geodirectory'); ?></span>
                    <span class="geodir_user_define"> <input field_type="<?php echo $type; ?>" type="radio"
                                                             class="gd-checkbox"
                                                             name="<?php echo $prefix . 'mapview'; ?>"
                                                             id="map_view1" <?php if ($mapview == 'SATELLITE') {
                            echo 'checked="checked"';
                        } ?> value="SATELLITE" size="25"/> <?php _e('Satellite Map', 'geodirectory'); ?></span>

                    <span class="geodir_user_define"><input field_type="<?php echo $type; ?>" type="radio"
                                                            class="gd-checkbox"
                                                            name="<?php echo $prefix . 'mapview'; ?>"
                                                            id="map_view2" <?php if ($mapview == 'HYBRID') {
                            echo 'checked="checked"';
                        } ?>  value="HYBRID" size="25"/> <?php _e('Hybrid Map', 'geodirectory'); ?></span>
					<span class="geodir_user_define"><input field_type="<?php echo $type; ?>" type="radio"
                                                            class="gd-checkbox"
                                                            name="<?php echo $prefix . 'mapview'; ?>"
                                                            id="map_view3" <?php if ($mapview == 'TERRAIN') {
                            echo 'checked="checked"';
                        } ?>  value="TERRAIN" size="25"/> <?php _e('Terrain Map', 'geodirectory'); ?></span>


            </div>
        <?php }?>

        <?php if (isset($extra_fields['show_mapzoom']) && $extra_fields['show_mapzoom']) { ?>
            <input type="hidden" value="<?php if (isset($mapzoom)) {
                echo esc_attr($mapzoom);
            } ?>" name="<?php echo $prefix . 'mapzoom'; ?>" id="<?php echo $prefix . 'mapzoom'; ?>"/>
        <?php }

        $html = ob_get_clean();
    }

    return $html;
}
add_filter('geodir_custom_field_input_address','geodir_cfi_address',10,2);



/**
 * Get the html input for the custom field: taxonomy
 *
 * @param string $html The html to be filtered.
 * @param array $cf The custom field array details.
 * @since 1.6.6
 *
 * @return string The html to output for the custom field.
 */
function geodir_cfi_taxonomy($html,$cf){

    $html_var = $cf['htmlvar_name'];

    // Check if there is a custom field specific filter.
    if(has_filter("geodir_custom_field_input_taxonomy_{$html_var}")){
        /**
         * Filter the taxonomy html by individual custom field.
         *
         * @param string $html The html to filter.
         * @param array $cf The custom field array.
         * @since 1.6.6
         */
        $html = apply_filters("geodir_custom_field_input_taxonomy_{$html_var}",$html,$cf);
    }

    // If no html then we run the standard output.
    if(empty($html)) {

        ob_start(); // Start  buffering;
        $value = geodir_get_cf_value($cf);

        $name = $cf['name'];
        $site_title = $cf['site_title'];
        $admin_desc = $cf['desc'];
        $is_required = $cf['is_required'];
        $is_admin = $cf['is_admin'];
        $required_msg = $cf['required_msg'];

        if ($value == $cf['default']) {
            $value = '';
        } ?>
        <div id="<?php echo $name;?>_row"
             class="<?php if ($is_required) echo 'required_field';?> geodir_form_row clearfix gd-fieldset-details">
            <label>
                <?php $site_title = __($site_title, 'geodirectory');
                echo (trim($site_title)) ? $site_title : '&nbsp;'; ?>
                <?php if ($is_required) echo '<span>*</span>';?>
            </label>

            <div id="<?php echo $name;?>" class="geodir_taxonomy_field" style="float:left; width:70%;">
                <?php
                global $wpdb, $post, $cat_display, $post_cat, $package_id, $exclude_cats;

                $exclude_cats = array();

                if ($is_admin == '1') {

                    $post_type = get_post_type();

                    $package_info = array();

                    $package_info = (array)geodir_post_package_info($package_info, $post, $post_type);

                    if (!empty($package_info)) {

                        if (isset($package_info['cat']) && $package_info['cat'] != '') {

                            $exclude_cats = explode(',', $package_info['cat']);

                        }
                    }
                }

                $cat_display = unserialize($cf['extra_fields']);

                if (isset($_REQUEST['backandedit']) && !empty($post_cat[$name]) && is_array($post_cat[$name])) {
                    $post_cat = implode(",", $post_cat[$name]);
                } else {
                    if (isset($_REQUEST['pid']) && $_REQUEST['pid'] != '')
                        $post_cat = geodir_get_post_meta($_REQUEST['pid'], $name, true);
                }


                global $geodir_addon_list;
                if (!empty($geodir_addon_list) && array_key_exists('geodir_payment_manager', $geodir_addon_list) && $geodir_addon_list['geodir_payment_manager'] == 'yes') {

                    $catadd_limit = $wpdb->get_var(
                        $wpdb->prepare(
                            "SELECT cat_limit FROM " . GEODIR_PRICE_TABLE . " WHERE pid = %d",
                            array($package_id)
                        )
                    );


                } else {
                    $catadd_limit = 0;
                }


                if ($cat_display != '' && $cat_display != 'ajax_chained') {

                    $required_limit_msg = '';
                    if ($catadd_limit > 0 && $cat_display != 'select' && $cat_display != 'radio') {

                        $required_limit_msg = __('Only select', 'geodirectory') . ' ' . $catadd_limit . __(' categories for this package.', 'geodirectory');

                    } else {
                        $required_limit_msg = $required_msg;
                    }

                    echo '<input type="hidden" cat_limit="' . $catadd_limit . '" id="cat_limit" value="' . esc_attr($required_limit_msg) . '" name="cat_limit[' . $name . ']"  />';


                    if ($cat_display == 'select' || $cat_display == 'multiselect') {

                        $cat_display == '';
                        $multiple = '';
                        if ($cat_display == 'multiselect')
                            $multiple = 'multiple="multiple"';

                        echo '<select id="' . $name . '" ' . $multiple . ' type="' . $name . '" name="post_category[' . $name . '][]" alt="' . $name . '" field_type="' . $cat_display . '" class="geodir_textfield textfield_x chosen_select" data-placeholder="' . __('Select Category', 'geodirectory') . '">';


                        if ($cat_display == 'select')
                            echo '<option value="">' . __('Select Category', 'geodirectory') . '</option>';

                    }

                    echo geodir_custom_taxonomy_walker($name, $catadd_limit = 0);

                    if ($cat_display == 'select' || $cat_display == 'multiselect')
                        echo '</select>';

                } else {

                    echo geodir_custom_taxonomy_walker2($name, $catadd_limit);

                }

                ?>
            </div>

            <span class="geodir_message_note"><?php _e($admin_desc, 'geodirectory');?></span>
            <?php if ($is_required) { ?>
                <span class="geodir_message_error"><?php _e($required_msg, 'geodirectory'); ?></span>
            <?php } ?>
        </div>

        <?php
        $html = ob_get_clean();
    }

    return $html;
}
add_filter('geodir_custom_field_input_taxonomy','geodir_cfi_taxonomy',10,2);


/**
 * Get the html input for the custom field: file
 *
 * @param string $html The html to be filtered.
 * @param array $cf The custom field array details.
 * @since 1.6.6
 *
 * @return string The html to output for the custom field.
 */
function geodir_cfi_file($html,$cf){

    $html_var = $cf['htmlvar_name'];

    // Check if there is a custom field specific filter.
    if(has_filter("geodir_custom_field_input_file_{$html_var}")){
        /**
         * Filter the file html by individual custom field.
         *
         * @param string $html The html to filter.
         * @param array $cf The custom field array.
         * @since 1.6.6
         */
        $html = apply_filters("geodir_custom_field_input_file_{$html_var}",$html,$cf);
    }

    // If no html then we run the standard output.
    if(empty($html)) {

        ob_start(); // Start  buffering;
        $value = geodir_get_cf_value($cf);

        $name = $cf['name'];
        $site_title = $cf['site_title'];
        $admin_desc = $cf['desc'];
        $is_required = $cf['is_required'];
        $required_msg = $cf['required_msg'];
        $extra_fields = unserialize($cf['extra_fields']);


        // adjust values here
        $file_id = $name; // this will be the name of form field. Image url(s) will be submitted in $_POST using this key. So if $id == �img1� then $_POST[�img1�] will have all the image urls

        if ($value != '') {

            $file_value = trim($value, ","); // this will be initial value of the above form field. Image urls.

        } else
            $file_value = '';

        if (isset($extra_fields['file_multiple']) && $extra_fields['file_multiple'])
            $file_multiple = true; // allow multiple files upload
        else
            $file_multiple = false;

        if (isset($extra_fields['image_limit']) && $extra_fields['image_limit'])
            $file_image_limit = $extra_fields['image_limit'];
        else
            $file_image_limit = 1;

        $file_width = geodir_media_image_large_width(); // If you want to automatically resize all uploaded images then provide width here (in pixels)

        $file_height = geodir_media_image_large_height(); // If you want to automatically resize all uploaded images then provide height here (in pixels)

        if (!empty($file_value)) {
            $curImages = explode(',', $file_value);
            if (!empty($curImages))
                $file_totImg = count($curImages);
        }

        $allowed_file_types = !empty($extra_fields['gd_file_types']) && is_array($extra_fields['gd_file_types']) && !in_array("*", $extra_fields['gd_file_types'] ) ? implode(",", $extra_fields['gd_file_types']) : '';
        $display_file_types = $allowed_file_types != '' ? '.' . implode(", .", $extra_fields['gd_file_types']) : '';

        ?>
        <?php /*?> <h5 class="geodir-form_title"> <?php echo $site_title; ?>
				 <?php if($file_image_limit!=0 && $file_image_limit==1 ){echo '<br /><small>('.__('You can upload').' '.$file_image_limit.' '.__('image with this package').')</small>';} ?>
				 <?php if($file_image_limit!=0 && $file_image_limit>1 ){echo '<br /><small>('.__('You can upload').' '.$file_image_limit.' '.__('images with this package').')</small>';} ?>
				 <?php if($file_image_limit==0){echo '<br /><small>('.__('You can upload unlimited images with this package').')</small>';} ?>
			</h5>   <?php */
        ?>

        <div id="<?php echo $name;?>_row"
             class="<?php if ($is_required) echo 'required_field';?> geodir_form_row clearfix gd-fieldset-details">

            <div id="<?php echo $file_id; ?>dropbox" style="text-align:center;">
                <label
                    style="text-align:left; padding-top:10px;"><?php $site_title = __($site_title, 'geodirectory');
                    echo $site_title; ?><?php if ($is_required) echo '<span>*</span>';?></label>
                <input class="geodir-custom-file-upload" field_type="file" type="hidden"
                       name="<?php echo $file_id; ?>" id="<?php echo $file_id; ?>"
                       value="<?php echo esc_attr($file_value); ?>"/>
                <input type="hidden" name="<?php echo $file_id; ?>image_limit"
                       id="<?php echo $file_id; ?>image_limit" value="<?php echo $file_image_limit; ?>"/>
                <?php if ($allowed_file_types != '') { ?>
                    <input type="hidden" name="<?php echo $file_id; ?>_allowed_types"
                           id="<?php echo $file_id; ?>_allowed_types" value="<?php echo esc_attr($allowed_file_types); ?>" data-exts="<?php echo esc_attr($display_file_types);?>"/>
                <?php } ?>
                <input type="hidden" name="<?php echo $file_id; ?>totImg" id="<?php echo $file_id; ?>totImg"
                       value="<?php if (isset($file_totImg)) {
                           echo esc_attr($file_totImg);
                       } else {
                           echo '0';
                       } ?>"/>

                <div style="float:left; width:55%;">
                    <div
                        class="plupload-upload-uic hide-if-no-js <?php if ($file_multiple): ?>plupload-upload-uic-multiple<?php endif; ?>"
                        id="<?php echo $file_id; ?>plupload-upload-ui" style="float:left; width:30%;">
                        <?php /*?><h4><?php _e('Drop files to upload');?></h4><br/><?php */
                        ?>
                        <input id="<?php echo $file_id; ?>plupload-browse-button" type="button"
                               value="<?php ($file_image_limit > 1 ? esc_attr_e('Select Files', 'geodirectory') : esc_attr_e('Select File', 'geodirectory') ); ?>"
                               class="geodir_button" style="margin-top:10px;"/>
                            <span class="ajaxnonceplu"
                                  id="ajaxnonceplu<?php echo wp_create_nonce($file_id . 'pluploadan'); ?>"></span>
                        <?php if ($file_width && $file_height): ?>
                            <span class="plupload-resize"></span>
                            <span class="plupload-width" id="plupload-width<?php echo $file_width; ?>"></span>
                            <span class="plupload-height" id="plupload-height<?php echo $file_height; ?>"></span>
                        <?php endif; ?>
                        <div class="filelist"></div>
                    </div>
                    <div
                        class="plupload-thumbs <?php if ($file_multiple): ?>plupload-thumbs-multiple<?php endif; ?> "
                        id="<?php echo $file_id; ?>plupload-thumbs"
                        style=" clear:inherit; margin-top:0; margin-left:15px; padding-top:10px; float:left; width:50%;">
                    </div>
                    <?php /*?><span id="upload-msg" ><?php _e('Please drag &amp; drop the images to rearrange the order');?></span><?php */
                    ?>

                    <span id="<?php echo $file_id; ?>upload-error" style="display:none"></span>

                </div>
            </div>
            <span class="geodir_message_note"><?php _e($admin_desc, 'geodirectory');?> <?php echo ( $display_file_types != '' ? __('Allowed file types:', 'geodirectory') . ' ' . $display_file_types : '' );?></span>
            <?php if ($is_required) { ?>
                <span class="geodir_message_error"><?php _e($required_msg, 'geodirectory'); ?></span>
            <?php } ?>
        </div>


        <?php
        $html = ob_get_clean();
    }

    return $html;
}
add_filter('geodir_custom_field_input_file','geodir_cfi_file',10,2);