<script lang="ts">
  import { Eye, EyeOff } from "@lucide/svelte";

  let email        = $state("");
  let password     = $state("");
  let showPassword = $state(false);
  let isLoading    = $state(false);
  let error        = $state("");
  let remember     = $state(false);

  async function handleLogin(e: Event) {
    e.preventDefault();
    error = "";
    if (!email || !password) { error = "Lengkapi semua field terlebih dahulu."; return; }
    isLoading = true;
    await new Promise(r => setTimeout(r, 1400));
    isLoading = false;
    // goto("/dashboard")
  }
</script>

<svelte:head>
  <title>Login · Khwarizmi Inventory</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet"/>
</svelte:head>

<style>
  :global(*, *::before, *::after) { box-sizing: border-box; margin: 0; padding: 0; }

  @keyframes fade-up {
    from { opacity: 0; transform: translateY(16px); }
    to   { opacity: 1; transform: translateY(0); }
  }
  @keyframes spin {
    to { transform: rotate(360deg); }
  }

  .page {
    min-height: 100svh;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f9fafb;
    font-family: 'Inter', sans-serif;
    padding: 24px;
  }

  .card {
    width: 100%;
    max-width: 400px;
    animation: fade-up .5s ease both;
  }

  /* Logo */
  .brand {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 40px;
  }
  .brand-icon {
    width: 36px;
    height: 36px;
    background: #111827;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
  }
  .brand-icon svg {
    width: 18px;
    height: 18px;
    stroke: #fff;
    fill: none;
    stroke-width: 1.8;
    stroke-linecap: round;
    stroke-linejoin: round;
  }
  .brand-name {
    font-size: 14px;
    font-weight: 600;
    color: #111827;
    letter-spacing: -.01em;
  }
  .brand-sub {
    font-size: 11px;
    color: #9ca3af;
    font-weight: 400;
    margin-top: 1px;
  }

  /* Heading */
  .heading {
    font-size: 22px;
    font-weight: 600;
    color: #111827;
    letter-spacing: -.025em;
    line-height: 1.3;
    margin-bottom: 6px;
  }
  .subheading {
    font-size: 13.5px;
    color: #6b7280;
    font-weight: 400;
    line-height: 1.5;
    margin-bottom: 32px;
  }

  /* Form */
  .form { display: flex; flex-direction: column; gap: 18px; }

  .field { display: flex; flex-direction: column; gap: 6px; }

  .label {
    font-size: 12px;
    font-weight: 500;
    color: #374151;
    letter-spacing: .01em;
  }

  .input-wrap { position: relative; }

  .input {
    width: 100%;
    padding: 11px 14px;
    background: #fff;
    border: 1px solid #e5e7eb;
    border-radius: 10px;
    font-family: 'Inter', sans-serif;
    font-size: 14px;
    color: #111827;
    font-weight: 400;
    outline: none;
    transition: border-color .15s, box-shadow .15s;
    -webkit-appearance: none;
  }
  .input::placeholder { color: #d1d5db; }
  .input:hover  { border-color: #d1d5db; }
  .input:focus  {
    border-color: #111827;
    box-shadow: 0 0 0 3px rgba(17,24,39,.07);
  }
  .input.has-eye { padding-right: 42px; }

  .eye-btn {
    position: absolute;
    right: 12px;
    top: 50%;
    transform: translateY(-50%);
    width: 24px;
    height: 24px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: none;
    border: none;
    color: #9ca3af;
    cursor: pointer;
    border-radius: 6px;
    transition: color .15s;
    padding: 0;
  }
  .eye-btn:hover { color: #374151; }

  /* Row */
  .row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-top: -4px;
  }

  .check-label {
    display: flex;
    align-items: center;
    gap: 7px;
    font-size: 13px;
    color: #6b7280;
    cursor: pointer;
    user-select: none;
  }
  .check-label input[type="checkbox"] {
    width: 15px;
    height: 15px;
    border: 1px solid #d1d5db;
    border-radius: 4px;
    accent-color: #111827;
    cursor: pointer;
  }

  .forgot {
    font-size: 13px;
    color: #6b7280;
    text-decoration: none;
    font-weight: 400;
    transition: color .15s;
  }
  .forgot:hover { color: #111827; }

  /* Error */
  .error {
    font-size: 13px;
    color: #dc2626;
    background: #fef2f2;
    border: 1px solid #fecaca;
    border-radius: 8px;
    padding: 10px 12px;
    margin-top: -4px;
  }

  /* Submit */
  .submit {
    width: 100%;
    padding: 12px;
    background: #111827;
    color: #fff;
    font-family: 'Inter', sans-serif;
    font-size: 14px;
    font-weight: 500;
    border: none;
    border-radius: 10px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    transition: background .15s, transform .1s, box-shadow .15s;
    margin-top: 4px;
    letter-spacing: -.01em;
  }
  .submit:hover:not(:disabled) {
    background: #1f2937;
    box-shadow: 0 4px 12px rgba(17,24,39,.18);
  }
  .submit:active:not(:disabled) { transform: scale(.99); }
  .submit:disabled { opacity: .55; cursor: not-allowed; }

  .spinner {
    width: 15px;
    height: 15px;
    border: 2px solid rgba(255,255,255,.35);
    border-top-color: #fff;
    border-radius: 50%;
    animation: spin .7s linear infinite;
  }

  /* Divider */
  .divider {
    height: 1px;
    background: #f3f4f6;
    margin: 4px 0;
  }

  /* Footer */
  .footer {
    text-align: center;
    font-size: 13px;
    color: #9ca3af;
    margin-top: 28px;
  }
  .footer a {
    color: #374151;
    font-weight: 500;
    text-decoration: none;
    transition: color .15s;
  }
  .footer a:hover { color: #111827; }
</style>

<div class="page">
  <div class="card">

    <!-- Brand -->
    <div class="brand">
      <div class="brand-icon">
        <svg viewBox="0 0 24 24">
          <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
          <polyline points="3.27 6.96 12 12.01 20.73 6.96"/>
          <line x1="12" y1="22.08" x2="12" y2="12"/>
        </svg>
      </div>
      <div>
        <div class="brand-name">Khwarizmi Academy</div>
        <div class="brand-sub">Inventory System</div>
      </div>
    </div>

    <!-- Heading -->
    <h1 class="heading">Masuk ke akun Anda</h1>
    <p class="subheading">Kelola inventaris gudang Khwarizmi dari satu tempat.</p>

    <!-- Form -->
    <form class="form" onsubmit={handleLogin}>

      <div class="field">
        <label class="label" for="email">Email</label>
        <div class="input-wrap">
          <input
            id="email"
            type="email"
            class="input"
            bind:value={email}
            placeholder="nama@khwarizmi.sch.id"
            autocomplete="email"
          />
        </div>
      </div>

      <div class="field">
        <label class="label" for="password">Password</label>
        <div class="input-wrap">
          <input
            id="password"
            type={showPassword ? "text" : "password"}
            class="input has-eye"
            bind:value={password}
            placeholder="••••••••"
            autocomplete="current-password"
          />
          <button
            type="button"
            class="eye-btn"
            onclick={() => showPassword = !showPassword}
            aria-label={showPassword ? "Sembunyikan password" : "Tampilkan password"}
          >
            {#if showPassword}<EyeOff size={15}/>{:else}<Eye size={15}/>{/if}
          </button>
        </div>
      </div>

      <div class="row">
        <label class="check-label">
          <input type="checkbox" bind:checked={remember} />
          Ingat saya
        </label>
        <a href="/forgot-password" class="forgot">Lupa password?</a>
      </div>

      {#if error}
        <div class="error">{error}</div>
      {/if}

      <div class="divider"></div>

      <button type="submit" class="submit" disabled={isLoading}>
        {#if isLoading}
          <span class="spinner"></span>
          <span>Masuk...</span>
        {:else}
          Masuk ke Dashboard
        {/if}
      </button>

    </form>

    <p class="footer">
      Belum punya akun? <a href="/register">Daftar sekarang</a>
    </p>

  </div>
</div>