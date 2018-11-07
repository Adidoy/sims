<li class="header">Operations</li>
    <li><a href="{{ url('inventory/supply') }}"><i class="fa fa-list-alt" aria-hidden="true"></i><span> Inventory </span></a></li>
    <li><a href="{{ url('inventory/physical') }}"><i class="fa fa-archive" aria-hidden="true"></i> <span> Physical Inventory </span></a></li>
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
    <li class="treeview">
        <a href="#"><i class="fa fa-share" aria-hidden="true"></i><span>Requesition and Issuance</span><i class="fa fa-angle-left pull-right"></i></a>
        <ul class="treeview-menu">
            <li><a href="{{ url('request') }}"><li><i class="fa fa-list" aria-hidden="true"></i>View</a></li>
            <li><a href="@if(Auth::user()->access == 1) {{ url('inventory/supply/stockcard/release') }} @endif"><li><i class="fa fa-pencil" aria-hidden="true"></i>Allocate</a></li>
        </ul>
    </li>
    <li class="treeview"><a href="#"><i class="fa fa-sliders" aria-hidden="true"></i><span>Adjustment</span><i class="fa fa-angle-left pull-right"></i></a>
        <ul class="treeview-menu">
            <li><a href="{{ url('adjustment') }}"><li><i class="fa fa-eye" aria-hidden="true"></i>View</a></li>
            <li><a href="{{ url('adjustment/dispose') }}"><li><i class="fa fa-trash-o" aria-hidden="true"></i>Disposal</a></li>
            <li><a href="{{ url('adjustment/return') }}"><li><i class="fa fa-pencil" aria-hidden="true"></i>Return</a></li>
        </ul>
    </li>
          
<li class="header">Information System</li>
    <li><a href="{{ url('announcement') }}"><i class="fa fa-bullhorn" aria-hidden="true"></i> <span> Announcement </span></a></li>
    <li><a href="{{ url('maintenance/supply') }}"><i class="fa fa-database" aria-hidden="true"></i> <span> Supply</span></a></li>
    <li><a href="{{ url('maintenance/unit') }}"><i class="fa fa-balance-scale" aria-hidden="true"></i> <span> Unit </span></a></li>
    <li><a href="{{ url('maintenance/supplier') }}"><i class="fa fa-truck" aria-hidden="true"></i> <span> Supplier </span></a></li>

<li class="header">Reports</li>
    <li><a href="{{ url('rsmi') }}"><i class="fa fa-ticket" aria-hidden="true"></i> <span> RSMI </span></a></li>

<li class="header">Queries</li>
    <li><a href="{{ url('purchaseorder') }}"><i class="fa fa-shopping-bag" aria-hidden="true"></i> <span> References </span></a></li>
    <li><a href="{{ url('receipt') }}"><i class="fa fa-truck" aria-hidden="true"></i> <span> Receipts </span></a></li>
    <li><a href="{{ url('reports/rislist') }}"><i class="fa fa-list-ul" aria-hidden="true"></i> <span> RIS List </span></a></li>