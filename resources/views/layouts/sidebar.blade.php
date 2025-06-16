<div class="sidebar" data-background-color="dark">
    <div class="sidebar-logo">
        <div class="logo-header" data-background-color="dark">
            <a href="{{ route('dashboard') }}" class="logo">
                <x-application-logo class="block h-9 w-auto fill-current text-light" />
                <div class="text-light text-center ml-5"> {{ Auth::user()->role }}</div>
            </a>
            <div class="nav-toggle">
                <button class="btn btn-toggle toggle-sidebar">
                    <i class="gg-menu-right"></i>
                </button>
                <button class="btn btn-toggle sidenav-toggler">
                    <i class="gg-menu-left"></i>
                </button>
            </div>
            <button class="topbar-toggler more">
                <i class="gg-more-vertical-alt"></i>
            </button>
        </div>
        </div>
    <div class="sidebar-wrapper scrollbar scrollbar-inner">
        <div class="sidebar-content">
            <ul class="nav nav-secondary">
                <li class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <a href="{{ route('dashboard') }}">
                        <i class="fas fa-home"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                {{-- ===================================================================== --}}
                {{-- LOGIKA BARU UNTUK MENAMPILKAN MENU BERDASARKAN ROLE DAN STATUS MAGANG --}}
                {{-- ===================================================================== --}}
                @php
                    $showExtraMenus = false;
                    $user = Auth::user();

                    if ($user) {
                        // Tampilkan semua menu untuk admin, guru, dan perusahaan
                        if (in_array($user->role, ['admin', 'teacher', 'company'])) {
                            $showExtraMenus = true;
                        }
                        // Untuk siswa, cek kondisi khusus
                        elseif ($user->role === 'student') {
                            $student = $user->student;
                            // Ambil tahun akademik yang dipilih dari request (diset oleh middleware)
                            $selectedYear = request()->attributes->get('selected_academic_year');

                            if ($student && $selectedYear) {
                                // Cek apakah siswa memiliki data magang di TAHUN AKADEMIK YANG DIPILIH
                                $showExtraMenus = $student->internships()
                                    ->whereHas('internshipGroup', function ($query) use ($selectedYear) {
                                        $query->where('academic_year_id', $selectedYear->id);
                                    })
                                    ->exists();
                            }
                        }
                    }
                @endphp
                {{-- ===================================================================== --}}


                {{-- Menu Kelompok PKL (Hanya untuk Admin, Guru, Company) --}}
                @if ($user->role === 'admin' || $user->role === 'teacher' || $user->role === 'company')
                    <li class="nav-item {{ request()->routeIs('internship-groups.index') ? 'active' : '' }}">
                        <a href="{{ route('internship-groups.index') }}">
                            <i class="fa fa-users-cog"></i>
                            <p>Kelompok PKL</p>
                        </a>
                    </li>
                @endif


                {{-- Menu Siswa (Hanya untuk Admin, Guru, Company) --}}
                @if ($user->role === 'admin')
                    <li class="nav-item {{ request()->routeIs('students.index') ? 'active' : '' }}">
                        <a href="{{ route('students.index') }}">
                            <i class="fa fa-users"></i>
                            <p>Siswa</p>
                        </a>
                    </li>
                @endif

                {{-- Tampilkan menu Kehadiran, Kegiatan, Penilaian jika kondisi terpenuhi --}}
                @if ($showExtraMenus)
                    <li class="nav-item {{ request()->routeIs('attendances.index') ? 'active' : '' }}">
                        <a href="{{ route('attendances.index') }}">
                            <i class="fa fa-clock"></i>
                            <p>Kehadiran</p>
                        </a>
                    </li>

                    <li class="nav-item {{ request()->routeIs('activities.index') ? 'active' : '' }}">
                        <a href="{{ route('activities.index') }}">
                            <i class="fa fa-tasks"></i>
                            <p>Kegiatan</p>
                        </a>
                    </li>

                    <li class="nav-item {{ request()->routeIs('internship-assessments.index') ? 'active' : '' }}">
                        <a href="{{ route('internship-assessments.index') }}">
                            <i class="fa fa-award"></i>
                            <p>Penilaian</p>
                        </a>
                    </li>
                @endif

                {{-- Menu Khusus Admin --}}
                @if ($user->role == 'admin')
                    <li class="nav-item {{ request()->routeIs('teachers.index') ? 'active' : '' }}">
                        <a href="{{ route('teachers.index') }}">
                            <i class="fas fa-user-graduate"></i>
                            <p>Guru</p>
                        </a>
                    </li>

                    <li class="nav-item {{ request()->routeIs('companies.index') ? 'active' : '' }}">
                        <a href="{{ route('companies.index') }}">
                            <i class="fas fa-building"></i>
                            <p>DUDIKA</p>
                        </a>
                    </li>

                    <li class="nav-item {{ request()->routeIs('admins.index') ? 'active' : '' }}">
                        <a href="{{ route('admins.index') }}">
                            <i class="fa fa-user-shield"></i>
                            <p>Admin</p>
                        </a>
                    </li>

                    <li class="nav-item {{ request()->routeIs('academic-years.index') ? 'active' : '' }}">
                        <a href="{{ route('academic-years.index') }}">
                            <i class="fa fa-calendar-alt"></i>
                            <p>Tahun Akademik</p>
                        </a>
                    </li>
                    <hr>

                    <li class="nav-section">
                        <span class="sidebar-mini-icon">
                            <i class="fa fa-ellipsis-h"></i>
                        </span>
                        <h4 class="text-section">Data Pendukung</h4>
                    </li>

                    <li class="nav-item {{ request()->routeIs('departments.index') ? 'active' : '' }}">
                        <a href="{{ route('departments.index') }}">
                            <i class="fa fa-building"></i>
                            <p>Jurusan</p>
                        </a>
                    </li>

                    <li class="nav-item {{ request()->routeIs('class-rooms.index') ? 'active' : '' }}">
                        <a href="{{ route('class-rooms.index') }}">
                            <i class="fa fa-calendar-alt"></i>
                            <p>Kelas</p>
                        </a>
                    </li>

                    <li class="nav-item {{ request()->routeIs('assessment-criteria.index') ? 'active' : '' }}">
                        <a href="{{ route('assessment-criteria.index') }}">
                            <i class="fa fa-star"></i>
                            <p>Kriteria Penilaian</p>
                        </a>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</div>
