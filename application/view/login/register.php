<div class="container">
  <div class="clearfix">

    <?php $this->renderFeedbackMessages(); ?>

              <!-- login box on left side -->
    <div class="sm-col md-col-12 lg-col-12">
    <div class="p4 bg-kclite m1 rounded">
    <h2><?php echo _("REGISTER_NEW_ACCOUNT"); ?></h2>

        <!-- register form -->
        <form method="post" action="<?php echo Config::get('URL'); ?>login/register_action">
            <input type="text" pattern="[a-zA-Z0-9]{2,64}" name="user_name" placeholder="<?php echo _("REGISTER_USERNAME"); ?>" class="block col-12 field mt1 " required />
            <input type="text" name="user_email" placeholder="<?php echo _("REGISTER_EMAIL"); ?>" class="block col-12 field mt1 " required />
            <input type="password" name="user_password_new" pattern=".{6,}" placeholder="<?php echo _("REGISTER_PASSWORD"); ?>" class="block col-12 field mt1 " required autocomplete="off" />
            <input type="password" name="user_password_repeat" pattern=".{6,}" required placeholder="<?php echo _("REGISTER_REPEAT_PASSWORD"); ?>" autocomplete="off" class="block col-12 field mt1 " />

            <div class="block col-12 mt1 ">
            <img id="captcha" src="<?php echo Config::get('URL'); ?>login/showCaptcha" />
            <a href="#" onclick="document.getElementById('captcha').src = '<?php echo Config::get('URL'); ?>login/showCaptcha?' + Math.random(); return false"><?php echo _("REGISTER_RELOAD_CAPTCHA"); ?></a>
            </div>
            
            <input type="text" name="captcha" placeholder="<?php echo _("REGISTER_ENTER_CAPTCHA"); ?>" class="block col-12 field mt1 " required />

                        <input type="submit" class="btn btn-primary mb1 mt1 black bg-kcms" value="<?php echo _("REGISTER_REGISTER"); ?>" />
        </form>
    

            </div></div>

           
           

        
    
  </div>
</div>                            


