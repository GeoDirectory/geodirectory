<?php
if(isset($_GET['redirect_to']) && $_GET['redirect_to'] != '')
{
	$redirect_to = $_GET['redirect_to'];
}
else
{
	//echo $_SERVER['HTTP_HOST'] ;
	$redirect_to = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
	if(strpos($redirect_to,$_SERVER['HTTP_HOST'] )===false)
	{	
		$redirect_to = home_url();
	}
	
}

?>

    <div class="login_content">
        <?php echo stripslashes(get_option('ptthemes_logoin_page_content'));?>
    </div>
    
    <div class="login_form_box">
    	
        <h4>
			<?php 
            if(isset($_REQUEST['page']) && $_REQUEST['page']=='login' && isset($_REQUEST['page1']) && $_REQUEST['page1']=='sign_up')
            { 	echo apply_filters('geodir_registration_page_title',REGISTRATION_NOW_TEXT);}
            else
            {	echo apply_filters('geodir_login_page_title',SIGN_IN_PAGE_TITLE);}
            ?>
        </h4>
        <?php
		if ( isset($_REQUEST['emsg']) && $_REQUEST['emsg']=='fw'){
			echo "<p class=\"error_msg\"> ".INVALID_USER_FPW_MSG." </p>";
		}elseif ( isset($_REQUEST['logemsg']) && $_REQUEST['logemsg']==1){
			echo "<p class=\"error_msg\"> ".INVALID_USER_PW_MSG." </p>";
		}
		
		if(isset($_REQUEST['checkemail']) && $_REQUEST['checkemail']=='confirm')
			echo '<p class="sucess_msg">'.PW_SEND_CONFIRM_MSG.'</p>';
		
		?>
        <form name="cus_loginform" id="cus_loginform" action="<?php echo geodir_curPageURL(); ?>" method="post" >
        
            <div class="form_row clearfix">
                <input placeholder='<?php echo USERNAME_TEXT; ?>' type="text" name="log" id="user_login" value="<?php global $user_login; if(!isset($user_login)){$user_login='';} echo esc_attr($user_login); ?>" size="20" class="textfield" />
                <span class="user_loginInfo"></span>
            </div> 
        
            <div class="form_row clearfix">
                <input placeholder='<?php echo PASSWORD_TEXT; ?>' type="password" name="pwd" id="user_pass" class="textfield input-text" value="" size="20"  />
                <span class="user_passInfo"></span>
            </div>
            
            <?php do_action('login_form'); ?>
            <p class="rember">
                <input name="rememberme" type="checkbox" id="rememberme" value="forever" class="fl" />
                <?php echo REMEMBER_ON_COMPUTER_TEXT; ?>
            </p>
            
    
            <input class="geodir_button" type="submit" value="<?php echo SIGN_IN_BUTTON;?>"  name="submit" />
            <input type="hidden" name="redirect_to" value="<?php echo $redirect_to; ?>" />
            <input type="hidden" name="testcookie" value="1" />
            <a href="javascript:void(0);showhide_forgetpw();"><?php echo FORGOT_PW_TEXT;?></a> 
        </form>
        
        <div id="lostpassword_form" style="display:none;">
            <h4><?php echo FORGOT_PW_TEXT;?></h4> 
            <form name="lostpasswordform" id="lostpasswordform" action="<?php echo geodir_curPageURL(); ?>" method="post">
                <input type="hidden" name="action" value="lostpassword" />	 
                <div class="form_row clearfix"> 
                    <input placeholder='<?php echo USERNAME_EMAIL_TEXT; ?>' type="text" name="user_login" value="<?php echo esc_attr($user_login); ?>" size="20" class="user_login1 textfield" />
                    <?php do_action('lostpassword_form'); ?>
                </div>
                <input type="submit" name="get_new_password" value="<?php echo GET_NEW_PW_TEXT;?>" class="geodir_button" />
            </form>
        </div>
    </div>
<script  type="text/javascript" >
function showhide_forgetpw()
{
	if(document.getElementById('lostpassword_form').style.display=='none')
	{
		document.getElementById('lostpassword_form').style.display = ''
	}else
	{
		document.getElementById('lostpassword_form').style.display = 'none';
	}	
}
</script>