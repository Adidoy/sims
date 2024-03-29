@if (Auth::check())
    <!-- Left side column. contains the sidebar -->
    <aside class="main-sidebar">
      <!-- sidebar: style can be found in sidebar.less -->
      <section class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel">
          <div class="row">
            <div class="col-sm-12">
              <div class="text-center">
                <p style="color:#FFFFFF;">
                  {{ strtoupper(Auth::user()->firstname) }} {{strtoupper(Auth::user()->lastname) }}
                  <br />
                  {{  substr(ucfirst(Auth::user()->position), 0, 30) . ((strlen(Auth::user()->position) > 15) ? "..." : "") }}
                </p>
              </div>
            </div>
          </div>
        </div>
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu"  style="width=700px">
          <li class="header">Supplies Inventory</li>
          <!-- ================================================ -->
          <!-- ==== Recommended place for admin menu items ==== -->
          <!-- ================================================ -->

          <li><a href="{{ url(config('backpack.base.route_prefix', 'admin').'/dashboard') }}"><i class="fa fa-dashboard"></i> <span>{{ trans('backpack::base.dashboard') }}</span></a></li>

          @if(false)

          <li><a href="{{ url(config('backpack.base.route_prefix', 'admin').'/elfinder') }}"><i class="fa fa-files-o"></i> <span>File manager</span></a></li>
          <li><a href="{{ url(config('backpack.base.route_prefix', 'admin').'/language') }}"><i class="fa fa-flag-o"></i> <span>Languages</span></a></li>
          <li><a href="{{ url(config('backpack.base.route_prefix', 'admin').'/language/texts') }}"><i class="fa fa-language"></i> <span>Language Files</span></a></li>
          <li><a href="{{ url(config('backpack.base.route_prefix', 'admin').'/log') }}"><i class="fa fa-terminal"></i> <span>Logs</span></a></li>
          <li><a href="{{ url(config('backpack.base.route_prefix', 'admin').'/setting') }}"><i class="fa fa-cog"></i> <span>Settings</span></a></li>
          <li><a href="{{ url(config('backpack.base.route_prefix', 'admin').'/page') }}"><i class="fa fa-file-o"></i> <span>Pages</span></a></li>

          @endif
          @if(Auth::user()->access == 0)

          <li><a href="{{ url(config('backpack.base.route_prefix', 'admin').'/backup') }}"><i class="fa fa-hdd-o"></i> <span>Backups</span></a></li>
          <li><a href="{{ url('sync') }}"><i class="fa fa-refresh"></i> <span>Sync</span></a></li>

          <li class="header">Information System</li>
          <li><a href="{{ url('maintenance/office') }}"><i class="fa fa-home" aria-hidden="true"></i> <span> Office </span></a></li>

          <li class="header">Utilities</li>
          <li><a href="{{ url('account') }}"><i class="fa fa-users" aria-hidden="true"></i> <span>Accounts</span></a></li>
          <li><a href="{{ url('audittrail') }}"><i class="fa fa-history" aria-hidden="true"></i> <span>Audit Trail</span></a></li>
          <li><a href="{{ url('import') }}"><i class="fa fa-upload" aria-hidden="true"></i> <span>Import</span></a></li>
          @endif
          
          @if(Auth::user()->access == 1)
            @include('vendor.backpack.base.inc.sidebar.menu_psmo')
          @endif
          
          @if(Auth::user()->access == 2)
            @include('vendor.backpack.base.inc.sidebar.menu_accounting')
          @endif
          
          @if(Auth::user()->access == 3)
          @include('vendor.backpack.base.inc.sidebar.menu_offices')
          @endif
          
          @if(Auth::user()->access == 4)
            @include('vendor.backpack.base.inc.sidebar.menu_psmo_chief_supplies')
          @endif
          
          @if(Auth::user()->access == 5)
          <li><a href="{{ url('inventory/physical') }}"><i class="fa fa-archive" aria-hidden="true"></i> <span> Physical Inventory </span></a></li>
          <li><a href="{{ url('inspection') }}"><i class="fa fa-search" aria-hidden="true"></i> <span> Inspection </span></a></li>
          @endif
          
          @if(Auth::user()->access == 6)
            @include('vendor.backpack.base.inc.sidebar.menu_psmo_releasing')
          @endif
          
          @if(Auth::user()->access == 7)
          <li><a href="{{ url('inventory/supply') }}"><i class="fa fa-list-alt" aria-hidden="true"></i> <span> Inventory </span></a></li>
          <li><a href="{{ url('inventory/supply/stockcard/accept') }}"><i class="fa fa-plus" aria-hidden="true"></i> <span> Accepts Item </span></a></li>
          
          <li class="header">Information System</li>
          <li><a href="{{ url('announcement') }}"><i class="fa fa-bullhorn" aria-hidden="true"></i> <span> Announcement </span></a></li>
          <li><a href="{{ url('maintenance/supply') }}"><i class="fa fa-database" aria-hidden="true"></i> <span> Supply</span></a></li>
          <li><a href="{{ url('maintenance/unit') }}"><i class="fa fa-balance-scale" aria-hidden="true"></i> <span> Unit </span></a></li>
          <li><a href="{{ url('maintenance/supplier') }}"><i class="fa fa-truck" aria-hidden="true"></i> <span> Supplier </span></a></li>
          @endif
          
          @if(Auth::user()->access == 8)
          <li><a href="{{ url('inventory/supply') }}"><i class="fa fa-list-alt" aria-hidden="true"></i> <span> Inventory </span></a></li>
          <li class="treeview"><a href="#"><i class="fa fa-sliders" aria-hidden="true"></i><span>Adjustment</span><i class="fa fa-angle-left pull-right"></i></a>
              <ul class="treeview-menu">
                <li><a href="{{ url('adjustment') }}"><li><i class="fa fa-eye" aria-hidden="true"></i>View</a></li>
                <li><a href="{{ url('adjustment/dispose') }}"><li><i class="fa fa-trash-o" aria-hidden="true"></i>Disposal</a></li>
                <li><a href="{{ url('adjustment/return') }}"><li><i class="fa fa-pencil" aria-hidden="true"></i>Return</a></li>
              </ul>
          </li>
          <li class="header">Information System</li>
          <li><a href="{{ url('announcement') }}"><i class="fa fa-bullhorn" aria-hidden="true"></i> <span> Announcement </span></a></li>
          @endif

          @if((Auth::user()->access == 10)||(Auth::user()->access == 9))
            @include('vendor.backpack.base.inc.sidebar.menu_inspection_team')
          @endif
          <!-- ======================================= -->
          <li class="header">{{ trans('backpack::base.user') }}</li>
          <!-- @if(Auth::user()->access == 0)
          <li><a href="{{ url('clientfeedback') }}"><i class="fa fa-twitch" aria-hidden="true"></i><span> Comments and Suggestion</span></a></li>
          @else
          <li><a href="{{ url('clientfeedback/create') }}"><i class="fa fa-twitch" aria-hidden="true"></i><span> Comments and Suggestion</span></a></li>
          @endif -->
          <li><a href="{{ url('settings') }}"><i class="fa fa-user-o" aria-hidden="true"></i> <span> Settings</span></a></li>

          <li><a href="{{ url(config('backpack.base.route_prefix', 'admin').'/logout') }}"><i class="fa fa-sign-out"></i> <span>{{ trans('backpack::base.logout') }}</span></a></li>
        </ul>
      </section>
      <!-- /.sidebar -->
    </aside>
@endif
