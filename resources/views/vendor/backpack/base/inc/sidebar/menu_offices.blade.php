<li class="header">Operations</li>
    <li class="treeview">
        <a href="#"><i class="fa fa-share" aria-hidden="true"></i><span>Requests</span><i class="fa fa-angle-left pull-right"></i></a>
        <ul class="treeview-menu">
            <li><a href="{{ url('request/client/create') }}"><i class="fa fa-plus-square" aria-hidden="true"></i><span>Create New Request</span></a></li>
            <li><a href="{{ url('request/client/pending') }}"><i class="fa fa-edit" aria-hidden="true"></i><span>Pending</span></a></li>
            <li><a href="{{ url('request/client/approved') }}"><i class="fa fa-thumbs-up" aria-hidden="true"></i><span>Approved</span></a></li>            
            <li><a href="{{ url('request/client/released') }}"><i class="fa fa-angle-double-right" aria-hidden="true"></i><span>Released</span></a></li>
            <li><a href="{{ url('request/client/disapproved') }}"><i class="fa fa-times-circle" aria-hidden="true"></i><span>Disapproved/Expired/<br/>&emsp;&nbsp;&nbsp;Cancelled</span></a></li>
        </ul>
    </li>