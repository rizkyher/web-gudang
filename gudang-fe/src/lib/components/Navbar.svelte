<script lang="ts">
  import { Menu, Bell, User, LogOut, Settings } from "@lucide/svelte";

  let { toggleSidebar } = $props();

  // State untuk mengatur buka/tutup dropdown
  let isProfileOpen = $state(false);
  let isNotifOpen = $state(false);

  // Aksi Logout (Nanti akan hit API /logout Laravel)
  function handleLogout() {
    if (confirm("Apakah Anda yakin ingin keluar dari sistem?")) {
      // Nanti diganti dengan logika penghapusan cookie & redirect ke /login
      alert("Simulasi: Anda berhasil Logout!");
    }
  }

  // Fungsi pembantu agar kalau satu dropdown buka, yang lain tutup
  function toggleNotif() {
    isNotifOpen = !isNotifOpen;
    isProfileOpen = false;
  }

  function toggleProfile() {
    isProfileOpen = !isProfileOpen;
    isNotifOpen = false;
  }
</script>

<header class="bg-white border-b border-slate-200 h-16 flex items-center justify-between px-6 sticky top-0 z-10">
  <button onclick={toggleSidebar} class="p-2 -ml-2 text-slate-500 hover:bg-slate-100 rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500">
    <Menu size={24} />
  </button>

  <div class="flex items-center gap-4">
    <div class="relative">
      <button onclick={toggleNotif} class="p-2 text-slate-500 hover:bg-slate-100 rounded-lg transition-colors relative">
        <Bell size={20} />
        <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-rose-500 rounded-full border-2 border-white"></span>
      </button>

      {#if isNotifOpen}
        <button class="fixed inset-0 z-10 w-full h-full cursor-default" onclick={() => (isNotifOpen = false)} aria-label="Tutup Notifikasi"></button>

        <div class="absolute right-0 mt-2 w-80 bg-white rounded-xl shadow-lg border border-slate-200 z-20 overflow-hidden">
          <div class="px-4 py-3 border-b border-slate-100 bg-slate-50">
            <h3 class="font-bold text-slate-800 text-sm">Notifikasi Baru</h3>
          </div>
          <div class="max-h-64 overflow-y-auto">
            <a href="/items" class="block p-4 hover:bg-slate-50 border-b border-slate-50 transition-colors">
              <div class="flex items-start gap-3">
                <div class="w-2 h-2 mt-1.5 bg-rose-500 rounded-full shrink-0"></div>
                <div>
                  <p class="text-sm font-semibold text-slate-800">Stok Kritis: Router Mikrotik</p>
                  <p class="text-xs text-slate-500 mt-1">Sisa stok saat ini hanya 2 pcs. Segera lakukan restock.</p>
                </div>
              </div>
            </a>
          </div>
        </div>
      {/if}
    </div>

    <div class="h-8 w-px bg-slate-200 mx-2"></div>

    <div class="relative">
      <button onclick={toggleProfile} class="flex items-center gap-3 text-left hover:bg-slate-50 p-1.5 rounded-lg transition-colors">
        <div class="w-8 h-8 bg-blue-100 text-blue-700 rounded-full flex items-center justify-center font-bold shadow-inner">
          <User size={16} />
        </div>
        <div class="hidden sm:block">
          <p class="text-sm font-semibold text-slate-700">Admin Utama</p>
          <p class="text-xs text-slate-500">admin@khwarizmi.com</p>
        </div>
      </button>

      {#if isProfileOpen}
        <button class="fixed inset-0 z-10 w-full h-full cursor-default" onclick={() => (isProfileOpen = false)} aria-label="Tutup Profil"></button>

        <div class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border border-slate-200 z-20 overflow-hidden py-1">
          <div class="px-4 py-3 border-b border-slate-100 sm:hidden">
            <p class="text-sm font-bold text-slate-800">Admin Utama</p>
            <p class="text-xs text-slate-500">admin@khwarizmi.com</p>
          </div>

          <a href="#settings" class="flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-50 hover:text-blue-600 transition-colors">
            <Settings size={18} /> Pengaturan Akun
          </a>

          <div class="h-px bg-slate-100 my-1"></div>

          <button onclick={handleLogout} class="w-full flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-rose-600 hover:bg-rose-50 transition-colors">
            <LogOut size={18} /> Keluar (Logout)
          </button>
        </div>
      {/if}
    </div>
  </div>
</header>
