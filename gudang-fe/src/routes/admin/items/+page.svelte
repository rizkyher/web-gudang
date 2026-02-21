<script lang="ts">
  import { Plus, Search, Package } from "@lucide/svelte";
  import Table from "$lib/components/Table.svelte";
  import Modal from "$lib/components/Modal.svelte";
  import FormInput from "$lib/components/FormInput.svelte";

  // State Data Barang
  let items = $state([
    { id: 1, code: "BRG-001", name: "Laptop Asus ROG", category: "Elektronik", stock: 15 },
    { id: 2, code: "BRG-002", name: "Kabel UTP Cat 6", category: "Networking", stock: 120 },
  ]);
  let searchQuery = $state("");

  // State untuk Modal & Form
  let isModalOpen = $state(false);
  let isEditing = $state(false);

  // Objek kosong untuk form reset
  const defaultForm = { id: null, code: "", name: "", category: "", stock: 0 };
  let formData = $state({ ...defaultForm });

  // Pilihan Kategori untuk Select Input
  const categoryOptions = [
    { value: "Elektronik", label: "Elektronik" },
    { value: "Networking", label: "Networking" },
    { value: "Aksesoris", label: "Aksesoris" },
    { value: "Lainnya", label: "Lainnya" },
  ];

  // Fitur Pencarian Dinamis
  let filteredItems = $derived(items.filter((item) => item.name.toLowerCase().includes(searchQuery.toLowerCase()) || item.code.toLowerCase().includes(searchQuery.toLowerCase())));

  const columns = [
    { label: "Kode Barang", key: "code" },
    { label: "Nama Barang", key: "name" },
    { label: "Kategori", key: "category" },
    { label: "Sisa Stok", key: "stock" },
  ];

  // Aksi Buka Modal Tambah
  function openAddModal() {
    isEditing = false;
    formData = { ...defaultForm };
    isModalOpen = true;
  }

  // Aksi Buka Modal Edit
  function openEditModal(item: any) {
    isEditing = true;
    formData = { ...item }; // Copy data ke form
    isModalOpen = true;
  }

  // Aksi Simpan Data (Nanti disambung ke Laravel API)
  function saveItem() {
    if (isEditing) {
      // Update data yang ada
      const index = items.findIndex((i) => i.id === formData.id);
      if (index !== -1) items[index] = { ...formData };
    } else {
      // Tambah data baru (Simulasi ID baru)
      items = [...items, { ...formData, id: Date.now() }];
    }
    isModalOpen = false;
  }

  // Aksi Hapus Data
  function deleteItem(item: any) {
    if (confirm(`Yakin ingin menghapus ${item.name}? Data yang dihapus tidak bisa dikembalikan.`)) {
      items = items.filter((i) => i.id !== item.id);
    }
  }
</script>

<div class="space-y-6">
  <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <div class="flex items-center gap-3">
      <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-xl flex items-center justify-center">
        <Package size={24} />
      </div>
      <div>
        <h1 class="text-2xl font-bold text-slate-900">Master Barang</h1>
        <p class="text-sm text-slate-500">Kelola semua daftar barang di gudang Khwarizmi.</p>
      </div>
    </div>

    <button onclick={openAddModal} class="flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl font-medium transition-all shadow-sm hover:shadow-blue-500/30">
      <Plus size={20} />
      Tambah Barang
    </button>
  </div>

  <div class="bg-white p-2 rounded-xl border border-slate-200 shadow-sm flex items-center gap-4">
    <div class="relative flex-1 max-w-md">
      <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
        <Search size={18} class="text-slate-400" />
      </div>
      <input type="text" bind:value={searchQuery} class="block w-full pl-11 pr-4 py-2 bg-slate-50 border-none rounded-lg focus:ring-0 text-sm transition-all" placeholder="Ketik nama atau kode barang untuk mencari..." />
    </div>
  </div>

  <Table {columns} data={filteredItems} onEdit={openEditModal} onDelete={deleteItem} emptyMessage="Belum ada barang. Klik tombol 'Tambah Barang' di pojok kanan atas." />
</div>

<Modal bind:show={isModalOpen} title={isEditing ? "Edit Data Barang" : "Tambah Barang Baru"}>
  <div class="space-y-2">
    <FormInput label="Kode Barang" placeholder="Contoh: BRG-001" bind:value={formData.code} required />
    <FormInput label="Nama Barang" placeholder="Contoh: Kabel UTP" bind:value={formData.name} required />
    <FormInput label="Kategori" type="select" options={categoryOptions} bind:value={formData.category} required />
    <FormInput label="Stok Awal" type="number" bind:value={formData.stock} required />
  </div>

  {#snippet footer()}
    <button onclick={() => (isModalOpen = false)} class="px-5 py-2.5 text-slate-600 hover:bg-slate-100 font-medium rounded-xl transition-colors"> Batal </button>
    <button onclick={saveItem} class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-xl transition-colors shadow-sm"> Simpan Data </button>
  {/snippet}
</Modal>
