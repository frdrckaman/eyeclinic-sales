<!DOCTYPE html>
<html lang="en">

<head>
    <title>Login - OnaEyeCare</title>
    <?php include 'head.php'?>
</head>
<body>

<div class="loginBlock" id="login" style="display: block;">
    <h1>Welcom. Please Sign In</h1>
    <div class="dr"><span></span></div>
    <div class="loginForm">
        <form class="form-horizontal" method="post" id="validation">
            <div class="form-group">
                <div class="input-group">
                    <span class="input-group-addon"><span class="glyphicon glyphicon-envelope"></span></span>
                    <input type="text" id="inputEmail" placeholder="Email" class="form-control validate[required]"/>
                </div>
            </div>
            <div class="form-group">
                <div class="input-group">
                    <span class="input-group-addon"><span class="glyphicon glyphicon-lock"></span></span>
                    <input type="password" id="inputPassword" placeholder="Password" class="form-control validate[required]"/>
                </div>
            </div>
            <div class="row">
                <div class="col-md-8">
                    <div class="form-group" style="margin-top: 5px;">
                        <label class="checkbox"><input type="checkbox"> Remember me</label>
                    </div>
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-default btn-block">Sign in</button>
                </div>
            </div>
        </form>
        <div class="dr"><span></span></div>
        <div class="controls">
            <div class="row">
                <div class="col-md-6">
                    <button class="btn btn-link btn-block" onClick="loginBlock('#forgot');">Forgot password?</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="loginBlock" id="forgot">
    <h1>Forgot your password?</h1>
    <div class="dr"><span></span></div>
    <div class="loginForm">
        <form class="form-horizontal" action="http://aqvatarius.com/themes/aquarius/index.html" method="POST">
            <p>This form help you return your password. Please, enter your password, and send request</p>
            <div class="form-group">
                <div class="input-group">
                    <span class="input-group-addon"><span class="glyphicon glyphicon-envelope"></span></span>
                    <input type="text" placeholder="Your email" class="form-control"/>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6"></div>
                <div class="col-md-6">
                    <button type="submit" class="btn btn-default btn-block">Send request</button>
                </div>
            </div>
        </form>
        <div class="dr"><span></span></div>
        <div class="controls">
            <div class="row">
                <div class="col-md-12">
                    <button class="btn btn-link" onClick="loginBlock('#login');">&laquo; Back</button>
                </div>
            </div>
        </div>
    </div>
</div>

</body>

</html>
