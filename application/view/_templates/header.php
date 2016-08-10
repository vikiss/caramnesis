<!doctype html>
<html>
<head>
    <title>CARAMNESIS</title>
    <meta charset="utf-8">
    <link rel="apple-touch-icon" sizes="57x57" href="/apple-touch-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="/apple-touch-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="/apple-touch-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="/apple-touch-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="/apple-touch-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="/apple-touch-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="/apple-touch-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="/apple-touch-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon-180x180.png">
    <link rel="icon" type="image/png" href="/favicon-32x32.png" sizes="32x32">
    <link rel="icon" type="image/png" href="/favicon-194x194.png" sizes="194x194">
    <link rel="icon" type="image/png" href="/favicon-96x96.png" sizes="96x96">
    <link rel="icon" type="image/png" href="/android-chrome-192x192.png" sizes="192x192">
    <link rel="icon" type="image/png" href="/favicon-16x16.png" sizes="16x16">
    <link rel="manifest" href="/manifest.json">
    <link rel="mask-icon" href="/safari-pinned-tab.svg" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#ffc40d">
    <meta name="msapplication-TileImage" content="/mstile-144x144.png">
    <meta name="theme-color" content="#ffffff">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,700,400italic&subset=latin,latin-ext' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="<?php echo Config::get('URL'); ?>css/basscss.css" />
    <link rel="stylesheet" href="<?php echo Config::get('URL'); ?>css/fontello.css" />
    <link rel="stylesheet" href="<?php echo Config::get('URL'); ?>css/jquery-ui.css" /> 
</head>
<body>
    <div class="flex flex-column" style="min-height:100vh">
    <nav class="clearfix white bg-kcms">
        <!-- navigation -->
        <div class="col col-6">
          <a href="<?php echo Config::get('URL'); ?>index/index" class="sbtn py1"><img src="/img/tiny-exhaust-header-gasket.png" alt="caramnesis" /></a>
          
       
            <?php if (Session::userIsLoggedIn()) { ?>
                    <a href="<?php echo Config::get('URL'); ?>car/index" class="sbtn py1" title="<?php echo _("MENU_MY_CARS"); ?>"><i class ="icon-th"> </i> <span class="sm-hide"><?php echo _("MENU_MY_CARS"); ?></span></a>
            <?php } else {  // for not logged-in users 
            ?>
                                
                <?php if (!View::checkForActiveControllerAndAction($filename, "login/index")) {  ?>
                    <a href="<?php echo Config::get('URL'); ?>login/index" class="sbtn py1"><?php echo _("MENU_LOGIN"); ?></a>
                <?php };
                if (!View::checkForActiveControllerAndAction($filename, "login/register")) {  ?>
                    <a href="<?php echo Config::get('URL'); ?>login/register" class="sbtn py1"><?php echo _("MENU_REGISTER"); ?></a>
                
            <?php }} ?>
        </div>
        <!-- my account -->
        <div class="col col-6 right-align ltop">
        <?php if (Session::userIsLoggedIn()) { ?> 
                    
                <span id="msgcount" class="relative">
                <?php MessageModel::getUnreadMessages(Session::get('user_uuid')); ?>                    
                <a href="<?php echo Config::get('URL'); ?>message" class="sbtn py1" title="<?= _("MESSAGES").' ('.Session::get('unread_messages').')'; ?>"><i class ="icon-mail"> </i> <span class="sm-hide"><?= _("MESSAGES"); ?></span></a>
                <div class="absolute top-0  right-0 bg-red small bold <?php if (Session::get('unread_messages') == 0) { echo 'hide '; } ?>"><?= Session::get('unread_messages'); ?></div>
                </span>
                <a href="<?php echo Config::get('URL'); ?>login/showprofile" class="sbtn py1" title="<?= _("MENU_MY_ACCOUNT").' ('.Session::get('user_name').')'; ?>"><i class ="icon-user"> </i> <span class="sm-hide"><?= _("MENU_MY_ACCOUNT"); ?></span></a>
                                     
        <?php } else { if (!View::checkForActiveControllerAndAction($filename, "index/about")) { ?>
        
          <a href="<?php echo Config::get('URL'); ?>aboutCaramnesis" class="sbtn py1"><?php echo _("MENU_ABOUT_CARAMNESIS"); ?></a>
        
        <?php } }; ?>
            
        </div>
      </nav>
      <section class="flex-auto p2">
