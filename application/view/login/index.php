<div class="container">
  <div class="clearfix">  
    <div class="relative center">
    <h1 class="fw300 mt2 mb2"><img src="/img/caramnesis.svg" width="50%" alt="caramnesis" /></h1>
    <p><i><?php echo _("HERO_BLURB"); ?></i></p>
    </div>

    <?php $this->renderFeedbackMessages(); ?>
 <div class="bg-kclite rounded clearfix mb2">
              <!-- login box on left side -->
    <div class="sm-col md-col-6 lg-col-6">
    <div class="p1  m1 ">
       <p><?php echo _("LOGIN_HERE"); ?></p>
  <form action="<?php echo Config::get('URL'); ?>login/login" method="post">
								<div class="lblgrp"><label for="user_name"><?= _("LOGIN_USERNAME_OR_EMAIL"); ?></label>
                    <input type="text" name="user_name" id="user_name" class="block col-12 field mt1 " autofocus required />
								</div>
								<div class="lblgrp"><label for="user_password"><?= _("LOGIN_PASSWORD"); ?></label>
                    <input type="password" name="user_password" id="user_password" class="block col-12 field mt1 " required />
								</div>
                    <?php if (!empty($this->redirect)) { ?>
                        <input type="hidden" name="redirect" value="<?php echo $this->redirect ?>" />
                    <?php } ?>
					<input type="hidden" name="csrf_token" value="<?= Csrf::makeToken(); ?>" />
                    <input type="submit" class="btn btn-primary mb1 mt1 black bg-kcms" value="<?php echo _("LOGIN_LOG_IN"); ?>"/>
						<div class=" right">
                    <a class="smallish" href="<?php echo Config::get('URL'); ?>login/requestPasswordReset"><?php echo _("LOGIN_FORGOT_MY_PASSWORD"); ?></a>
                </div>
                    <label for="set_remember_me_cookie" class="block col-12 mt1 smallish">
                        <input type="checkbox" name="set_remember_me_cookie" />
                        <?php echo _("LOGIN_REMEMBER_ME"); ?>
                    </label>										
										
                </form>
<div class="py1 right-align">	
<?php /*<fb:login-button 
  scope="public_profile,email"
  onlogin="checkLoginState();">
</fb:login-button>
*/ ?>
</div>
      </div>
		</div>   
								
			<div class="sm-col md-col-6 lg-col-6 p2">
            <div class="m2">
            <p><strong>CarAmnesis.com</strong><?php echo _("HERO_WRITEUP"); ?></p>
            </div>
			</div>
		
		</div>
 
   <div class="border-bottom my2"><div class="mx2"><?php
    $languages = $this->languages;
    foreach ($languages AS $key => $language) {
      if ($key !== $this->current_language) {
        echo '<a class="smallish mr2" href="?lang='.$key.'">'.$this->language_names[$key].'</a>  ';
      } else {
				echo '<span class="smallish mr2">'.$this->language_names[$key].'</span> ';
			}
    }
    ?></div></div>
		
		<div class="sm-col md-col-6 lg-col-6 py2">
				<div class="p1 m1">

							<p><?php echo _("LOGIN_NO_ACCOUNT_YET"); ?></p>
                <a class="btn btn-primary mb1 black bg-kcms" href="<?php echo Config::get('URL'); ?>login/register"><?php echo _("LOGIN_REGISTER_NEW_USR"); ?></a>

				</div>
				
		</div>
		
		
		

            <!-- register box on right side -->
            <div class="sm-col md-col-6 lg-col-6 p2">
            <div class="m2">
                <p><strong>CarAmnesis.com</strong><?php echo _("REGISTRATION_WRITEUP"); ?></p>
								  
            </div>
						</div>

   
  </div>
</div>                            

