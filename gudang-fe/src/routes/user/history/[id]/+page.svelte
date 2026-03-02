<script lang="ts">
  import { ChevronLeft, Printer, MessageCircle, Package, Clock, CheckCircle, FileText, Calendar, Box, Check, AlertCircle } from "@lucide/svelte";
  import { fade, fly } from "svelte/transition";

  // Mock Data: Detail dari satu ID Permintaan
  const detail = {
    id: "REQ-2602-005",
    date: "21 Feb 2026, 09:00 WIB",
    status: "Diproses", // Bisa: "Selesai", "Ditolak", "Diproses"
    priority: "Normal",
    note: "Untuk keperluan setup ruang lab komputer baru di lantai 3. Mohon disiapkan kabel yang panjang.",
    items: [
      { name: "Tinta Printer Epson Black", qty: 2, unit: "Botol", category: "Elektronik" },
      { name: "Kertas HVS A4 80gr", qty: 1, unit: "Rim", category: "ATK" },
      { name: "Kabel UTP Cat6 (Gulungan Besar)", qty: 1, unit: "Roll", category: "Elektronik" },
    ],
    timeline: [
      { title: "Permintaan Dibuat", time: "21 Feb 2026, 09:00", isDone: true, isCurrent: false },
      { title: "Disetujui Kepala Unit", time: "21 Feb 2026, 09:15", isDone: true, isCurrent: false },
      { title: "Diproses Gudang", time: "21 Feb 2026, 10:00", isDone: true, isCurrent: true },
      { title: "Barang Siap Diambil", time: "Menunggu", isDone: false, isCurrent: false },
    ],
  };

  const getBadgeStyle = (status: string) => {
    switch (status) {
      case "Diproses":
        return { bg: "bg-amber-100", text: "text-amber-700", border: "border-amber-200", icon: Clock };
      case "Selesai":
        return { bg: "bg-emerald-100", text: "text-emerald-700", border: "border-emerald-200", icon: CheckCircle };
      case "Ditolak":
        return { bg: "bg-rose-100", text: "text-rose-700", border: "border-rose-200", icon: AlertCircle };
      default:
        return { bg: "bg-slate-100", text: "text-slate-700", border: "border-slate-200", icon: Package };
    }
  };

  const badge = getBadgeStyle(detail.status);
</script>

<svelte:head>
  <title>Detail Permintaan - {detail.id}</title>
</svelte:head>

<div class="bg-blue-600 px-6 pt-12 pb-32 rounded-b-[40px] text-white relative shadow-md mb-3">
  <div class="flex items-center justify-between mb-2">
    <a href="/user/history" class="w-10 h-10 bg-white/20 backdrop-blur-sm rounded-full flex items-center justify-center hover:bg-white/30 transition-colors active:scale-95 shrink-0">
      <ChevronLeft size={24} />
    </a>
    <p class="text-[13px] font-black tracking-widest uppercase text-blue-200">Detail Resi</p>
    <div class="w-10"></div>
  </div>
</div>

