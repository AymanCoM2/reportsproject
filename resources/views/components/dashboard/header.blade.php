<div class="header">
    <div class="header-content clearfix">
        <div class="nav-control">
            <div class="hamburger">
                <span class="toggle-icon"><i class="icon-menu"></i></span>
            </div>
        </div>
        <div class="form w-75 ml-5  p-2 ">
            <form action="{{ route('search-get') }}" method="get">
                <input type="search" class="form-control" placeholder="Search..." name="qq" />
            </form>
        </div>
        <div class="header-right">
            <ul class="clearfix">
                <li class="icons dropdown">
                    <div class="user-img c-pointer position-relative" data-toggle="dropdown">
                        <span class="activity active"></span>
                        <img src="{{ asset('images/user/1.png') }}" height="40" width="40" alt="">
                    </div>
                    <div class="drop-down dropdown-profile   dropdown-menu">
                        <div class="dropdown-content-body">
                            <ul>
                                <li>User : {{ auth()->user()->name }}</li>
                                <li><a href="page-login.html"><span> <a href="{{ route('logout', [], 0) }}"
                                                onclick="event.preventDefault();
                                             document.getElementById('logout-form').submit();">
                                                {{ __('Logout') }}
                                            </a>
                                            <form id="logout-form" action="{{ route('logout', [], 0) }}" method="POST"
                                                class="d-none">
                                                @csrf
                                            </form>
                                        </span></a></li>
                                <li>
                                    @if (app()->getLocale() == 'ar')
                                        <a href="{{ route('name-switcher', 'en') }}">English</a>
                                    @else
                                        <a href="{{ route('name-switcher', 'ar') }}">عربي</a>
                                    @endif
                                </li>
                            </ul>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</div>
