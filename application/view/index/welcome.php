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
       <h2>Almost there</h2>
  <form action="<?php echo Config::get('URL'); ?>index/save_it" method="post">
                    <input type="text" name="email" placeholder="Your email address" class="block col-12 field mt1 " required />
                    <p>Enter your email and we'll shoot you a message when we're ready</p>
					<input type="hidden" name="csrf_token" value="<?= Csrf::makeToken(); ?>" />
                    <input type="submit" class="btn btn-primary mb1 mt1 black bg-kcms" value="Save"/>
                </form>
            </div></div>

            <!-- register box on right side -->
            <div class="sm-col md-col-6 lg-col-6">
            <div class="m2">
            <p><strong>Caramnesis.com</strong><?php echo Text::get("HERO_WRITEUP"); ?></p>
            </div></div>

        
    
  </div>
</div>                            

