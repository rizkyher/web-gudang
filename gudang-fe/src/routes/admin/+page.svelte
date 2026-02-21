<script lang="ts">
  import { Package, Truck, RotateCcw, AlertCircle, PlusCircle, FileText, ArrowRight } from "@lucide/svelte";

  // State data (Nanti data ini diisi dari API Laravel)
  let stats = $state([
    { title: "Total Barang", value: "1,240", icon: Package, color: "text-blue-600", bg: "bg-blue-100" },
    { title: "DO Pending", value: "12", icon: Truck, color: "text-amber-600", bg: "bg-amber-100" },
    { title: "Return Bulan Ini", value: "5", icon: RotateCcw, color: "text-emerald-600", bg: "bg-emerald-100" },
    { title: "Stok Kritis", value: "8", icon: AlertCircle, color: "text-rose-600", bg: "bg-rose-100" },
  ]);

  let recentActivities = $state([
    { id: 1, action: "Barang Masuk", desc: "50pcs Kabel UTP Cat 6 ditambahkan", time: "10 menit yang lalu", type: "in" },
    { id: 2, action: "Delivery Order", desc: "DO #1029 telah disetujui Finance", time: "1 jam yang lalu", type: "out" },
    { id: 3, action: "Return Barang", desc: "2pcs Router Mikrotik dikembalikan (Rusak)", time: "3 jam yang lalu", type: "return" },
  ]);

  let lowStockItems = $state([
    { code: "BRG-003", name: "Router Mikrotik", stock: 2 },
    { code: "BRG-012", name: "Switch Hub 8 Port", stock: 1 },
    { code: "BRG-045", name: "Tang Crimping", stock: 0 },
  ]);
</script>

<svelte:head>
  <title>Dashboard | Khwarizmi Academy Inventory</title>
</svelte:head>

<div class="space-y-6">
  <div class="bg-gradient-to-r from-blue-600 to-blue-800 rounded-2xl p-8 text-white shadow-md">
    <h1 class="text-3xl font-bold mb-2">Selamat Datang di Sistem Gudang Khwarizmi</h1>
    <p class="text-blue-100 max-w-2xl">Ringkasan aktivitas dan status inventaris Anda hari ini. Perhatikan barang dengan stok kritis agar operasional tetap berjalan lancar.</p>
  </div>

  <div class="flex flex-wrap gap-4">
    <a href="/items" class="flex items-center gap-2 bg-white border border-slate-200 hover:border-blue-500 hover:text-blue-600 px-5 py-3 rounded-xl font-medium text-slate-700 transition-all shadow-sm">
      <PlusCircle size={20} />
      Tambah Barang Baru
    </a>
    <a href="/delivery-orders" class="flex items-center gap-2 bg-white border border-slate-200 hover:border-amber-500 hover:text-amber-600 px-5 py-3 rounded-xl font-medium text-slate-700 transition-all shadow-sm">
      <FileText size={20} />
      Buat Delivery Order
    </a>
  </div>

  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
    {#each stats as stat}
      <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex items-center gap-4 hover:shadow-md transition-shadow">
        <div class="w-14 h-14 {stat.bg} {stat.color} rounded-2xl flex items-center justify-center">
          <stat.icon size={28} />
        </div>
        <div>
          <p class="text-sm font-medium text-slate-500">{stat.title}</p>
          <p class="text-2xl font-bold text-slate-900 mt-0.5">{stat.value}</p>
        </div>
      </div>
    {/each}
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
      <div class="flex items-center justify-between mb-6">
        <h3 class="text-lg font-bold text-slate-800">Aktivitas Terbaru</h3>
        <button class="text-sm text-blue-600 hover:text-blue-800 font-medium flex items-center gap-1">
          Lihat Semua <ArrowRight size={16} />
        </button>
      </div>

      <div class="space-y-6">
        {#each recentActivities as act}
          <div class="flex gap-4 items-start">
            <div
              class="mt-1 w-2.5 h-2.5 rounded-full mt-1.5 shrink-0
                            {act.type === 'in' ? 'bg-emerald-500' : act.type === 'out' ? 'bg-blue-500' : 'bg-rose-500'}"
            ></div>
            <div>
              <p class="text-sm font-semibold text-slate-900">{act.action}</p>
              <p class="text-sm text-slate-600 mt-0.5">{act.desc}</p>
              <p class="text-xs text-slate-400 mt-1">{act.time}</p>
            </div>
          </div>
        {/each}
      </div>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 flex flex-col">
      <div class="flex items-center gap-2 mb-6 text-rose-600">
        <AlertCircle size={24} />
        <h3 class="text-lg font-bold text-slate-800">Stok Kritis</h3>
      </div>

      {#if lowStockItems.length > 0}
        <div class="flex-1 space-y-4">
          {#each lowStockItems as item}
            <div class="flex items-center justify-between p-3 bg-rose-50 rounded-xl border border-rose-100">
              <div>
                <p class="text-sm font-bold text-slate-900">{item.name}</p>
                <p class="text-xs text-slate-500">{item.code}</p>
              </div>
              <div class="text-right">
                <span class="inline-block px-2.5 py-1 bg-rose-200 text-rose-800 text-xs font-bold rounded-lg">
                  Sisa {item.stock}
                </span>
              </div>
            </div>
          {/each}
        </div>
        <button class="mt-6 w-full py-2.5 bg-slate-50 hover:bg-slate-100 text-slate-700 text-sm font-semibold rounded-xl transition-colors border border-slate-200"> Restock Sekarang </button>
      {:else}
        <div class="flex-1 flex flex-col items-center justify-center text-center p-6">
          <div class="w-16 h-16 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center mb-3">
            <Package size={32} />
          </div>
          <p class="text-slate-800 font-medium">Semua Stok Aman!</p>
          <p class="text-sm text-slate-500 mt-1">Tidak ada barang yang perlu di-restock saat ini.</p>
        </div>
      {/if}
    </div>
  </div>
</div>
