<script lang="ts">
  import { Search, Plus, Minus, ShoppingBag, ArrowRight, Package, Box } from "@lucide/svelte";
  import { svelte } from "@sveltejs/vite-plugin-svelte";
  import { fade, fly, scale } from "svelte/transition";

  // Mock Data
  const availableItems = [
    { id: "BRG-001", name: "Spidol Snowman Hitam", category: "ATK", stock: 50, unit: "Pcs", icon: "🖋️" },
    { id: "BRG-002", name: "Kertas HVS A4 80gr", category: "ATK", stock: 20, unit: "Rim", icon: "📄" },
    { id: "BRG-003", name: "Tinta Printer Epson Black", category: "Elektronik", stock: 5, unit: "Botol", icon: "💧" },
    { id: "BRG-004", name: "Kabel HDMI 2 Meter", category: "Elektronik", stock: 0, unit: "Pcs", icon: "🔌" },
  ];

  let searchQuery = $state("");
  let activeCategory = $state("Semua");
  const categories = ["Semua", "ATK", "Elektronik", "Kebersihan"];

  // State Keranjang
  let cart: Record<string, number> = $state({});

  // Derivations
  let totalCartItems = $derived(Object.values(cart).reduce((a, b) => a + b, 0));
  let filteredItems = $derived(availableItems.filter((item) => (activeCategory === "Semua" || item.category === activeCategory) && item.name.toLowerCase().includes(searchQuery.toLowerCase())));

  // Actions
  function addToCart(id: string, maxStock: number) {
    if (!cart[id]) cart[id] = 0;
    if (cart[id] < maxStock) cart[id] += 1;
  }

  function removeFromCart(id: string) {
    if (cart[id] > 0) {
      cart[id] -= 1;
      if (cart[id] === 0) delete cart[id];
    }
  }

  function submitRequest() {
    if (totalCartItems === 0) return;
    alert("Permintaan berhasil diajukan!");
    cart = {};
  }
</script>

<svelte:head>
  <title>Minta Barang - Gudang IT</title>
  <style>
    :global(body) {
      background-color: #f8fafc; /* slate-50 */
      font-family: "Plus Jakarta Sans", sans-serif;
    }
    .scrollbar-hide::-webkit-scrollbar {
      display: none;
    }
    .scrollbar-hide {
      -ms-overflow-style: none;
      scrollbar-width: none;
    }
  </style>
</svelte:head>

<header class="sticky top-0 z-30 bg-blue-600/95 backdrop-blur-md px-6 pt-12 pb-6 text-white shadow-lg shadow-blue-900/10 rounded-b-[2.5rem]">
  <div class="flex items-center justify-between mb-6">
    <div>
      <p class="text-blue-100 text-xs font-medium uppercase tracking-widest">Inventory</p>
      <h1 class="text-2xl font-extrabold tracking-tight">Minta Barang</h1>
    </div>
    <div class="bg-white/20 p-2 rounded-full backdrop-blur-sm">
      <Package size={20} />
    </div>
  </div>

  <div class="relative group">
    <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
      <Search size={18} class="text-blue-300 group-focus-within:text-blue-600 transition-colors" />
    </div>
    <input
      type="text"
      bind:value={searchQuery}
      placeholder="Cari kebutuhanmu..."
      class="w-full bg-white/10 border border-white/20 rounded-2xl py-3.5 pl-12 pr-4 text-sm text-white placeholder:text-blue-100 outline-none focus:bg-white focus:text-slate-800 focus:ring-4 focus:ring-blue-400/30 transition-all shadow-inner"
    />
  </div>
</header>

