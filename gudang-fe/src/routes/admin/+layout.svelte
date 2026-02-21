<script lang="ts">
  import Navbar from "$lib/components/Navbar.svelte";
  import Sidebar from "$lib/components/Sidebar.svelte";

  let { children } = $props();

  // State untuk Sidebar
  let isSidebarOpen = $state(true);

  function toggleSidebar() {
    isSidebarOpen = !isSidebarOpen;
  }
</script>

<div class="flex h-screen bg-slate-50 font-sans text-slate-900 overflow-hidden">
  <Sidebar {isSidebarOpen} />

  <div class="flex flex-1 flex-col overflow-hidden w-full">
    <Navbar {toggleSidebar} />

    <main class="flex-1 overflow-y-auto p-4 sm:p-6">
      <div class="mx-auto max-w-7xl h-full">
        {@render children()}
      </div>
    </main>
  </div>
</div>
