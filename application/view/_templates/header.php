<!doctype html>
<html>
<head>
<?php
    $pagetitle = (property_exists($this, 'page_title')) ? $this->page_title : 'Motorgaga';
    //MessageModel::getUnreadMessages(Session::get('user_uuid')); already set somewhere
    ReminderModel::getReminderCount(Session::get('user_uuid')); //needs to be set here, maybe will be replaced by universal notification
?>
    <title><?= $pagetitle; ?></title>
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
    <link rel="stylesheet" href="<?= Config::get('URL'); ?>css/basscss.css?ver=15" />
    <link rel="stylesheet" href="<?= Config::get('URL'); ?>css/fontello.css" />
    <link rel="stylesheet" href="<?= Config::get('URL'); ?>css/jquery-ui.css" />
    <link rel="stylesheet" href="<?= Config::get('URL'); ?>js/photoswipe/photoswipe.css">
    <link rel="stylesheet" href="<?= Config::get('URL'); ?>js/photoswipe/default-skin/default-skin.css">
    <script src="<?= Config::get('URL'); ?>js/photoswipe/photoswipe.min.js"></script>
    <script src="<?= Config::get('URL'); ?>js/photoswipe/photoswipe-ui-default.min.js"></script>
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
        <?php
        $current_car_id = false; $current_car_name = '';
        if (property_exists($this, 'car'))
        { $current_car_id = $this->car;
            $current_car_id = $current_car_id[0];
            $current_car_name = $current_car_id->car_name;
            $current_car_id = $current_car_id->id;
        };
        ?>
        <div class="col col-6">
          <a href="<?= Config::get('URL'); ?>index/index" class="sbtn py1"><img src="/img/mrgagamascot.svg" alt="motorgaga" style="height:24px;" /></a>
            <?php if (Session::userIsLoggedIn()) { ?>
                    <a href="<?= Config::get('URL'); ?>car/index" class="sbtn py1" title="<?= _("MENU_MY_CARS"); ?>"><i class ="icon-th"> </i> <span class="sm-hide"><?= _("MENU_MY_CARS"); ?></span></a>
            <?php if ($current_car_id) { ?>
<a href="<?= Config::get('URL'); ?>car/index/<?= $current_car_id; ?>" class="sbtn py1" title="<?= $current_car_name; ?>"><i class ="icon-cab"> </i> <span class=""><?= $current_car_name; ?></span></a>
            <?php }
        } else { ?>
          <a href="<?= Config::get('URL'); ?>index/index" class="sbtn py1"><img src="/img/mrgagascript.svg" alt="motorgaga" style="height:16px;" /></a>
            <?php };  ?>
        </div>
        <!-- my account -->
        <div class="col col-6 right-align ltop">

        <?php if (Session::userIsLoggedIn()) { ?>
                            <div class="inline relative">
                              <a href="javascript:;" class="dropdown-toggle sbtn py1">
                                 <?= Session::get('user_name'); ?>
                                <i class ="icon-down-open"> </i>
                              </a>
                              <ul class="dropdown-menu closeonclick mt2 z4 active list-reset left-align">
                                <li>
                                    <a class="py1 px2" href="<?= Config::get('URL'); ?>login/showprofile" title="<?= _("MENU_MY_ACCOUNT"); ?>">
                                        <i class ="icon-user right"> </i>
                                        <span><?= _("MENU_MY_ACCOUNT"); ?></span>
                                    </a>
                                </li>
                                <li>
                                    <a class="py1 px2" href="<?= Config::get('URL'); ?>message"  title="<?= _("MESSAGES"); ?>">
                                        <i class ="icon-mail right"> </i>
                                        <span><?= _("MESSAGES").' ('.Session::get('unread_messages').')'; ?></span>
                                    </a>
                                </li>
                                <li>
                                    <a class="py1 px2" href="<?= Config::get('URL'); ?>message/reminders" title="<?= _("REMINDERS"); ?>">
                                        <span><?= _("REMINDERS").' ('.Session::get('active_reminders').')'; ?></span>
                                        <i class ="icon-bell-alt right"> </i>
                                    </a>
                                </li>
                                <li>
                                    <a class="py1 px2" href="<?= Config::get('URL'); ?>login/login/logout" title="<?= _("MENU_LOGOUT"); ?>">
                                        <i class ="icon-logout right"> </i>
                                        <span><?= _("MENU_LOGOUT"); ?></span>
                                    </a>
                                </li>
                              </ul>
                          </div>
        <?php } else {
            if (!View::checkForActiveControllerAndAction($filename, "index/about")) { ?>
                 <a href="<?= Config::get('URL'); ?>aboutMotorGaga" class="sbtn py1"><?= _("MENU_ABOUT_CARAMNESIS"); ?></a>
            <?php }
                if (!View::checkForActiveControllerAndAction($filename, "login/index")) { ?>
            <a href="<?= Config::get('URL'); ?>login/index" class="sbtn py1"><?= _("LOGIN"); ?></a>
               <?php }

        }; ?>

        </div>
      </nav>
      <section class="flex-auto p2">
