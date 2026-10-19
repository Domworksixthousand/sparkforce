
<style>
  :root { --brand: #0d9488; --brand-dark: #0a6f66; --brand-soft: #f0fdfa; --brand-ring: #99f6e4; }
  .btn-brand { background: var(--brand); border-color: var(--brand); color: #fff; }
  .btn-brand:hover { background: var(--brand-dark); border-color: var(--brand-dark); }
  .btn-outline-brand { border-color: var(--brand); color: var(--brand); background: transparent; }
  .btn-outline-brand:hover { background: var(--brand); border-color: var(--brand); color: #fff; }

  .req-item { transition: border-color .2s, background-color .2s, box-shadow .2s, transform .2s; }
  .req-item:hover { border-color: var(--brand); box-shadow: 0 6px 18px rgba(13,148,136,.12); transform: translateY(-1px); }
  .req-item.open { border-color: var(--brand); background: var(--brand-soft); }
  .req-item .num { background: var(--brand-soft); color: var(--brand); transition: background-color .2s, color .2s; }
  .req-item.open .num { background: var(--brand); color: #fff; }

  .detail { display: grid; grid-template-rows: 0fr; transition: grid-template-rows .25s ease; }
  .req-item.open .detail { grid-template-rows: 1fr; }
  .detail > div { overflow: hidden; }
  .chev { transition: transform .25s; }
  .req-item.open .chev { transform: rotate(180deg); color: var(--brand); }

  .req-item { animation: rise .35s ease both; }
  @keyframes rise { from { opacity: 0; transform: translateY(8px); } to { opacity: 1; transform: none; } }
</style>


<!-- MODAL: TERMS AND REGULATIONS -->
<dialog id="requirements_modal" class="modal modal-bottom sm:modal-middle">
  <div class="modal-box max-w-2xl p-0 bg-base-100 overflow-hidden">

    <!-- Header -->
    <div class="text-white px-6 py-5 relative" style="background: linear-gradient(135deg, #0d9488 0%, #0a6f66 100%);">
      <form method="dialog">
        <button class="btn btn-sm btn-circle btn-ghost absolute right-4 top-4 text-white" aria-label="Close">✕</button>
      </form>
      <div class="flex items-center gap-3">
        <div class="w-11 h-11 rounded-xl bg-white/20 flex items-center justify-center">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 12h-5"/><path d="M15 8h-5"/><path d="M19 17V5a2 2 0 0 0-2-2H4"/><path d="M8 21h12a2 2 0 0 0 2-2v-1a1 1 0 0 0-1-1H11a1 1 0 0 0-1 1v1a2 2 0 1 1-4 0V5a2 2 0 1 0-4 0v2a1 1 0 0 0 1 1h3"/></svg>
        </div>
        <div>
          <h3 class="text-xl font-bold leading-tight">Requirements for Landlords</h3>
          <p class="text-sm text-white/80">Prepare these 4 documents to list your property.</p>
        </div>
      </div>
    </div>

    <!-- Body -->
    <div class="px-6 pt-4 pb-5 max-h-[55vh] overflow-y-auto">
      <div class="flex items-center justify-between mb-3">
        <p class="text-sm text-slate-500">Tap a requirement to see what it is for.</p>
        <button id="toggleAll" class="text-sm font-medium hover:underline" style="color: var(--brand)">Expand all</button>
      </div>
      <div id="list" class="space-y-3"></div>

      <div class="mt-4 flex gap-2 rounded-xl p-3 text-sm text-slate-600" style="background: var(--brand-soft); border: 1px solid var(--brand-ring);">
        <svg class="shrink-0 mt-0.5" style="color: var(--brand)" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4M12 8h.01"/></svg>
        <span>Use clear, readable copies. Documents must be valid and up to date.</span>
      </div>
    </div>

    <!-- Footer -->
    <div class="px-6 py-4 border-t border-base-200 flex justify-end gap-2">
      <form method="dialog"><button class="btn btn-outline-brand">Close</button></form>
      <button id="continueBtn" class="btn btn-brand">I understand</button>
    </div>
  </div>

  <form method="dialog" class="modal-backdrop"><button>close</button></form>
</dialog>

<script>
const requirements = [
  { name: 'Land Title', short: 'Proof of property ownership',
    why: 'Confirms you are the legal owner of the property, or authorized to lease it.',
    icon: '<path d="M3 21h18"/><path d="M5 21V7l7-4 7 4v14"/><path d="M9 21v-6h6v6"/>' },
  { name: 'Barangay Permit', short: 'Local clearance for your area',
    why: 'Issued by the barangay where the property is located. It shows your rental is recognized at the community level.',
    icon: '<path d="M12 21s-7-6.2-7-11a7 7 0 0 1 14 0c0 4.8-7 11-7 11z"/><circle cx="12" cy="10" r="2.5"/>' },
  { name: 'BIT Permit', short: 'Tax / business registration',
    why: 'Shows that your rental activity is registered. Provide a valid, unexpired copy.',
    icon: '<rect x="4" y="3" width="16" height="18" rx="2"/><path d="M8 8h8M8 12h8M8 16h5"/>' },
  { name: 'Business Permit', short: 'Authority to operate',
    why: 'Authorizes you to operate a rental business. Make sure the permit is for the current year.',
    icon: '<rect x="3" y="7" width="18" height="13" rx="2"/><path d="M8 7V5a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/><path d="M3 13h18"/>' }
];

const list = document.getElementById('list');
const svg = p => `<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">${p}</svg>`;

requirements.forEach((r, i) => {
  const el = document.createElement('div');
  el.className = 'req-item border border-base-300 rounded-xl p-4 bg-white cursor-pointer';
  el.style.animationDelay = (i * 60) + 'ms';
  el.setAttribute('role', 'button');
  el.setAttribute('tabindex', '0');
  el.innerHTML = `
    <div class="flex items-center gap-3">
      <div class="num w-9 h-9 rounded-full flex items-center justify-center font-bold shrink-0">${i + 1}</div>
      <div class="w-10 h-10 rounded-lg flex items-center justify-center shrink-0" style="background: var(--brand-soft); color: var(--brand)">${svg(r.icon)}</div>
      <div class="flex-1 min-w-0">
        <div class="font-semibold text-slate-800">${r.name}</div>
        <div class="text-xs text-slate-500">${r.short}</div>
      </div>
      <svg class="chev text-slate-400 shrink-0" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
    </div>
    <div class="detail"><div><p class="text-sm text-slate-600 pt-3 pl-1">${r.why}</p></div></div>`;
  const toggle = () => { el.classList.toggle('open'); syncToggleAll(); };
  el.addEventListener('click', toggle);
  el.addEventListener('keydown', e => { if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); toggle(); } });
  list.appendChild(el);
});

const toggleAll = document.getElementById('toggleAll');
const items = () => [...list.querySelectorAll('.req-item')];
function syncToggleAll() {
  toggleAll.textContent = items().every(el => el.classList.contains('open')) ? 'Collapse all' : 'Expand all';
}
toggleAll.addEventListener('click', () => {
  const open = !items().every(el => el.classList.contains('open'));
  items().forEach(el => el.classList.toggle('open', open));
  syncToggleAll();
});

document.getElementById('continueBtn').addEventListener('click', () => {
  requirements_modal.close();
  // TODO: redirect to your landlord application page
});


</script>
