<div class="container">
  <div class="clearfix">  <?php
  
 
  
    ?>

    <div class="relative center">
    <h1 class="fw300 mt2 mb2"><img src="/img/caramnesis.svg" width="50%" alt="caramnesis" /></h1>
    <p><i><?php echo _("HERO_BLURB"); ?></i></p>
    </div>

    <?php $this->renderFeedbackMessages(); ?>

              <!-- login box on left side -->
    <div class="sm-col md-col-6 lg-col-6">
    <div class="p4 bg-kclite m1 rounded">
       <h2><?php echo _("LOGIN_HERE"); ?></h2>
  <form action="<?php echo Config::get('URL'); ?>login/login" method="post">
								<div class="lblgrp"><label for="user_name"><?= _("LOGIN_USERNAME_OR_EMAIL"); ?></label>
                    <input type="text" name="user_name" id="user_name" class="block col-12 field mt1 " required />
								</div>
								<div class="lblgrp"><label for="user_password"><?= _("LOGIN_PASSWORD"); ?></label>
                    <input type="password" name="user_password" id="user_password" class="block col-12 field mt1 " required />
								</div>
                    <label for="set_remember_me_cookie" class="block col-12 mt1">
                        <input type="checkbox" name="set_remember_me_cookie" />
                        <?php echo _("LOGIN_REMEMBER_ME"); ?>
                    </label>
                    <?php if (!empty($this->redirect)) { ?>
                        <input type="hidden" name="redirect" value="<?php echo $this->redirect ?>" />
                    <?php } ?>
					<input type="hidden" name="csrf_token" value="<?= Csrf::makeToken(); ?>" />
                    <input type="submit" class="btn btn-primary mb1 mt1 black bg-kcms" value="<?php echo _("LOGIN_LOG_IN"); ?>"/>
                </form>
                <div class="link-forgot-my-password">
                    <a href="<?php echo Config::get('URL'); ?>login/requestPasswordReset"><?php echo _("LOGIN_FORGOT_MY_PASSWORD"); ?></a>
                </div>
            </div></div>

            <!-- register box on right side -->
            <div class="sm-col md-col-6 lg-col-6">
            <div class="m2">
            <p><strong>Caramnesis.com</strong><?php echo _("HERO_WRITEUP"); ?></p>
                <h2><?php echo _("LOGIN_NO_ACCOUNT_YET"); ?></h2>
                <a class="btn btn-primary mb1 black bg-kcms" href="<?php echo Config::get('URL'); ?>login/register"><?php echo _("LOGIN_REGISTER_NEW_USR"); ?></a>
                <p><strong>Caramnesis.com</strong><?php echo _("REGISTRATION_WRITEUP"); ?></p>
            </div></div>

        
    
  </div>
</div>                            

