<?php
include("includes/controller.php");
/*
 * index.php
 *
 * This is an example of the index page of a website. Here users will be able to login. 
 * However, like on most sites the login form doesn't just have to be on the main page,
 * but re-appear on subsequent pages, depending on whether the user has logged in or not.
 *
 * Last Updated : October 17, 2014
*/
?>
<!DOCTYPE html>
<!--[if IE 9]>         <html class="no-js lt-ie10"> <![endif]-->
<!--[if gt IE 9]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">

        <title>OddMock - Collection Monitoring</title>

        <meta name="description" content="AppUI is a Web App Bootstrap Admin Template created by pixelcave and published on Themeforest">
        <meta name="author" content="pixelcave">
        <meta name="robots" content="noindex, nofollow">

        <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1.0">

        <!-- Icons -->
        <!-- The following icons can be replaced with your own, they are used by desktop and mobile browsers -->
        <link rel="shortcut icon" href="img/favicon.png">
        <link rel="apple-touch-icon" href="img/icon57.png" sizes="57x57">
        <link rel="apple-touch-icon" href="img/icon72.png" sizes="72x72">
        <link rel="apple-touch-icon" href="img/icon76.png" sizes="76x76">
        <link rel="apple-touch-icon" href="img/icon114.png" sizes="114x114">
        <link rel="apple-touch-icon" href="img/icon120.png" sizes="120x120">
        <link rel="apple-touch-icon" href="img/icon144.png" sizes="144x144">
        <link rel="apple-touch-icon" href="img/icon152.png" sizes="152x152">
        <link rel="apple-touch-icon" href="img/icon180.png" sizes="180x180">
        <!-- END Icons -->

        <!-- Stylesheets -->
        <!-- Bootstrap is included in its original form, unaltered -->
        <link rel="stylesheet" href="../css/bootstrap.min.css">

        <!-- Related styles of various icon packs and plugins -->
        <link rel="stylesheet" href="../css/plugins.css">

        <!-- The main stylesheet of this template. All Bootstrap overwrites are defined in here -->
        <link rel="stylesheet" href="../css/main.css">

        <!-- Include a specific file here from css/themes/ folder to alter the default theme of the template -->

        <!-- The themes stylesheet of this template (for using specific theme color in individual elements - must included last) -->
        <link rel="stylesheet" href="../css/themes.css">
        <!-- END Stylesheets -->

        <!-- Modernizr (browser feature detection library) -->
        <script src="../js/vendor/modernizr-2.8.3.min.js"></script>
    </head>
    <body>
        <?php
/**
 * User has already logged in, so display relavent links, including
 * a link to the admin center if the user is an administrator.
 */

