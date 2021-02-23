<?php $total = 0;$assigned=0;
    foreach ($override->getData('frame_stock') as $stock){
        $total += $stock['quantity'];
    }
foreach ($override->getData('assigned_stock') as $stock){
    $assigned += $stock['quantity'];
}
?>
<div class="menu">

    <div class="breadLine">
        <div class="arrow"></div>
        <div class="adminControl active">
            Hi, <?=$user->data()->firstname?>
        </div>
    </div>

    <div class="admin">
        <div class="image">
            <img src="img/users/no-image.jpg" class="img-thumbnail"/>
        </div>
        <ul class="control">
            <li><span class="glyphicon glyphicon-comment"></span> <a href="#">Messages</a></li>
            <li><span class="glyphicon glyphicon-cog"></span> <a href="profile.php">Profile</a></li>
            <li><span class="glyphicon glyphicon-share-alt"></span> <a href="logout.php">Logout</a></li>
        </ul>
        <div class="info">
            <span>Welcom back! Your last visit: <?=$user->data()->last_login?></span>
        </div>
    </div>

    <ul class="navigation">
        <li class="active">
            <a href="dashboard.php">
                <span class="isw-grid"></span><span class="text">Dashboard</span>
            </a>
        </li>
        <?php if($user->data()->position == 1){?>
        <li class="openable">
            <a href="#"><span class="isw-attachment"></span><span class="text">Batch</span></a>
            <ul>
                <li class="">
                    <a href="add.php?id=6">
                        <span class="glyphicon glyphicon-plus"></span><span class="text">Add Batch</span>
                    </a>
                </li>
                <li class="">
                    <a href="add.php?id=5">
                        <span class="glyphicon glyphicon-plus-sign"></span><span class="text">Add Frame Stock Batch</span>
                    </a>
                </li>
                <li class="">
                    <a href="add.php?id=13">
                        <span class="glyphicon glyphicon-plus-sign"></span><span class="text">Add Lens Stock Batch</span>
                    </a>
                </li>
                <li class="">
                    <a href="add.php?id=21">
                        <span class="glyphicon glyphicon-plus-sign"></span><span class="text">Add Accessories Batch</span>
                    </a>
                </li>
                <li class="">
                    <a href="info.php?id=7">
                        <span class="glyphicon glyphicon-grain"></span><span class="text">Manage Batch</span>
                    </a>
                </li>
            </ul>
        </li>

        <li class="openable">
            <a href="#"><span class="isw-users"></span><span class="text">Staff</span></a>
            <ul>
                <li>
                    <a href="add.php?id=1">
                        <span class="glyphicon glyphicon-user"></span><span class="text">Add staff</span>
                    </a>
                </li>
                <li>
                    <a href="info.php?id=22">
                        <span class="glyphicon glyphicon-registration-mark"></span><span class="text">Manage staff</span>
                    </a>
                </li>
            </ul>
        </li>
            <li class="openable">
                <a href="#"><span class="isw-user"></span><span class="text">Customers</span></a>
                <ul>
                    <li>
                        <a href="add.php?id=10">
                            <span class="glyphicon glyphicon-user"></span><span class="text">Add Customer</span>
                        </a>
                    </li>
                    <li>
                        <a href="info.php?id=16">
                            <span class="glyphicon glyphicon-registration-mark"></span><span class="text">Manage Customers</span>
                        </a>
                    </li>
                </ul>
            </li>

            <li class="openable">
                <a href="#"><span class="isw-right"></span><span class="text">Assign Stock</span></a>
                <ul>
                    <li>
                        <a href="add.php?id=4">
                            <span class="glyphicon glyphicon-plus"></span><span class="text">Assign Frame Stock</span>
                        </a>
                    </li>
                    <li>
                        <a href="add.php?id=14">
                            <span class="glyphicon glyphicon-zoom-in"></span><span class="text">Assign Lens Stock</span>
                        </a>
                    </li>
                    <li>
                        <a href="add.php?id=22">
                            <span class="glyphicon glyphicon-plus-sign"></span><span class="text">Assign Lens Accessories</span>
                        </a>
                    </li>
                </ul>
            </li>


        <li class="openable">
            <a href="#"><span class="isw-bookmark"></span><span class="text">Branch</span></a>
            <ul>
                <li>
                    <a href="add.php?id=2">
                        <span class="glyphicon glyphicon-plus"></span><span class="text">Add Branch</span>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <span class="glyphicon glyphicon-floppy-disk"></span><span class="text">Manage Branch</span>
                    </a>
                </li>
            </ul>
        </li>
        <li class="openable">
            <a href="#"><span class="isw-documents"></span><span class="text">Reports</span></a>
            <ul>
                <li>
                    <a href="add.php?id=8" data-toggle="modal">
                        <span class="glyphicon glyphicon-search"></span><span class="text">Search Report</span>
                    </a>
                </li>
                <li>
                    <a href="info.php?id=1">
                        <span class="glyphicon glyphicon-user"></span><span class="text">Staff Report</span>
                    </a>
                </li>
                <li>
                    <a href="info.php?id=5">
                        <span class="glyphicon glyphicon-share"></span><span class="text">Sales Report</span>
                    </a>
                </li>
                <li>
                    <a href="info.php?id=7">
                        <span class="glyphicon glyphicon-download"></span><span class="text">Purchase Report</span>
                    </a>
                </li>
                <li>
                    <a href="info.php?id=11">
                        <span class="glyphicon glyphicon-file"></span><span class="text">Cash Sales Report</span>
                    </a>
                </li>
                <li>
                    <a href="info.php?id=12">
                        <span class="glyphicon glyphicon-open-file"></span><span class="text">Credit Sales Report</span>
                    </a>
                </li>
                <li>
                    <a href="info.php?id=19">
                        <span class="glyphicon glyphicon-download-alt"></span><span class="text">Frame Returned Report</span>
                    </a>
                </li>
                <li>
                    <a href="info.php?id=17&typ=fr">
                        <span class="glyphicon glyphicon-list"></span><span class="text">Frame Payment Report</span>
                    </a>
                </li>
                <li>
                    <a href="info.php?id=17&typ=ln">
                        <span class="glyphicon glyphicon-list"></span><span class="text">Lens Payment Report</span>
                    </a>
                </li>
                <li>
                    <a href="info.php?id=21&typ=ac">
                        <span class="glyphicon glyphicon-list"></span><span class="text">Accessories Payment Report</span>
                    </a>
                </li>
                <li>
                    <a href="info.php?id=18">
                        <span class="glyphicon glyphicon-list-alt"></span><span class="text">Pending Payment Report</span>
                    </a>
                </li>
                <li>
                    <a href="info.php?id=9">
                        <span class="glyphicon glyphicon-download-alt"></span><span class="text">Stock Report</span>
                    </a>
                </li>
            </ul>
        </li>
            <li class="openable">
                <a href="#"><span class="isw-tag"></span><span class="text">Extra</span></a>
                <ul>
                    <li>
                        <a href="add.php?id=18">
                            <span class="glyphicon glyphicon-plus"></span><span class="text">Add Frame Brand</span>
                        </a>
                    </li>
                    <li>
                        <a href="add.php?id=19">
                            <span class="glyphicon glyphicon-plus-sign"></span><span class="text">Add Lens Category</span>
                        </a>
                    </li>
                    <li>
                        <a href="add.php?id=20">
                            <span class="glyphicon glyphicon-plus"></span><span class="text">Add Lens Type</span>
                        </a>
                    </li>
                    <li>
                        <a href="add.php?id=23">
                            <span class="glyphicon glyphicon-plus-sign"></span><span class="text">Add Accessories</span>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <span class="glyphicon glyphicon-list"></span><span class="text">Manage Frame Brand</span>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <span class="glyphicon glyphicon-list-alt"></span><span class="text">Manage Lens Category</span>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <span class="glyphicon glyphicon-list"></span><span class="text">Manage Lens Type</span>
                        </a>
                    </li>
                </ul>
            </li>
<!--            <li>-->
<!--                <a href="add.php?id=7">-->
<!--                    <span class="isw-text_document"></span><span class="text">Sale Frame</span>-->
<!--                </a>-->
<!--            </li>-->
<!--            <li>-->
<!--                <a href="info.php?id=13">-->
<!--                    <span class="isw-folder"></span><span class="text">My Sock</span>-->
<!--                </a>-->
<!--            </li>-->
<!--            <li>-->
<!--                <a href="info.php?id=15">-->
<!--                    <span class="isw-fullscreen"></span><span class="text">My Sales</span>-->
<!--                </a>-->
<!--            </li>-->
<!--            <li>-->
<!--                <a href="add.php?id=7">-->
<!--                    <span class="isw-text_document"></span><span class="text">Sale Frame ( Cash Customer )</span>-->
<!--                </a>-->
<!--            </li>-->
<!--            <li>-->
<!--                <a href="add.php?id=9">-->
<!--                    <span class="isw-attachment"></span><span class="text">Sale Frame ( Credit Customer )</span>-->
<!--                </a>-->
<!--            </li>-->
<!--            <li>-->
<!--                <a href="add.php?id=15">-->
<!--                    <span class="isw-attachment"></span><span class="text">Sale Lens ( Cash Customer )</span>-->
<!--                </a>-->
<!--            </li>-->
<!--            <li>-->
<!--                <a href="add.php?id=16">-->
<!--                    <span class="isw-attachment"></span><span class="text">Sale Lens ( Credit Customer )</span>-->
<!--                </a>-->
<!--            </li>-->
<!--            <li>-->
<!--                <a href="add.php?id=24">-->
<!--                    <span class="isw-attachment"></span><span class="text">Accessories (Cash Customer)</span>-->
<!--                </a>-->
<!--            </li>-->
<!--            <li>-->
<!--                <a href="add.php?id=25">-->
<!--                    <span class="isw-attachment"></span><span class="text">Accessories (Credit Customer)</span>-->
<!--                </a>-->
<!--            </li>-->
<!--            <li>-->
<!--                <a href="add.php?id=11">-->
<!--                    <span class="isw-attachment"></span><span class="text">Add Frame Payment</span>-->
<!--                </a>-->
<!--            </li>-->
<!--            <li>-->
<!--                <a href="add.php?id=17">-->
<!--                    <span class="isw-attachment"></span><span class="text">Add Lens Payment</span>-->
<!--                </a>-->
<!--            </li>-->
<!--            <li>-->
<!--                <a href="add.php?id=26">-->
<!--                    <span class="isw-attachment"></span><span class="text">Add Accessories Payment</span>-->
<!--                </a>-->
<!--            </li>-->
        <?php }elseif ($user->data()->position == 2){?>
        <li>
            <a href="add.php?id=7">
                <span class="isw-text_document"></span><span class="text">Sale Frame (Cash Customer)</span>
            </a>
        </li>
            <li>
                <a href="add.php?id=9">
                    <span class="isw-attachment"></span><span class="text">Sale Frame (Credit Customer)</span>
                </a>
            </li>
            <li>
                <a href="add.php?id=15">
                    <span class="isw-attachment"></span><span class="text">Sale Lens ( Cash Customer )</span>
                </a>
            </li>
            <li>
                <a href="add.php?id=16">
                    <span class="isw-attachment"></span><span class="text">Sale Lens ( Credit Customer )</span>
                </a>
            </li>
            <li>
                <a href="add.php?id=24">
                    <span class="isw-attachment"></span><span class="text">Accessories (Cash Customer)</span>
                </a>
            </li>
            <li>
                <a href="add.php?id=25">
                    <span class="isw-attachment"></span><span class="text">Accessories (Credit Customer)</span>
                </a>
            </li>
            <li>
                <a href="add.php?id=11">
                    <span class="isw-attachment"></span><span class="text">Add Frame Payment</span>
                </a>
            </li>
            <li>
                <a href="add.php?id=17">
                    <span class="isw-attachment"></span><span class="text">Add Lens Payment</span>
                </a>
            </li>
            <li>
                <a href="add.php?id=26">
                    <span class="isw-attachment"></span><span class="text">Add Accessories Payment</span>
                </a>
            </li>
        <li>
            <a href="info.php?id=13">
                <span class="isw-folder"></span><span class="text">My Sock</span>
            </a>
        </li>
        <li>
            <a href="info.php?id=15">
                <span class="isw-fullscreen"></span><span class="text">My Sales</span>
            </a>
        </li>
            <li>
                <a href="info.php?id=17">
                    <span class="isw-list"></span><span class="text">Payments</span>
                </a>
            </li>
            <li>
                <a href="info.php?id=18">
                    <span class="isw-documents"></span><span class="text">Pending Payments</span>
                </a>
            </li>

        <?php }?>
    </ul>

    <div class="dr"><span></span></div>

    <div class="widget-fluid">
        <div id="menuDatepicker"></div>
    </div>

    <div class="dr"><span></span></div>

    <div class="widget">

        <div class="input-group">
            <input id="appendedInputButton" class="form-control" type="text">
            <div class="input-group-btn">
                <button class="btn btn-default" type="button">Search</button>
            </div>
        </div>

    </div>

    <div class="dr"><span></span></div>

    <div class="widget-fluid">

        <div class="wBlock clearfix">
            <div class="dSpace">
                <h3>Total Frames</h3>
                <span class="number"><?=$total?></span>
                <span>5,774 <b>unique</b></span>
                <span>3,512 <b>returning</b></span>
            </div>
