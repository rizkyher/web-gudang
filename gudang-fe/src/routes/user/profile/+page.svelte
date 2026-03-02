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

<div class="bg-blue-600 px-6 pt-12 pb-24 text-white rounded-b-[40px] relative shadow-md">
  <div class="flex flex-col items-center text-center">
    <div class="w-24 h-24 bg-white/20 backdrop-blur-sm border-2 border-white/30 rounded-full flex items-center justify-center shadow-inner mb-4">
      <User size={40} class="text-white" />
    </div>

    <h1 class="text-2xl font-black tracking-tight">Pak Budi</h1>
    <p class="text-blue-200 text-sm font-medium mt-1">Guru IT / Staff Pengajar</p>

    <div class="mt-4 bg-white/10 text-white border border-white/20 backdrop-blur-sm px-4 py-1.5 rounded-full text-[11px] font-bold tracking-wide">Akun User Gudang</div>
  </div>
</div>

<div class="px-6 -mt-10 relative z-10 space-y-4 pb-24">
  <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
    <div class="p-4 flex items-center gap-4">
      <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-full flex items-center justify-center shrink-0">
        <Mail size={20} />
      </div>
      <div>
        <p class="text-[11px] text-slate-500 font-semibold mb-0.5">Email Terdaftar</p>
        <p class="text-sm font-bold text-slate-800 tracking-tight">budi.it@khwarizmi.com</p>
      </div>
    </div>
  </div>

  <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden flex flex-col">
    <button class="w-full p-4 flex items-center justify-between border-b border-slate-50 hover:bg-slate-50 transition-colors group">
      <div class="flex items-center gap-4 text-slate-700">
        <div class="w-10 h-10 bg-slate-50 text-slate-500 group-hover:bg-amber-50 group-hover:text-amber-600 transition-colors rounded-full flex items-center justify-center">
          <KeyRound size={18} />
        </div>
        <span class="font-bold text-sm">Ganti Password</span>
      </div>
      <ChevronRight size={18} class="text-slate-400 group-hover:text-amber-600 transition-colors" />
    </button>

    <button class="w-full p-4 flex items-center justify-between hover:bg-slate-50 transition-colors group">
      <div class="flex items-center gap-4 text-slate-700">
        <div class="w-10 h-10 bg-slate-50 text-slate-500 group-hover:bg-blue-50 group-hover:text-blue-600 transition-colors rounded-full flex items-center justify-center">
          <ShieldAlert size={18} />
        </div>
        <span class="font-bold text-sm">Bantuan & Syarat Ketentuan</span>
      </div>
      <ChevronRight size={18} class="text-slate-400 group-hover:text-blue-600 transition-colors" />
    </button>
  </div>

  <button
    onclick={handleLogout}
    class="w-full mt-2 bg-white border border-rose-100 text-rose-600 p-4 rounded-2xl shadow-sm flex items-center justify-center gap-2.5 font-bold hover:bg-rose-50 hover:border-rose-200 transition-all active:scale-[0.98] group"
  >
    <LogOut size={20} class="group-hover:-translate-x-1 transition-transform" />
    Keluar dari Aplikasi
  </button>

  <p class="text-center text-xs font-medium text-slate-400 mt-8">Khwarizmi Inventory App v1.0.0</p>
</div>

<style>
  :global(body) {
    background-color: #f8fafc;
  }
</style>
