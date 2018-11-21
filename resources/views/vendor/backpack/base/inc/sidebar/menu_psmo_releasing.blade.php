<li class="header">Operations</li>
    <li><a href="{{ url('inventory/supply') }}"><i class="fa fa-list-alt" aria-hidden="true"></i><span> Inventory </span></a></li>
    <li class="treeview">
        <a href="#"><i class="fa fa-share" aria-hidden="true"></i><span>Requesition and Issuance</span><i class="fa fa-angle-left pull-right"></i></a>
        <ul class="treeview-menu">
        <li><a href="{{ url('request/custodian/?type=pending') }}"><i class="fa fa-edit" aria-hidden="true"></i><span>For Approval and Issuance</span></a></li>
            <li><a href="{{ url('request/custodian/?type=approved') }}"><i class="fa fa-thumbs-up" aria-hidden="true"></i><span>For Releasing</span></a></li>            
            <li><a href="{{ url('request/custodian/?type=released') }}"><i class="fa fa-angle-double-right" aria-hidden="true"></i><span>Released Requests</span></a></li>
            <li><a href="{{ url('request/custodian/?type=disapproved') }}"><i class="fa fa-times-circle" aria-hidden="true"></i><span>Disapproved/Expired/<br/>&emsp;&nbsp;&nbsp;Cancelled</span></a></li>
            <li><a href="{{ url('request') }}"><li><i class="fa fa-list" aria-hidden="true"></i>View</a></li>
            <li><a href="@if(Auth::user()->access == 1) {{ url('inventory/supply/stockcard/release') }} @endif"><li><i class="fa fa-pencil" aria-hidden="true"></i>Allocate</a></li>
        </ul>
    </li>