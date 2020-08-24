<?php
require_once'php/core/init.php';
$user = new User();
$override = new OverideData();
$email = new Email();
$random = new Random();

$successMessage=null;$pageError=null;$errorMessage=null;
$users = $override->getData('user');
if($user->isLoggedIn()) {

}else{
    Redirect::to('index.php');
}
?>
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
                <li><a href="#">Dashboard</a> <span class="divider">></span></li>
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
                            <?php if($user->data()->accessLevel == 1){foreach ($override->getData('user') as $usr){?>
                                <div class="item clearfix">
                                    <div class="image"><a href="#"><img src="img/users/no-image.jpg" width="32"/></a></div>
                                    <div class="info">
                                        <a href="#" class="name"><?=$usr['firstname'].' '.$usr['lastname']?></a>
                                        <span></span>
                                    </div>
                                </div>
                            <?php }}?>
                        </div>
                        <div class="footer">
                            <a href="add.php?id=1" class="btn btn-default" type="button">Add new</a>
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

                <div class="col-md-4">

                    <div class="wBlock red clearfix">
                        <div class="dSpace">
                            <h3>Total Stock</h3>
                            <span class="mChartBar" sparkType="bar" sparkBarColor="white"><!--130,190,260,230,290,400,340,360,390--></span>
                            <span class="number"><?=$total?></span>
                        </div>
                    </div>

                </div>

                <div class="col-md-4">

                    <div class="wBlock green clearfix">
                        <div class="dSpace">
                            <h3>Assigned Stock</h3>
                            <span class="mChartBar" sparkType="bar" sparkBarColor="white"><!--5,10,15,20,23,21,25,20,15,10,25,20,10--></span>
                            <span class="number"><?=$assigned?></span>
                        </div>
                    </div>

                </div>

                <div class="col-md-4">

                    <div class="wBlock blue clearfix">
                        <div class="dSpace">
                            <h3>Sold</h3>
                            <span class="mChartBar" sparkType="bar" sparkBarColor="white"><!--240,234,150,290,310,240,210,400,320,198,250,222,111,240,221,340,250,190--></span>
                            <span class="number">0</span>
                        </div>

                    </div>

                </div>
            </div>

            <div class="dr"><span></span></div>

            <div class="row">

                <div class="col-md-4">
                    <div class="head clearfix">
                        <div class="isw-archive"></div>
                        <h1>Orders</h1>
                        <ul class="buttons">
                            <li>
                                <a href="#" class="isw-settings"></a>
                                <ul class="dd-list">
                                    <li><a href="#"><span class="isw-list"></span> Show all</a></li>
                                    <li><a href="#"><span class="isw-ok"></span> Approved</a></li>
                                    <li><a href="#"><span class="isw-minus"></span> Unapproved</a></li>
                                    <li><a href="#"><span class="isw-refresh"></span> Refresh</a></li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                    <div class="block-fluid accordion">

                        <h3>November 2012</h3>
                        <div>
                            <table cellpadding="0" cellspacing="0" width="100%" class="sOrders">
                                <thead>
                                <tr>
                                    <th width="60">Date</th><th>User</th><th width="60">Price</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td><span class="date">Nov 6</span><span class="time">12:35</span></td>
                                    <td><a href="#">Aqvatarius</a></td>
                                    <td><span class="price">$1366.12</span></td>
                                </tr>
                                <tr>
                                    <td><span class="date">Nov 8</span><span class="time">18:42</span></td>
                                    <td><a href="#">Olga</a></td>
                                    <td><span class="price">$146.00</span></td>
                                </tr>
                                <tr>
                                    <td><span class="date">Nov 15</span><span class="time">8:21</span></td>
                                    <td><a href="#">Alex</a></td>
                                    <td><span class="price">$879.24</span></td>
                                </tr>
                                </tbody>
                                <tfoot>
                                <tr>
                                    <td colspan="3" align="right"><button class="btn btn-default btn-sm">More...</button></td>
                                </tr>
                                </tfoot>
                            </table>
                        </div>

                        <h3>October 2012</h3>
                        <div>
                            <table cellpadding="0" cellspacing="0" width="100%" class="sOrders">
                                <thead>
                                <tr>
                                    <th width="60">Date</th><th>User</th><th width="60">Price</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td><span class="date">Oct 6</span><span class="time">12:35</span></td>
                                    <td><a href="#">Aqvatarius</a></td>
                                    <td><span class="price">$1366.12</span></td>
                                </tr>
                                <tr>
                                    <td><span class="date">Oct 8</span><span class="time">18:42</span></td>
                                    <td><a href="#">Olga</a></td>
                                    <td><span class="price">$146.00</span></td>
                                </tr>
                                <tr>
                                    <td><span class="date">Oct 15</span><span class="time">8:21</span></td>
                                    <td><a href="#">Alex</a></td>
                                    <td><span class="price">$879.24</span></td>
                                </tr>
                                </tbody>
                                <tfoot>
                                <tr>
                                    <td colspan="3" align="right"><button class="btn btn-default btn-sm">More...</button></td>
                                </tr>
                                </tfoot>
                            </table>
                        </div>

                        <h3>September 2012</h3>
                        <div>
                            <table cellpadding="0" cellspacing="0" width="100%" class="sOrders">
                                <thead>
                                <tr>
                                    <th width="60">Date</th><th>User</th><th width="60">Price</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td><span class="date">Sep 6</span><span class="time">12:35</span></td>
                                    <td><a href="#">Aqvatarius</a></td>
                                    <td><span class="price">$1366.12</span></td>
                                </tr>
                                <tr>
                                    <td><span class="date">Sep 8</span><span class="time">18:42</span></td>
                                    <td><a href="#">Olga</a></td>
                                    <td><span class="price">$146.00</span></td>
                                </tr>
                                <tr>
                                    <td><span class="date">Sep 15</span><span class="time">8:21</span></td>
                                    <td><a href="#">Alex</a></td>
                                    <td><span class="price">$879.24</span></td>
                                </tr>
                                </tbody>
                                <tfoot>
                                <tr>
                                    <td colspan="3" align="right"><button class="btn btn-default btn-sm">More...</button></td>
                                </tr>
                                </tfoot>
                            </table>
                        </div>

                    </div>
                </div>

                <div class="col-md-4">
                    <div class="head clearfix">
                        <div class="isw-edit"></div>
                        <h1>Latest news</h1>
                        <ul class="buttons">
                            <li>
                                <a href="#" class="isw-text_document"></a>
                            </li>
                            <li>
                                <a href="#" class="isw-settings"></a>
                                <ul class="dd-list">
                                    <li><a href="#"><span class="isw-list"></span> Show all</a></li>
                                    <li><a href="#"><span class="isw-edit"></span> Add new</a></li>
                                    <li><a href="#"><span class="isw-refresh"></span> Refresh</a></li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                    <div class="block news scrollBox">

                        <div class="scroll" style="height: 270px;">

                            <div class="item">
                                <a href="#">Lorem ipsum dolor sit amet, consectetur adipiscing elit.</a>
                                <p>Phasellus ut diam quis dolor mollis tristique. Suspendisse vestibulum convallis felis vitae facilisis. Praesent eu nisi vestibulum erat lacinia sollicitudin. Cras nec risus dolor, ut tristique neque. Donec mauris sapien, pellentesque at porta id, varius eu tellus.</p>
                                <span class="date">02.11.2012 14:23</span>
                                <div class="controls">
                                    <a href="#" class="glyphicon glyphicon-pencil tip" title="Edit"></a>
                                    <a href="#" class="glyphicon glyphicon-trash tip" title="Remove"></a>
                                </div>
                            </div>

                            <div class="item">
                                <a href="#">Lorem ipsum dolor sit amet, consectetur adipiscing elit.</a>
                                <p>Phasellus ut diam quis dolor mollis tristique. Suspendisse vestibulum convallis felis vitae facilisis. Praesent eu nisi vestibulum erat lacinia sollicitudin. Cras nec risus dolor, ut tristique neque. Donec mauris sapien, pellentesque at porta id, varius eu tellus.</p>
                                <span class="date">02.11.2012 14:23</span>
                                <div class="controls">
                                    <a href="#" class="glyphicon glyphicon-pencil tip" title="Edit"></a>
                                    <a href="#" class="glyphicon glyphicon-trash tip" title="Remove"></a>
                                </div>
                            </div>

                            <div class="item">
                                <a href="#">Lorem ipsum dolor sit amet, consectetur adipiscing elit.</a>
                                <p>Phasellus ut diam quis dolor mollis tristique. Suspendisse vestibulum convallis felis vitae facilisis. Praesent eu nisi vestibulum erat lacinia sollicitudin. Cras nec risus dolor, ut tristique neque. Donec mauris sapien, pellentesque at porta id, varius eu tellus.</p>
                                <span class="date">02.11.2012 14:23</span>
                                <div class="controls">
                                    <a href="#" class="glyphicon glyphicon-pencil tip" title="Edit"></a>
                                    <a href="#" class="glyphicon glyphicon-trash tip" title="Remove"></a>
                                </div>
                            </div>

                            <div class="item">
                                <a href="#">Lorem ipsum dolor sit amet, consectetur adipiscing elit.</a>
                                <p>Phasellus ut diam quis dolor mollis tristique. Suspendisse vestibulum convallis felis vitae facilisis. Praesent eu nisi vestibulum erat lacinia sollicitudin. Cras nec risus dolor, ut tristique neque. Donec mauris sapien, pellentesque at porta id, varius eu tellus.</p>
                                <span class="date">02.11.2012 14:23</span>
                                <div class="controls">
                                    <a href="#" class="glyphicon glyphicon-pencil tip" title="Edit"></a>
                                    <a href="#" class="glyphicon glyphicon-trash tip" title="Remove"></a>
                                </div>
                            </div>

                            <div class="item">
                                <a href="#">Lorem ipsum dolor sit amet, consectetur adipiscing elit.</a>
                                <p>Phasellus ut diam quis dolor mollis tristique. Suspendisse vestibulum convallis felis vitae facilisis. Praesent eu nisi vestibulum erat lacinia sollicitudin. Cras nec risus dolor, ut tristique neque. Donec mauris sapien, pellentesque at porta id, varius eu tellus.</p>
                                <span class="date">02.11.2012 14:23</span>
                                <div class="controls">
                                    <a href="#" class="glyphicon glyphicon-pencil tip" title="Edit"></a>
                                    <a href="#" class="glyphicon glyphicon-trash tip" title="Remove"></a>
                                </div>
                            </div>

                            <div class="item">
                                <a href="#">Lorem ipsum dolor sit amet, consectetur adipiscing elit.</a>
                                <p>Phasellus ut diam quis dolor mollis tristique. Suspendisse vestibulum convallis felis vitae facilisis. Praesent eu nisi vestibulum erat lacinia sollicitudin. Cras nec risus dolor, ut tristique neque. Donec mauris sapien, pellentesque at porta id, varius eu tellus.</p>
                                <span class="date">02.11.2012 14:23</span>
                                <div class="controls">
                                    <a href="#" class="glyphicon glyphicon-pencil tip" title="Edit"></a>
                                    <a href="#" class="glyphicon glyphicon-trash tip" title="Remove"></a>
                                </div>
                            </div>

                        </div>

                    </div>
                </div>

                <div class="col-md-4">
                    <div class="head clearfix">
                        <div class="isw-cloud"></div>
                        <h1>Staff</h1>
                        <ul class="buttons">
                            <li>
                                <a href="#" class="isw-users"></a>
                            </li>
                            <li>
                                <a href="#" class="isw-settings"></a>
                                <ul class="dd-list">
                                    <li><a href="#"><span class="isw-list"></span> Show all</a></li>
                                    <li><a href="#"><span class="isw-mail"></span> Send mail</a></li>
                                    <li><a href="#"><span class="isw-refresh"></span> Refresh</a></li>
                                </ul>
                            </li>
                            <li class="toggle"><a href="#"></a></li>
                        </ul>
                    </div>
                    <div class="block users scrollBox">

                        <div class="scroll" style="height: 270px;">
                            <?php foreach ($users as $user){?>
                                <div class="item clearfix">
                                    <div class="image"><a href="#"><img src="img/users/no-image.jpg" width="32"/></a></div>
                                    <div class="info">
                                        <a href="#" class="name"><?=$user['firstname'].' '.$user['lastname']?></a>
                                        <div class="controls">
                                            <a href="#" class="glyphicon glyphicon-ok"></a>
                                            <a href="#" class="glyphicon glyphicon-remove"></a>
                                        </div>
                                    </div>
                                </div>
                            <?php }?>

                        </div>

                    </div>
                </div>

            </div>

            <div class="dr"><span></span></div>

            <div class="dr"><span></span></div>

            <div class="row">

                <div class="col-md-12">
                    <div class="head clearfix">
                        <div class="isw-grid"></div>
                        <h1>Simple table</h1>
                        <ul class="buttons">
                            <li><a href="#" class="isw-download"></a></li>
                            <li><a href="#" class="isw-attachment"></a></li>
                            <li>
                                <a href="#" class="isw-settings"></a>
                                <ul class="dd-list">
                                    <li><a href="#"><span class="isw-plus"></span> New document</a></li>
                                    <li><a href="#"><span class="isw-edit"></span> Edit</a></li>
                                    <li><a href="#"><span class="isw-delete"></span> Delete</a></li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                    <div class="block-fluid">
                        <table cellpadding="0" cellspacing="0" width="100%" class="table">
                            <thead>
                            <tr>
                                <th><input type="checkbox" name="checkall"/></th>
                                <th width="25%">Name</th>
                                <th width="25%">Sold</th>
                                <th width="25%">Remained</th>
                                <th width="25%">Total</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($override->getData('assigned_stock') as $aStock){
                                $staff=$override->get('user','id',$aStock['user_id'])?>
                                <tr>
                                    <td><input type="checkbox" name="checkbox"/></td>
                                    <td><?=$staff[0]['firstname'].' '.$staff[0]['lastname']?></td>
                                    <td>0</td>
                                    <td><?=$aStock['quantity']?></td>
                                    <td><?=$aStock['quantity']?></td>
                                </tr>
                            <?php }?>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>


            <div class="dr"><span></span></div>

        </div>

    </div>
</div>
</body>

</html>
