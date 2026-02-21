<script lang="ts">
  import { X } from "@lucide/svelte";

  let { show = $bindable(false), title = "Modal Title", children, footer } = $props();

  function closeModal() {
    show = false;
  }
</script>

{#if show}
  <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-40 flex items-center justify-center p-4 transition-opacity">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg z-50 overflow-hidden flex flex-col max-h-[90vh]">
      <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
        <h3 class="text-lg font-bold text-slate-800">{title}</h3>
        <button onclick={closeModal} class="p-2 text-slate-400 hover:text-rose-500 hover:bg-rose-50 rounded-xl transition-colors">
          <X size={20} />
        </button>
      </div>

      <div class="p-6 overflow-y-auto flex-1">
        {@render children()}
      </div>

      {#if footer}
        <div class="px-6 py-4 border-t border-slate-100 bg-slate-50 flex justify-end gap-3">
          {@render footer()}
        </div>
      {/if}
    </div>
  </div>
{/if}
