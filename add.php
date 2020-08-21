<!DOCTYPE html>
<html lang="en">

<head>
    <title>Dashboard - OnaEyeCare</title>
    <?php include "head.php";?>
</head>
<body>
<div class="wrapper">

    <?php include 'topbar.php'?>
    <?php include 'menu.php'?>
    <div class="content">


        <div class="breadLine">

            <ul class="breadcrumb">
                <li><a href="#">Simple Admin</a> <span class="divider">></span></li>
                <li class="active">Dashboard</li>
            </ul>

            <ul class="buttons">
                <li>
                    <a href="#" class="link_bcPopupList"><span class="glyphicon glyphicon-user"></span><span class="text">Users list</span></a>

                    <div id="bcPopupList" class="popup">
                        <div class="head clearfix">
                            <div class="arrow"></div>
                            <span class="isw-users"></span>
                            <span class="name">List users</span>
                        </div>
                        <div class="body-fluid users">

                            <div class="item clearfix">
                                <div class="image"><a href="#"><img src="img/users/aqvatarius_s.jpg" width="32"/></a></div>
                                <div class="info">
                                    <a href="#" class="name">Aqvatarius</a>
                                    <span>online</span>
                                </div>
                            </div>

                            <div class="item clearfix">
                                <div class="image"><a href="#"><img src="img/users/olga_s.jpg" width="32"/></a></div>
                                <div class="info">
                                    <a href="#" class="name">Olga</a>
                                    <span>online</span>
                                </div>
                            </div>

                            <div class="item clearfix">
                                <div class="image"><a href="#"><img src="img/users/alexey_s.jpg" width="32"/></a></div>
                                <div class="info">
                                    <a href="#" class="name">Alexey</a>
                                    <span>online</span>
                                </div>
                            </div>

                            <div class="item clearfix">
                                <div class="image"><a href="#"><img src="img/users/dmitry_s.jpg" width="32"/></a></div>
                                <div class="info">
                                    <a href="#" class="name">Dmitry</a>
                                    <span>online</span>
                                </div>
                            </div>

                            <div class="item clearfix">
                                <div class="image"><a href="#"><img src="img/users/helen_s.jpg" width="32"/></a></div>
                                <div class="info">
                                    <a href="#" class="name">Helen</a>
                                </div>
                            </div>

                            <div class="item clearfix">
                                <div class="image"><a href="#"><img src="img/users/alexander_s.jpg" width="32"/></a></div>
                                <div class="info">
                                    <a href="#" class="name">Alexander</a>
                                </div>
                            </div>

                        </div>
                        <div class="footer">
                            <button class="btn btn-default" type="button">Add new</button>
                            <button class="btn btn-danger link_bcPopupList" type="button">Close</button>
                        </div>
                    </div>

                </li>
                <li>
                    <a href="#" class="link_bcPopupSearch"><span class="glyphicon glyphicon-search"></span><span class="text">Search</span></a>

                    <div id="bcPopupSearch" class="popup">
                        <div class="head clearfix">
                            <div class="arrow"></div>
                            <span class="isw-zoom"></span>
                            <span class="name">Search</span>
                        </div>
                        <div class="body search">
                            <input type="text" placeholder="Some text for search..." name="search"/>
                        </div>
                        <div class="footer">
                            <button class="btn btn-default" type="button">Search</button>
                            <button class="btn btn-danger link_bcPopupSearch" type="button">Close</button>
                        </div>
                    </div>
                </li>
            </ul>

        </div>

        <div class="workplace">

            <div class="row">
                <div class="col-md-offset-1 col-md-8">
                    <div class="head clearfix">
                        <div class="isw-ok"></div>
                        <h1>Add User</h1>
                    </div>
                    <div class="block-fluid">
                        <form id="validation" method="post" >
                            <div class="row-form clearfix">
                                <div class="col-md-3">Clinic Branch:</div>
                                <div class="col-md-9">
                                    <select name="branch" id="branch" class="validate[required]">
                                        <option value="">Choose branch</option>
                                        <option value="option1">Tennis</option>
                                        <option value="option2">Football</option>
                                        <option value="option3">Golf</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row-form clearfix">
                                <div class="col-md-3">First Name:</div>
                                <div class="col-md-9">
                                    <input value="" class="validate[required]" type="text" name="firstname" id="firstname"/>
                                </div>
                            </div>
                            <div class="row-form clearfix">
                                <div class="col-md-3">Last Name:</div>
                                <div class="col-md-9">
                                    <input value="" class="validate[required]" type="text" name="lastname" id="lastname"/>
                                </div>
                            </div>
                            <div class="row-form clearfix">
                                <div class="col-md-3">Username:</div>
                                <div class="col-md-9">
                                    <input value="" class="validate[required]" type="text" name="username" id="username"/>
                                </div>
                            </div>
                            <div class="row-form clearfix">
                                <div class="col-md-3">Phone Number:</div>
                                <div class="col-md-9">
                                    <input value="" class="validate[required]" type="text" name="phone_number" id="firstname"/>
                                </div>
                            </div>

                            <div class="row-form clearfix">
                                <div class="col-md-3">E-mail Address:</div>
                                <div class="col-md-9"><input value="" class="validate[required,custom[email]]" type="text" name="email" id="email" />  <span>Example: someone@nowhere.com</span></div>
                            </div>

                            <div class="footer tar">
                                <input type="submit" value="Submit" class="btn btn-default">
                            </div>

                        </form>
                    </div>

                </div>
            </div>

            <div class="dr"><span></span></div>

            <div class="row">
                <div class="col-md-offset-1 col-md-8">
                    <div class="head clearfix">
                        <div class="isw-ok"></div>
                        <h1>Add Branch</h1>
                    </div>
                    <div class="block-fluid">
                        <form id="validation" method="post" >
                            <div class="row-form clearfix">
                                <div class="col-md-3">Branch Name:</div>
                                <div class="col-md-9">
                                    <input value="" class="validate[required]" type="text" name="firstname" id="firstname"/>
                                </div>
                            </div>
                            <div class="row-form clearfix">
                                <div class="col-md-3">Branch ID:</div>
                                <div class="col-md-9">
                                    <input value="" class="validate[required]" type="text" name="lastname" id="lastname"/>
                                </div>
                            </div>
                            <div class="row-form clearfix">
                                <div class="col-md-3">Short Code:</div>
                                <div class="col-md-9">
                                    <input value="" class="validate[required]" type="text" name="username" id="username"/>
                                </div>
                            </div>

                            <div class="footer tar">
                                <input type="submit" value="Submit" class="btn btn-default">
                            </div>

                        </form>
                    </div>

                </div>
            </div>

            <div class="dr"><span></span></div>

            <div class="row">
                <div class="col-md-offset-1 col-md-8">
                    <div class="head clearfix">
                        <div class="isw-ok"></div>
                        <h1>Add Stock</h1>
                    </div>
                    <div class="block-fluid">
                        <form id="validation" method="post" >
                            <div class="row-form clearfix">
                                <div class="col-md-3">Branch Name:</div>
                                <div class="col-md-9">
                                    <input value="" class="validate[required]" type="text" name="firstname" id="firstname"/>
                                </div>
                            </div>
                            <div class="row-form clearfix">
                                <div class="col-md-3">Branch ID:</div>
                                <div class="col-md-9">
                                    <input value="" class="validate[required]" type="text" name="lastname" id="lastname"/>
                                </div>
                            </div>
                            <div class="row-form clearfix">
                                <div class="col-md-3">Short Code:</div>
                                <div class="col-md-9">
                                    <input value="" class="validate[required]" type="text" name="username" id="username"/>
                                </div>
                            </div>

                            <div class="footer tar">
                                <input type="submit" value="Submit" class="btn btn-default">
                            </div>

                        </form>
                    </div>

                </div>
            </div>

            <div class="dr"><span></span></div>
        </div>
    </div>
</div>
</body>

</html>

