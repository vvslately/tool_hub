<!-- Navbar -->
<nav class="sticky top-0 z-50 w-full bg-white/90 backdrop-blur-md shadow border-b border-orange-200">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex justify-between items-center py-4">
      <!-- Logo -->
      <a href="/" class="flex items-center gap-3 text-2xl font-extrabold text-orange-600 hover:text-orange-800 transition-all animate-bounceSlow">
        <img src="/archive/public/favicon.ico" alt="Logo" class="w-8 h-8 animate-wave">
        <span>Tools Hub</span>
      </a>
      <!-- Menu (Desktop) -->
      <div class="hidden md:flex gap-8 text-base font-medium text-orange-700">
        <a href="/archive/home" class="hover:text-orange-500 transition">หน้าแรก</a>
        <a href="/archive/about" class="hover:text-orange-500 transition">เกี่ยวกับ</a>
      </div>
      <!-- Mobile Menu Button -->
      <div class="md:hidden">
        <button id="menuToggle" class="text-orange-600 hover:text-orange-800 focus:outline-none">
          <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round"
              d="M4 6h16M4 12h16M4 18h16" />
          </svg>
        </button>
      </div>
    </div>
  </div>
  <!-- Mobile Menu -->
  <div id="mobileMenu" class="md:hidden px-6 pb-4 hidden bg-white/90 border-t border-orange-100 shadow-md">
    <a href="/archive/home" class="block py-2 text-orange-700 hover:text-orange-500">หน้าแรก</a>
    <a href="/archive/about" class="block py-2 text-orange-700 hover:text-orange-500">เกี่ยวกับ</a>
  </div>
  <!-- Script for toggle -->
  <script>
    const menuToggle = document.getElementById('menuToggle');
    const mobileMenu = document.getElementById('mobileMenu');
    menuToggle.addEventListener('click', () => {
      mobileMenu.classList.toggle('hidden');
    });
  </script>
</nav>