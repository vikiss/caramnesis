<!doctype html>
<html>
<head>
    <title>Motorgaga</title>
    <meta charset="utf-8">
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png?ver=2">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png?ver=2">
    <link rel="manifest" href="/manifest.json">
    <link rel="mask-icon" href="/safari-pinned-tab.svg" color="#000000">
    <meta name="theme-color" content="#ffc40d">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
<?php
    if ($filename == 'view/event') {include('view-event-meta.php'); }
    if ($filename == 'view/car') {include('view-car-meta.php'); }
?>
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,700,400italic&subset=latin,latin-ext' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="<?php echo Config::get('URL'); ?>css/basscss.css" />
    <link rel="stylesheet" href="<?php echo Config::get('URL'); ?>css/fontello.css" />
    <link rel="stylesheet" href="<?php echo Config::get('URL'); ?>css/jquery-ui.css" />
    <link rel="stylesheet" href="<?php echo Config::get('URL'); ?>js/photoswipe/photoswipe.css"> 
    <link rel="stylesheet" href="<?php echo Config::get('URL'); ?>js/photoswipe/default-skin/default-skin.css"> 
    <script src="<?php echo Config::get('URL'); ?>js/photoswipe/photoswipe.min.js"></script> 
    <script src="<?php echo Config::get('URL'); ?>js/photoswipe/photoswipe-ui-default.min.js"></script> 
</head>
<body>
<?php include 'pswp.php'; ?>
<script>
  window.fbAsyncInit = function() {
    FB.init({
      appId      : '176273039511303',
      xfbml      : true,
      version    : 'v2.6'
    });
  };

  (function(d, s, id){
     var js, fjs = d.getElementsByTagName(s)[0];
     if (d.getElementById(id)) {return;}
     js = d.createElement(s); js.id = id;
     js.src = "//connect.facebook.net/en_US/sdk.js";
     fjs.parentNode.insertBefore(js, fjs);
   }(document, 'script', 'facebook-jssdk'));
</script>
    <div class="flex flex-column" style="min-height:100vh">
    <nav class="clearfix white bg-kcms">
        <!-- navigation -->
        <div class="col col-6">
          <a href="<?php echo Config::get('URL'); ?>index/index" class="sbtn py1"><img src="/img/tiny-exhaust-header-gasket.png" alt="caramnesis" /></a>
          
       
            <?php if (Session::userIsLoggedIn()) { ?>
                    <a href="<?php echo Config::get('URL'); ?>car/index" class="sbtn py1" title="<?php echo _("MENU_MY_CARS"); ?>"><i class ="icon-th"> </i> <span class="sm-hide"><?php echo _("MENU_MY_CARS"); ?></span></a>
            <?php }; ?>
        </div>
        <!-- my account -->
        <div class="col col-6 right-align ltop">
        <?php if (Session::userIsLoggedIn()) { ?> 
                    
                <div id="msgcount" class="inline relative">
                <?php MessageModel::getUnreadMessages(Session::get('user_uuid')); ?>                    
                <a href="<?php echo Config::get('URL'); ?>message" class="sbtn py1" title="<?= _("MESSAGES").' ('.Session::get('unread_messages').')'; ?>"><i class ="icon-mail"> </i> <span class="sm-hide"><?= _("MESSAGES"); ?></span></a>
                <div class="absolute top-0  right-0 bg-red small bold <?php if (Session::get('unread_messages') == 0) { echo 'hide '; } ?>"><?= Session::get('unread_messages'); ?></div>
                </div>
                <div id="reminderCount" class="inline relative">
                <?php ReminderModel::getReminderCount(Session::get('user_uuid')); ?>                                        
                <a href="<?php echo Config::get('URL'); ?>message/reminders" class="sbtn py1" title="<?= _("REMINDERS").' ('.Session::get('active_reminders').')'; ?>"><i class ="icon-bell-alt"> </i> <span class="sm-hide"><?= _("REMINDERS"); ?></span></a>
                <div class="absolute top-0  right-0 bg-red small bold <?php if (Session::get('active_reminders') == 0) { echo 'hide '; } ?>"><?= Session::get('active_reminders'); ?></div>
                </div>
                <a href="<?php echo Config::get('URL'); ?>login/showprofile" class="sbtn py1" title="<?= _("MENU_MY_ACCOUNT").' ('.Session::get('user_name').')'; ?>">
                <i class ="icon-user"> </i> <span class="sm-hide"><?= _("MENU_MY_ACCOUNT"); ?></span></a>
                
                <a href="<?php echo Config::get('URL'); ?>login/login/logout" class="sbtn py1" title="<?= _("MENU_LOGOUT"); ?>">
                <i class ="icon-logout"> </i> </a>
                                     
        <?php } else {
            if (!View::checkForActiveControllerAndAction($filename, "index/about")) { ?>
                 <a href="<?php echo Config::get('URL'); ?>aboutCaramnesis" class="sbtn py1"><?php echo _("MENU_ABOUT_CARAMNESIS"); ?></a>
            <?php }
                if (!View::checkForActiveControllerAndAction($filename, "login/index")) { ?>
            <a href="<?php echo Config::get('URL'); ?>login/index" class="sbtn py1"><?php echo _("LOGIN"); ?></a>
               <?php }
        
        }; ?>
            
        </div>
      </nav>
      <section class="flex-auto p2">