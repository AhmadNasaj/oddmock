<!-- /. NAV TOP  -->
        <nav class="navbar-default navbar-side" role="navigation">
            <div class="sidebar-collapse">
                <ul class="nav" id="main-menu">
                    <li class="text-center">
                    <img src="assets/img/find_user.png" class="user-image img-responsive"/>
                    </li>
						
                    <li>
                        <a  href="index.php" <?php if ($page == 'index') { echo 'class="active-menu"'; }  ?>><i class="glyphicon glyphicon-dashboard"></i> Dashboard</a>
                    </li>
                    <?php if ($session->isSuperAdmin()){ ?>
                        <li>
                            <a href="configurations.php" <?php if ($page == 'configurations') { echo 'class="active-menu"'; }  ?>><i class="glyphicon glyphicon-cog"></i>General Site Settings</a>
                        </li>
                        <li>
                            <a  href="registration.php" <?php if ($page == 'registration') { echo 'class="active-menu"'; }  ?>><i class="glyphicon glyphicon-check"></i>Registration Settings</a>
                        </li>
                        <li>
                            <a  href="session.php" <?php if ($page == 'session') { echo 'class="active-menu"'; }  ?>><i class="glyphicon glyphicon-eye-open"></i>Session Settings</a>
                        </li>
                        <li>
                            <a  href="security.php" <?php if ($page == 'security') { echo 'class="active-menu"'; }  ?>><i class="glyphicon glyphicon-lock"></i>Security Settings</a>
                        </li>
                    <?php } ?>  
                    <li>
                        <a  href="useradmin.php" <?php if ($page == 'useradmin') { echo 'class="active-menu"'; }  ?>><i class="glyphicon glyphicon-user"></i>User Admin</a>
                    </li>
                    <li>
                        <a  href="usergroups.php" <?php if ($page == 'usergroups') { echo 'class="active-menu"'; }  ?>><i class="glyphicon glyphicon-globe"></i>Group Admin</a>
                    </li>
                    <li>
                        <a  href="help.php" <?php if ($page == 'help') { echo 'class="active-menu"'; }  ?>><i class="glyphicon glyphicon-question-sign"></i>Help / Support</a>
                    </li>
                    <li>
                        <a  href="about.php" <?php if ($page == 'about') { echo 'class="active-menu"'; }  ?>><i class="glyphicon glyphicon-info-sign"></i>About</a>
                    </li>
                </ul>
            </div> 
        </nav>             
<!-- /. NAV SIDE  -->