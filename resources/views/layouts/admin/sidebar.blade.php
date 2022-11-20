<div class="main-sidebar">
        <aside id="sidebar-wrapper">
          <div class="sidebar-brand">
            <a href="/" target="_blank">DTKS</a>
          </div>
          <div class="sidebar-brand sidebar-brand-sm">
            <a href="/">DTKS</a>
          </div>

          <ul class="sidebar-menu">
              <li class="@if(Request::url() === route('admin.dashboard')) active @endif"><a href="{{ route('admin.dashboard') }}" class="nav-link"><i class="fas fa-rocket"></i> <span>Dashboard</span></a></li>

              @if(Auth::user()->role !== 'psm')
                <li class="@if(Request::url() === route('warga.index')) active @endif"><a class="nav-link" href="{{ route('warga.index') }}"><i class="fas fa-clipboard-list"></i> <span>Data Warga</span></a></li>
              @endif

              <li class="@if(Request::url() === route('dtks.index')) active @endif"><a class="nav-link" href="{{ route('dtks.index') }}"><i class="fas fa-table"></i> <span>Data DTKS</span></a></li>

              @if(Auth::user()->role !== 'rt')
                <li class="@if(Request::url() === route('alternatif.index')) active @endif"><a class="nav-link" href="{{ route('alternatif.index') }}"><i class="fas fa-table"></i> <span>Data Alternatif</span></a></li>
              @endif

              <li class="@if(Request::url() === route('bansos.index')) active @endif"><a class="nav-link" href="{{ route('bansos.index') }}"><i class="fas fa-layer-group"></i> <span>Bansos</span></a></li>

              @php
                  $kelayakan = [route('rt.index'), route('psm.index'), route('kelurahan.index')];
                  $isSelected = in_array(Request::url(), $kelayakan);
              @endphp

              <li class="dropdown @if($isSelected) active @endif">
                <a href="#" class="nav-link has-dropdown">
                  <i class="fas fa-users"></i><span>AHP</span>
                </a>

                <ul class="dropdown-menu" style="display: none;">
                  @if(Auth::user()->role === 'rt' || Auth::user()->role === 'admin')
                    <li class="@if(Request::url() === route('rt.index')) active @endif"><a class="nav-link" href="{{ route('rt.index') }}">RT</a></li>
                  @endif

                  @if(Auth::user()->role === 'psm' || Auth::user()->role === 'admin')
                    <li class="@if(Request::url() === route('psm.index')) active @endif"><a class="nav-link" href="{{ route('psm.index') }}">PSM</a></li>
                  @endif

                  @if(Auth::user()->role === 'kelurahan' || Auth::user()->role === 'admin')
                    <li class="@if(Request::url() === route('kelurahan.index')) active @endif"><a class="nav-link" href="{{ route('kelurahan.index') }}">Kelurahan</a></li>
                  @endif
                </ul>
              </li>

              @if(! in_array(Auth::user()->role, ['rt', 'psm']))
                <li class="@if(Request::url() === route('borda.index')) active @endif"><a class="nav-link" href="{{ route('borda.index') }}"><i class="fas fa-list-alt"></i> <span>Borda</span></a></li>
              @endif

              {{-- <li class="@if(Request::url() === route('example.index')) active @endif"><a href="{{ route('example.index') }}" class="nav-link"><i class="fas fa-rocket"></i> <span>Example</span></a></li> --}}
          </ul>
        </aside>
      </div>
