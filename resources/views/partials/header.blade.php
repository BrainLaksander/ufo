<header class="ufo-shell-header">
    <div class="container ufo-shell-header__inner">
        <a href="{{ route('home') }}" class="ufo-shell-brand" aria-label="UFO Home">
            <span class="ufo-shell-brand__mark">UFO</span>
            <span class="ufo-shell-brand__name">Universitas Klabat</span>
        </a>

        <div class="ufo-shell-auth">
            @if(session()->has('user'))
                <span class="ufo-shell-chip">{{ session('user.name') }}</span>
                <form action="{{ route('logout') }}" method="POST" class="ufo-shell-inline-form">
                    @csrf
                    <button type="submit" class="ufo-shell-btn">Logout</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="ufo-shell-btn">Login</a>
            @endif
        </div>
    </div>
</header>
