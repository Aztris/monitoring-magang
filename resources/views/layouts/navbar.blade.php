<nav class="navbar navbar-header navbar-header-transparent navbar-expand-lg border-bottom">
    <div class="container-fluid">
        <nav class="navbar navbar-header-left navbar-expand-lg navbar-form nav-search p-0 d-none d-lg-flex">
            @php
                $selectedYear = request()->attributes->get('selected_academic_year');
                $academicYears = App\Models\AcademicYear::all();
            @endphp

            {{-- @if ($academicYears->isNotEmpty() && Auth::user()->role != 'student') --}}
            @if ($academicYears->isNotEmpty())
                <div class="dropdown academic-year-selector">
                    <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="academicYearDropdown"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        @if ($selectedYear)
                            {{ $selectedYear->name }}
                            @if ($selectedYear->is_active)
                                <span class="badge bg-success ms-2">Aktif</span>
                            @endif
                        @else
                            Pilih Tahun Akademik
                        @endif
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="academicYearDropdown">
                        @foreach ($academicYears as $year)
                            <li>
                                <form action="{{ route('academic-years.select', $year) }}" method="POST"
                                    class="d-inline">
                                    @csrf
                                    <button type="submit"
                                        class="dropdown-item {{ $selectedYear && $selectedYear->id == $year->id ? 'active' : '' }}">
                                        {{ $year->name }}
                                        @if ($year->is_active)
                                            <span class="badge bg-success float-end">Aktif</span>
                                        @endif
                                    </button>
                                </form>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @else
                @if (Auth::user()->role != 'student')
                    <span class="text-muted">Tidak ada tahun akademik</span>
                @endif
            @endif

        </nav>

        <style>
            .academic-year-selector .dropdown-toggle {
                min-width: 200px;
                text-align: left;
                display: flex;
                align-items: center;
                justify-content: space-between;
            }

            .academic-year-selector .dropdown-item {
                cursor: pointer;
            }

            .academic-year-selector .dropdown-item.active {
                background-color: #f8f9fa;
                color: #212529;
            }
        </style>

        <ul class="navbar-nav topbar-nav ms-md-auto align-items-center">
            <li class="nav-item topbar-icon dropdown hidden-caret d-flex d-lg-none">
                {{-- <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#"
                    role="button" aria-expanded="false" aria-haspopup="true">
                    <i class="fa fa-search"></i>
                </a>
                <ul class="dropdown-menu dropdown-search animated fadeIn">
                    <form class="navbar-left navbar-form nav-search">
                        <div class="input-group">
                            <input type="text" placeholder="Search ..." class="form-control" />
                        </div>
                    </form>
                </ul> --}}
                {{ Auth::user()->role }}
            </li>

            <li class="nav-item topbar-user dropdown hidden-caret">
                <a class="dropdown-toggle profile-pic" data-bs-toggle="dropdown" href="#"
                    aria-expanded="false">
                    <div class="avatar-sm">
                        <img src="{{ Auth::user()->foto_profil ? Storage::url(Auth::user()->foto_profil) : asset('storage/profile_photos/default_avatar.png') }}"
                            alt="Foto Profil" class="avatar-img rounded-circle" />
                    </div>

                    <span class="profile-username">
                        <span class="op-7">Hi,</span>
                        <span class="fw-bold">{{ Auth::user()->nama }}</span>
                    </span>
                </a>
                <ul class="dropdown-menu dropdown-user animated fadeIn">
                    <div class="dropdown-user-scroll scrollbar-outer">
                        <li>
                            <div class="user-box">
                                <div class="avatar-lg">
                                    <img src="{{ Auth::user()->foto_profil ? Storage::url(Auth::user()->foto_profil) : asset('storage/profile_photos/default_avatar.png') }}"
                                        alt="Foto Profil" class="avatar-img rounded-circle" />
                                </div>
                                <div class="u-text">
                                    <h4>{{ Auth::user()->nama }}</h4>
                                    <p class="text-muted">{{ Auth::user()->email }}</p>
                                    <a href="{{ route('profile.edit') }}" class="btn btn-xs btn-secondary btn-sm">View Profile</a>
                                </div>
                            </div>
                        </li>
                        <li class="text-center align-item-center">
                            <div class="dropdown-divider"></div>
                            {{-- <a class="dropdown-item" href="#">My Profile</a> --}}
                            {{-- <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="#">Account Setting</a> --}}
                            {{-- <div class="dropdown-divider"></div> --}}
                            {{-- <a class="dropdown-item" href="{{ route('logout') }}"> --}}
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf

                                <x-responsive-nav-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                        this.closest('form').submit();">
                                    {{ __('Log Out') }}
                                </x-responsive-nav-link>
                            </form>
                            {{-- </a> --}}
                        </li>
                    </div>
                </ul>
            </li>
        </ul>
    </div>
</nav>