<div class="px-6 -mt-20 relative z-10 space-y-5 pb-32">
  <div in:fly={{ y: 20, duration: 400 }} class="bg-white p-5 rounded-3xl border border-slate-100 shadow-md shadow-slate-200/50 relative overflow-hidden">
    <div class="absolute z-0 pointer-events-none" style="right: -15px; top: -15px; transform: rotate(15deg); opacity: 0.25;">
      <FileText size={90} strokeWidth={2} color="#cbd5e1" />
    </div>

    <div class="relative z-10">
      <div class="flex justify-between items-start mb-5">
        <div>
          <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-1">ID Permintaan</p>
          <h2 class="text-xl font-black text-slate-800 tracking-tight">{detail.id}</h2>
        </div>

        <div class="flex items-center gap-1.5 px-3 py-1.5 rounded-xl border bg-white shadow-sm {badge.border}">
          {#if detail.status === "Diproses"}
            <span class="w-2.5 h-2.5 rounded-full bg-amber-500 animate-pulse"></span>
          {:else}
            <badge.icon size={14} strokeWidth={2.5} class={badge.text} />
          {/if}
          <span class="text-[11px] font-black uppercase tracking-wide {badge.text}">{detail.status}</span>
        </div>
      </div>

      <div class="flex items-center gap-3 text-xs font-medium text-slate-500 bg-slate-50 p-3.5 rounded-2xl border border-slate-100">
        <div class="flex items-center gap-1.5 flex-1">
          <Calendar size={14} class="text-blue-500 shrink-0" />
          <span class="truncate">{detail.date}</span>
        </div>
        <div class="w-px h-4 bg-slate-200 shrink-0"></div>
        <div class="flex items-center gap-1.5 font-bold shrink-0 {detail.priority === 'Normal' ? 'text-blue-600' : 'text-rose-600'}">
          {detail.priority}
        </div>
      </div>
    </div>
  </div>

  <div in:fly={{ y: 20, duration: 400, delay: 100 }} class="bg-white p-5 rounded-3xl border border-slate-100 shadow-sm">
    <h3 class="text-sm font-extrabold text-slate-800 tracking-tight mb-5">Lacak Proses</h3>

    <div class="ml-1 space-y-0 relative">
      <div class="absolute left-[11px] top-2 bottom-6 w-0.5 bg-slate-100"></div>

      {#each detail.timeline as step, index}
        <div class="flex gap-4 relative">
          {#if step.isDone && index !== detail.timeline.length - 1}
            <div class="absolute left-[11px] top-6 bottom-[-8px] w-0.5 bg-blue-600 z-0"></div>
          {/if}

          <div class="relative z-10 shrink-0 mt-1">
            {#if step.isCurrent}
              <div class="w-6 h-6 rounded-full bg-blue-100 flex items-center justify-center border-2 border-white shadow-sm ring-2 ring-blue-600 ring-offset-1">
                <span class="w-2.5 h-2.5 rounded-full bg-blue-600 animate-pulse"></span>
              </div>
            {:else if step.isDone}
              <div class="w-6 h-6 rounded-full bg-blue-600 flex items-center justify-center border-2 border-white shadow-sm">
                <Check size={12} strokeWidth={3} class="text-white" />
              </div>
            {:else}
              <div class="w-6 h-6 rounded-full bg-slate-100 border-2 border-white shadow-sm"></div>
            {/if}
          </div>

          <div class="pb-6">
            <p class="text-xs font-bold {step.isCurrent ? 'text-blue-600' : step.isDone ? 'text-slate-800' : 'text-slate-400'}">
              {step.title}
            </p>
            <p class="text-[10px] font-medium mt-0.5 {step.isCurrent ? 'text-blue-400' : 'text-slate-400'}">
              {step.time}
            </p>
          </div>
        </div>
      {/each}
    </div>
  </div>

  <div in:fly={{ y: 20, duration: 400, delay: 200 }} class="bg-white p-5 rounded-3xl border border-slate-100 shadow-sm">
    <div class="flex justify-between items-center mb-5">
      <h3 class="text-sm font-extrabold text-slate-800 tracking-tight">Daftar Barang</h3>
      <span class="bg-blue-50 text-blue-600 text-[10px] font-black px-2.5 py-1 rounded-lg uppercase tracking-wider">{detail.items.length} Item</span>
    </div>

    <div class="space-y-3">
      {#each detail.items as item}
        <div class="flex items-center justify-between gap-3 p-3 bg-slate-50 border border-slate-100 rounded-2xl">
          <div class="flex items-center gap-3 min-w-0">
            <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center border border-slate-100 shrink-0 text-blue-600 shadow-sm">
              <Box size={18} strokeWidth={2} />
            </div>
            <div class="min-w-0">
              <p class="text-xs font-bold text-slate-800 truncate">{item.name}</p>
              <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">{item.category}</p>
            </div>
          </div>
          <div class="shrink-0 text-right bg-white px-3 py-1.5 rounded-xl border border-slate-100 shadow-sm">
            <p class="text-sm font-black text-blue-600 leading-none">{item.qty}</p>
            <p class="text-[9px] font-bold text-slate-400 uppercase mt-0.5">{item.unit}</p>
          </div>
        </div>
      {/each}
    </div>

    {#if detail.note}
      <div class="mt-5 p-4 bg-amber-50/80 border border-amber-100 rounded-2xl relative overflow-hidden">
        <div class="absolute top-0 left-0 w-1 h-full bg-amber-400"></div>
        <p class="text-[10px] font-bold text-amber-600 uppercase tracking-widest mb-1.5">Catatan Keperluan</p>
        <p class="text-[11px] font-medium text-amber-900 leading-relaxed">{detail.note}</p>
      </div>
    {/if}
  </div>
</div>

<div class="fixed bottom-0 left-1/2 -translate-x-1/2 w-full max-w-md z-50 p-4 pb-safe bg-gradient-to-t from-slate-50 via-slate-50/90 to-transparent pt-12 pointer-events-none">
  <div class="flex gap-3 pointer-events-auto bg-white p-2 rounded-3xl shadow-lg shadow-slate-200/50 border border-slate-100">
    <button class="flex-[1] bg-slate-50 text-slate-700 p-3.5 rounded-2xl flex flex-col items-center justify-center gap-1 hover:bg-slate-100 transition-colors active:scale-95 font-bold text-xs">
      <MessageCircle size={18} strokeWidth={2.5} class="text-slate-500" />
      Hubungi
    </button>

    <button class="flex-[2] bg-blue-600 text-white p-3.5 rounded-2xl flex items-center justify-center gap-2.5 hover:bg-blue-700 transition-colors active:scale-95 shadow-md shadow-blue-500/20 font-extrabold text-sm group">
      <Printer size={18} strokeWidth={2.5} class="group-hover:-translate-y-0.5 transition-transform" />
      Cetak Bukti Resi
    </button>
  </div>
</div>
