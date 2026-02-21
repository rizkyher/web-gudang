<script lang="ts">
  import { page } from "$app/stores";
  import { LayoutDashboard, Box, PackagePlus, Truck, RotateCcw, Contact, FileBarChart, Users } from "@lucide/svelte";

  // Menerima props dari +layout.svelte
  let { isSidebarOpen } = $props();

  // Daftar Menu Berdasarkan Kebutuhan Anda
  const menus = [
    { name: "Dashboard", href: "/admin", icon: LayoutDashboard },
    { name: "Master Barang", href: "/admin/items", icon: Box },
    { name: "Stock Masuk", href: "/admin/stock-in", icon: PackagePlus },
    { name: "Delivery Order", href: "/admin/delivery-orders", icon: Truck },
    { name: "Return Barang", href: "/admin/returns", icon: RotateCcw },
    { name: "Customer & Supplier", href: "/admin/partners", icon: Contact },
    { name: "Laporan", href: "/admin/reports", icon: FileBarChart },
    { name: "User Management", href: "/admin/users", icon: Users },
  ];

  // Fungsi untuk mengecek apakah menu sedang aktif
  // Menggunakan $derived agar selalu update saat pindah halaman
  let currentPath = $derived($page.url.pathname);

  function isActive(href: string) {
    // Logika khusus agar '/items' aktif meskipun URL-nya '/items/1' (halaman detail)
    if (href === "/admin" && currentPath === "/admin") return true;
    if (href !== "/admin" && currentPath.startsWith(href)) return true;
    return false;
  }
</script>

<aside class="bg-white border-r border-slate-200 h-full flex flex-col transition-all duration-300 ease-in-out shrink-0 overflow-y-auto hidden sm:flex {isSidebarOpen ? 'w-64' : 'w-20'} relative z-20">
  <div class="h-16 flex items-center px-4 border-b border-slate-200 sticky top-0 bg-white z-10 shrink-0">
    <div class="flex items-center gap-3 w-full {isSidebarOpen ? 'justify-start' : 'justify-center'}">
      <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center text-white font-bold shrink-0 shadow-sm">K</div>

      {#if isSidebarOpen}
        <span class="font-bold text-lg tracking-tight text-slate-800 whitespace-nowrap transition-opacity duration-300"> Khwarizmi App </span>
      {/if}
    </div>
  </div>

  <nav class="flex-1 p-3 space-y-1">
    <p class="px-3 text-xs font-semibold text-slate-400 mb-2 mt-2 uppercase tracking-wider {isSidebarOpen ? 'block' : 'hidden'}">Menu Utama</p>

    {#each menus as menu}
      {@const active = isActive(menu.href)}
      <a
        href={menu.href}
        title={!isSidebarOpen ? menu.name : ""}
        class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 group relative
                {active ? 'bg-blue-50 text-blue-700 font-semibold' : 'text-slate-500 hover:bg-slate-100 hover:text-slate-900 font-medium'}"
      >
        <div class={active ? "text-blue-600" : "text-slate-400 group-hover:text-slate-600"}>
          <menu.icon size={20} strokeWidth={active ? 2.5 : 2} />
        </div>

        {#if isSidebarOpen}
          <span class="whitespace-nowrap">{menu.name}</span>
        {/if}

        {#if active}
          <div class="absolute left-0 top-2 bottom-2 w-1 bg-blue-600 rounded-r-full"></div>
        {/if}
      </a>
    {/each}
  </nav>

  {#if isSidebarOpen}
    <div class="p-4 border-t border-slate-200 bg-slate-50">
      <p class="text-xs text-slate-500 font-medium text-center">v1.0.0 &copy; 2026</p>
    </div>
  {/if}
</aside>
