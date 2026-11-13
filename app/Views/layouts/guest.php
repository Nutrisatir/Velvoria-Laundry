<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Velvoria Laundry - Baju Bersih, Waktu Lebih Luang') ?></title>
    
    <!-- Google Fonts: Montserrat -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS with custom Velvoria Design Tokens -->
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
                        'success': '#287A55',
                        'warning': '#A96D18',
                        'danger': '#B84242',
                    },
                    fontFamily: {
                        sans: ['Montserrat', 'Arial', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        body {
            font-family: 'Montserrat', Arial, sans-serif;
            background-color: #F7F4EF;
            color: #282331;
            scroll-behavior: smooth;
        }
        @media (prefers-reduced-motion: reduce) {
            * {
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.01ms !important;
                scroll-behavior: auto !important;
            }
        }
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
@view-transition { navigation: auto; }
::view-transition-old(root) { animation: velvoriaPageOut .24s ease both; }
::view-transition-new(root) { animation: velvoriaPageIn .48s cubic-bezier(.22,.61,.36,1) both; }
@keyframes velvoriaPageIn { from { opacity:0; transform:translateY(10px); } to { opacity:1; transform:translateY(0); } }
@keyframes velvoriaPageOut { to { opacity:0; } }
main { animation: velvoriaPageIn .52s cubic-bezier(.22,.61,.36,1) both; }
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
  const nodes = document.querySelectorAll(
    'main section, main article, main > div, main h1, main h2, main h3, main p, main .bg-white, main .rounded-2xl, main .rounded-3xl, .velvoria-animate'
  );
  nodes.forEach(node => node.classList.add('velvoria-reveal'));
  if ('IntersectionObserver' in window) {
    const observer = new IntersectionObserver(entries => entries.forEach(entry => {
      if (entry.isIntersecting) { entry.target.classList.add('is-visible'); observer.unobserve(entry.target); }
    }), {threshold:.08, rootMargin:'0px 0px -24px 0px'});
    nodes.forEach(node => observer.observe(node));
  } else nodes.forEach(node => node.classList.add('is-visible'));
});
</script>


<style id="velvoria-hero-repeating-text">
.velvoria-hero-loop .hero-loop-item {
    opacity: 0;
    transform: translate3d(0, 22px, 0);
    filter: blur(2px);
    will-change: transform, opacity, filter;
}
.velvoria-hero-loop .hero-loop-item.is-looping {
    animation: velvoriaHeroTextLoop .95s cubic-bezier(.22,.61,.36,1) both;
    animation-delay: var(--hero-loop-delay, 0ms);
}
.velvoria-hero-loop .hero-loop-item.hero-loop-static {
    opacity: 1;
    transform: none;
    filter: none;
}
@keyframes velvoriaHeroTextLoop {
    0%   { opacity: 0; transform: translate3d(0, 22px, 0); filter: blur(2px); }
    72%  { opacity: 1; transform: translate3d(0, -2px, 0); filter: blur(0); }
    100% { opacity: 1; transform: translate3d(0, 0, 0); filter: blur(0); }
}
@media (prefers-reduced-motion: reduce) {
    .velvoria-hero-loop .hero-loop-item,
    .velvoria-hero-loop .hero-loop-item.is-looping {
        opacity: 1 !important;
        transform: none !important;
        filter: none !important;
        animation: none !important;
    }
}
</style>
<script id="velvoria-hero-repeating-text-script">
(() => {
    const initHeroTextLoop = () => {
        const hero = document.querySelector('.velvoria-hero-loop');
        if (!hero || hero.dataset.heroTextLoopReady === '1') return;
        hero.dataset.heroTextLoopReady = '1';

        // Ambil elemen utama kolom kiri secara eksplisit.
        // Jangan memakai filter parent/child yang sebelumnya justru membuang H1 dan paragraf.
        const leftColumn = hero.querySelector('.grid.grid-cols-1.lg\\:grid-cols-12')?.firstElementChild;
        if (!leftColumn) return;

        const items = [
            leftColumn.children[0], // badge pembuka
            leftColumn.querySelector('h1'),
            leftColumn.querySelector(':scope > p'),
            leftColumn.querySelector(':scope > div:nth-of-type(2)'), // tombol CTA
            leftColumn.querySelector(':scope > div:last-child') // trust badges
        ].filter(Boolean);

        items.forEach((item, index) => {
            item.classList.add('hero-loop-item');
            item.style.setProperty('--hero-loop-delay', `${index * 140}ms`);
        });

        if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
            items.forEach(item => item.classList.add('hero-loop-static'));
            return;
        }

        const replay = () => {
            items.forEach((item, index) => {
                window.setTimeout(() => {
                    item.classList.remove('is-looping');
                    // Memaksa browser menghitung ulang animasi agar bisa diputar lagi.
                    void item.offsetWidth;
                    item.classList.add('is-looping');
                }, index * 140);
            });
        };

        // Animasi pertama saat halaman dibuka, lalu diputar ulang terus dengan jeda.
        window.setTimeout(replay, 180);
        window.setInterval(replay, 5200);
    };

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initHeroTextLoop, { once: true });
    } else {
        initHeroTextLoop();
    }
})();
</script>


