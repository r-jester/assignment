<header class="bg-dark text-white sticky-top d-flex align-items-center justify-content-between px-3 shadow"
    style="height: 50px; z-index: 2001;">
    <div class="d-flex align-items-center">
        <button class="bg-transparent border-0 text-white me-2" id="toggleSidebar" aria-expanded="false"
            aria-controls="sidebar">
            <i class="fas fa-bars"></i>
        </button>
        <a class="text-white text-decoration-none" title="home" href="{{ url('/') }}"> {{ session()->get('NAME', 'Guest') }} Syst</a>
    </div>
    <div class="d-flex align-items-center gap-3 position-relative">
        <a class="text-white" title="home" href="{{ url('/') }}"><i class="fas fa-home d-block"></i></a>
        {{-- <i class="fas fa-bell"></i> --}}
        <a class="text-white" title="users" href="/users"><i class="fas fa-users d-block"></i></a>
        {{-- <i class="fas fa-envelope"></i> --}}
        <a class="text-white" title="products" href="/products"><i class="fas fa-shop d-block"></i></a>
        <a class="text-white" title="categories" href="/categories"><i class="fas fa-box d-block"></i></a>
        <i class="fas fa-user text-white cursor-pointer" id="profilePopup" title="profile"></i>

        <!-- Modal Profile -->
        <div class="modalProfile position-absolute bg-white rounded shadow-lg"
            style="width: 240px; top: 40px; right: 0; display: none; z-index: 2010;">
            <!-- Profile Header -->
            <div class="bg-primary bg-gradient text-center py-3">
                <i class="fas fa-user-circle text-white fs-1"></i>
            </div>

            <!-- Profile Content -->
            <div class="p-3">
                <h2 class="d-block text-center mb-3 text-success font-extrabold text-4xl">{{ session()->get('NAME') }}
                </h2>

                <!-- Profile Actions -->
                <div class="border-top border-bottom py-2 mb-3">
                    <a href="#" class="d-block text-decoration-none text-dark py-2 px-1 rounded hover-bg-light">
                        <i class="fas fa-id-card text-primary me-2"></i> My Profile
                    </a>
                    <a href="#" class="d-block text-decoration-none text-dark py-2 px-1 rounded hover-bg-light">
                        <i class="fas fa-cog text-primary me-2"></i> Settings
                    </a>
                </div>

                <!-- Logout Button -->
                <form action="{{ route('logout') }}" method="POST" class="w-100">
                    @csrf
                    <button type="submit" class="btn btn-danger w-100 d-flex align-items-center justify-content-center">
                        <i class="fas fa-sign-out-alt me-2"></i> Log Out
                    </button>
                </form>
            </div>
        </div>

        {{-- <a class="text-white" title="sign out" href="/logout"><i class="fas fa-sign-out-alt d-block"></i></a> --}}
        <form action="{{ route('logout') }}" method="POST" class="w-100">
            @csrf
            <button type="submit" class="text-white">
                <i class="fas fa-sign-out-alt me-2"></i>
            </button>
        </form>
    </div>
</header>

<script type="module">
    $(function() {
        $("#profilePopup").click(function(e) {
            $(".modalProfile").slideToggle("fast");
            e.stopPropagation();
        });

        // Close modal when clicking outside
        $(document).click(function(e) {
            if (!$(e.target).closest('.modalProfile').length && !$(e.target).is('#profilePopup')) {
                $(".modalProfile").slideUp("fast");
            }
        });
    });
</script>

<style>
    /* Minimal custom styles that can't be achieved with Bootstrap/Tailwind */
    .cursor-pointer {
        cursor: pointer;
    }

    .hover-bg-light:hover {
        background-color: #f8f9fa;
    }
</style>
