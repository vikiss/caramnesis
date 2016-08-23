<div class="container">
  <div class="clearfix">
    <?php $this->renderFeedbackMessages(); ?>

              <!-- login box on left side -->
    <div class="sm-col md-col-12 lg-col-12">
    <div class="p4 bg-kclite m1 rounded">
    <h2><?php echo _("REGISTER_NEW_ACCOUNT"); ?></h2>
    <p class="right-align"><?php
    $languages = $this->languages;
    foreach ($languages AS $key => $language) {
      if ($key !== $this->current_language) {
        echo '<a class="btn black bg-kcms" href="?lang='.$key.'">'.$this->language_names[$key].'</a> ';
      }
    }
    ?></p>

        <!-- register form -->
        <form method="post" action="<?php echo Config::get('URL'); ?>login/register_action">
            <div class="lblgrp"><label for="user_name"><?php echo _("REGISTER_USERNAME"); ?></label>
              <input type="text" pattern="[a-zA-Z0-9]{2,64}" name="user_name" id="user_name" class="block col-12 field mt1" required />
            </div>
            <div class="lblgrp"><label for="user_email"><?php echo _("REGISTER_EMAIL"); ?></label>
            <input type="text" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$" name="user_email" id="user_email" class="block col-12 field mt1" required />
            </div>
            <div class="lblgrp"><label for="user_password_new"><?php echo _("REGISTER_PASSWORD"); ?></label>
            <input type="password" name="user_password_new" id="user_password_new" pattern=".{6,}" class="block col-12 field mt1 " required autocomplete="off" />
            </div>
            <div class="lblgrp"><label for="user_password_repeat"><?php echo _("REGISTER_REPEAT_PASSWORD"); ?></label>
            <input type="password" name="user_password_repeat" id="user_password_repeat" pattern=".{6,}" required autocomplete="off" class="block col-12 field mt1 " />
            </div>
            <input type="hidden" name="startLat" id="startLat" />
            <input type="hidden" name="startLon" id="startLon" />
            <div class="block col-12 mt1 clearfix">
            <img class="left" id="captcha" src="<?php echo Config::get('URL'); ?>login/showCaptcha" />
            <a class="left mx2 btn black bg-kcms" href="#" onclick="document.getElementById('captcha').src = '<?php echo Config::get('URL'); ?>login/showCaptcha?' + Math.random(); return false"><?php echo _("REGISTER_RELOAD_CAPTCHA"); ?></a>
            </div>
            <div class="lblgrp"><label for="captchatext"><?php echo _("REGISTER_ENTER_CAPTCHA"); ?></label>
            <input type="text" name="captcha" id="captchatext" class="block col-12 field mt1 " required />
            </div>

                        <input type="submit" class="btn btn-primary mb1 mt1 black bg-kcms" value="<?php echo _("REGISTER_REGISTER"); ?>" />
        </form>

            </div></div>

           
           

    <script src="/js/geoloc.js"></script>    
    
  </div>
</div>                            


