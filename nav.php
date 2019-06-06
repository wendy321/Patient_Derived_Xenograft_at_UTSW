<nav class="navbar navbar-default templatemo-nav" role="navigation">
    <div class="container">
        <div class="navbar-header">
            <button class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="fa fa-bars"></span>
            </button>
            <a href="#" class="navbar-brand">
                <img class="img-responsive" src="images/utsw_logo.png" alt="UTSW"/>
            </a>
        </div>
        <div class="collapse navbar-collapse">
            <ul class="nav navbar-nav navbar-right">
                <li><a href="index.php">HOME</a></li>
                <li><a href="about.php">ABOUT</a></li>
                <li><a href="discovery.php">DISCOVERY</a></li>
                <li><a href="data_inventory.php">DATA MANAGEMENT</a></li>
                <li><a href="analysis.php">ANALYSIS</a></li>
                <li><a href="contact.php">CONTACT</a></li>
                <?php
                    $user = isset($_SESSION["user_name"])?$_SESSION["user_name"]:null;
                    if($user===null){
                        echo "<li ><a href='login.php'>LOGIN</a></li>";
                    }else{
                        echo "<li class='dropdown'>".
                            "<a id='welcome_name' href='#' style='color:#49b53e;'>WELCOME, " .$user
                            ."!  <i id='welcome_icon' class='fa fa-chevron-down'></i></a>".
                            "<ul class='dropdown-menu'>".
                            "<li><a href='php_script/log_out.php'><i class='fa fa-fw fa-sign-out'></i> Log Out</a></li>".
                            "</ul>".
                            "</li>";
                    }
                ?>
            </ul>
        </div>
    </div>
</nav>
<script>
    const welcome=document.getElementById("welcome_name");
    const icon=document.getElementById("welcome_icon");
    if(welcome!=null) {
        welcome.onmouseenter = function () {mouseEnter()};
    }
    function mouseEnter() {
        icon.classList.remove("fa-chevron-down");
        icon.classList.add("fa-chevron-up");
    }


    const dropdown=document.getElementsByClassName("dropdown-menu")[0];
    if(dropdown!=null){
        dropdown.onmouseleave=function(){mouseLeave()};
    }
    function mouseLeave(){
        icon.classList.remove("fa-chevron-up");
        icon.classList.add("fa-chevron-down");
    }

</script>