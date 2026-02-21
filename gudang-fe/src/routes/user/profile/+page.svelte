<script lang="ts">
  import { User, Mail, ShieldAlert, LogOut, ChevronRight, KeyRound } from "@lucide/svelte";

  function handleLogout() {
    if (confirm("Yakin ingin keluar dari aplikasi?")) {
      // Logic hapus cookie/token dan redirect ke /login
      alert("Simulasi: Berhasil Logout!");
      window.location.href = "/login";
    }
  }
</script>

<div class="bg-slate-900 px-6 pt-12 pb-20 text-white rounded-b-[40px] relative shadow-lg">
  <div class="flex flex-col items-center text-center">
    <div class="w-24 h-24 bg-slate-700 border-4 border-slate-800 rounded-full flex items-center justify-center shadow-inner mb-4">
      <User size={40} class="text-slate-300" />
    </div>
    <h1 class="text-2xl font-bold">Pak Budi</h1>
    <p class="text-slate-400 text-sm mt-1">Guru IT / Staff Pengajar</p>
    <div class="mt-3 bg-blue-600/20 text-blue-400 border border-blue-500/30 px-3 py-1 rounded-full text-xs font-semibold">Akun User Gudang</div>
  </div>
</div>

<div class="px-6 -mt-10 relative z-10 space-y-4">
  <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
    <div class="p-4 border-b border-slate-50 flex items-center gap-4">
      <div class="w-10 h-10 bg-slate-50 text-slate-500 rounded-full flex items-center justify-center">
        <Mail size={18} />
      </div>
      <div>
        <p class="text-xs text-slate-500 font-semibold">Email Terdaftar</p>
        <p class="text-sm font-bold text-slate-800">budi.it@khwarizmi.com</p>
      </div>
    </div>
  </div>

  <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
    <button class="w-full p-4 flex items-center justify-between border-b border-slate-50 hover:bg-slate-50 transition-colors">
      <div class="flex items-center gap-4 text-slate-700">
        <KeyRound size={20} class="text-slate-400" />
        <span class="font-semibold text-sm">Ganti Password</span>
      </div>
      <ChevronRight size={18} class="text-slate-400" />
    </button>

    <button class="w-full p-4 flex items-center justify-between hover:bg-slate-50 transition-colors">
      <div class="flex items-center gap-4 text-slate-700">
        <ShieldAlert size={20} class="text-slate-400" />
        <span class="font-semibold text-sm">Bantuan & Syarat Ketentuan</span>
      </div>
      <ChevronRight size={18} class="text-slate-400" />
    </button>
  </div>

  <button onclick={handleLogout} class="w-full mt-6 bg-white border border-rose-200 text-rose-600 p-4 rounded-2xl shadow-sm flex items-center justify-center gap-2 font-bold hover:bg-rose-50 transition-colors active:scale-95">
    <LogOut size={20} />
    Keluar dari Aplikasi
  </button>

  <p class="text-center text-xs text-slate-400 mt-6 pb-6">Khwarizmi Inventory App v1.0.0</p>
</div>