<style id="velvoria-care-opening-only">
/* Care System: one-time entrance, each group follows its own direction. */
.velvoria-care-card.care-motion-ready .velvoria-care-heading,
.velvoria-care-card.care-motion-ready .velvoria-care-subheading,
.velvoria-care-card.care-motion-ready .velvoria-care-step,
.velvoria-care-card.care-motion-ready .velvoria-care-price-label,
.velvoria-care-card.care-motion-ready .velvoria-care-price {
    opacity: 0;
    will-change: transform, opacity;
}
.velvoria-care-card.is-care-open .velvoria-care-heading {
    animation: velvoriaCareDrop .78s cubic-bezier(.22,.61,.36,1) .02s both;
}
.velvoria-care-card.is-care-open .velvoria-care-subheading {
    animation: velvoriaCareDrop .78s cubic-bezier(.22,.61,.36,1) .18s both;
}
.velvoria-care-card.is-care-open .velvoria-care-step {
    animation: velvoriaCareFromLeft .82s cubic-bezier(.22,.61,.36,1) both;
}
.velvoria-care-card.is-care-open .velvoria-care-step:nth-child(1) { animation-delay: .34s; }
.velvoria-care-card.is-care-open .velvoria-care-step:nth-child(2) { animation-delay: .49s; }
.velvoria-care-card.is-care-open .velvoria-care-step:nth-child(3) { animation-delay: .64s; }
.velvoria-care-card.is-care-open .velvoria-care-price-label,
.velvoria-care-card.is-care-open .velvoria-care-price {
    animation: velvoriaCareRise .78s cubic-bezier(.22,.61,.36,1) both;
}
.velvoria-care-card.is-care-open .velvoria-care-price-label { animation-delay: .82s; }
.velvoria-care-card.is-care-open .velvoria-care-price { animation-delay: .94s; }

@keyframes velvoriaCareDrop {
    from { opacity: 0; transform: translate3d(0,-24px,0); }
    to   { opacity: 1; transform: translate3d(0,0,0); }
}
@keyframes velvoriaCareFromLeft {
    from { opacity: 0; transform: translate3d(-32px,0,0); }
    to   { opacity: 1; transform: translate3d(0,0,0); }
}
@keyframes velvoriaCareRise {
    from { opacity: 0; transform: translate3d(0,24px,0); }
    to   { opacity: 1; transform: translate3d(0,0,0); }
}
@media (prefers-reduced-motion: reduce) {
    .velvoria-care-card.care-motion-ready .velvoria-care-heading,
    .velvoria-care-card.care-motion-ready .velvoria-care-subheading,
    .velvoria-care-card.care-motion-ready .velvoria-care-step,
    .velvoria-care-card.care-motion-ready .velvoria-care-price-label,
    .velvoria-care-card.care-motion-ready .velvoria-care-price {
        opacity: 1 !important;
        transform: none !important;
        animation: none !important;
    }
}
</style>
<script id="velvoria-care-opening-only-script">
(() => {
    const initVelvoriaCareOpening = () => {
        const card = document.querySelector('.velvoria-care-card');
        if (!card || card.dataset.openingOnlyReady === '1') return;
        card.dataset.openingOnlyReady = '1';

        const reveal = () => card.classList.add('is-care-open');

        if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
            reveal();
            return;
        }

        card.classList.add('care-motion-ready');

        if ('IntersectionObserver' in window) {
            const observer = new IntersectionObserver(entries => {
                if (entries.some(entry => entry.isIntersecting)) {
                    reveal();
                    observer.disconnect(); // Opening animation runs once only.
                }
            }, { threshold: 0.16, rootMargin: '0px 0px -20px 0px' });
            observer.observe(card);
        } else {
            reveal();
        }
    };

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initVelvoriaCareOpening, { once: true });
    } else {
        initVelvoriaCareOpening();
    }
})();
</script>

