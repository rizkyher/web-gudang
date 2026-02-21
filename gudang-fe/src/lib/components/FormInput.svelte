<script lang="ts">
  // Menggunakan Svelte 5 $bindable agar value bisa terhubung bolak-balik (two-way binding)
  let {
    label,
    id = crypto.randomUUID(),
    type = "text",
    value = $bindable(""),
    placeholder = "",
    required = false,
    options = [], // Khusus untuk type select
  } = $props();
</script>

<div class="flex flex-col gap-1.5 mb-4">
  <label for={id} class="text-sm font-semibold text-slate-700 flex gap-1">
    {label}
    {#if required}
      <span class="text-rose-500">*</span>
    {/if}
  </label>

  {#if type === "select"}
    <select {id} bind:value {required} class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all outline-none text-slate-700">
      <option value="" disabled selected>Pilih {label}</option>
      {#each options as opt}
        <option value={opt.value}>{opt.label}</option>
      {/each}
    </select>
  {:else}
    <input
      {type}
      {id}
      bind:value
      {placeholder}
      {required}
      class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all outline-none text-slate-700"
    />
  {/if}
</div>
