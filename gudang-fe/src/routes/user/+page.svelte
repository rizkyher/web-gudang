<script lang="ts">
  import { Bell, Package, Clock, CheckCircle, ChevronRight, PackagePlus } from "@lucide/svelte";

  // Mock Data User
  let userName = "Pak Budi (Guru IT)";

  // Mock Data Riwayat Permintaan Singkat
  let recentRequests = $state([
    { id: "REQ-001", items: "Laptop Asus ROG, Kabel UTP...", status: "Diproses", date: "Hari ini, 09:00", color: "text-amber-600", bg: "bg-amber-100" },
    { id: "REQ-002", items: "Spidol, Penghapus Papan...", status: "Selesai", date: "Kemarin", color: "text-emerald-600", bg: "bg-emerald-100" },
  ]);
</script>

<div class="bg-blue-600 px-6 pt-10 pb-20 rounded-b-[40px] text-white relative shadow-md">
  <div class="flex justify-between items-center mb-6">
    <div>
      <p class="text-blue-200 text-sm font-medium mb-1">Selamat Pagi,</p>
      <h1 class="text-xl font-bold">{userName}</h1>
    </div>
    <button class="relative p-2 bg-blue-700/50 rounded-full hover:bg-blue-700 transition">
      <Bell size={20} />
      <span class="absolute top-1.5 right-1.5 w-2.5 h-2.5 bg-rose-500 rounded-full border-2 border-blue-600"></span>
    </button>
  </div>

  <div class="bg-white/10 p-4 rounded-2xl backdrop-blur-sm border border-white/20 flex items-center gap-4">
    <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center">
      <Package size={24} />
    </div>
    <div>
      <p class="text-blue-100 text-xs">Total Permintaan Bulan Ini</p>
      <p class="text-2xl font-bold">12 <span class="text-sm font-normal">Barang</span></p>
    </div>
  </div>
</div>

<div class="px-6 -mt-10 relative z-10 space-y-6">
  <div class="bg-white p-5 rounded-2xl shadow-lg border border-slate-100 flex items-center justify-between gap-4">
    <div>
      <h2 class="text-slate-800 font-bold text-lg">Butuh Barang?</h2>
      <p class="text-slate-500 text-xs mt-1">Ajukan permintaan ke gudang sekarang.</p>
    </div>
    <a href="/user/request" class="w-14 h-14 bg-blue-600 text-white rounded-2xl flex items-center justify-center shadow-md hover:bg-blue-700 transition shadow-blue-500/30 shrink-0">
      <PackagePlus size={28} />
    </a>
  </div>

  <div class="grid grid-cols-2 gap-4">
    <a href="/user/history" class="bg-white p-4 rounded-2xl shadow-sm border border-slate-100 hover:border-amber-300 transition block">
      <div class="w-10 h-10 bg-amber-50 text-amber-600 rounded-full flex items-center justify-center mb-3">
        <Clock size={20} />
      </div>
      <p class="text-2xl font-bold text-slate-800">2</p>
      <p class="text-xs text-slate-500 font-medium mt-0.5">Sedang Diproses</p>
    </a>
    <a href="/user/history" class="bg-white p-4 rounded-2xl shadow-sm border border-slate-100 hover:border-emerald-300 transition block">
      <div class="w-10 h-10 bg-emerald-50 text-emerald-600 rounded-full flex items-center justify-center mb-3">
        <CheckCircle size={20} />
      </div>
      <p class="text-2xl font-bold text-slate-800">10</p>
      <p class="text-xs text-slate-500 font-medium mt-0.5">Selesai Diterima</p>
    </a>
  </div>

  <div>
    <div class="flex justify-between items-center mb-4">
      <h3 class="font-bold text-slate-800">Permintaan Terakhir</h3>
      <a href="/user/history" class="text-xs text-blue-600 font-semibold flex items-center">Lihat Semua <ChevronRight size={14} /></a>
    </div>

    <div class="space-y-3">
      {#each recentRequests as req}
        <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm flex items-center gap-4">
          <div class="w-12 h-12 {req.bg} {req.color} rounded-full flex items-center justify-center shrink-0">
            {#if req.status === "Diproses"}
              <Clock size={20} />
            {:else}
              <CheckCircle size={20} />
            {/if}
          </div>
          <div class="flex-1 min-w-0">
            <p class="text-sm font-bold text-slate-800 truncate">{req.id}</p>
            <p class="text-xs text-slate-500 truncate mt-0.5">{req.items}</p>
            <p class="text-[10px] text-slate-400 mt-1">{req.date}</p>
          </div>
          <div class="shrink-0 text-right">
            <span class="text-[10px] font-bold px-2 py-1 rounded-md {req.bg} {req.color}">
              {req.status}
            </span>
          </div>
        </div>
      {/each}
    </div>
  </div>
</div>
