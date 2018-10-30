<li class="header">Operations</li>
    <li><a href="{{ url('inventory/supply') }}"><i class="fa fa-list-alt" aria-hidden="true"></i><span> Inventory </span></a></li>
    <li class="treeview">
        <a href="#"><i class="fa fa-share" aria-hidden="true"></i><span>Requesition and Issuance</span><i class="fa fa-angle-left pull-right"></i></a>
        <ul class="treeview-menu">
            <li><a href="{{ url('request') }}"><li><i class="fa fa-list" aria-hidden="true"></i>View</a></li>
            <li><a href="{{ url('inventory/supply/stockcard/release') }}"><li><i class="fa fa-pencil" aria-hidden="true"></i>Allocate</a></li>
        </ul>
    </li>