<main class="px-6 py-6 space-y-8">
  <div class="flex gap-3 overflow-x-auto pb-2 scrollbar-hide -mx-6 px-6">
    {#each categories as cat}
      <button
        onclick={() => (activeCategory = cat)}
        class="px-5 py-2.5 rounded-2xl text-sm font-bold transition-all border-2
        {activeCategory === cat ? 'bg-slate-800 text-white border-slate-800 shadow-md scale-105' : 'bg-white text-slate-500 border-slate-100 hover:border-blue-200'}"
      >
        {cat}
      </button>
    {/each}
  </div>

  <div class="grid gap-4 pb-32">
    {#each filteredItems as item (item.id)}
      <div in:fly={{ y: 20, duration: 400 }} class="group bg-white p-4 rounded-[2rem] border border-slate-100 shadow-sm hover:shadow-xl hover:shadow-blue-900/5 hover:-translate-y-1 transition-all flex items-center gap-4">
        <div class="w-16 h-16 bg-slate-50 rounded-2xl flex items-center justify-center text-3xl group-hover:scale-110 transition-transform duration-300 border border-slate-50">
          {item.icon || "📦"}
        </div>

        <div class="flex-1 min-w-0">
          <span class="inline-block px-2 py-0.5 bg-slate-100 text-[10px] font-bold text-slate-500 rounded-md uppercase mb-1">{item.category}</span>
          <h3 class="font-bold text-slate-800 text-base truncate leading-tight">{item.name}</h3>
          <div class="flex items-center gap-2 mt-1">
            <div class="w-1.5 h-1.5 rounded-full {item.stock > 0 ? 'bg-emerald-500' : 'bg-rose-500'}"></div>
            <p class="text-xs font-medium {item.stock > 0 ? 'text-slate-500' : 'text-rose-500'}">
              {item.stock > 0 ? `${item.stock} ${item.unit} tersedia` : "Stok sedang kosong"}
            </p>
          </div>
        </div>

        <div class="flex flex-col items-center gap-1 shrink-0 bg-slate-50 p-1.5 rounded-2xl border border-slate-100">
          {#if item.stock > 0}
            <button onclick={() => addToCart(item.id, item.stock)} class="w-10 h-10 rounded-xl bg-white text-blue-600 shadow-sm flex items-center justify-center hover:bg-blue-600 hover:text-white transition-all active:scale-90">
              <Plus size={18} />
            </button>

            {#if cart[item.id]}
              <span in:scale class="font-black text-slate-800 text-sm py-1">{cart[item.id]}</span>
              <button onclick={() => removeFromCart(item.id)} class="w-10 h-10 rounded-xl bg-white text-slate-400 flex items-center justify-center hover:text-rose-500 transition-all active:scale-90">
                <Minus size={18} />
              </button>
            {/if}
          {:else}
            <div class="p-2 text-slate-300">
              <Box size={20} />
            </div>
          {/if}
        </div>
      </div>
    {/each}
  </div>
</main>

{#if totalCartItems > 0}
  <div in:fly={{ y: 100, duration: 500 }} out:fade class="fixed bottom-8 left-1/2 -translate-x-1/2 w-[calc(100%-3rem)] max-w-md z-40">
    <button onclick={submitRequest} class="w-full bg-blue-600 text-white p-5 rounded-[2rem] shadow-2xl shadow-blue-500/40 flex items-center justify-between hover:bg-blue-700 transition-all active:scale-95 group">
      <div class="flex items-center gap-4">
        <div class="relative bg-white/20 p-2 rounded-xl">
          <ShoppingBag size={24} />
          <span class="absolute -top-2 -right-2 bg-rose-500 text-white text-[10px] font-black w-6 h-6 flex items-center justify-center rounded-full border-2 border-blue-600">
            {totalCartItems}
          </span>
        </div>
        <div class="text-left">
          <p class="text-sm font-extrabold uppercase tracking-tight">Ajukan Sekarang</p>
          <p class="text-xs text-blue-100">Cek kembali barang pilihanmu</p>
        </div>
      </div>
      <div class="bg-white/20 p-2 rounded-full group-hover:translate-x-1 transition-transform">
        <ArrowRight size={20} />
      </div>
    </button>
  </div>
{/if}
