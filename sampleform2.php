 <!DOCTYPE html>
<html>
<head>
<meta char set="UTF-8">
    <link type="text/css" rel="stylesheet" href="css/bootstrap.css"/>
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
    <script type="text/javascript" src="js/bootstrap-dropdown.js"></script>
    <script type="text/javascript" src="js/bootstrap.js"></script>
</head>
<body>
   <div>
    <div class="navbar fixed-top" style="width:90px;">
        <div class="navbar-inner" style="width:90px;">
            <div class="container" style="width:90px;">
                <ul class="nav">
           <li class="dropdown" id="menu1">
             <a class="dropdown-toggle" data-toggle="dropdown" href="#menu1">
               Login
                <b class="caret"></b>
             </a>
             <div class="dropdown-menu">
                <div style="width:17.5em;height:15em;border-top-left-radius:15px;border-bottom-left-radius:15px;padding:5px;background-color:#74ba53;float:left">
                                        <fieldset id="fields">
                                            <legend class="formhead"> Network </legend>
                                            <input id="username" type="text" name="username" placeholder="username"/>
                                            <input id="password" type="password" name="password" placeholder="password"/>
                                            <a style="padding-bottom:10px;color:#fff;" href="forgotpass.php"> Forgot your password?</a>
                                        </fieldset>    
                                        <fieldset>
                                             <input class="btn btn-primary" style="clear: left; width: 65%; height: 32px; font-size: 13px;margin-top:10px;" type="submit" name="commit" value="Sign In" />
                                        </fieldset>    
                                    </div>
                                    <div style="width:17.5em;height:15em;border-top-right-radius:15px;border-bottom-right-radius:15px;padding:5px;background-color:#666666;float:right;">
                                        <fieldset id="fields">
                                            <legend class="formhead"> Market </legend>
                                            <input id="username" type="text" name="username" placeholder="username"/>
                                            <input id="password" type="password" name="password" placeholder="password"/>
                                            <a style="padding-bottom:10px;color:#fff;" href="forgotpass.php"> Forgot your password?</a>
                                        </fieldset>    
                                        <fieldset>
                                             <input class="btn btn-warning" style="clear: left; width: 65%; height: 32px; font-size: 13px;margin-top:10px;" type="submit" name="commit" value="Sign In" />
                                        </fieldset>    
                                    </div>  
             </div>
             <ul>
           </div>
        </div>
    </div>            


    </body>
</html>
    
​