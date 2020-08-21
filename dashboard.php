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

                <div class="col-md-4">

                    <div class="wBlock red clearfix">
                        <div class="dSpace">
                            <h3>Invoices statistics</h3>
                            <span class="mChartBar" sparkType="bar" sparkBarColor="white"><!--130,190,260,230,290,400,340,360,390--></span>
                            <span class="number">60%</span>
                        </div>
                        <div class="rSpace">
                            <span>$1,530 <b>amount paid</b></span>
                            <span>$2,102 <b>in queue</b></span>
                            <span>$11,100 <b>total taxes</b></span>
                        </div>
                    </div>

                </div>

                <div class="col-md-4">

                    <div class="wBlock green clearfix">
                        <div class="dSpace">
                            <h3>Users</h3>
                            <span class="mChartBar" sparkType="bar" sparkBarColor="white"><!--5,10,15,20,23,21,25,20,15,10,25,20,10--></span>
                            <span class="number">2,513</span>
                        </div>
                        <div class="rSpace">
                            <span>351 <b>active</b></span>
                            <span>2102 <b>passive</b></span>
                            <span>100 <b>removed</b></span>
                        </div>
                    </div>

                </div>

                <div class="col-md-4">

                    <div class="wBlock blue clearfix">
                        <div class="dSpace">
                            <h3>Last visits</h3>
                            <span class="mChartBar" sparkType="bar" sparkBarColor="white"><!--240,234,150,290,310,240,210,400,320,198,250,222,111,240,221,340,250,190--></span>
                            <span class="number">6,302</span>
                        </div>
                        <div class="rSpace">
                            <span>65% <b>New</b></span>
                            <span>35% <b>Returning</b></span>
                            <span>00:05:12 <b>Average time on site</b></span>
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
                        <h1>Registrations</h1>
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

                            <div class="item clearfix">
                                <div class="image"><a href="#"><img src="img/users/aqvatarius_s.jpg" width="32"/></a></div>
                                <div class="info">
                                    <a href="#" class="name">Aqvatarius</a>
                                    <div class="controls">
                                        <a href="#" class="glyphicon glyphicon-ok"></a>
                                        <a href="#" class="glyphicon glyphicon-remove"></a>
                                    </div>
                                </div>
                            </div>

                            <div class="item clearfix">
                                <div class="image"><a href="#"><img src="img/users/olga_s.jpg" width="32"/></a></div>
                                <div class="info">
                                    <a href="#" class="name">Olga</a>
                                    <div class="controls">
                                        <a href="#" class="glyphicon glyphicon-ok"></a>
                                        <a href="#" class="glyphicon glyphicon-remove"></a>
                                    </div>
                                </div>
                            </div>

                            <div class="item clearfix">
                                <div class="image"><a href="#"><img src="img/users/alexey_s.jpg" width="32"/></a></div>
                                <div class="info">
                                    <a href="#" class="name">Alexey</a>
                                    <div class="controls">
                                        <a href="#" class="glyphicon glyphicon-ok"></a>
                                        <a href="#" class="glyphicon glyphicon-remove"></a>
                                    </div>
                                </div>
                            </div>

                            <div class="item clearfix">
                                <div class="image"><a href="#"><img src="img/users/dmitry_s.jpg" width="32"/></a></div>
                                <div class="info">
                                    <a href="#" class="name">Dmitry</a>
                                    <span>approved</span>
                                </div>
                            </div>

                            <div class="item clearfix">
                                <div class="image"><a href="#"><img src="img/users/helen_s.jpg" width="32"/></a></div>
                                <div class="info">
                                    <a href="#" class="name">Helen</a>
                                    <span>approved</span>
                                </div>
                            </div>

                            <div class="item clearfix">
                                <div class="image"><a href="#"><img src="img/users/alexander_s.jpg" width="32"/></a></div>
                                <div class="info">
                                    <a href="#" class="name">Alexander</a>
                                    <span>approved</span>
                                </div>
                            </div>

                            <div class="item clearfix">
                                <div class="image"><a href="#"><img src="img/users/aqvatarius_s.jpg" width="32"/></a></div>
                                <div class="info">
                                    <a href="#" class="name">Aqvatarius</a>
                                    <span>approved</span>
                                </div>
                            </div>

                            <div class="item clearfix">
                                <div class="image"><a href="#"><img src="img/users/olga_s.jpg" width="32"/></a></div>
                                <div class="info">
                                    <a href="#" class="name">Olga</a>
                                    <span>approved</span>
                                </div>
                            </div>

                            <div class="item clearfix">
                                <div class="image"><a href="#"><img src="img/users/alexey_s.jpg" width="32"/></a></div>
                                <div class="info">
                                    <a href="#" class="name">Alexey</a>
                                    <span>approved</span>
                                </div>
                            </div>

                            <div class="item clearfix">
                                <div class="image"><a href="#"><img src="img/users/dmitry_s.jpg" width="32"/></a></div>
                                <div class="info">
                                    <a href="#" class="name">Dmitry</a>
                                    <span>approved</span>
                                </div>
                            </div>

                            <div class="item clearfix">
                                <div class="image"><a href="#"><img src="img/users/helen_s.jpg" width="32"/></a></div>
                                <div class="info">
                                    <a href="#" class="name">Helen</a>
                                    <span>approved</span>
                                </div>
                            </div>

                            <div class="item clearfix">
                                <div class="image"><a href="#"><img src="img/users/alexander_s.jpg" width="32"/></a></div>
                                <div class="info">
                                    <a href="#" class="name">Alexander</a>
                                    <span>approved</span>
                                </div>
                            </div>

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
                                <th width="25%">E-mail</th>
                                <th width="25%">Phone</th>
                                <th width="25%">Status</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td><input type="checkbox" name="checkbox"/></td>
                                <td>Dmitry</td>
                                <td>dmitry@domain.com</td>
                                <td>+98(765) 432-10-98</td>
                                <td><span class="label label-success">aviable</span></td>
                            </tr>
                            <tr>
                                <td><input type="checkbox" name="checkbox"/></td>
                                <td>Alex</td>
                                <td>alex@domain.com</td>
                                <td>+98(765) 432-10-99</td>
                                <td><span class="label label-success">aviable</span></td>
                            </tr>
                            <tr>
                                <td><input type="checkbox" name="checkbox"/></td>
                                <td>John</td>
                                <td>john@domain.com</td>
                                <td>+98(765) 432-10-97</td>
                                <td><span class="label label-warning">away</span></td>
                            </tr>
                            <tr>
                                <td><input type="checkbox" name="checkbox"/></td>
                                <td>Angelina</td>
                                <td>angelina@domain.com</td>
                                <td>+98(765) 432-10-90</td>
                                <td><span class="label label-warning">away</span></td>
                            </tr>
                            <tr>
                                <td><input type="checkbox" name="checkbox"/></td>
                                <td>Tom</td>
                                <td>tom@domain.com</td>
                                <td>+98(765) 432-10-92</td>
                                <td><span class="label label-default">offline</span></td>
                            </tr>
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
