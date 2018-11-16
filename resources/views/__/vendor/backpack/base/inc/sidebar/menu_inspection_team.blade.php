<li class="header">Operations</li>
    <li><a href="{{ url('inventory/supply') }}"><i class="fa fa-list-alt" aria-hidden="true"></i><span> Inventory </span></a></li>
    <li class="treeview">
        <a href="#"><i class="fa fa-truck" aria-hidden="true"></i><span> Delivered Items </span><i class="fa fa-angle-left pull-right"></i></a>
        <ul class="treeview-menu">
            <li><a href="{{ url('inspection/supply') }}"><li><i class="fa fa-pencil-square-o" aria-hidden="true"></i>Supplies</a></li>
            <li><a href="{{ url('inspection/property') }}"><li><i class="fa fa-laptop" aria-hidden="true"></i>Equipment and Other Property</a></li>
        </ul>
    </li>
    <li class="treeview">
        <a href="#"><i class="fa fa-paperclip" aria-hidden="true"></i><span> Inspection </span><i class="fa fa-angle-left pull-right"></i></a>
        <ul class="treeview-menu">
            <li><a href="{{ url('inspection/view/supply/') }}"><li><i class="fa fa-pencil-square-o" aria-hidden="true"></i>Supplies</a></li>
            <li><a href="{{ url('inspection/property/view') }}"><li><i class="fa fa-laptop" aria-hidden="true"></i>Equipment and Other Property</a></li>
        </ul>
    </li>