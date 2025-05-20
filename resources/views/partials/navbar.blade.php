<header class="fixed w-full z-20 top-0 left-0 border-b bg-white shadow-lg h-14 flex items-center px-4 md:px-8">
  <!-- Logo -->
  <a href="/" class="flex-shrink-0 flex items-center">
    <img src="{{ asset('storage/img/logo.png') }}" alt="Logo" class="h-10 w-auto" />
  </a>

  <!-- Navigation -->
  <nav class="hidden md:flex ml-6 space-x-4 font-semibold text-base lg:text-lg text-orange-500">
    <a href="/" class="px-3 py-3 hover:bg-gray-300 rounded">Home</a>
    <a href="/sponsors" class="px-3 py-3 hover:bg-gray-300 rounded {{ request()->is('sponsors') ? 'bg-gray-300' : '' }}">Sponsorship</a>
    <a href="/news" class="px-3 py-3 hover:bg-gray-300 rounded {{ request()->is('news') ? 'bg-gray-300' : '' }}">News</a>
  </nav>

  <div class="ml-auto flex items-center space-x-4">
    @auth
    <!-- Dropdown -->
    <div class="relative">
      <button id="userMenuButton" aria-expanded="false" aria-haspopup="true" class="flex items-center bg-black text-white text-xs font-bold px-4 py-2 rounded hover:bg-orange-400 focus:outline-none focus:ring-2 focus:ring-orange-400">
        Welcome, {{ auth()->user()->name }}
        <svg class="w-3 h-3 ml-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 10 6" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
          <path stroke-linecap="round" stroke-linejoin="round" d="M1 1l4 4 4-4" />
        </svg>
      </button>

      <!-- Dropdown Menu -->
      <div id="userDropdown" class="hidden absolute right-0 mt-2 w-44 bg-white border rounded shadow-lg dark:bg-black dark:border-gray-700" role="menu" aria-orientation="vertical" aria-labelledby="userMenuButton">
        <a href="/dashboard" class="block px-4 py-2 text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-orange-400 dark:hover:text-white" role="menuitem">My Dashboard</a>
        <form method="POST" action="/logout" class="block">
          @csrf
          <button type="submit" class="w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-orange-400 dark:hover:text-white flex items-center">
            <img src="{{ asset('storage/icon/icon-logout.png') }}" alt="Logout Icon" class="w-4 h-4 mr-2" />
            Logout
          </button>
        </form>
      </div>
    </div>
    @else
    <a href="/login" class="flex items-center bg-black text-white text-xs font-bold px-4 py-2 rounded hover:bg-orange-400">
      <img src="{{ asset('storage/icon/icon-login.png') }}" alt="Login Icon" class="h-4 w-4 mr-2" />
      Login
    </a>
    @endauth

    <!-- Mobile menu button -->
    <button id="mobileMenuButton" aria-controls="mobileMenu" aria-expanded="false" class="md:hidden inline-flex items-center justify-center p-2 rounded-lg text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200" type="button">
      <span class="sr-only">Open main menu</span>
      <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"></path>
      </svg>
    </button>
  </div>
</header>

<!-- Mobile Menu -->
<nav id="mobileMenu" class="hidden md:hidden fixed top-14 left-0 w-full bg-white border-b shadow-lg z-30">
  <ul class="flex flex-col text-orange-500 font-semibold text-base">
    <li><a href="/" class="block px-4 py-3 hover:bg-gray-300 {{ request()->is('/') ? 'bg-gray-300' : '' }}">Home</a></li>
    <li><a href="/sponsors" class="block px-4 py-3 hover:bg-gray-300 {{ request()->is('sponsors') ? 'bg-gray-300' : '' }}">Sponsors</a></li>
    <li><a href="/news" class="block px-4 py-3 hover:bg-gray-300 {{ request()->is('news') ? 'bg-gray-300' : '' }}">News</a></li>
    @auth
    <li><a href="/dashboard" class="block px-4 py-3 hover:bg-gray-300">My Dashboard</a></li>
    <li>
      <form method="POST" action="/logout" class="px-4 py-3">
        @csrf
        <button type="submit" class="w-full text-left text-gray-700 hover:bg-gray-300 flex items-center">
          <img src="{{ asset('storage/icon/icon-logout.png') }}" alt="Logout Icon" class="w-4 h-4 mr-2" /> Logout
        </button>
      </form>
    </li>
    @else
    <li><a href="/login" class="block px-4 py-3 hover:bg-gray-300 flex items-center">
      <img src="{{ asset('storage/icon/icon-login.png') }}" alt="Login Icon" class="h-4 w-4 mr-2" /> Login
    </a></li>
    @endauth
  </ul>
</nav>

<script>
  // Toggle user dropdown menu
  const userMenuButton = document.getElementById('userMenuButton');
  const userDropdown = document.getElementById('userDropdown');

  if (userMenuButton) {
    userMenuButton.addEventListener('click', () => {
      userDropdown.classList.toggle('hidden');
    });
  }

  // Toggle mobile menu
  const mobileMenuButton = document.getElementById('mobileMenuButton');
  const mobileMenu = document.getElementById('mobileMenu');

  mobileMenuButton.addEventListener('click', () => {
    const expanded = mobileMenuButton.getAttribute('aria-expanded') === 'true' || false;
    mobileMenuButton.setAttribute('aria-expanded', !expanded);
    mobileMenu.classList.toggle('hidden');
  });

  // Close dropdowns if clicked outside
  document.addEventListener('click', (e) => {
    if (userDropdown && !userDropdown.contains(e.target) && !userMenuButton.contains(e.target)) {
      userDropdown.classList.add('hidden');
    }
    if (mobileMenu && !mobileMenu.contains(e.target) && !mobileMenuButton.contains(e.target)) {
      mobileMenu.classList.add('hidden');
      mobileMenuButton.setAttribute('aria-expanded', false);
    }
  });
</script>

<div class="relative top-[56px]"></div>
