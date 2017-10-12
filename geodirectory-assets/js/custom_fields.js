jQuery(document).ready(function () {
    jQuery("#gt-form-builder-tab ul li a").click(function () {
        if(!jQuery(this).attr('id')){return;}
        //var type = jQuery(this).attr('id').replace('gd-', '');
        var type = jQuery(this).data("field-type");
        var type_key = jQuery(this).data("field-type-key");
        var custom_type = jQuery(this).data("field-custom-type");
        var post_type = jQuery(this).closest('#gt-form-builder-tab').find('#new_post_type').val();
        var id = 'new' + jQuery(".field_row_main ul.core li:last").index();
        var manage_field_type = jQuery(this).closest('#geodir-available-fields').find(".manage_field_type").val();
        if (manage_field_type == 'custom_fields' || manage_field_type == 'sorting_options') {
            jQuery.get(geodir_admin_ajax.url + '?action=geodir_ajax_action&create_field=true', {
                field_type: type,
                field_type_key: type_key,
                listing_type: post_type,
                field_id: id,
                field_ins_upd: 'new',
                manage_field_type: manage_field_type,
                custom_type: custom_type
            }, function (data) {
                jQuery('.field_row_main ul.core').append(data);
                jQuery('#licontainer_' + id).find('#sort_order').val(parseInt(jQuery('#licontainer_' + id).index()) + 1);
                // reset the chosen selects
                jQuery("select.chosen_select").chosen();

                show_hide('field_frm'+id);
                jQuery('html, body').animate({
                    scrollTop: jQuery("#licontainer_"+id).offset().top
                }, 1000);

            });
            if (manage_field_type == 'sorting_options') {
                jQuery(this).closest('li').hide();
            }
        }
    });
    jQuery(".field_row_main ul.core").sortable({
        opacity: 0.8,
        cursor: 'move',
        placeholder: "ui-state-highlight",
        cancel: "input,label,select",
        delay: 500,
        update: function () {
            var manage_field_type = jQuery(this).closest('#geodir-selected-fields').find(".manage_field_type").val();
            var order = jQuery(this).sortable("serialize") + '&update=update&manage_field_type=' + manage_field_type;
            if (manage_field_type == 'custom_fields' || manage_field_type == 'sorting_options') {
                jQuery.get(geodir_admin_ajax.url + '?action=geodir_ajax_action&create_field=true', order, function (theResponse) {
                    //alert('Fields have been ordered.');
                });
            }
        }
    });
});

function gd_data_type_changed(obj, cont) {
    if (obj && cont) {
        jQuery('#licontainer_' + cont).find('.decimal-point-wrapper').hide();
        if (jQuery(obj).val() == 'FLOAT') {
            jQuery('#licontainer_' + cont).find('.decimal-point-wrapper').show();
        }

        if (jQuery(obj).val() == 'FLOAT' || jQuery(obj).val() == 'INT') {
            jQuery('#licontainer_' + cont).find('.gdcf-price-extra-set').show();

            if(jQuery('#licontainer_' + cont).find(".gdcf-price-extra-set input[name='extra[is_price]']:checked").val()=='1'){
                jQuery('#licontainer_' + cont).find('.gdcf-price-extra').show();
            }

        }else{
            jQuery('#licontainer_' + cont).find('.gdcf-price-extra-set').hide();
            jQuery('#licontainer_' + cont).find('.gdcf-price-extra').hide();
        }
    }
}

