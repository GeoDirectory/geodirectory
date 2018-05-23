<?php
/**
 * Template for the send enquiry popup forms
 *
 * You can make most changes via hooks or see the link below for info on how to replace the template in your theme.
 *
 * @link http://docs.wpgeodirectory.com/customizing-geodirectory-templates/
 * @since 1.0.0
 * @package GeoDirectory
 */

$post_id = $_REQUEST['post_id'];
$post_info = get_post($post_id);

/**
 * Called at the start of the popup-forms.php template, can be used to add extra forms to the popup form template.
 *
 * @since 1.4.2
 * @param int $post_id The post id requested by the popup.
 * @param object $post_info The post object requested by the popup.
 */
do_action('geodir_popup_forms_template_start',$post_id,$post_info);

if ($_REQUEST['popuptype'] == 'b_send_inquiry') { ?>

    <div id="basic-modal-content2" class="clearfix">
        <form method="post" name="agt_mail_agent" id="agt_mail_agent"
              action="<?php echo get_permalink($post_info->ID); ?>">

            <?php
            wp_nonce_field( 'send_inquiry_'.$post_info->ID );
            ?>
            <input type="hidden" name="sendact" value="send_inqury"/>
            <input type="hidden" name="pid" value="<?php echo $post_info->ID;?>"/>

            <h3><?php
                /**
                 * Filter the title for the send inquiry popup form.
                 *
                 * @since 1.0.0
                 * @param string $title The title for the form, defaults to global `SEND_INQUIRY` text.
                 */
                echo apply_filters('geodir_send_inquiry_page_title', SEND_INQUIRY);?> </h3>

            <p id="inquiry_send_success" class="sucess_msg" style="display:none;"></p>
            <?php
            /**
             * Called before each field in the send inquiry template.
             *
             * @since 1.0.0
             * @param string $field The field name the actions is called before.
             */
            do_action('geodir_before_inquiry_form_field', 'inq_name');?>
            <div class="row  clearfix">
                    <input required field_type="text" name="inq_name" type="text" value="" placeholder="<?php _e('Your Name', 'geodirectory');?>"/>
            </div>
            <?php
            /**
             * Called after each field in the send inquiry template.
             *
             * @since 1.0.0
             * @param string $field The field name the actions is called after.
             */
            do_action('geodir_after_inquiry_form_field', 'inq_name');?>
            <?php
            /** This action is documented in geodirectory-templates/popup-forms.php */
            do_action('geodir_before_inquiry_form_field', 'inq_email');?>
            <div class="row  clearfix">
                    <input required field_type="email" name="inq_email" type="email" value="" placeholder="<?php _e('Email', 'geodirectory');?>"/>
            </div>
            <?php
            /** This action is documented in geodirectory-templates/popup-forms.php */
            do_action('geodir_after_inquiry_form_field', 'inq_email');?>
            <?php
            /** This action is documented in geodirectory-templates/popup-forms.php */
            do_action('geodir_before_inquiry_form_field', 'inq_phone');?>
            <div class="row  clearfix">
                    <input name="inq_phone" id="agt_mail_phone" type="text" value="" placeholder="<?php _e('Phone number', 'geodirectory');?>"/>
            </div>
            <?php
            /** This action is documented in geodirectory-templates/popup-forms.php */
            do_action('geodir_after_inquiry_form_field', 'inq_phone');?>
            <?php
            /** This action is documented in geodirectory-templates/popup-forms.php */
            do_action('geodir_before_inquiry_form_field', 'inq_msg');?>
            <div class="row  clearfix">
                    <textarea required field_type="textarea" name="inq_msg" cols=""
                              rows="" placeholder="<?php echo SEND_INQUIRY_SAMPLE_CONTENT;?>"></textarea>
            </div>
            <?php
            /** This action is documented in geodirectory-templates/popup-forms.php */
            do_action('geodir_after_inquiry_form_field', 'inq_msg');?>
            <input name="Send" type="submit" value="<?php _e('Send', 'geodirectory');?>"
                   class="button clearfix"/>
        </form>
    </div> <?php
}
?>