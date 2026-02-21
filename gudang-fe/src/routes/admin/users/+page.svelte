<script lang="ts">
  import { Users, UserPlus, Search, ShieldAlert, KeyRound } from "@lucide/svelte";
  import Table from "$lib/components/Table.svelte";
  import Modal from "$lib/components/Modal.svelte";
  import FormInput from "$lib/components/FormInput.svelte";

  // State Data User (Mock Data)
  let usersList = $state([
    { id: 1, name: "Budi Santoso", email: "admin@khwarizmi.com", role: "admin", status: "Aktif" },
    { id: 2, name: "Siti Aminah", email: "siti.gudang@khwarizmi.com", role: "staff_gudang", status: "Aktif" },
    { id: 3, name: "Ahmad Finance", email: "ahmad.fin@khwarizmi.com", role: "finance", status: "Nonaktif" },
  ]);

  let searchQuery = $state("");
  let isModalOpen = $state(false);
  let isEditing = $state(false);

  // Form State
  const defaultForm = { id: null, name: "", email: "", password: "", role: "staff_gudang", status: "Aktif" };
  let formData = $state({ ...defaultForm });

  // Pilihan Role & Status
  const roleOptions = [
    { value: "admin", label: "Admin Utama (Akses Semua)" },
    { value: "manager", label: "Manager (Laporan & Approval)" },
    { value: "finance", label: "Finance (Cek DO & Harga)" },
    { value: "staff_gudang", label: "Staff Gudang (Input Barang)" },
  ];

  const statusOptions = [
    { value: "Aktif", label: "🟢 Aktif (Bisa Login)" },
    { value: "Nonaktif", label: "🔴 Nonaktif (Tidak Bisa Login)" },
  ];

  // Derived State untuk filter dan styling badge role
  let filteredUsers = $derived(usersList.filter((user) => user.name.toLowerCase().includes(searchQuery.toLowerCase()) || user.email.toLowerCase().includes(searchQuery.toLowerCase())));

  let tableData = $derived(
    filteredUsers.map((user) => {
      // Mapping warna Role agar mudah dibedakan secara visual
      let roleBadge = "";
      if (user.role === "admin") roleBadge = "🔵 Admin";
      else if (user.role === "manager") roleBadge = "🟣 Manager";
      else if (user.role === "finance") roleBadge = "🟡 Finance";
      else roleBadge = "🟢 Staff Gudang";

      return {
        ...user,
        role_display: roleBadge,
        // Status menggunakan fitur col.type = 'status' dari Table.svelte kita sebelumnya
      };
    }),
  );

  const columns = [
    { label: "Nama Lengkap", key: "name" },
    { label: "Email", key: "email" },
    { label: "Hak Akses (Role)", key: "role_display" },
    { label: "Status Akun", key: "status", type: "status" },
  ];

  function openAddModal() {
    isEditing = false;
    formData = { ...defaultForm };
    isModalOpen = true;
  }

  function openEditModal(user: any) {
    isEditing = true;
    formData = { ...user, password: "" }; // Kosongkan password saat edit
    isModalOpen = true;
  }

  function saveUser() {
    if (isEditing) {
      const index = usersList.findIndex((u) => u.id === formData.id);
      if (index !== -1) {
        // Jangan timpa password jika tidak diisi saat edit
        usersList[index] = {
          ...usersList[index],
          name: formData.name,
          email: formData.email,
          role: formData.role,
          status: formData.status,
        };
      }
    } else {
      usersList = [
        ...usersList,
        {
          id: Date.now(),
          name: formData.name,
          email: formData.email,
          role: formData.role,
          status: formData.status,
        },
      ];
    }
    isModalOpen = false;
  }

  function deleteUser(user: any) {
    if (user.id === 1) {
      alert("Akses Ditolak: Anda tidak bisa menghapus akun Admin Utama Anda sendiri!");
      return;
    }
    if (confirm(`Yakin ingin mencabut akses dan menghapus akun ${user.name}?`)) {
      usersList = usersList.filter((u) => u.id !== user.id);
    }
  }
</script>

<div class="space-y-6">
  <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <div class="flex items-center gap-3">
      <div class="w-12 h-12 bg-slate-800 text-white rounded-xl flex items-center justify-center shadow-md">
        <Users size={24} />
      </div>
      <div>
        <h1 class="text-2xl font-bold text-slate-900">Manajemen Pengguna</h1>
        <p class="text-sm text-slate-500">Atur siapa saja yang bisa mengakses sistem gudang ini.</p>
      </div>
    </div>

    <button onclick={openAddModal} class="flex items-center justify-center gap-2 bg-slate-800 hover:bg-slate-900 text-white px-5 py-2.5 rounded-xl font-medium transition-all shadow-sm">
      <UserPlus size={20} />
      Tambah Pengguna
    </button>
  </div>

  <div class="bg-white p-2 rounded-xl border border-slate-200 shadow-sm flex items-center gap-4">
    <div class="relative flex-1 w-full max-w-md">
      <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
        <Search size={18} class="text-slate-400" />
      </div>
      <input type="text" bind:value={searchQuery} class="block w-full pl-11 pr-4 py-2 bg-slate-50 border-none rounded-lg focus:ring-0 text-sm transition-all" placeholder="Cari nama atau email pengguna..." />
    </div>
  </div>

  <Table {columns} data={tableData} onEdit={openEditModal} onDelete={deleteUser} emptyMessage="Belum ada pengguna tambahan." />
</div>

<Modal bind:show={isModalOpen} title={isEditing ? "Edit Data Pengguna" : "Tambah Pengguna Baru"}>
  <div class="space-y-4">
    <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 text-sm text-amber-800 flex items-start gap-3">
      <ShieldAlert size={18} class="mt-0.5 shrink-0" />
      <div>
        <p class="font-bold mb-1">Penting: Pemilihan Role (Hak Akses)</p>
        <ul class="list-disc pl-4 space-y-0.5 text-xs">
          <li><b>Admin</b>: Memiliki akses penuh menghapus data & user lain.</li>
          <li><b>Staff Gudang</b>: Hanya bisa melihat & menambah stok.</li>
        </ul>
      </div>
    </div>

    <FormInput label="Nama Lengkap" placeholder="Contoh: Budi Santoso" bind:value={formData.name} required />
    <FormInput label="Alamat Email" type="email" placeholder="email@khwarizmi.com" bind:value={formData.email} required />

    <div class="relative">
      <FormInput label={isEditing ? "Password Baru (Kosongkan jika tidak ingin ganti)" : "Password"} type="password" placeholder="Minimal 8 karakter" bind:value={formData.password} required={!isEditing} />
      <div class="absolute right-3 top-9 text-slate-400">
        <KeyRound size={18} />
      </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
      <FormInput label="Hak Akses (Role)" type="select" options={roleOptions} bind:value={formData.role} required />
      <FormInput label="Status Akun" type="select" options={statusOptions} bind:value={formData.status} required />
    </div>
  </div>

  {#snippet footer()}
    <button onclick={() => (isModalOpen = false)} class="px-5 py-2.5 text-slate-600 hover:bg-slate-100 font-medium rounded-xl transition-colors"> Batal </button>
    <button onclick={saveUser} class="px-5 py-2.5 bg-slate-800 hover:bg-slate-900 text-white font-medium rounded-xl transition-colors shadow-sm"> Simpan Pengguna </button>
  {/snippet}
</Modal>
