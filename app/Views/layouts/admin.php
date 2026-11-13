<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Panel Admin - Velvoria Laundry') ?></title>
    
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
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        body { font-family: 'Montserrat', Arial, sans-serif; background-color: #F8F9FA; }
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
  const nodes = document.querySelectorAll('main section, main article, .velvoria-animate');
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
<body class="bg-gray-50 text-velvoria-ink antialiased min-h-screen flex flex-col" x-data="{ mobileSidebar: false }">

    <!-- Flash Messages / Toast -->
    <?php if (session()->getFlashdata('success')): ?>
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)" 
             class="fixed top-6 right-6 z-50 flex items-center p-4 bg-emerald-50 text-emerald-900 border border-emerald-200 rounded-xl shadow-lg transition-all">
            <i data-lucide="check-circle" class="w-5 h-5 text-emerald-600 mr-3"></i>
            <span class="text-sm font-medium"><?= session()->getFlashdata('success') ?></span>
            <button @click="show = false" class="ml-4 text-emerald-500 hover:text-emerald-800"><i data-lucide="x" class="w-4 h-4"></i></button>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" 
             class="fixed top-6 right-6 z-50 flex items-center p-4 bg-rose-50 text-rose-900 border border-rose-200 rounded-xl shadow-lg transition-all">
            <i data-lucide="alert-circle" class="w-5 h-5 text-rose-600 mr-3"></i>
            <span class="text-sm font-medium"><?= session()->getFlashdata('error') ?></span>
            <button @click="show = false" class="ml-4 text-rose-500 hover:text-rose-800"><i data-lucide="x" class="w-4 h-4"></i></button>
        </div>
    <?php endif; ?>

    <div class="flex-grow flex">
        <!-- Desktop Sidebar -->
        <aside class="hidden lg:flex lg:w-64 flex-col bg-velvoria-ink text-white min-h-screen p-5 justify-between shrink-0">
            <div class="space-y-6">
                <!-- Brand -->
                <div class="flex items-center space-x-3 px-2 py-3 border-b border-white/10">
                    <div class="w-9 h-9 rounded-xl bg-velvoria-plum text-white flex items-center justify-center">
                        <i data-lucide="sparkles" class="w-5 h-5 text-velvoria-lilac"></i>
                    </div>
                    <div>
                        <span class="text-base font-bold tracking-tight text-white block">VELVORIA</span>
                        <span class="text-[9px] tracking-widest text-velvoria-lilac uppercase font-semibold block">Admin Operations</span>
                    </div>
                </div>

                <!-- Navigation -->
                <nav class="space-y-1 text-xs font-medium">
                    <p class="px-3 py-2 text-[10px] font-bold uppercase tracking-wider text-white/40">Operasional</p>
                    <a href="<?= base_url('admin/dashboard') ?>" class="flex items-center space-x-3 px-3 py-2.5 rounded-lg transition-colors <?= url_is('admin/dashboard') ? 'bg-velvoria-plum text-white font-semibold' : 'text-white/70 hover:bg-white/5 hover:text-white' ?>">
                        <i data-lucide="layout-dashboard" class="w-4 h-4"></i>
                        <span>Dashboard</span>
                    </a>
                    <a href="<?= base_url('admin/orders') ?>" class="flex items-center space-x-3 px-3 py-2.5 rounded-lg transition-colors <?= url_is('admin/orders*') ? 'bg-velvoria-plum text-white font-semibold' : 'text-white/70 hover:bg-white/5 hover:text-white' ?>">
                        <i data-lucide="shopping-bag" class="w-4 h-4"></i>
                        <span>Pesanan</span>
                    </a>
                    <a href="<?= base_url('admin/payments') ?>" class="flex items-center space-x-3 px-3 py-2.5 rounded-lg transition-colors <?= url_is('admin/payments*') ? 'bg-velvoria-plum text-white font-semibold' : 'text-white/70 hover:bg-white/5 hover:text-white' ?>">
                        <i data-lucide="credit-card" class="w-4 h-4"></i>
                        <span>Pembayaran</span>
                    </a>

                    <p class="px-3 pt-4 pb-2 text-[10px] font-bold uppercase tracking-wider text-white/40">Katalog & Promo</p>
                    <a href="<?= base_url('admin/services') ?>" class="flex items-center space-x-3 px-3 py-2.5 rounded-lg transition-colors <?= url_is('admin/services*') ? 'bg-velvoria-plum text-white font-semibold' : 'text-white/70 hover:bg-white/5 hover:text-white' ?>">
                        <i data-lucide="sparkles" class="w-4 h-4"></i>
                        <span>Layanan & Harga</span>
                    </a>
                    <a href="<?= base_url('admin/service-categories') ?>" class="flex items-center space-x-3 px-3 py-2.5 rounded-lg transition-colors <?= url_is('admin/service-categories*') ? 'bg-velvoria-plum text-white font-semibold' : 'text-white/70 hover:bg-white/5 hover:text-white' ?>">
                        <i data-lucide="folder" class="w-4 h-4"></i>
                        <span>Kategori Layanan</span>
                    </a>
                    <a href="<?= base_url('admin/promotions') ?>" class="flex items-center space-x-3 px-3 py-2.5 rounded-lg transition-colors <?= url_is('admin/promotions*') ? 'bg-velvoria-plum text-white font-semibold' : 'text-white/70 hover:bg-white/5 hover:text-white' ?>">
                        <i data-lucide="ticket" class="w-4 h-4"></i>
                        <span>Kode Promo</span>
                    </a>

                    <p class="px-3 pt-4 pb-2 text-[10px] font-bold uppercase tracking-wider text-white/40">Data & Laporan</p>
                    <a href="<?= base_url('admin/customers') ?>" class="flex items-center space-x-3 px-3 py-2.5 rounded-lg transition-colors <?= url_is('admin/customers*') ? 'bg-velvoria-plum text-white font-semibold' : 'text-white/70 hover:bg-white/5 hover:text-white' ?>">
                        <i data-lucide="users" class="w-4 h-4"></i>
                        <span>Pelanggan</span>
                    </a>
                    <a href="<?= base_url('admin/reports') ?>" class="flex items-center space-x-3 px-3 py-2.5 rounded-lg transition-colors <?= url_is('admin/reports*') ? 'bg-velvoria-plum text-white font-semibold' : 'text-white/70 hover:bg-white/5 hover:text-white' ?>">
                        <i data-lucide="bar-chart-3" class="w-4 h-4"></i>
                        <span>Laporan Transaksi</span>
                    </a>

                    <p class="px-3 pt-4 pb-2 text-[10px] font-bold uppercase tracking-wider text-white/40">Sistem</p>
                    <a href="<?= base_url('admin/settings') ?>" class="flex items-center space-x-3 px-3 py-2.5 rounded-lg transition-colors <?= url_is('admin/settings*') ? 'bg-velvoria-plum text-white font-semibold' : 'text-white/70 hover:bg-white/5 hover:text-white' ?>">
                        <i data-lucide="settings" class="w-4 h-4"></i>
                        <span>Pengaturan Toko</span>
                    </a>
                    <a href="<?= base_url('admin/staff') ?>" class="flex items-center space-x-3 px-3 py-2.5 rounded-lg transition-colors <?= url_is('admin/staff*') ? 'bg-velvoria-plum text-white font-semibold' : 'text-white/70 hover:bg-white/5 hover:text-white' ?>">
                        <i data-lucide="user-cog" class="w-4 h-4"></i>
                        <span>Staf & Akses</span>
                    </a>
                    <a href="<?= base_url('admin/activity-logs') ?>" class="flex items-center space-x-3 px-3 py-2.5 rounded-lg transition-colors <?= url_is('admin/activity-logs*') ? 'bg-velvoria-plum text-white font-semibold' : 'text-white/70 hover:bg-white/5 hover:text-white' ?>">
                        <i data-lucide="clipboard-list" class="w-4 h-4"></i>
                        <span>Activity Logs</span>
                    </a>
                </nav>
            </div>

            <!-- Admin Profile & Logout -->
            <div class="pt-4 border-t border-white/10 flex items-center justify-between">
                <div class="flex items-center space-x-3 overflow-hidden">
                    <div class="w-9 h-9 rounded-lg bg-velvoria-plum text-white font-bold flex items-center justify-center shrink-0">
                        <?= strtoupper(substr(session()->get('user_name') ?? 'A', 0, 1)) ?>
                    </div>
                    <div class="truncate">
                        <span class="block text-xs font-semibold text-white truncate"><?= esc(session()->get('user_name')) ?></span>
                        <span class="block text-[10px] text-velvoria-lilac uppercase font-medium"><?= esc(session()->get('user_role')) ?></span>
                    </div>
                </div>
                <a href="<?= base_url('logout') ?>" class="text-white/60 hover:text-rose-400 p-2 transition-colors" title="Keluar">
                    <i data-lucide="log-out" class="w-4 h-4"></i>
                </a>
            </div>
        </aside>

        <!-- Main Workspace -->
        <div class="flex-grow flex flex-col min-w-0">
            <!-- Top Navbar -->
            <header class="bg-white border-b border-gray-200 px-4 sm:px-6 py-3 flex items-center justify-between sticky top-0 z-30">
                <div class="flex items-center space-x-4">
                    <button @click="mobileSidebar = !mobileSidebar" class="lg:hidden p-2 text-gray-600 hover:text-gray-900">
                        <i data-lucide="menu" class="w-5 h-5"></i>
                    </button>
                    <div>
                        <h2 class="text-base font-semibold text-gray-800"><?= esc($title ?? 'Admin Dashboard') ?></h2>
                    </div>
                </div>

                <div class="flex items-center space-x-3">
                    <a href="<?= base_url('/') ?>" target="_blank" class="px-3 py-1.5 text-xs text-gray-600 hover:text-velvoria-plum border border-gray-200 rounded-lg flex items-center space-x-1 transition-colors">
                        <i data-lucide="external-link" class="w-3.5 h-3.5"></i>
                        <span>Lihat Web</span>
                    </a>
                    <a href="<?= base_url('logout') ?>" class="px-3 py-1.5 text-xs text-rose-600 hover:bg-rose-50 border border-rose-200 rounded-lg transition-colors flex items-center space-x-1">
                        <i data-lucide="log-out" class="w-3.5 h-3.5"></i>
                        <span>Keluar</span>
                    </a>
                </div>
            </header>

            <!-- Main Content Container -->
            <main class="flex-grow p-4 sm:p-6 lg:p-8">
                <?= $this->renderSection('content') ?>
            </main>
        </div>
    </div>

    <!-- Mobile Drawer Sidebar -->
    <div x-show="mobileSidebar" class="lg:hidden fixed inset-0 z-50 flex" style="display: none;">
        <div @click="mobileSidebar = false" class="fixed inset-0 bg-black/50"></div>
        <div class="relative w-64 bg-velvoria-ink text-white p-5 flex flex-col justify-between z-10">
            <div class="space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-white/10">
                    <span class="text-sm font-bold text-white">MENU ADMIN</span>
                    <button @click="mobileSidebar = false" class="text-white/60 hover:text-white"><i data-lucide="x" class="w-5 h-5"></i></button>
                </div>
                <nav class="space-y-1 text-xs">
                    <a href="<?= base_url('admin/dashboard') ?>" class="block px-3 py-2 rounded text-white/80 hover:bg-white/10">Dashboard</a>
                    <a href="<?= base_url('admin/orders') ?>" class="block px-3 py-2 rounded text-white/80 hover:bg-white/10">Pesanan</a>
                    <a href="<?= base_url('admin/payments') ?>" class="block px-3 py-2 rounded text-white/80 hover:bg-white/10">Pembayaran</a>
                    <a href="<?= base_url('admin/services') ?>" class="block px-3 py-2 rounded text-white/80 hover:bg-white/10">Layanan & Harga</a>
                    <a href="<?= base_url('admin/service-categories') ?>" class="block px-3 py-2 rounded text-white/80 hover:bg-white/10">Kategori</a>
                    <a href="<?= base_url('admin/promotions') ?>" class="block px-3 py-2 rounded text-white/80 hover:bg-white/10">Kode Promo</a>
                    <a href="<?= base_url('admin/customers') ?>" class="block px-3 py-2 rounded text-white/80 hover:bg-white/10">Pelanggan</a>
                    <a href="<?= base_url('admin/reports') ?>" class="block px-3 py-2 rounded text-white/80 hover:bg-white/10">Laporan</a>
                    <a href="<?= base_url('admin/settings') ?>" class="block px-3 py-2 rounded text-white/80 hover:bg-white/10">Pengaturan</a>
                    <a href="<?= base_url('admin/staff') ?>" class="block px-3 py-2 rounded text-white/80 hover:bg-white/10">Staf</a>
                    <a href="<?= base_url('admin/activity-logs') ?>" class="block px-3 py-2 rounded text-white/80 hover:bg-white/10">Activity Logs</a>
                </nav>
            </div>
            <div class="pt-4 border-t border-white/10">
                <a href="<?= base_url('logout') ?>" class="block text-center py-2 text-xs text-rose-400 font-semibold border border-rose-400/30 rounded">Keluar</a>
            </div>
        </div>
    </div>

    <script>
        lucide.createIcons();
    </script>
    <?= $this->renderSection('scripts') ?>
</body>
</html>