if($session->logged_in){
?>
        <!-- Page Wrapper -->
        <!-- In the PHP version you can set the following options from inc/config file -->
        <!--
            Available classes:

            'page-loading'      enables page preloader
        -->
        <div id="page-wrapper" class="page-loading">
            <!-- Preloader -->
            <!-- Preloader functionality (initialized in js/app.js) - pageLoading() -->
            <!-- Used only if page preloader enabled from inc/config (PHP version) or the class 'page-loading' is added in #page-wrapper element (HTML version) -->
            <div class="preloader">
                <div class="inner">
                    <!-- Animation spinner for all modern browsers -->
                    <div class="preloader-spinner themed-background hidden-lt-ie10"></div>

                    <!-- Text for IE9 -->
                    <h3 class="text-primary visible-lt-ie10"><strong>Loading..</strong></h3>
                </div>
            </div>
            <!-- END Preloader -->

            <!-- Page Container -->
            <!-- In the PHP version you can set the following options from inc/config file -->
            <!--
                Available #page-container classes:

                'sidebar-light'                                 for a light main sidebar (You can add it along with any other class)

                'sidebar-visible-lg-mini'                       main sidebar condensed - Mini Navigation (> 991px)
                'sidebar-visible-lg-full'                       main sidebar full - Full Navigation (> 991px)

                'sidebar-alt-visible-lg'                        alternative sidebar visible by default (> 991px) (You can add it along with any other class)

                'header-fixed-top'                              has to be added only if the class 'navbar-fixed-top' was added on header.navbar
                'header-fixed-bottom'                           has to be added only if the class 'navbar-fixed-bottom' was added on header.navbar

                'fixed-width'                                   for a fixed width layout (can only be used with a static header/main sidebar layout)

                'enable-cookies'                                enables cookies for remembering active color theme when changed from the sidebar links (You can add it along with any other class)
            -->
            <div id="page-container" class="header-fixed-top sidebar-visible-lg-full">
                <!-- Main Sidebar -->
                <div id="sidebar">
                    <!-- Sidebar Brand -->
                    <div id="sidebar-brand" class="themed-background">
                        <a href="index.php" class="sidebar-title">
                            <i style="padding-left: 5px;" class="fa fa-cube"></i> <span class="sidebar-nav-mini-hide">Odd<strong>Mock</strong></span>
                        </a>
                    </div>
                    <!-- END Sidebar Brand -->

                    <!-- Wrapper for scrolling functionality -->
                    <div id="sidebar-scroll">
                        <!-- Sidebar Content -->
                        <div class="sidebar-content">
                            <!-- Sidebar Navigation -->
                            <ul class="sidebar-nav">
                                <li>
                                    <a href="index.php"><i class="fa fa-home sidebar-nav-icon"></i><span class="sidebar-nav-mini-hide">Home</span></a>
                                </li>
                                <li class="sidebar-separator">
                                    <i class="fa fa-ellipsis-h"></i>
                                </li>
                                <li>
                                    <a href="ban.php"><i class="fa fa-gamepad sidebar-nav-icon"></i><span class="sidebar-nav-mini-hide">Collection Item Monitor</span></a>
                                </li>
                                <li>
                                    <a href="blacklist.php" class="active"><i class="fa fa-book sidebar-nav-icon"></i><span class="sidebar-nav-mini-hide">Collection Monitoring</span></a>
                                </li>
                               
                                <li class="sidebar-separator">
                                <i class="fa fa-ellipsis-h"></i>
                                </li>
                                <li>
                                    <a href="../faq.php"><i class="fa fa-file-text-o sidebar-nav-icon"></i><span class="sidebar-nav-mini-hide">FAQ</span></a>
                                </li>
                                <li>
                                    <a href="../tos.php"><i class="fa fa-file-text-o sidebar-nav-icon"></i><span class="sidebar-nav-mini-hide">Terms of Service</span></a>
                                </li>
                                <li>
                                    <a href="../contact.php"><i class="fa fa-envelope-o sidebar-nav-icon"></i><span class="sidebar-nav-mini-hide">Contact Us</span></a>
                                </li>
                            </ul>
                            <!-- END Sidebar Navigation -->
                        </div>
                        <!-- END Sidebar Content -->
                    </div>
                    <!-- END Wrapper for scrolling functionality -->

                    <!-- Sidebar Extra Info -->
                    <div id="sidebar-extra-info" class="sidebar-content sidebar-nav-mini-hide">
                        <div class="text-center">
                            <small>&copy; Copyright 2021 - OddMock</small><br>
                        </div>
                    </div>
                    <!-- END Sidebar Extra Info -->
                </div>
                <!-- END Main Sidebar -->

                <!-- Main Container -->
                <div id="main-container">
                    <!-- Header -->
                    <!-- In the PHP version you can set the following options from inc/config file -->
                    <!--
                        Available header.navbar classes:

                        'navbar-default'            for the default light header
                        'navbar-inverse'            for an alternative dark header

                        'navbar-fixed-top'          for a top fixed header (fixed main sidebar with scroll will be auto initialized, functionality can be found in js/app.js - handleSidebar())
                            'header-fixed-top'      has to be added on #page-container only if the class 'navbar-fixed-top' was added

                        'navbar-fixed-bottom'       for a bottom fixed header (fixed main sidebar with scroll will be auto initialized, functionality can be found in js/app.js - handleSidebar()))
                            'header-fixed-bottom'   has to be added on #page-container only if the class 'navbar-fixed-bottom' was added
                    -->
                    <header class="navbar navbar-inverse navbar-fixed-top">
                        <!-- Left Header Navigation -->
                        <ul class="nav navbar-nav-custom">
                            <!-- Header Link -->
                            <li class="hidden-xs animation-fadeInQuick">
                                <a href=""><strong>Daily Notice:</strong> Welcome to OddMock</a>
                            </li>
                            <!-- END Header Link -->
                        </ul>
                        <!-- END Left Header Navigation -->

                        <!-- Right Header Navigation -->
                        <ul class="nav navbar-nav-custom pull-right">
                            <li>
                                <a href="">Welcome, <strong><?php echo $session->username; ?></strong></a>
                            </li>

                            <!-- User Dropdown -->
                            <li class="dropdown">
                                <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown">
                                    <img src="../img/placeholders/avatars/avatar9.jpg" alt="avatar">
                                </a>
                                <ul class="dropdown-menu dropdown-menu-right">
                                    <li class="dropdown-header">
                                        <strong><?php echo $session->username; ?></strong>
                                    </li>
                                    <li>
                                        <a href="includes/process.php">
                                            <i class="fa fa-power-off fa-fw pull-right"></i>
                                            Log out
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <!-- END User Dropdown -->
                        </ul>
                        <!-- END Right Header Navigation -->
                    </header>
                    <!-- END Header -->

                    <!-- Page content -->
                    <div id="page-content">
                    
                        <!-- START of announcement -->
                        <div class="alert alert-success">
                            <h4><strong>Beta Testing</strong></h4>
                            <p>Welcome you NFT smurf-fucks, you all are the very first users of OddMock. Please expect miscellaneous, temporary bugs and errors. Please report errors to staff for a reasonable reward.</p>
                        </div>
                        <!-- END of announcement -->
                    
                        <div class="row">
	                        <div class="col-xs-12">
		                        <div class="block">
			                        <div class="block-title" style="text-align: center;">
				                        <h2>Collection Monitoring</h2>
			                        </div>
			                        
			                        <div class="row">
                                        
                                        
                                        
                                        <div class="col-sm-4"></div>
                                        
                                        
                                        <div class="col-sm-4">
                                            <table class="table table-borderless table-pricing">
                                                <thead>
                                                    <tr>
                                                        <th>New Collection Monitoring</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr class="active">
                                                        <td>
                                                            <h1>$<strong>15</strong><small>.00</small></h1>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>3</strong> Collections Monitored</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>24/7</strong> Support</td>
                                                    </tr>
                                                    
                                                    <tr class="active">
                                                        <td>
                                                             <a href="#buy-popup" data-toggle="modal"><img src="../img/paypal.png" style="width: 140px;"></a><br><br>
                                                             
                                                             <a href="#buy-popup2" data-toggle="modal"><img src="../img/bitcoin.png" style="width: 140px;"></a>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                             <br><br><br>
                                        </div>
                                        
                                        
                                        
                                        
			                        </div>
		                        </div>
	                        </div>
                        </div>
                    </div>
                    <!-- END Page Content -->
                </div>
                <!-- END Main Container -->
            </div>
            <!-- END Page Container -->
        </div>
        <!-- END Page Wrapper -->
        
        
        
        
        
        <div id="buy-popup" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title"><strong>Purchase using PayPal</strong></h4>
                    </div>
                    <div class="modal-body">
                        
                            <div class="form-group">
                                <div class="col-xs-12" align="center">
                                    
                                    
                                    <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
