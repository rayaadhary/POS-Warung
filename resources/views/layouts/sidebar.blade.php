    <aside class="main-sidebar">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">

            <!-- /.search form -->
            <!-- sidebar menu: : style can be found in sidebar.less -->
            <ul class="sidebar-menu" data-widget="tree">
                <li class="header">MENU</li>
                <li class="{{ request()->is('dashboard') ? 'active' : '' }}">
                    <a href="{{ route('dashboard') }}">
                        <i class="fa fa-dashboard"></i> <span>Dashboard</span>
                    </a>
                </li>
                </li>
                <li class="header">MASTER</li>
                <li class="{{ request()->is('kategori') ? 'active' : '' }}">
                    <a href="{{ route('kategori.index') }}">
                        <i class="fa fa-dashboard"></i> <span>Kategori</span>
                    </a>
                </li>
                <li class="{{ request()->is('produk') ? 'active' : '' }}">
                    <a href="{{ route('produk.index') }}">
                        <i class="fa fa-cubes"></i> <span>Produk</span>
                    </a>
                </li>
                <li class="{{ request()->is('supplier') ? 'active' : '' }}">
                    <a href="{{ route('supplier.index') }}">
                        <i class="fa fa-truck"></i> <span>Supplier</span>
                    </a>
                </li>
                <li class="header">TRANSAKSI</li>
                <li class="{{ request()->is('pengeluaran') ? 'active' : '' }}">
                    <a href="{{ route('pengeluaran.index') }}">
                        <i class="fa fa-money"></i> <span>Pengeluaran</span>
                    </a>
                </li>
                <li class="{{ request()->is('pembelian') ? 'active' : '' }}">
                    <a href="{{ route('pembelian.index') }}">
                        <i class="fa fa-download"></i> <span>Pembelian</span>
                    </a>
                </li>

                <li class="{{ request()->is('penjualan') ? 'active' : '' }}">
                    <a href="{{ route('penjualan.index') }}">
                        <i class="fa fa-upload"></i> <span>Penjualan</span>
                    </a>
                </li>
                @if (auth()->user()->roles == 'pemilik')
                    <li class="header">LAPORAN</li>
                    <li class="{{ request()->is('laporan') ? 'active' : '' }}">

                        <a href="{{ route('laporan.index') }}">
                            <i class="fa fa-file-pdf-o"></i> <span>Laporan</span>

                        </a>
                    </li>
                @endif
                <li class="header">SISTEM</li>
                <li class="{{ request()->is('pengguna') ? 'active' : '' }}">
                    <a href="{{ route('pengguna.index') }}">
                        <i class="fa fa-users"></i> <span>Pengguna</span>
                    </a>
                </li>


            </ul>
        </section>
        <!-- /.sidebar -->
    </aside>
