<script lang="ts">
  import { Inbox } from "@lucide/svelte";

  // Svelte 5 props
  let { columns = [], data = [], onEdit, onDelete, emptyMessage = "Belum ada data." } = $props();
</script>

<div class="overflow-x-auto rounded-xl border border-slate-200 bg-white shadow-sm">
  <table class="w-full text-left text-sm text-slate-600">
    <thead class="bg-slate-50 text-slate-900 border-b border-slate-200">
      <tr>
        {#each columns as col}
          <th scope="col" class="px-6 py-4 font-semibold">{col.label}</th>
        {/each}
        <th scope="col" class="px-6 py-4 font-semibold text-right">Aksi</th>
      </tr>
    </thead>
    <tbody class="divide-y divide-slate-100">
      {#if data.length === 0}
        <tr>
          <td colspan={columns.length + 1} class="px-6 py-12 text-center text-slate-500">
            <div class="flex flex-col items-center justify-center gap-2">
              <Inbox size={40} class="text-slate-300" />
              <p class="text-base font-medium text-slate-700">Data Kosong</p>
              <p class="text-sm">{emptyMessage}</p>
            </div>
          </td>
        </tr>
      {:else}
        {#each data as row}
          <tr class="hover:bg-slate-50 transition-colors">
            {#each columns as col}
              <td class="px-6 py-4">
                {#if col.type === "status"}
                  <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">
                    {row[col.key]}
                  </span>
                {:else}
                  {row[col.key]}
                {/if}
              </td>
            {/each}
            <td class="px-6 py-4 text-right space-x-2">
              <button onclick={() => onEdit(row)} class="text-blue-600 hover:text-blue-800 font-medium transition-colors"> Edit </button>
              <button onclick={() => onDelete(row)} class="text-rose-600 hover:text-rose-800 font-medium transition-colors"> Hapus </button>
            </td>
          </tr>
        {/each}
      {/if}
    </tbody>
  </table>
</div>
