<script lang="ts">
  import { Truck, Plus, Search, FileText, Printer, CheckCircle, Clock, MapPin, Package, Save } from "@lucide/svelte";
  import Table from "$lib/components/Table.svelte";
  import Modal from "$lib/components/Modal.svelte";
  import FormInput from "$lib/components/FormInput.svelte";

  // --- 1. STATE MANAGEMENT ---
  let deliveryOrders = $state([
    {
      id: 1,
      do_number: "DO-2602-001",
      customer: "PT. Maju Mundur",
      date: "21 Feb 2026",
      items_count: 3,
      status: "Dikirim",
      address: "Jl. Industri Raya No. 12, Jakarta",
      items: [
        { code: "BRG-001", name: "Laptop Asus ROG", qty: 2 },
        { code: "BRG-003", name: "Router Mikrotik", qty: 1 },
      ],
    },
    {
      id: 2,
      do_number: "DO-2602-002",
      customer: "CV. Angin Ribut",
      date: "21 Feb 2026",
      items_count: 1,
      status: "Pending",
      address: "Kawasan Niaga Blok B",
      items: [{ code: "BRG-002", name: "Kabel UTP Cat 6", qty: 1 }],
    },
  ]);

  let searchQuery = $state("");
  let isModalOpen = $state(false);

  // State untuk Mode Edit
  let isEditing = $state(false);
  let editingId: number | null = $state(null);

  // State Print
  let isPrintModalOpen = $state(false);
  let printData: any = $state(null);

  // Form State
  const defaultForm = { customer: "", address: "", notes: "" };
  let formData = $state({ ...defaultForm });
  // selectedItems menggunakan 'item_code' untuk binding select option
  let selectedItems = $state([{ item_code: "", qty: 1 }]);

  const availableItems = [
    { value: "BRG-001", label: "Laptop Asus ROG (Stok: 15)", clean_name: "Laptop Asus ROG" },
    { value: "BRG-002", label: "Kabel UTP Cat 6 (Stok: 120)", clean_name: "Kabel UTP Cat 6" },
    { value: "BRG-003", label: "Router Mikrotik (Stok: 5)", clean_name: "Router Mikrotik" },
  ];

  // --- 2. DERIVED STATES ---
  let filteredDO = $derived(deliveryOrders.filter((doItem) => doItem.do_number.toLowerCase().includes(searchQuery.toLowerCase()) || doItem.customer.toLowerCase().includes(searchQuery.toLowerCase())));

  let tableData = $derived(
    filteredDO.map((item) => ({
      ...item,
      status_badge: item.status === "Selesai" ? "🟢 Selesai" : item.status === "Dikirim" ? "🔵 Dikirim" : "🟠 Pending",
    })),
  );

  const columns = [
    { label: "No. DO", key: "do_number" },
    { label: "Tanggal", key: "date" },
    { label: "Pelanggan", key: "customer" },
    { label: "Total Item", key: "items_count" },
    { label: "Status", key: "status_badge" },
  ];

  // --- 3. LOGIC FUNCTIONS ---

  // Membuka Modal untuk CREATE
  function openCreateModal() {
    isEditing = false;
    editingId = null;
    formData = { ...defaultForm };
    selectedItems = [{ item_code: "", qty: 1 }];
    isModalOpen = true;
  }

  // Membuka Modal untuk EDIT
  function openEditModal(doItem: any) {
    isEditing = true;
    editingId = doItem.id;
    // Populate form dengan data yang ada
    formData = {
      customer: doItem.customer,
      address: doItem.address,
      notes: "", // Asumsi notes tidak disimpan di mock data utama
    };
    // Map data items yang ada ke format selectedItems (item_code & qty)
    selectedItems = doItem.items.map((i: any) => ({
      item_code: i.code,
      qty: i.qty,
    }));
    isModalOpen = true;
  }

  function addItemRow() {
    selectedItems = [...selectedItems, { item_code: "", qty: 1 }];
  }

  function removeItemRow(index: number) {
    if (selectedItems.length > 1) {
      selectedItems = selectedItems.filter((_, i) => i !== index);
    }
  }

  // Fungsi Simpan (Bisa untuk Create Baru atau Update Existing)
  function saveDO() {
    // 1. Validasi Sederhana
    if (!formData.customer || selectedItems.length === 0 || !selectedItems[0].item_code) {
      alert("Mohon isi nama pelanggan dan minimal 1 barang.");
      return;
    }

    // 2. Siapkan data barang yang bersih
    const itemsToSave = selectedItems.map((item) => {
      const foundItem = availableItems.find((opt) => opt.value === item.item_code);
      return {
        code: item.item_code,
        name: foundItem ? foundItem.clean_name : "Barang Tidak Diketahui",
        qty: item.qty || 0,
      };
    });

    // 3. Hitung total item
    const totalItemsCount = itemsToSave.reduce((acc, curr) => acc + curr.qty, 0);

    if (isEditing && editingId) {
      // --- LOGIKA UPDATE (EDIT) ---
      deliveryOrders = deliveryOrders.map((order) => {
        if (order.id === editingId) {
          return {
            ...order,
            customer: formData.customer,
            address: formData.address || "Alamat tidak diinput",
            items: itemsToSave,
            items_count: totalItemsCount,
            // Catatan: Tanggal, No DO, dan Status biasanya tidak berubah saat edit data dasar
          };
        }
        return order;
      });
    } else {
      // --- LOGIKA CREATE NEW ---
      const newDO = {
        id: Date.now(),
        do_number: `DO-2602-00${deliveryOrders.length + 1}`,
        customer: formData.customer,
        address: formData.address || "Alamat tidak diinput",
        date: new Date().toLocaleDateString("id-ID", { day: "2-digit", month: "short", year: "numeric" }),
        items_count: totalItemsCount,
        status: "Pending",
        items: itemsToSave,
      };
      deliveryOrders = [newDO, ...deliveryOrders];
    }

    // Reset dan tutup modal
    isModalOpen = false;
    isEditing = false;
    editingId = null;
  }

  // Fungsi Print dari Tabel atau dari Modal Edit
  function preparePrint(doItem: any) {
    // Jika dipanggil dari modal edit, gunakan data yang sedang diedit
    if (isEditing && editingId) {
      // Cari data terbaru dari state deliveryOrders berdasarkan ID yang sedang diedit
      const currentData = deliveryOrders.find((o) => o.id === editingId);
      if (currentData) {
        printData = currentData;
      }
    } else {
      // Jika dipanggil dari tabel langsung
      printData = doItem;
    }
    isPrintModalOpen = true;
  }

  function executePrint() {
    window.print();
  }
