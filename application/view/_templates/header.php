<!doctype html>
<html>
<head>
    <title>CARAMNESIS</title>
    <!-- META -->
    <meta charset="utf-8">
    <meta name="google-site-verification" content="k2m_5lCV-bnPaS6nDYZy65_66i5YGRRaRhOHVWCoOps" />
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
    <script src="http://cdn.jquerytools.org/1.2.6/full/jquery.tools.min.js"></script>
</head>
<body>
    <div class="flex flex-column" style="min-height:100vh">
    <nav class="clearfix white bg-kcms">
        <!-- navigation -->
        <div class="sm-col">
          <a href="<?php echo Config::get('URL'); ?>index/index" class="sbtn py2"><img src="/img/tiny-exhaust-header-gasket.png" alt="caramnesis" /></a>
          
          
            
            <?php if (Session::userIsLoggedIn()) { ?>
                    <a href="<?php echo Config::get('URL'); ?>car/index" class="sbtn py2"><?php echo _("MENU_MY_CARS"); ?></a>
            <?php } else {  // for not logged-in users 
            ?>
                                
                <?php if (!View::checkForActiveControllerAndAction($filename, "login/index")) {  ?>
                    <a href="<?php echo Config::get('URL'); ?>login/index" class="sbtn py2"><?php echo _("MENU_LOGIN"); ?></a>
                <?php };
                if (!View::checkForActiveControllerAndAction($filename, "login/register")) {  ?>
                    <a href="<?php echo Config::get('URL'); ?>login/register" class="sbtn py2"><?php echo _("MENU_REGISTER"); ?></a>
                
            <?php }} ?>
        </div>

        <div class="sm-col-right"><a href="?lang=lt">LT</a> <a href="?lang=en">EN</a></div>

        <!-- my account -->
        <div class="sm-col-right">
        <?php if (Session::userIsLoggedIn()) { ?> 
                    
                <a href="<?php echo Config::get('URL'); ?>login/showprofile" class="sbtn py2"><?php echo Session::get('user_name') ; ?><?php // echo _("MENU_MY_ACCOUNT"); ?></a>
                                     
                        <a href="<?php echo Config::get('URL'); ?>login/logout" class="sbtn py2"><?php echo _("MENU_LOGOUT"); ?></a>
                    
               
        <?php } else { if (!View::checkForActiveControllerAndAction($filename, "index/about")) { ?>
        
          <a href="<?php echo Config::get('URL'); ?>aboutCaramnesis" class="sbtn py2"><?php echo _("MENU_ABOUT_CARAMNESIS"); ?></a>
        
        <?php } }; ?>
        </div>
      </nav>
      <section class="flex-auto p2">
