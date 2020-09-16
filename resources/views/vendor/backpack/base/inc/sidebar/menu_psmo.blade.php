<li class="header">Operations</li>
<li class="treeview">
        <a href="#"><i class="fa fa-share" aria-hidden="true"></i><span>Requesition and Issuance</span><i class="fa fa-angle-left pull-right"></i></a>
        <ul class="treeview-menu">
        <li><a href="{{ url('request/custodian/pending') }}"><i class="fa fa-edit" aria-hidden="true"></i><span>For Approval and Issuance</span></a></li>
            <li><a href="{{ url('request/custodian/approved') }}"><i class="fa fa-thumbs-up" aria-hidden="true"></i><span>For Releasing</span></a></li>            
            <li><a href="{{ url('request/custodian/released') }}"><i class="fa fa-angle-double-right" aria-hidden="true"></i><span>Released Requests</span></a></li>
            <li><a href="{{ url('request/custodian/disapproved') }}"><i class="fa fa-times-circle" aria-hidden="true"></i><span>Disapproved/Expired/<br/>&emsp;&nbsp;&nbsp;Cancelled</span></a></li>
        </ul>
    </li>
<li class="treeview">
        <a href="#"><i class="fa fa-paperclip" aria-hidden="true"></i><span>Item Delivery</span><i class="fa fa-angle-left pull-right"></i></a>
        <ul class="treeview-menu">
            <li><a href="{{ url('delivery/supplies') }}"><li><i class="fa fa-pencil-square-o" aria-hidden="true"></i>Supplies</a></li>
            <!-- <li><a href="{{ url('delivery/property') }}"><li><i class="fa fa-laptop" aria-hidden="true"></i>Equipment and Other Property</a></li> -->
        </ul>
    </li>
    <li class="treeview">
        <a href="#"><i class="fa fa-folder-open" aria-hidden="true"></i><span>Inventory</span><i class="fa fa-angle-left pull-right"></i></a>
        <ul class="treeview-menu">
            <!-- <li><a href="{{ url('adjustment') }}"><li><i class="fa fa-sliders" aria-hidden="true"></i>Adjustment</a></li> -->
            <li><a href="{{ url('inventory/supply') }}"><i class="fa fa-list-alt" aria-hidden="true"></i><span> Inventory </span></a></li>
            <li><a href="{{ url('inventory/physical') }}"><i class="fa fa-archive" aria-hidden="true"></i> <span> Physical Count</span></a></li>
        </ul>
    </li>
    <li class="treeview"><a href="#"><i class="fa fa-sliders" aria-hidden="true"></i><span>Adjustment</span><i class="fa fa-angle-left pull-right"></i></a>
        <ul class="treeview-menu">
            <li><a href="{{ url('inventory/supply/stockcard/release') }}"><li><i class="fa fa-file" aria-hidden="true"></i>Late Entry</a></li>
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
    <li><a href="{{ url('reports/rsmi') }}"><i class="fa fa-ticket" aria-hidden="true"></i> <span> RSMI </span></a></li>
    <li><a href="{{ url('reports/summary') }}"><i class="fa fa-ticket" aria-hidden="true"></i> <span> Summary Report </span></a></li>

<li class="header">Queries</li>
    <li><a href="{{ url('purchaseorder') }}"><i class="fa fa-shopping-bag" aria-hidden="true"></i> <span> References </span></a></li>
    <li><a href="{{ url('receipt') }}"><i class="fa fa-truck" aria-hidden="true"></i> <span> Receipts </span></a></li>
    <li><a href="{{ url('reports/rislist') }}"><i class="fa fa-list-ul" aria-hidden="true"></i> <span> RIS List </span></a></li>