</script>

<svelte:head>
  <style>
    @media print {
      @page {
        size: A4 portrait;
        margin: 15mm;
      }
      body {
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
        background-color: white !important;
      }
      body * {
        visibility: hidden;
      }
      #printable-surat,
      #printable-surat * {
        visibility: visible;
      }
      #printable-surat {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        margin: 0;
        padding: 0;
        border: none !important;
        box-shadow: none !important;
      }
      .no-print {
        display: none !important;
      }
    }
  </style>
</svelte:head>

<div class="space-y-6">
  <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <div class="flex items-center gap-3">
      <div class="w-12 h-12 bg-amber-100 text-amber-600 rounded-xl flex items-center justify-center shadow-sm">
        <Truck size={24} />
      </div>
      <div>
        <h1 class="text-2xl font-bold text-slate-900">Delivery Order (DO)</h1>
        <p class="text-sm text-slate-500">Kelola pengiriman barang keluar ke pelanggan.</p>
      </div>
    </div>

    <button onclick={openCreateModal} class="flex items-center justify-center gap-2 bg-amber-500 hover:bg-amber-600 text-white px-5 py-2.5 rounded-xl font-medium transition-all shadow-sm hover:shadow-amber-500/30">
      <Plus size={20} /> Buat DO Baru
    </button>
  </div>

  <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
    <div class="bg-white p-5 rounded-2xl border border-slate-200 flex items-center gap-4 shadow-sm hover:shadow-md transition-shadow">
      <div class="p-3 bg-amber-50 text-amber-600 rounded-xl"><Clock size={24} /></div>
      <div>
        <p class="text-sm font-medium text-slate-500">Menunggu Diproses</p>
        <p class="text-2xl font-bold text-slate-800">1</p>
      </div>
    </div>
    <div class="bg-white p-5 rounded-2xl border border-slate-200 flex items-center gap-4 shadow-sm hover:shadow-md transition-shadow">
      <div class="p-3 bg-blue-50 text-blue-600 rounded-xl"><Truck size={24} /></div>
      <div>
        <p class="text-sm font-medium text-slate-500">Sedang Dikirim</p>
        <p class="text-2xl font-bold text-slate-800">1</p>
      </div>
    </div>
    <div class="bg-white p-5 rounded-2xl border border-slate-200 flex items-center gap-4 shadow-sm hover:shadow-md transition-shadow">
      <div class="p-3 bg-emerald-50 text-emerald-600 rounded-xl"><CheckCircle size={24} /></div>
      <div>
        <p class="text-sm font-medium text-slate-500">Pengiriman Selesai</p>
        <p class="text-2xl font-bold text-slate-800">1</p>
      </div>
    </div>
  </div>

  <div class="bg-white p-2 rounded-xl border border-slate-200 shadow-sm flex items-center gap-4">
    <div class="relative flex-1 w-full max-w-md">
      <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
        <Search size={18} class="text-slate-400" />
      </div>
      <input
        type="text"
        bind:value={searchQuery}
        class="block w-full pl-11 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-amber-500 outline-none text-sm transition-all"
        placeholder="Cari No. DO atau nama pelanggan..."
      />
    </div>
  </div>

  <Table {columns} data={tableData} onEdit={openEditModal} onDelete={() => {}} emptyMessage="Belum ada data Delivery Order." />
