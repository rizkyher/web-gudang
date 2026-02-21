<script lang="ts">
  import { Home, PackagePlus, ClipboardList, User } from "@lucide/svelte";
  import { page } from "$app/stores";

  let { children } = $props();

  const navItems = [
    { name: "Beranda", href: "/user", icon: Home },
    { name: "Minta Barang", href: "/user/request", icon: PackagePlus },
    { name: "Riwayat", href: "/user/history", icon: ClipboardList },
    { name: "Profil", href: "/user/profile", icon: User },
  ];

  let currentPath = $derived($page.url.pathname);
</script>

<div class="bg-slate-100 min-h-screen flex justify-center font-sans text-slate-900">
  <div class="w-full max-w-md bg-white min-h-screen relative flex flex-col shadow-2xl overflow-hidden">
    <main class="flex-1 overflow-y-auto pb-20">
      {@render children()}
    </main>

    <nav class="fixed bottom-0 w-full max-w-md bg-white border-t border-slate-200 px-6 py-2 pb-safe z-50">
      <div class="flex justify-between items-center">
        {#each navItems as item}
          {@const isActive = currentPath.startsWith(item.href)}
          <a href={item.href} class="flex flex-col items-center gap-1 p-2 rounded-xl transition-all {isActive ? 'text-blue-600' : 'text-slate-400'}">
            <div class={isActive ? "bg-blue-50 p-1.5 rounded-lg" : "p-1.5"}>
              <item.icon size={22} strokeWidth={isActive ? 2.5 : 2} />
            </div>
            <span class="text-[10px] font-semibold">{item.name}</span>
          </a>
        {/each}
      </div>
    </nav>
  </div>
</div>
