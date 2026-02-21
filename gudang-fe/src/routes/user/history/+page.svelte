<script lang="ts">
  import { Clock, CheckCircle, XCircle, Package, ClipboardList, ChevronRight } from "@lucide/svelte";
  import { fade, fly } from "svelte/transition";
  import { flip } from "svelte/animate";

  let activeTab = $state("Semua");
  const tabs = ["Semua", "Diproses", "Selesai"];

  // Mock Data: Format item diubah menjadi array agar bisa dirender sebagai 'chips' yang cantik
  const historyData = [
    {
      id: "REQ-2602-005",
      date: "21 Feb 2026",
      status: "Diproses",
      items: [
        { name: "Tinta Printer Epson", qty: 2 },
        { name: "Kertas HVS", qty: 1 },
      ],
    },
    {
      id: "REQ-2602-002",
      date: "18 Feb 2026",
      status: "Selesai",
      items: [{ name: "Spidol Snowman", qty: 5 }],
    },
    {
      id: "REQ-2602-001",
      date: "15 Feb 2026",
      status: "Ditolak",
      note: "Stok habis, sedang dipesan ke supplier.",
      items: [{ name: "Kabel HDMI", qty: 1 }],
    },
  ];

  let filteredHistory = $derived(activeTab === "Semua" ? historyData : historyData.filter((h) => h.status === activeTab));

  // Helper untuk mendapatkan warna berdasarkan status
  const getStatusStyle = (status: string) => {
    switch (status) {
      case "Diproses":
        return { bg: "bg-amber-50", text: "text-amber-600", border: "border-amber-200", icon: "text-amber-500" };
      case "Selesai":
        return { bg: "bg-emerald-50", text: "text-emerald-600", border: "border-emerald-200", icon: "text-emerald-500" };
      case "Ditolak":
        return { bg: "bg-rose-50", text: "text-rose-600", border: "border-rose-200", icon: "text-rose-500" };
      default:
        return { bg: "bg-slate-50", text: "text-slate-600", border: "border-slate-200", icon: "text-slate-500" };
    }
  };
</script>

<header class="bg-white/90 backdrop-blur-xl px-6 pt-12 pb-4 sticky top-0 z-30 shadow-sm shadow-slate-200/50">
  <div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-black text-slate-900 tracking-tight">Riwayat</h1>
    <button class="bg-white border border-slate-200 p-2.5 rounded-full text-slate-600 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all active:scale-95">
      <ClipboardList size={20} />
    </button>
  </div>

  <div class="relative flex bg-slate-100 p-1.5 rounded-2xl">
    <div class="absolute inset-y-1.5 bg-white rounded-xl shadow-sm transition-all duration-300 ease-out" style="width: calc({100 / tabs.length}% - 6px); left: calc({(tabs.indexOf(activeTab) * 100) / tabs.length}% + 3px);"></div>

    {#each tabs as tab}
      <button
        onclick={() => (activeTab = tab)}
        class="flex-1 py-2.5 text-sm font-bold transition-colors duration-300 rounded-xl relative z-10
        {activeTab === tab ? 'text-blue-700' : 'text-slate-500 hover:text-slate-800'}"
      >
        {tab}
      </button>
    {/each}
  </div>
</header>

<main class="p-6 pb-24">
  <div class="space-y-4">
    {#each filteredHistory as item (item.id)}
      {@const style = getStatusStyle(item.status)}
      <div
        animate:flip={{ duration: 400 }}
        in:fly={{ y: 20, duration: 300, delay: 100 }}
        class="group cursor-pointer bg-white border border-slate-200 rounded-3xl p-5 shadow-sm hover:shadow-xl hover:shadow-blue-900/5 hover:border-blue-200 transition-all duration-300 active:scale-[0.98]"
      >
        <div class="flex justify-between items-start mb-4">
          <div class="flex items-center gap-3.5">
            <div class="w-12 h-12 rounded-2xl flex items-center justify-center border transition-colors {style.bg} {style.border} {style.icon}">
              <Package size={22} strokeWidth={2.5} />
            </div>
            <div>
              <p class="font-extrabold text-slate-800 tracking-tight text-base">{item.id}</p>
              <div class="flex items-center gap-1.5 mt-0.5">
                <Clock size={12} class="text-slate-400" />
                <p class="text-[11px] font-semibold text-slate-500">{item.date}</p>
              </div>
            </div>
          </div>

          {#if item.status === "Diproses"}
            <span class="bg-amber-100 text-amber-700 text-[10px] font-black px-3 py-1.5 rounded-full flex items-center gap-1.5">
              <span class="w-1.5 h-1.5 bg-amber-500 rounded-full animate-pulse"></span>
              PROSES
            </span>
          {:else if item.status === "Selesai"}
            <span class="bg-emerald-100 text-emerald-700 text-[10px] font-black px-3 py-1.5 rounded-full flex items-center gap-1.5">
              <CheckCircle size={12} strokeWidth={3} />
              SELESAI
            </span>
          {:else}
            <span class="bg-rose-100 text-rose-700 text-[10px] font-black px-3 py-1.5 rounded-full flex items-center gap-1.5">
              <XCircle size={12} strokeWidth={3} />
              DITOLAK
            </span>
          {/if}
        </div>

        <div class="h-px w-full bg-slate-100 mb-4"></div>

        <div class="flex items-center justify-between gap-4">
          <div class="flex-1">
            <div class="flex flex-wrap gap-2">
              {#each item.items as barang}
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-slate-50 border border-slate-200 rounded-lg text-xs font-semibold text-slate-700">
                  <span class="font-bold text-blue-600">{barang.qty}x</span>
                  {barang.name}
                </span>
              {/each}
            </div>

            {#if item.note}
              <div class="mt-3 bg-rose-50 border-l-2 border-rose-400 p-2.5 rounded-r-xl">
                <p class="text-[11px] font-medium text-rose-700 leading-snug">
                  {item.note}
                </p>
              </div>
            {/if}
          </div>

          <div class="w-8 h-8 rounded-full bg-slate-50 flex items-center justify-center text-slate-400 group-hover:bg-blue-600 group-hover:text-white transition-all shrink-0">
            <ChevronRight size={18} />
          </div>
        </div>
      </div>
    {:else}
      <div in:fade class="flex flex-col items-center justify-center py-20 text-center">
        <div class="relative mb-6">
          <div class="absolute inset-0 bg-blue-100 rounded-full scale-[2] blur-3xl opacity-60"></div>
          <div class="relative w-28 h-28 bg-white/50 backdrop-blur-sm border border-white rounded-full shadow-xl flex items-center justify-center text-blue-300">
            <ClipboardList size={48} strokeWidth={1.5} />
          </div>
        </div>
        <h3 class="text-xl font-black text-slate-800">Riwayat Kosong</h3>
        <p class="text-sm font-medium text-slate-500 mt-2">
          Belum ada permintaan dengan status <span class="text-blue-600 font-bold bg-blue-50 px-2 py-0.5 rounded-md">{activeTab}</span>.
        </p>
      </div>
    {/each}
  </div>
</main>

<style>
  :global(body) {
    background-color: #f8fafc; /* slate-50 background for contrast with white cards */
  }
</style>
