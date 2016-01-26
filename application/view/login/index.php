<div class="container">
  <div class="clearfix">

    <div class="relative center">
    <h1 class="fw300 mt2 mb2 hero-header has-text-shadow">CARAMNESIS</h1>
    <p><i><?php echo Text::get("HERO_BLURB"); ?></i></p>
    </div>

    <?php $this->renderFeedbackMessages(); ?>

              <!-- login box on left side -->
    <div class="sm-col md-col-6 lg-col-6">
    <div class="p4 bg-kclite m1 rounded">
       <h2><?php echo Text::get("LOGIN_HERE"); ?></h2>
  <form action="<?php echo Config::get('URL'); ?>login/login" method="post">
                    <input type="text" name="user_name" placeholder="<?php echo Text::get("LOGIN_USERNAME_OR_EMAIL"); ?>" class="block col-12 field mt1 " required />
                    <input type="password" name="user_password" placeholder="<?php echo Text::get("LOGIN_PASSWORD"); ?>" class="block col-12 field mt1 " required />
                    <label for="set_remember_me_cookie" class="block col-12 mt1">
                        <input type="checkbox" name="set_remember_me_cookie" />
                        <?php echo Text::get("LOGIN_REMEMBER_ME"); ?>
                    </label>
                    <?php if (!empty($this->redirect)) { ?>
                        <input type="hidden" name="redirect" value="<?php echo $this->redirect ?>" />
                    <?php } ?>
					<input type="hidden" name="csrf_token" value="<?= Csrf::makeToken(); ?>" />
                    <input type="submit" class="btn btn-primary mb1 mt1 black bg-kcms" value="<?php echo Text::get("LOGIN_LOG_IN"); ?>"/>
                </form>
                <div class="link-forgot-my-password">
                    <a href="<?php echo Config::get('URL'); ?>login/requestPasswordReset"><?php echo Text::get("LOGIN_FORGOT_MY_PASSWORD"); ?></a>
                </div>
            </div></div>

            <!-- register box on right side -->
            <div class="sm-col md-col-6 lg-col-6">
            <div class="m2">
            <p><strong>Caramnesis.com</strong><?php echo Text::get("HERO_WRITEUP"); ?></p>
                <h2><?php echo Text::get("LOGIN_NO_ACCOUNT_YET"); ?></h2>
                <a class="btn btn-primary mb1 black bg-kcms" href="<?php echo Config::get('URL'); ?>login/register"><?php echo Text::get("LOGIN_REGISTER_NEW_USR"); ?></a>
                <p><strong>Caramnesis.com</strong><?php echo Text::get("REGISTRATION_WRITEUP"); ?></p>
            </div></div>

        
    
  </div>
</div>                            