</div>

<Modal bind:show={isModalOpen} title={isEditing ? "Edit Delivery Order" : "Buat Delivery Order Baru"}>
  <div class="space-y-5 p-1">
    <div class="bg-amber-50 border border-amber-200/60 rounded-xl p-4 flex gap-3">
      <FileText size={20} class="text-amber-600 shrink-0" />
      <div>
        <p class="text-sm font-semibold text-amber-800">Formulir Pengiriman Barang</p>
        <p class="text-xs text-amber-700 mt-1">Pastikan data pelanggan dan alamat diisi dengan lengkap untuk keperluan cetak Surat Jalan kurir.</p>
      </div>
    </div>

    <div class="space-y-4 bg-white border border-slate-200 p-4 rounded-xl">
      <h4 class="font-bold text-slate-800 text-sm flex items-center gap-2"><MapPin size={16} /> Tujuan Pengiriman</h4>
      <FormInput label="Nama Pelanggan / Perusahaan" bind:value={formData.customer} required />
      <div class="flex flex-col gap-1.5">
        <label class="text-sm font-semibold text-slate-700">Alamat Lengkap</label>
        <textarea bind:value={formData.address} rows="2" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl focus:bg-white focus:ring-2 focus:ring-amber-500 focus:border-amber-500 outline-none transition-all text-sm"
        ></textarea>
      </div>
    </div>

    <div class="space-y-4 bg-slate-50 border border-slate-200 p-4 rounded-xl">
      <h4 class="font-bold text-slate-800 text-sm flex items-center gap-2"><Package size={16} /> Daftar Barang Keluar</h4>

      <div class="space-y-3">
        {#each selectedItems as item, index}
          <div class="flex gap-2 items-start bg-white p-2 rounded-lg border border-slate-200 shadow-sm">
            <div class="flex-1">
              <select bind:value={item.item_code} class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-amber-500 outline-none">
                <option value="" disabled selected>-- Pilih Barang --</option>
                {#each availableItems as opt}
                  <option value={opt.value}>{opt.label}</option>
                {/each}
              </select>
            </div>
            <div class="w-24 shrink-0">
              <input type="number" bind:value={item.qty} min="1" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-amber-500 outline-none text-center" placeholder="Qty" />
            </div>
            {#if selectedItems.length > 1}
              <button onclick={() => removeItemRow(index)} class="p-2 text-rose-500 hover:bg-rose-50 hover:text-rose-600 rounded-lg transition-colors" title="Hapus baris"> ✖ </button>
            {/if}
          </div>
        {/each}
      </div>

      <button onclick={addItemRow} class="text-sm font-semibold text-amber-600 hover:text-amber-700 flex items-center gap-1.5 mt-2 bg-amber-50 px-3 py-1.5 rounded-lg border border-amber-200/50 transition-colors">
        <Plus size={16} /> Tambah Barang Lain
      </button>
    </div>
  </div>

  {#snippet footer()}
    <div class="flex justify-between w-full">
      {#if isEditing}
        <button onclick={() => preparePrint(null)} class="flex items-center gap-2 px-4 py-2.5 text-blue-600 hover:bg-blue-50 font-medium rounded-xl transition-colors border border-blue-200 mr-auto">
          <Printer size={18} /> Print Preview
        </button>
      {:else}
        <div></div>
      {/if}

      <div class="flex gap-3">
        <button onclick={() => (isModalOpen = false)} class="px-5 py-2.5 text-slate-600 hover:bg-slate-100 font-medium rounded-xl transition-colors"> Batal </button>
        <button onclick={saveDO} class="flex items-center gap-2 px-5 py-2.5 bg-amber-500 hover:bg-amber-600 text-white font-medium rounded-xl transition-all shadow-sm hover:shadow-amber-500/30">
          <Save size={18} />
          {isEditing ? "Simpan Perubahan" : "Simpan & Buat DO"}
        </button>
      </div>
    </div>
  {/snippet}
</Modal>

{#if isPrintModalOpen && printData}
  <Modal bind:show={isPrintModalOpen} title="Preview Cetak Surat Jalan">
    <div id="printable-surat" class="bg-white p-6 sm:p-10 text-slate-900 border border-slate-200 rounded-xl shadow-sm font-sans max-w-4xl mx-auto">
      <div class="flex items-center justify-between border-b-4 border-slate-800 pb-5 mb-6">
        <div class="flex items-center gap-4">
          <div class="w-16 h-16 bg-slate-800 text-white flex items-center justify-center font-bold text-4xl rounded-xl print:border-2 print:border-slate-800 print:text-slate-800 print:bg-transparent">K</div>
          <div>
            <h2 class="text-2xl font-black uppercase tracking-widest text-slate-900">Khwarizmi Academy</h2>
            <p class="text-sm font-semibold text-slate-600 tracking-wide mt-0.5">SISTEM LOGISTIK & INVENTARIS</p>
            <p class="text-xs text-slate-500 mt-1">Jl. Teknologi Raya No. 99, Jakarta Selatan 12345<br />Telp: (021) 123-4567 | Email: logistic@khwarizmi.com</p>
          </div>
        </div>
        <div class="text-right">
          <h1 class="text-3xl font-black text-slate-800 tracking-widest uppercase mb-2">Surat Jalan</h1>
          <div class="inline-block bg-slate-100 print:border print:border-slate-400 px-4 py-1.5 rounded-lg">
            <p class="font-bold text-slate-800 text-lg">{printData.do_number}</p>
          </div>
        </div>
      </div>

      <div class="flex justify-between text-sm border-2 border-slate-200 rounded-xl p-4 mb-6">
        <div class="w-1/2 pr-4 border-r-2 border-slate-200">
          <p class="text-slate-500 font-bold mb-2 uppercase text-xs tracking-wider">Dikirim Kepada:</p>
          <p class="font-black text-slate-800 text-lg">{printData.customer}</p>
          <p class="text-slate-700 mt-1 leading-relaxed whitespace-pre-wrap">{printData.address || "Alamat tidak tersedia. Barang diambil di tempat."}</p>
        </div>
        <div class="w-1/2 pl-4 flex flex-col justify-center space-y-3">
          <div class="flex justify-between items-center border-b border-dashed border-slate-200 pb-2">
            <span class="text-slate-500 font-semibold">Tanggal Kirim:</span>
            <span class="font-bold text-slate-800">{printData.date}</span>
          </div>
          <div class="flex justify-between items-center border-b border-dashed border-slate-200 pb-2">
            <span class="text-slate-500 font-semibold">Status Order:</span>
            <span class="font-bold text-slate-800 uppercase">{printData.status}</span>
          </div>
          <div class="flex justify-between items-center">
            <span class="text-slate-500 font-semibold">Diproses Oleh:</span>
            <span class="font-bold text-slate-800">Admin Utama</span>
          </div>
        </div>
      </div>

      <table class="w-full text-left border-collapse mb-6 text-sm">
        <thead>
          <tr class="bg-slate-100 border-y-2 border-slate-800">
            <th class="py-3 px-4 font-bold text-center w-16 border-x border-slate-300">No.</th>
            <th class="py-3 px-4 font-bold border-r border-slate-300">Kode & Nama Barang</th>
            <th class="py-3 px-4 font-bold text-center w-32 border-r border-slate-300">Jumlah (Qty)</th>
            <th class="py-3 px-4 font-bold text-center w-40 border-r border-slate-300">Keterangan</th>
          </tr>
        </thead>
        <tbody class="border-b-2 border-slate-800">
          {#if printData.items && printData.items.length > 0}
            {#each printData.items as item, index}
              <tr class="border-b border-slate-200">
                <td class="py-4 px-4 text-center border-x border-slate-300">{index + 1}</td>
                <td class="py-4 px-4 border-r border-slate-300">
                  <p class="font-bold text-slate-800 text-base">{item.name}</p>
                  <p class="text-xs text-slate-500 mt-0.5 uppercase tracking-wide">KODE: {item.code}</p>
                </td>
                <td class="py-4 px-4 text-center border-r border-slate-300">
                  <span class="font-black text-lg">{item.qty}</span> Unit
                </td>
                <td class="py-4 px-4 text-center text-slate-500 border-r border-slate-300">-</td>
              </tr>
            {/each}
          {:else}
            <tr class="border-b border-slate-200">
              <td colspan="4" class="py-6 px-4 text-center text-slate-500 border-x border-slate-300 italic">Data barang tidak ditemukan atau belum diinput.</td>
            </tr>
          {/if}
          <tr class="h-8 border-b border-slate-200"><td class="border-x border-slate-300"></td><td class="border-r border-slate-300"></td><td class="border-r border-slate-300"></td><td class="border-r border-slate-300"></td></tr>
        </tbody>
      </table>

      <div class="mb-10">
        <p class="text-xs font-bold text-slate-800 mb-1 uppercase tracking-wider">Catatan Penting:</p>
        <div class="border border-slate-300 bg-slate-50 p-3 text-xs text-slate-700 rounded-lg">
          <ol class="list-decimal list-inside space-y-1">
            <li>Barang telah dicek dan diserahkan dalam keadaan baik dan jumlah yang cukup.</li>
            <li>Surat jalan ini merupakan bukti resmi serah terima barang.</li>
            <li>Segala bentuk klaim/komplain setelah kurir meninggalkan lokasi tidak akan dilayani tanpa bukti valid.</li>
          </ol>
        </div>
      </div>

      <div class="grid grid-cols-3 gap-6 text-center text-sm mt-8">
        <div>
          <p class="font-semibold text-slate-600 mb-20">Dibuat Oleh,</p>
          <p class="font-black text-slate-800 underline">Admin Gudang</p>
          <p class="text-xs text-slate-500 mt-1">Khwarizmi Academy</p>
        </div>
        <div>
          <p class="font-semibold text-slate-600 mb-20">Pengemudi / Kurir,</p>
          <hr class="border-slate-800 w-3/4 mx-auto mb-1 border-dashed" />
          <p class="text-xs text-slate-500">Nama Terang & Tanda Tangan</p>
        </div>
        <div>
          <p class="font-semibold text-slate-600 mb-20">Penerima Barang,</p>
          <hr class="border-slate-800 w-3/4 mx-auto mb-1 border-dashed" />
          <p class="text-xs text-slate-500">Nama, Tanda Tangan & Cap Toko</p>
        </div>
      </div>
    </div>

    {#snippet footer()}
      <button onclick={() => (isPrintModalOpen = false)} class="no-print px-5 py-2.5 text-slate-600 hover:bg-slate-100 font-medium rounded-xl transition-colors"> Batal </button>
      <button onclick={executePrint} class="no-print flex items-center gap-2 px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl transition-all shadow-md hover:shadow-blue-500/30">
        <Printer size={20} /> Cetak / Save PDF
      </button>
    {/snippet}
  </Modal>
{/if}
