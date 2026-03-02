<script lang="ts">
  import { Clock, CheckCircle, XCircle, Package, ClipboardList, ChevronRight } from "@lucide/svelte";
  import { fade, fly } from "svelte/transition";
  import { flip } from "svelte/animate";

  let activeTab = $state("Semua");
  const tabs = ["Semua", "Diproses", "Selesai"];

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

  // Warna disesuaikan dengan style dashboard
  const getStatusStyle = (status: string) => {
    switch (status) {
      case "Diproses":
        return { bg: "bg-amber-100", text: "text-amber-600", iconStyle: "bg-amber-50 text-amber-600" };
      case "Selesai":
        return { bg: "bg-emerald-100", text: "text-emerald-600", iconStyle: "bg-emerald-50 text-emerald-600" };
      case "Ditolak":
        return { bg: "bg-rose-100", text: "text-rose-600", iconStyle: "bg-rose-50 text-rose-600" };
      default:
        return { bg: "bg-slate-100", text: "text-slate-600", iconStyle: "bg-slate-50 text-slate-600" };
    }
  };
</script>

<div class="bg-blue-600 px-6 pt-12 pb-24 rounded-b-[40px] text-white relative shadow-md">
  <div class="flex items-center justify-between mb-8">
    <h1 class="text-2xl font-black tracking-tight">Riwayat</h1>
    <button class="relative p-2.5 bg-blue-700/50 rounded-full hover:bg-blue-700 transition">
      <ClipboardList size={20} />
    </button>
  </div>

  <div class="relative flex bg-white/10 p-1.5 rounded-2xl backdrop-blur-sm border border-white/20">
    <div class="absolute inset-y-1.5 bg-white rounded-xl shadow-sm transition-all duration-300 ease-out" style="width: calc({100 / tabs.length}% - 6px); left: calc({(tabs.indexOf(activeTab) * 100) / tabs.length}% + 3px);"></div>

    {#each tabs as tab}
      <button
        onclick={() => (activeTab = tab)}
        class="flex-1 py-2 text-sm font-bold transition-colors duration-300 rounded-xl relative z-10
        {activeTab === tab ? 'text-blue-700' : 'text-blue-100 hover:text-white'}"
      >
        {tab}
      </button>
    {/each}
  </div>
</div>

<main class="px-6 -mt-10 relative z-10 pb-24 space-y-4">
  {#each filteredHistory as item (item.id)}
    {@const style = getStatusStyle(item.status)}
    <div
      animate:flip={{ duration: 400 }}
      in:fly={{ y: 20, duration: 300, delay: 100 }}
      class="bg-white p-4 rounded-2xl shadow-sm border border-slate-100 hover:border-blue-200 transition-all duration-300 group cursor-pointer flex flex-col gap-3"
    >
      <div class="flex justify-between items-start">
        <div class="flex items-center gap-3">
          <div class="w-12 h-12 rounded-full flex items-center justify-center shrink-0 {style.iconStyle}">
            {#if item.status === "Diproses"}
              <Clock size={20} />
            {:else if item.status === "Selesai"}
              <CheckCircle size={20} />
            {:else}
              <XCircle size={20} />
            {/if}
          </div>
          <div>
            <p class="font-bold text-slate-800 tracking-tight text-sm">{item.id}</p>
            <p class="text-[11px] font-medium text-slate-500 mt-0.5">{item.date}</p>
          </div>
        </div>

        <div class="shrink-0 text-right">
          <span class="text-[10px] font-bold px-2.5 py-1 rounded-md {style.bg} {style.text} flex items-center gap-1">
            {#if item.status === "Diproses"}
              <span class="w-1.5 h-1.5 bg-amber-500 rounded-full animate-pulse"></span>
            {/if}
            {item.status.toUpperCase()}
          </span>
        </div>
      </div>

      <div class="h-px w-full bg-slate-50"></div>

      <div class="flex items-center justify-between gap-4">
        <div class="flex-1">
          <div class="flex flex-wrap gap-1.5">
            {#each item.items as barang}
              <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-slate-50 border border-slate-100 rounded-md text-[11px] font-medium text-slate-600">
                <span class="font-bold text-blue-600">{barang.qty}x</span>
                {barang.name}
              </span>
            {/each}
          </div>

          {#if item.note}
            <div class="mt-2.5 bg-rose-50 border-l-2 border-rose-400 p-2 rounded-r-lg">
              <p class="text-[10px] font-medium text-rose-700 leading-snug">
                {item.note}
              </p>
            </div>
          {/if}
        </div>

        <div class="w-8 h-8 rounded-full bg-slate-50 flex items-center justify-center text-slate-400 group-hover:bg-blue-600 group-hover:text-white transition-all shrink-0">
          <ChevronRight size={16} />
        </div>
      </div>
    </div>
  {:else}
    <div in:fade class="flex flex-col items-center justify-center py-16 text-center bg-white rounded-2xl border border-slate-100 shadow-sm mt-4">
      <div class="w-20 h-20 bg-blue-50 text-blue-300 rounded-full flex items-center justify-center mb-4">
        <ClipboardList size={36} strokeWidth={1.5} />
      </div>
      <h3 class="text-lg font-bold text-slate-800">Riwayat Kosong</h3>
      <p class="text-xs font-medium text-slate-500 mt-1">
        Tidak ada permintaan dengan status <span class="text-blue-600 font-bold bg-blue-50 px-1.5 py-0.5 rounded-md">{activeTab}</span>.
      </p>
    </div>
  {/each}
</main>

<style>
  :global(body) {
    background-color: #f8fafc;
  }
</style>
