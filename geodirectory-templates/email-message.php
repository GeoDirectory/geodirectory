<?php
/**
 * Template for the email content
 *
 * You can make most changes via hooks or see the link below for info on how to replace the template in your theme.
 *
 * @link http://docs.wpgeodirectory.com/customizing-geodirectory-templates/
 * @since 1.6.26
 * @package GeoDirectory
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $geodir_email_content;
?>
<!DOCTYPE html>
<html dir="<?php echo is_rtl() ? 'rtl' : 'ltr'?>">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=<?php bloginfo( 'charset' ); ?>" />
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="robots" content="noindex,nofollow">
        <title><?php echo wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES ); ?></title>
    </head>
    <body <?php echo is_rtl() ? 'rightmargin' : 'leftmargin'; ?>="0" marginwidth="0" topmargin="0" marginheight="0" offset="0">

	<?php do_action( 'geodir_email_content_before' ); ?>

	<?php 
	if ( ! empty( $geodir_email_content ) ) {
		echo wpautop( wptexturize( $geodir_email_content ) );
	}
	?>

	<?php do_action( 'geodir_email_content_after' ); ?>

	</body>
</html>