</head>
<body class="bg-velvoria-cream text-velvoria-ink antialiased min-h-screen flex flex-col" x-data="{ mobileMenu: false }">

    <!-- Flash Messages / Toast -->
    <?php if (session()->getFlashdata('success')): ?>
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)" 
             class="fixed top-20 right-4 z-50 flex items-center p-4 bg-emerald-50 text-emerald-900 border border-emerald-200 rounded-xl shadow-lg transition-all">
            <i data-lucide="check-circle" class="w-5 h-5 text-emerald-600 mr-3"></i>
            <span class="text-sm font-medium"><?= session()->getFlashdata('success') ?></span>
            <button @click="show = false" class="ml-4 text-emerald-500 hover:text-emerald-800"><i data-lucide="x" class="w-4 h-4"></i></button>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" 
             class="fixed top-20 right-4 z-50 flex items-center p-4 bg-rose-50 text-rose-900 border border-rose-200 rounded-xl shadow-lg transition-all">
            <i data-lucide="alert-circle" class="w-5 h-5 text-rose-600 mr-3"></i>
            <span class="text-sm font-medium"><?= session()->getFlashdata('error') ?></span>
            <button @click="show = false" class="ml-4 text-rose-500 hover:text-rose-800"><i data-lucide="x" class="w-4 h-4"></i></button>
        </div>
    <?php endif; ?>

    <!-- Navigation Header -->
    <header class="sticky top-0 z-40 bg-white/95 backdrop-blur border-b border-velvoria-border transition-all">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                <!-- Brand / Logo -->
                <a href="<?= base_url('/') ?>" class="flex items-center space-x-3 group">
                    <div class="w-10 h-10 rounded-xl bg-velvoria-plum text-white flex items-center justify-center shadow-md group-hover:scale-105 transition-transform">
                        <i data-lucide="sparkles" class="w-5 h-5 text-velvoria-lilac"></i>
                    </div>
                    <div>
                        <span class="text-xl font-bold tracking-tight text-velvoria-ink block">VELVORIA</span>
                        <span class="text-[10px] tracking-widest text-velvoria-muted uppercase block font-semibold">Premium Laundry</span>
                    </div>
                </a>

                <!-- Desktop Nav -->
                <nav class="hidden md:flex items-center space-x-8 text-sm font-medium text-velvoria-ink/80">
                    <a href="<?= base_url('/') ?>" class="hover:text-velvoria-plum transition-colors">Beranda</a>
                    <a href="<?= base_url('layanan') ?>" class="hover:text-velvoria-plum transition-colors">Layanan</a>
                    <a href="<?= base_url('harga') ?>" class="hover:text-velvoria-plum transition-colors">Harga</a>
                    <a href="<?= base_url('cara-kerja') ?>" class="hover:text-velvoria-plum transition-colors">Cara Kerja</a>
                    <a href="<?= base_url('faq') ?>" class="hover:text-velvoria-plum transition-colors">FAQ</a>
                    <a href="<?= base_url('kontak') ?>" class="hover:text-velvoria-plum transition-colors">Kontak</a>
                </nav>

                <!-- Auth Buttons / User Profile -->
                <div class="hidden md:flex items-center space-x-4">
                    <?php if (session()->get('logged_in')): ?>
                        <?php if (session()->get('user_role') === 'admin' || session()->get('user_role') === 'staff'): ?>
                            <a href="<?= base_url('admin/dashboard') ?>" class="px-5 py-2.5 text-sm font-medium text-white bg-velvoria-plum hover:bg-velvoria-plum/90 rounded-xl shadow-sm transition-all flex items-center space-x-2">
                                <i data-lucide="layout-dashboard" class="w-4 h-4"></i>
                                <span>Panel Admin</span>
                            </a>
                        <?php else: ?>
                            <a href="<?= base_url('customer/dashboard') ?>" class="px-5 py-2.5 text-sm font-medium text-velvoria-plum border border-velvoria-plum/30 hover:bg-velvoria-plum/5 rounded-xl transition-all">
                                Dashboard Saya
                            </a>
                            <a href="<?= base_url('customer/orders/create') ?>" class="px-5 py-2.5 text-sm font-medium text-white bg-velvoria-plum hover:bg-velvoria-plum/90 rounded-xl shadow-sm transition-all flex items-center space-x-2">
                                <i data-lucide="plus-circle" class="w-4 h-4"></i>
                                <span>Pesan Laundry</span>
                            </a>
                        <?php endif; ?>
                    <?php else: ?>
                        <a href="<?= base_url('login') ?>" class="px-4 py-2 text-sm font-medium text-velvoria-ink hover:text-velvoria-plum transition-colors">
                            Masuk
                        </a>
                        <a href="<?= base_url('register') ?>" class="px-5 py-2.5 text-sm font-medium text-white bg-velvoria-plum hover:bg-velvoria-plum/90 rounded-xl shadow-sm transition-all flex items-center space-x-2">
                            <span>Pesan Sekarang</span>
                            <i data-lucide="arrow-right" class="w-4 h-4"></i>
                        </a>
                    <?php endif; ?>
                </div>

                <!-- Mobile Menu Button -->
                <div class="md:hidden flex items-center">
                    <button @click="mobileMenu = !mobileMenu" class="p-2 text-velvoria-ink hover:text-velvoria-plum focus:outline-none">
                        <i data-lucide="menu" class="w-6 h-6" x-show="!mobileMenu"></i>
                        <i data-lucide="x" class="w-6 h-6" x-show="mobileMenu"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Drawer Menu -->
        <div x-show="mobileMenu" x-transition class="md:hidden bg-white border-b border-velvoria-border px-4 pt-3 pb-6 space-y-3">
            <a href="<?= base_url('/') ?>" class="block py-2 text-base font-medium text-velvoria-ink hover:text-velvoria-plum">Beranda</a>
            <a href="<?= base_url('layanan') ?>" class="block py-2 text-base font-medium text-velvoria-ink hover:text-velvoria-plum">Layanan</a>
            <a href="<?= base_url('harga') ?>" class="block py-2 text-base font-medium text-velvoria-ink hover:text-velvoria-plum">Harga</a>
            <a href="<?= base_url('cara-kerja') ?>" class="block py-2 text-base font-medium text-velvoria-ink hover:text-velvoria-plum">Cara Kerja</a>
            <a href="<?= base_url('faq') ?>" class="block py-2 text-base font-medium text-velvoria-ink hover:text-velvoria-plum">FAQ</a>
            <a href="<?= base_url('kontak') ?>" class="block py-2 text-base font-medium text-velvoria-ink hover:text-velvoria-plum">Kontak</a>
            <div class="pt-4 border-t border-velvoria-border space-y-2">
                <?php if (session()->get('logged_in')): ?>
                    <a href="<?= base_url(session()->get('user_role') === 'admin' ? 'admin/dashboard' : 'customer/dashboard') ?>" class="block w-full text-center py-2.5 text-sm font-semibold text-white bg-velvoria-plum rounded-xl">
                        Buka Dashboard
                    </a>
                <?php else: ?>
                    <a href="<?= base_url('login') ?>" class="block w-full text-center py-2.5 text-sm font-semibold text-velvoria-plum border border-velvoria-plum rounded-xl">
                        Masuk
                    </a>
                    <a href="<?= base_url('register') ?>" class="block w-full text-center py-2.5 text-sm font-semibold text-white bg-velvoria-plum rounded-xl">
                        Daftar & Pesan Sekarang
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-grow">
        <?= $this->renderSection('content') ?>
    </main>

    <!-- Footer -->
    <footer class="bg-velvoria-ink text-white pt-16 pb-12 border-t border-velvoria-border/10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-10 pb-12 border-b border-white/10">
                <!-- Col 1: Brand Info -->
                <div class="space-y-4">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-xl bg-white/10 text-white flex items-center justify-center">
                            <i data-lucide="sparkles" class="w-5 h-5 text-velvoria-lilac"></i>
                        </div>
                        <span class="text-xl font-bold tracking-tight text-white">VELVORIA</span>
                    </div>
                    <p class="text-sm text-velvoria-muted leading-relaxed">
                        Layanan laundry premium kiloan dan satuan dengan standar kebersihan tinggi. Baju bersih, wangi, dan waktu Anda lebih luang.
                    </p>
                    <div class="flex items-center space-x-3 pt-2">
                        <a href="https://wa.me/6281234567890" target="_blank" class="w-9 h-9 rounded-lg bg-white/5 hover:bg-emerald-600 flex items-center justify-center text-white/80 hover:text-white transition-colors">
                            <i data-lucide="phone" class="w-4 h-4"></i>
                        </a>
                        <a href="mailto:halo@velvorialaundry.com" class="w-9 h-9 rounded-lg bg-white/5 hover:bg-velvoria-plum flex items-center justify-center text-white/80 hover:text-white transition-colors">
                            <i data-lucide="mail" class="w-4 h-4"></i>
                        </a>
                    </div>
                </div>

                <!-- Col 2: Navigasi Cepat -->
                <div>
                    <h4 class="text-sm font-semibold uppercase tracking-wider text-velvoria-lilac mb-4">Navigasi</h4>
                    <ul class="space-y-2.5 text-sm text-velvoria-muted">
                        <li><a href="<?= base_url('layanan') ?>" class="hover:text-white transition-colors">Katalog Layanan</a></li>
                        <li><a href="<?= base_url('harga') ?>" class="hover:text-white transition-colors">Daftar Harga</a></li>
                        <li><a href="<?= base_url('cara-kerja') ?>" class="hover:text-white transition-colors">Cara Kerja</a></li>
                        <li><a href="<?= base_url('tentang') ?>" class="hover:text-white transition-colors">Tentang Kami</a></li>
                        <li><a href="<?= base_url('faq') ?>" class="hover:text-white transition-colors">Tanya Jawab (FAQ)</a></li>
                    </ul>
                </div>

                <!-- Col 3: Layanan Populer -->
                <div>
                    <h4 class="text-sm font-semibold uppercase tracking-wider text-velvoria-lilac mb-4">Layanan Kami</h4>
                    <ul class="space-y-2.5 text-sm text-velvoria-muted">
                        <li><a href="<?= base_url('layanan') ?>" class="hover:text-white transition-colors">Cuci Kering Lipat</a></li>
                        <li><a href="<?= base_url('layanan') ?>" class="hover:text-white transition-colors">Cuci Setrika Reguler</a></li>
                        <li><a href="<?= base_url('layanan') ?>" class="hover:text-white transition-colors">Cuci Express 24 Jam</a></li>
                        <li><a href="<?= base_url('layanan') ?>" class="hover:text-white transition-colors">Perawatan Jas & Kemeja</a></li>
                        <li><a href="<?= base_url('layanan') ?>" class="hover:text-white transition-colors">Cuci Sepatu & Bed Cover</a></li>
                    </ul>
                </div>

                <!-- Col 4: Informasi Operasional -->
                <div>
                    <h4 class="text-sm font-semibold uppercase tracking-wider text-velvoria-lilac mb-4">Outlet & Jam Buka</h4>
                    <p class="text-sm text-velvoria-muted leading-relaxed mb-3">
                        Jl. Senopati Raya No. 45, Kebayoran Baru, Jakarta Selatan
                    </p>
                    <p class="text-xs text-velvoria-sage leading-relaxed mb-2 font-medium">
                        Senin - Sabtu: 07:00 - 21:00 WIB<br>
                        Minggu: 08:00 - 18:00 WIB
                    </p>
                    <div class="mt-4 pt-3 border-t border-white/10">
                        <span class="inline-flex items-center text-xs text-emerald-400">
                            <span class="w-2 h-2 rounded-full bg-emerald-400 mr-2 animate-pulse"></span>
                            Outlet Buka & Menerima Pesanan
                        </span>
                    </div>
                </div>
            </div>

            <!-- Bottom Copyright -->
            <div class="pt-8 flex flex-col sm:flex-row items-center justify-between text-xs text-velvoria-muted space-y-4 sm:space-y-0">
                <p>&copy; <?= date('Y') ?> Velvoria Laundry. Hak Cipta Dilindungi Undang-Undang.</p>
                <div class="flex space-x-6">
                    <a href="<?= base_url('terms') ?>" class="hover:text-white transition-colors">Syarat & Ketentuan</a>
                    <a href="<?= base_url('faq') ?>" class="hover:text-white transition-colors">Kebijakan Privasi</a>
                </div>
            </div>
        </div>
    </footer>

    <script>
        lucide.createIcons();
    </script>
    <?= $this->renderSection('scripts') ?>
</body>
</html>
