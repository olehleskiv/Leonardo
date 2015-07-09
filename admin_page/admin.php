<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" ng-app="scotchApp">
<head>
      <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Villa Leonardo Admin page</title>
    <!-- BOOTSTRAP STYLES-->
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
     <!-- FONTAWESOME STYLES-->
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
        <!-- CUSTOM STYLES-->
    <link href="assets/css/custom.css" rel="stylesheet" />
     <!-- GOOGLE FONTS-->
   <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />

</head>
<body ng-controller="mainController">
    <div id="wrapper">
        <nav class="navbar navbar-default navbar-cls-top " role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".sidebar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="http://www.agencyleonard.com">Villa Leonardo</a> 
            </div>
            <ul class="nav navbar-nav">
                <li><a href="https://www.facebook.com/agency.leonardo"><img src="images/facebook.png" alt=""> Facebook</a></li>
                <li><a href="http://www.tripadvisor.com/VacationRentalReview-g304080-d8287023-Villa_Leonardo-Herceg_Novi_Herceg_Novi_Municipality.html"><img src="images/tripadvisor.jpg" alt=""> Tripadvisor</a></li>
                <li><a href="https://plus.google.com/u/0/101630119550525288050/posts"><img src="images/google.png" alt=""> Plus</a></li>
                <li><a href="https://twitter.com/AgencyLeonardo"><img src="images/twitter.png" alt=""> Twitter</a></li>
                <li><a href="https://mail.google.com/mail/#inbox"><img src="images/gmail.png" alt=""> Gmail</a></li>
                <li><a href="https://www.mail.lviv.ua/"><img src="images/mail.png" alt=""> Mail</a></li>
           </ul>
            <div style="color: white; padding: 15px 15px 5px 5px; float: right; font-size: 16px;"><a href="?logout=true" class="btn btn-danger square-btn-adjust">Logout</a> </div>
        </nav>   
           <!-- /. NAV TOP  -->
                <nav class="navbar-default navbar-side" role="navigation">
            <div class="sidebar-collapse">
                <ul class="nav" id="main-menu">
                    <li class="text-center">
                        <img src="assets/img/find_user.png" class="user-image img-responsive"/>
                    </li>
                    <li>
                        <a href="#dashboard"><i class="fa fa-dashboard fa-2x"></i> Dashboard</a>
                    </li>
                    <li>
                        <a href="#prices"><i class="fa fa-money fa-2x"></i>Управління цінами</a>
                    </li>
                   <!--  Frames -->
                    <li>
                        <a href="#visitors"><i class="fa fa fa-users fa-2x"></i>Відвідуваність сайту</a>
                    </li>
                     <li>
                        <a href="#holidaylettings"><i class="fa fa-list-alt fa-2x"></i>Holidaylettings</a>
                    </li>
                    <li>
                        <a href="#booking"><i class="fa fa-list-alt fa-2x"></i>Booking.com</a>
                    </li>
                     <li>
                        <a href="#club"><i class="fa fa-list-alt fa-2x"></i>chernogoriya-club</a>
                    </li>
                     <li>
                        <a href="#wakacyjnywynajem"><i class="fa fa-list-alt fa-2x"></i>wakacyjnywynajem</a>
                    </li>

                    <!--  <li>
                        <a  href="#ui"><i class="fa fa-desktop fa-2x"></i> UI Elements</a>
                    </li>
                    <li>
                        <a  href="#tab-panel"><i class="fa fa-qrcode fa-2x"></i> Tabs & Panels</a>
                    </li> -->
                        
                    <!-- <li>
                        <a href="#"><i class="fa fa-sitemap fa-2x"></i> Multi-Level Dropdown<span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li>
                                <a href="#">Second Level Link</a>
                            </li>
                            <li>
                                <a href="#">Second Level Link</a>
                            </li>
                            <li>
                                <a href="#">Second Level Link<span class="fa arrow"></span></a>
                                <ul class="nav nav-third-level">
                                    <li>
                                        <a href="#">Third Level Link</a>
                                    </li>
                                    <li>
                                        <a href="#">Third Level Link</a>
                                    </li>
                                    <li>
                                        <a href="#">Third Level Link</a>
                                    </li>

                                </ul>
                               
                            </li>
                        </ul>
                      </li>  -->
                </ul>
               
            </div>
            
        </nav>  
        <!-- /. NAV SIDE  -->

        </div>
     <!-- /. WRAPPER  -->
     <div id="main">
  
    <!-- angular templating -->
        <!-- this is where content will be injected -->
    <div ng-view></div>
    
  </div>
    <!-- SCRIPTS -AT THE BOTOM TO REDUCE THE LOAD TIME-->
    <script src="assets/js/angular.min.js"></script>
    <script src="//ajax.googleapis.com/ajax/libs/angularjs/1.2.25/angular-route.js"></script>
    <!-- JQUERY SCRIPTS -->
    <script src="assets/js/jquery-1.10.2.js"></script>
      <!-- BOOTSTRAP SCRIPTS -->
    <script src="assets/js/bootstrap.min.js"></script>
    <!-- METISMENU SCRIPTS -->
    <script src="assets/js/jquery.metisMenu.js"></script>
      <!-- CUSTOM SCRIPTS -->
    <script src="assets/js/custom.js"></script>
    <script src="assets/js/script.js"></script>
    <script src="assets/js/pricesCtrl.js"></script>
    
   
</body>
</html>
