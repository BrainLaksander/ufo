<header style="padding:1rem; background:#f5f5f5; border-bottom:1px solid #ddd;">
    <div class="container">
        <h1 style="margin:0; font-size:1.2rem;">UFO - Sistem Informasi Organisasi Mahasiswa UNKLAB</h1>
        <nav style="margin-top:0.5rem;">
            <a href="{{ route('home') }}">Beranda</a> |
            <a href="{{ route('organisasi.index') }}">Organisasi</a> |
            <a href="{{ route('kegiatan.index') }}">Kegiatan</a> |
            @if(session()->has('user'))
                <form action="{{ route('logout') }}" method="POST" style="display:inline">@csrf
                    <button type="submit">Logout</button>
                </form>
                <span style="margin-left:0.5rem;">Hi, {{ session('user.name') }}</span>
            @else
                <a href="{{ route('login') }}">Login</a>
            @endif
        </nav>
    </div>
</header>
