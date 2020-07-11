<style>
    .btn-submit {
        position: relative;
        right: 40px;
    }
</style>
<nav class="navbar navbar-light bg-light">
    <div class="container">
        <a class="navbar-brand" href=" {{ route('home') }} ">LarahubSanbercode</a>
        <div class="form-inline">

            @guest
                <a href=" {{ route('login') }} " class="btn btn-outline-success my-2 my-sm-0 mr-sm-2" type="submit">Login</a>
                <a href="{{ route('register') }}" class="btn btn-outline-success my-2 my-sm-0" type="submit">Register</a>
            @else
                <h5 class="mr-5">{{ Auth::user()->nama }}</h5>
                <a  href="{{ route('logout') }}" class="btn btn-outline-success my-2 my-sm-0"
                   onclick="event.preventDefault();
                           document.getElementById('logout-form').submit();">
                    Logout
                </a>

                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            @endguest
        </div>
    </div>
</nav>