function save_field(id) {

    if (jQuery('#licontainer_' + id + ' #htmlvar_name').length > 0) {

        var htmlvar_name = jQuery('#licontainer_' + id + ' #htmlvar_name').val();

        if (htmlvar_name == '') {

            alert(geodir_all_js_msg.custom_field_not_blank_var);

            return false;
        }

        if (htmlvar_name != '') {

            var iChars = "!`@#$%^&*()+=-[]\\\';,./{}|\":<>?~ ";

            for (var i = 0; i < htmlvar_name.length; i++) {
                if (iChars.indexOf(htmlvar_name.charAt(i)) != -1) {

                    alert(geodir_all_js_msg.custom_field_not_special_char);


                    return false;
                }
            }
        }
    }

    var fieldrequest = jQuery('#licontainer_' + id).find("select, textarea, input").serialize();
    var request_data = 'create_field=true&field_ins_upd=submit&' + fieldrequest;


    jQuery.ajax({
        'url': geodir_admin_ajax.url + '?action=geodir_ajax_action&manage_field_type=custom_fields',
        'type': 'POST',
        'data': request_data,
        'success': function (result) {

            //alert(result);
            if (jQuery.trim(result) == 'HTML Variable Name should be a unique name') {

                alert(geodir_all_js_msg.custom_field_unique_name);

            }
            else {
                jQuery('#licontainer_' + id).replaceWith(jQuery.trim(result));

                var order = jQuery(".field_row_main ul.core").sortable("serialize") + '&update=update&manage_field_type=custom_fields';

                jQuery.get(geodir_admin_ajax.url + '?action=geodir_ajax_action&create_field=true', order,
                    function (theResponse) {
                        //alert(theResponse);
                    });

                jQuery('.field_frm').hide();
            }

            // reset the chosen selects
            jQuery("select.chosen_select").chosen();


        }
    });


}


function show_hide(id) {
    jQuery('#' + id).toggle();
}

function show_hide_radio(id,sh,cl) {
    if(sh=='hide'){
        jQuery( id ).closest( '.widefat' ).find('.'+cl).hide('fast');
    }else{
        jQuery( id ).closest( '.widefat' ).find('.'+cl).show('fast');
    }

}


function delete_field(id, nonce) {

    var confarmation = confirm(geodir_all_js_msg.custom_field_delete);

    if (confarmation == true) {

        jQuery.get(geodir_admin_ajax.url + '?action=geodir_ajax_action&create_field=true&manage_field_type=custom_fields', {
                field_id: id,
                field_ins_upd: 'delete',
                _wpnonce: nonce
            },
            function (data) {
                jQuery('#licontainer_' + id).remove();

            });

    }

}


/* ================== CUSTOM SORT FIELDS =================== */

function save_sort_field(id) {

    var fieldrequest = jQuery('#licontainer_' + id).find("select, textarea, input").serialize();
    var request_data = 'create_field=true&field_ins_upd=submit&' + fieldrequest;

    jQuery.ajax({
        'url': geodir_admin_ajax.url + '?action=geodir_ajax_action&manage_field_type=sorting_options',
        'type': 'POST',
        'data': request_data,
        'success': function (result) {

            jQuery('#licontainer_' + id).replaceWith(jQuery.trim(result));

            var order = jQuery(".field_row_main ul.core").sortable("serialize") + '&update=update&manage_field_type=sorting_options';

            jQuery.get(geodir_admin_ajax.url + '?action=geodir_ajax_action&create_field=true', order,
                function (theResponse) {

                    jQuery('#licontainer_' + id).find('input[name="is_default"]').each(function () {
                        if (jQuery(this).attr('checked') == 'checked') {
                            jQuery('.field_row_main').find('input[name="is_default"]').not(this).attr('checked', false);
                        }
                    });


                });

            jQuery('.field_frm').hide();


        }
    });


}


function delete_sort_field(id, nonce, obj) {

    var confarmation = confirm(geodir_all_js_msg.custom_field_delete);

    if (confarmation == true) {

        jQuery.get(geodir_admin_ajax.url + '?action=geodir_ajax_action&create_field=true&manage_field_type=sorting_options', {
                field_id: id,
                field_ins_upd: 'delete',
                _wpnonce: nonce
            },
            function (data) {
                jQuery('#licontainer_' + id).remove();

                var field_type = jQuery(obj).closest('li').find('#field_type').val();
                var htmlvar_name = jQuery(obj).closest('li').find('#htmlvar_name').val();

                jQuery('#gd-' + field_type + '-_-' + htmlvar_name).closest('li').show();

            });

    }

}