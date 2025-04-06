<nav class="navbar navbar-expand-sm bg-light">
    <div class="container-fluid">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" href="./">Home</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="./even">Even Numbers</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="./prime">Prime Numbers</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="./multable">Multiplication Table</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{route('products_list')}}">Products</a>
            </li>
            @can('view_purchases')
            <li class="nav-item">
                <a class="nav-link" href="{{route('purchases.list')}}">My Purchases</a>
            </li>
            @endcan
            @hasanyrole('Admin|Employee')
            <li class="nav-item">
                <a class="nav-link" href="{{route('customers.list')}}">Manage Customers</a>
            </li>
            @endhasanyrole
            @can('show_users')
            <li class="nav-item">
                <a class="nav-link" href="{{route('users')}}">
                    {{ auth()->user()->hasRole('Employee') && !auth()->user()->hasRole('Admin') ? 'Customers' : 'All Users' }}
                </a>
            </li>
            @endcan
            @can('create_employees')
            <li class="nav-item">
                <a class="nav-link" href="{{route('employees.create')}}">Add Employee</a>
            </li>
            @endcan
        </ul>
        <ul class="navbar-nav">
            @auth
            <li class="nav-item">
                @if(auth()->user()->hasRole('Customer'))
                <a class="nav-link" href="{{route('profile')}}">
                    {{auth()->user()->name}} - ${{number_format(auth()->user()->credit, 2)}}
                </a>
                @else
                <a class="nav-link" href="{{route('profile')}}">{{auth()->user()->name}}</a>
                @endif
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{route('do_logout')}}">Logout</a>
            </li>
            @else
            <li class="nav-item">
                <a class="nav-link" href="{{route('login')}}">Login</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{route('register')}}">Register</a>
            </li>
            @endauth
        </ul>
    </div>
</nav>
