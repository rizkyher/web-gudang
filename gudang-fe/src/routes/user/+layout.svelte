<script lang="ts">
  import { Home, PackagePlus, ClipboardList, User } from "@lucide/svelte";
  import { page } from "$app/stores";

  let { children } = $props();

  const navItems = [
    { name: "Beranda", href: "/user", icon: Home },
    { name: "Minta", href: "/user/request", icon: PackagePlus },
    { name: "Riwayat", href: "/user/history", icon: ClipboardList },
    { name: "Profil", href: "/user/profile", icon: User },
  ];

  let currentPath = $derived($page.url.pathname);

  const isActiveRoute = (itemHref: string, path: string) => {
    if (itemHref === "/user") {
      return path === "/user" || path === "/user/";
    }
    return path.startsWith(itemHref);
  };
</script>

<div class="bg-slate-100 h-dvh overflow-hidden flex justify-center font-sans text-slate-900">
  <div class="w-full max-w-md bg-slate-50 h-full relative flex flex-col shadow-2xl overflow-x-hidden">
    <main class="flex-1 overflow-y-auto overflow-x-hidden w-full pb-28 scroll-smooth">
      {@render children()}
    </main>

    <nav class="absolute bottom-0 w-full z-50 px-4 py-5 pointer-events-none">
      <div class="bg-white/90 backdrop-blur-xl border border-slate-200/60 shadow-[0_8px_30px_rgb(0,0,0,0.08)] rounded-3xl px-6 py-3 flex justify-between items-center pointer-events-auto">
        {#each navItems as item}
          {@const isActive = isActiveRoute(item.href, currentPath)}
          <a href={item.href} class="relative flex flex-col items-center gap-1 p-2 rounded-2xl transition-all duration-300 active:scale-95 group {isActive ? 'text-blue-600' : 'text-slate-400 hover:text-slate-600'}">
            <div class="relative p-1.5 z-10 transition-colors duration-300 {isActive ? 'bg-blue-50 text-blue-600 rounded-xl' : 'group-hover:bg-slate-50 rounded-xl'}">
              <item.icon size={22} strokeWidth={isActive ? 2.5 : 2} class="transition-transform duration-300 {isActive ? 'scale-110' : ''}" />
            </div>

            <span class="text-[10px] font-bold tracking-wide transition-all duration-300">
              {item.name}
            </span>

            {#if isActive}
              <div class="absolute -bottom-1 w-1 h-1 bg-blue-600 rounded-full shadow-sm shadow-blue-500/50"></div>
            {/if}
          </a>
        {/each}
      </div>
    </nav>
  </div>
</div>