<input type="hidden" name="cmd" value="_s-xclick">
<table>
<tr><td><input type="hidden" name="on0" value="OddMock username to Blacklist">OddMock username to Blacklist</td></tr><tr><td><input type="text" name="os0" maxlength="200"></td></tr>
</table>
<input type="hidden" name="encrypted" value="-----BEGIN PKCS7-----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-----END PKCS7-----
"><br>
<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_buynowCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
</form><p>Please allow 72 hours after your purchase for the OddMock TEMP TEMPTEMPTMTEMP account to be fully banned!</p>
                                    
                                    
                                </div><br><br><br><br><br><br>
                            </div>
                            
                       
                    </div>
                </div>
            </div>
        </div>
        
        
        
        
        
        
        
        <div id="buy-popup2" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title"><strong>Purchase using BitCoin</strong></h4>
                    </div>
                    <div class="modal-body">
                        
                            <div class="form-group">
                                <div class="col-xs-12" align="center">
                                    <p>Please complete the following steps:</p>
                                    <br>
                                    
                                    <p><strong>1.</strong> Purchase/Checkout here:</p>
                                    <form action="https://www.coinpayments.net/index.php" method="post">
	<input type="hidden" name="cmd" value="_pay_simple">
	<input type="hidden" name="reset" value="1">
	<input type="hidden" name="merchant" value="248eabda1c62c8432214e98cc010e9a1">
	<input type="hidden" name="item_name" value="Product-SBL">
	<input type="hidden" name="item_desc" value="Blacklist A OddMock">
	<input type="hidden" name="item_number" value="2">
	<input type="hidden" name="invoice" value="15.00">
	<input type="hidden" name="currency" value="USD">
	<input type="hidden" name="amountf" value="15.00000000">
	<input type="hidden" name="want_shipping" value="0">
	<input type="hidden" name="success_url" value="http://oddmock.com/btcsuccess.php">
	<input type="hidden" name="cancel_url" value="http://oddmock.com/btcfail.php">
	<input type="image" src="https://www.coinpayments.net/images/pub/CP-main-medium.png" alt="Buy Now with CoinPayments.net">
</form><br>
 <p><strong>2.</strong> After purchasing you will be redirected to another page.</p>
 <p><strong>3.</strong> If you were NOT redirected to another page after purchasing please email your HASH + The OddMock TEMPTEMPTTEMPT username you want blacklisted to logic@null.net</p><br>
                                    <p>Please allow 12 hours after your purchase for the OddMock TEMPTPEPTEMTPET account to be fully blacklisted!</p>
                                    
                                    
                                </div><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
                                
                            </div>
                            
                       
                    </div>
                </div>
            </div>
        </div>
        
        
        
        
        
        
        
        

        <!-- Include Jquery library from Google's CDN but if something goes wrong get Jquery from local file -->
        <script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
        <script>!window.jQuery && document.write(decodeURI('%3Cscript src="js/vendor/jquery-2.1.3.min.js"%3E%3C/script%3E'));</script>

        <!-- Bootstrap.js, Jquery plugins and Custom JS code -->
        <script src="../js/vendor/bootstrap.min.js"></script>
        <script src="../js/plugins.js"></script>
        <script src="../js/app.js"></script>

        <!-- Load and execute javascript code used only in this page -->
        <script src="../js/pages/readyDashboard.js"></script>
        <script>$(function(){ ReadyDashboard.init(); });</script>
        <?php
}
else{
$form = new Form;
/**
 * User not logged in, display the login form. If the user has already tried to login, 
 * but errors were found, they will be displayed.
 */
?>

<?php
header('Location: index.php');
?>
        
<?php
}
?>
    </body>
</html>