<!--            <div class="rSpace">-->
<!--                <h3>Today</h3>-->
<!--                <span class="mChartBar" sparkType="bar" sparkBarColor="white">240,234,150,290,310,240,210,400,320,198,250,222,111,240,221,340,250,190</span>-->
<!--                <span>&nbsp;</span>-->
<!--                <span>65% <b>New</b></span>-->
<!--                <span>35% <b>Returning</b></span>-->
<!--            </div>-->
        </div>

    </div>

    <div class="modal fade" id="fModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h4>Search Report</h4>
                </div>
                <form method="post">
                    <div class="modal-body modal-body-np">
                        <div class="row">
                            <div class="block-fluid">
                                <div class="row-form clearfix">
                                    <div class="col-md-3">Start Date:</div>
                                    <div class="col-md-9">
                                        <input value="" class="validate[required,custom[date]]" type="text" name="start" id="date"/>
                                        <span>Example: 2010-12-01</span>
                                    </div>
                                </div>
                                <div class="row-form clearfix">
                                    <div class="col-md-3">End Date:</div>
                                    <div class="col-md-9">
                                        <input value="" class="validate[required,custom[date]]" type="text" name="start" id="date"/>
                                        <span>Example: 2010-12-01</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="submit" class="btn btn-info" value="Search" aria-hidden="true">
                        <button class="btn btn-default" data-dismiss="modal" aria-hidden="true">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>