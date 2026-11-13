<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Dashboard Pelanggan - Velvoria Laundry') ?></title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'velvoria-ink': '#282331',
                        'velvoria-plum': '#51415F',
                        'velvoria-lilac': '#A995C7',
                        'velvoria-cream': '#F7F4EF',
                        'velvoria-sage': '#A9B7A5',
                        'velvoria-muted': '#77717D',
                        'velvoria-border': '#E8E3EA',
                    },
                    fontFamily: {
                        sans: ['Montserrat', 'Arial', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        [x-cloak] { display: none !important; }
        body { font-family: 'Montserrat', Arial, sans-serif; background-color: #F7F4EF; }
        a, button, input, select, textarea, label, .transition-all { transition-property: color, background-color, border-color, box-shadow, transform, opacity, filter; transition-duration: 240ms; transition-timing-function: cubic-bezier(.22,.61,.36,1); }
        button:active { transform: scale(.98); }
        a:focus-visible, button:focus-visible, input:focus-visible, select:focus-visible, textarea:focus-visible { outline: 3px solid rgba(169,149,199,.45); outline-offset: 2px; }
        @media (prefers-reduced-motion: reduce) { *, *::before, *::after { scroll-behavior: auto !important; transition-duration: .01ms !important; animation-duration: .01ms !important; animation-iteration-count: 1 !important; } }
    </style>
    <?= $this->renderSection('styles') ?>

    <style id="velvoria-smooth-interactions">
        a, button, input, select, textarea, [role="button"] {
            transition: color .24s cubic-bezier(.22,.61,.36,1),
                        background-color .24s cubic-bezier(.22,.61,.36,1),
                        border-color .24s cubic-bezier(.22,.61,.36,1),
                        box-shadow .28s cubic-bezier(.22,.61,.36,1),
                        transform .28s cubic-bezier(.22,.61,.36,1),
                        opacity .24s ease;
        }
        button:active, [role="button"]:active { transform: scale(.98); }
        a:focus-visible, button:focus-visible, input:focus-visible,
        select:focus-visible, textarea:focus-visible {
            outline: 3px solid rgba(169,149,199,.42); outline-offset: 2px;
        }
        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after { scroll-behavior: auto !important; transition-duration: .01ms !important; animation-duration: .01ms !important; animation-iteration-count: 1 !important; }
        }
    </style>


<style id="velvoria-global-motion">
:root { scroll-behavior:smooth; }
html { scroll-behavior:smooth; }
body { -webkit-font-smoothing:antialiased; }
a,button,input,select,textarea,[role="button"],.transition-all,.card,.service-card {
  transition: color .26s cubic-bezier(.22,.61,.36,1),
    background-color .26s cubic-bezier(.22,.61,.36,1),
    border-color .26s cubic-bezier(.22,.61,.36,1),
    box-shadow .32s cubic-bezier(.22,.61,.36,1),
    transform .32s cubic-bezier(.22,.61,.36,1),
    opacity .26s ease, filter .26s ease;
}
button:active,[role="button"]:active { transform: translateY(1px) scale(.985); }
a:hover,button:hover,[role="button"]:hover { transform: translateY(-1px); }
input:focus,select:focus,textarea:focus { box-shadow:0 0 0 4px rgba(169,149,199,.16); }
.velvoria-reveal { opacity:0; transform:translateY(18px); transition:opacity .65s ease,transform .65s cubic-bezier(.2,.7,.2,1); }
.velvoria-reveal.is-visible { opacity:1; transform:translateY(0); }
.velvoria-toast { animation:velvoriaToastIn .42s cubic-bezier(.2,.8,.2,1) both; }
@keyframes velvoriaToastIn { from {opacity:0;transform:translate3d(0,-12px,0) scale(.98)} to {opacity:1;transform:translate3d(0,0,0) scale(1)} }
@keyframes velvoriaFloat { 0%,100%{transform:translateY(0)} 50%{transform:translateY(-7px)} }
.velvoria-float { animation:velvoriaFloat 4.5s ease-in-out infinite; }
@media (prefers-reduced-motion:reduce) {
  html {scroll-behavior:auto}
  *,*::before,*::after {animation-duration:.01ms!important;animation-iteration-count:1!important;transition-duration:.01ms!important;scroll-behavior:auto!important}
  .velvoria-reveal {opacity:1;transform:none}
}
</style>
<script>
document.addEventListener('DOMContentLoaded', () => {
  const nodes = document.querySelectorAll('.velvoria-reveal, main section, main article, .velvoria-animate');
  nodes.forEach(node => node.classList.add('velvoria-reveal'));
  if ('IntersectionObserver' in window) {
    const observer = new IntersectionObserver(entries => entries.forEach(entry => {
      if (entry.isIntersecting) { entry.target.classList.add('is-visible'); observer.unobserve(entry.target); }
    }), {threshold:.08, rootMargin:'0px 0px -24px 0px'});
    nodes.forEach(node => observer.observe(node));
  } else nodes.forEach(node => node.classList.add('is-visible'));
});
</script>

</head>
<body class="bg-velvoria-cream text-velvoria-ink antialiased min-h-screen flex flex-col" x-data="{ sidebarOpen: false }">

    <!-- Flash Messages / Toast -->
    <?php if (session()->getFlashdata('success')): ?>
        <div x-data="{ show: true }" x-show="show" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-3 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0 translate-y-2" x-init="setTimeout(() => show = false, 4000)" 
             class="fixed top-6 right-6 z-50 flex items-center p-4 bg-emerald-50 text-emerald-900 border border-emerald-200 rounded-xl shadow-lg">
            <i data-lucide="check-circle" class="w-5 h-5 text-emerald-600 mr-3"></i>
            <span class="text-sm font-medium"><?= session()->getFlashdata('success') ?></span>
            <button @click="show = false" class="ml-4 text-emerald-500 hover:text-emerald-800"><i data-lucide="x" class="w-4 h-4"></i></button>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div x-data="{ show: true }" x-show="show" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-3 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0 translate-y-2" x-init="setTimeout(() => show = false, 5000)" 
             class="fixed top-6 right-6 z-50 flex items-center p-4 bg-rose-50 text-rose-900 border border-rose-200 rounded-xl shadow-lg">
            <i data-lucide="alert-circle" class="w-5 h-5 text-rose-600 mr-3"></i>
            <span class="text-sm font-medium"><?= session()->getFlashdata('error') ?></span>
            <button @click="show = false" class="ml-4 text-rose-500 hover:text-rose-800"><i data-lucide="x" class="w-4 h-4"></i></button>
        </div>
    <?php endif; ?>

    <div class="flex-grow flex">
        <!-- Desktop Sidebar -->
        <aside class="hidden md:flex md:w-64 flex-col bg-white border-r border-velvoria-border min-h-screen p-6 justify-between shrink-0">
            <div class="space-y-8">
                <!-- Brand -->
                <a href="<?= base_url('/') ?>" class="flex items-center space-x-3 group">
                    <div class="w-10 h-10 rounded-xl bg-velvoria-plum text-white flex items-center justify-center shadow-sm">
                        <i data-lucide="sparkles" class="w-5 h-5 text-velvoria-lilac"></i>
                    </div>
                    <div>
                        <span class="text-lg font-bold tracking-tight text-velvoria-ink block">VELVORIA</span>
                        <span class="text-[9px] tracking-widest text-velvoria-muted uppercase font-semibold block">Pelanggan</span>
                    </div>
                </a>

                <!-- CTA Button -->
                <div>
                    <a href="<?= base_url('customer/orders/create') ?>" class="w-full py-3 px-4 rounded-xl bg-velvoria-plum text-white font-medium text-sm flex items-center justify-center space-x-2 shadow-sm hover:bg-velvoria-plum/90 transition-all">
                        <i data-lucide="plus-circle" class="w-4 h-4"></i>
                        <span>Buat Pesanan Baru</span>
                    </a>
                </div>

                <!-- Navigation Links -->
                <nav class="space-y-1.5 text-sm font-medium">
                    <a href="<?= base_url('customer/dashboard') ?>" class="flex items-center space-x-3 px-4 py-3 rounded-xl transition-all <?= url_is('customer/dashboard') ? 'bg-velvoria-plum/10 text-velvoria-plum font-semibold' : 'text-velvoria-muted hover:bg-velvoria-cream hover:text-velvoria-ink' ?>">
                        <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
                        <span>Dashboard</span>
                    </a>
                    <a href="<?= base_url('customer/orders') ?>" class="flex items-center space-x-3 px-4 py-3 rounded-xl transition-all <?= url_is('customer/orders*') && !url_is('customer/orders/create') ? 'bg-velvoria-plum/10 text-velvoria-plum font-semibold' : 'text-velvoria-muted hover:bg-velvoria-cream hover:text-velvoria-ink' ?>">
                        <i data-lucide="shopping-bag" class="w-5 h-5"></i>
                        <span>Pesanan Saya</span>
                    </a>
                    <a href="<?= base_url('customer/history') ?>" class="flex items-center space-x-3 px-4 py-3 rounded-xl transition-all <?= url_is('customer/history') ? 'bg-velvoria-plum/10 text-velvoria-plum font-semibold' : 'text-velvoria-muted hover:bg-velvoria-cream hover:text-velvoria-ink' ?>">
                        <i data-lucide="history" class="w-5 h-5"></i>
                        <span>Riwayat Pesanan</span>
                    </a>
                    <a href="<?= base_url('customer/addresses') ?>" class="flex items-center space-x-3 px-4 py-3 rounded-xl transition-all <?= url_is('customer/addresses*') ? 'bg-velvoria-plum/10 text-velvoria-plum font-semibold' : 'text-velvoria-muted hover:bg-velvoria-cream hover:text-velvoria-ink' ?>">
                        <i data-lucide="map-pin" class="w-5 h-5"></i>
                        <span>Alamat Antar-Jemput</span>
                    </a>
                    <a href="<?= base_url('customer/profile') ?>" class="flex items-center space-x-3 px-4 py-3 rounded-xl transition-all <?= url_is('customer/profile*') ? 'bg-velvoria-plum/10 text-velvoria-plum font-semibold' : 'text-velvoria-muted hover:bg-velvoria-cream hover:text-velvoria-ink' ?>">
                        <i data-lucide="user" class="w-5 h-5"></i>
                        <span>Profil Saya</span>
                    </a>
                    <a href="<?= base_url('customer/help') ?>" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-velvoria-muted hover:bg-velvoria-cream hover:text-velvoria-ink transition-all">
                        <i data-lucide="help-circle" class="w-5 h-5"></i>
                        <span>Pusat Bantuan</span>
                    </a>
                </nav>
            </div>

            <!-- User Info & Logout -->
            <div class="pt-6 border-t border-velvoria-border">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3 overflow-hidden">
                        <div class="w-10 h-10 rounded-full bg-velvoria-plum/10 text-velvoria-plum font-bold flex items-center justify-center shrink-0 overflow-hidden">
                            <?php if (session()->get('user_avatar')): ?>
                                <img src="<?= base_url('uploads/avatars/' . rawurlencode(basename((string) session()->get('user_avatar')))) ?>" alt="Foto profil" class="w-full h-full object-cover">
                            <?php else: ?>
                                <?= strtoupper(substr(session()->get('user_name') ?? 'U', 0, 1)) ?>
                            <?php endif; ?>
                        </div>
                        <div class="truncate">
                            <span class="block text-sm font-semibold text-velvoria-ink truncate"><?= esc(session()->get('user_name')) ?></span>
                            <span class="block text-xs text-velvoria-muted truncate"><?= esc(session()->get('user_email')) ?></span>
                        </div>
                    </div>
                    <a href="<?= base_url('logout') ?>" class="text-velvoria-muted hover:text-rose-600 p-2 transition-colors" title="Keluar">
                        <i data-lucide="log-out" class="w-5 h-5"></i>
                    </a>
                </div>
            </div>
        </aside>

        <!-- Main Column -->
        <div class="flex-grow flex flex-col min-w-0">
            <!-- Mobile Top Bar -->
            <div class="md:hidden bg-white border-b border-velvoria-border px-4 py-3 flex items-center justify-between sticky top-0 z-30">
                <a href="<?= base_url('/') ?>" class="flex items-center space-x-2">
                    <div class="w-8 h-8 rounded-lg bg-velvoria-plum text-white flex items-center justify-center">
                        <i data-lucide="sparkles" class="w-4 h-4 text-velvoria-lilac"></i>
                    </div>
                    <span class="text-base font-bold text-velvoria-ink">VELVORIA</span>
                </a>
                <div class="flex items-center space-x-3">
                    <a href="<?= base_url('customer/orders/create') ?>" class="p-2 rounded-lg bg-velvoria-plum text-white">
                        <i data-lucide="plus" class="w-4 h-4"></i>
                    </a>
                    <a href="<?= base_url('logout') ?>" class="p-2 text-velvoria-muted hover:text-rose-600">
                        <i data-lucide="log-out" class="w-5 h-5"></i>
                    </a>
                </div>
            </div>

            <!-- Content Area -->
            <div class="flex-grow p-4 sm:p-6 lg:p-8 max-w-7xl w-full mx-auto pb-24 md:pb-8">
                <?= $this->renderSection('content') ?>
            </div>

            <!-- Mobile Bottom Nav -->
            <nav class="md:hidden fixed bottom-0 inset-x-0 bg-white border-t border-velvoria-border flex justify-around py-2.5 z-30">
                <a href="<?= base_url('customer/dashboard') ?>" class="flex flex-col items-center text-xs <?= url_is('customer/dashboard') ? 'text-velvoria-plum font-semibold' : 'text-velvoria-muted' ?>">
                    <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
                    <span class="mt-1">Beranda</span>
                </a>
                <a href="<?= base_url('customer/orders') ?>" class="flex flex-col items-center text-xs <?= url_is('customer/orders*') && !url_is('customer/orders/create') ? 'text-velvoria-plum font-semibold' : 'text-velvoria-muted' ?>">
                    <i data-lucide="shopping-bag" class="w-5 h-5"></i>
                    <span class="mt-1">Pesanan</span>
                </a>
                <a href="<?= base_url('customer/orders/create') ?>" class="flex flex-col items-center text-xs text-velvoria-plum font-bold -mt-4">
                    <div class="w-11 h-11 rounded-full bg-velvoria-plum text-white flex items-center justify-center shadow-lg">
                        <i data-lucide="plus" class="w-6 h-6"></i>
                    </div>
                    <span class="mt-1">Pesan</span>
                </a>
                <a href="<?= base_url('customer/addresses') ?>" class="flex flex-col items-center text-xs <?= url_is('customer/addresses*') ? 'text-velvoria-plum font-semibold' : 'text-velvoria-muted' ?>">
                    <i data-lucide="map-pin" class="w-5 h-5"></i>
                    <span class="mt-1">Alamat</span>
                </a>
                <a href="<?= base_url('customer/profile') ?>" class="flex flex-col items-center text-xs <?= url_is('customer/profile*') ? 'text-velvoria-plum font-semibold' : 'text-velvoria-muted' ?>">
                    <i data-lucide="user" class="w-5 h-5"></i>
                    <span class="mt-1">Profil</span>
                </a>
            </nav>
        </div>
    </div>

    <script>
        window.velvoriaNotify = function (message, type = 'info') {
            let host = document.getElementById('velvoria-global-toast');
            if (!host) {
                host = document.createElement('div');
                host.id = 'velvoria-global-toast';
                host.className = 'fixed top-5 right-5 z-[120] w-[min(92vw,390px)]';
                host.setAttribute('role', 'status');
                host.setAttribute('aria-live', 'polite');
                document.body.appendChild(host);
            }
            const palette = type === 'success' ? ['border-emerald-200','bg-emerald-50','text-emerald-700','check-circle-2'] : (type === 'error' ? ['border-rose-200','bg-rose-50','text-rose-700','alert-circle'] : ['border-amber-200','bg-amber-50','text-amber-700','info']);
            host.innerHTML = '';
            const card = document.createElement('div');
            card.className = 'flex items-start gap-3 rounded-2xl border bg-white/95 p-4 shadow-2xl backdrop-blur opacity-0 translate-y-3 scale-95 transition-all duration-300 ' + palette[0];
            const icon = document.createElement('span');
            icon.className = 'flex h-9 w-9 shrink-0 items-center justify-center rounded-xl ' + palette[1] + ' ' + palette[2];
            icon.innerHTML = '<i data-lucide="' + palette[3] + '" class="h-5 w-5"></i>';
            const content = document.createElement('div');
            content.className = 'min-w-0 flex-1';
            const title = document.createElement('p');
            title.className = 'text-sm font-bold text-velvoria-ink';
            title.textContent = type === 'success' ? 'Berhasil' : (type === 'error' ? 'Ada kendala' : 'Perhatian');
            const body = document.createElement('p');
            body.className = 'mt-1 text-xs leading-relaxed text-velvoria-muted';
            body.textContent = message;
            content.append(title, body);
            const close = document.createElement('button');
            close.type = 'button';
            close.className = 'rounded-lg p-1 text-velvoria-muted transition hover:bg-velvoria-cream';
            close.setAttribute('aria-label', 'Tutup notifikasi');
            close.textContent = '×';
            close.onclick = () => card.remove();
            card.append(icon, content, close);
            host.appendChild(card);
            requestAnimationFrame(() => card.classList.remove('opacity-0','translate-y-3','scale-95'));
            clearTimeout(window.velvoriaToastTimer);
            window.velvoriaToastTimer = setTimeout(() => {
                card.classList.add('opacity-0','translate-y-2','scale-95');
                setTimeout(() => card.remove(), 250);
            }, 3800);
            if (window.lucide) lucide.createIcons({ nodes: [card] });
        };
        if (window.lucide) lucide.createIcons();
    </script>
    <?= $this->renderSection('scripts') ?>
</body>
</html>
