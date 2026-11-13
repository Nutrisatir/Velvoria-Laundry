<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Autentikasi - Velvoria Laundry') ?></title>

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
                    fontFamily: { sans: ['Montserrat', 'Arial', 'sans-serif'] }
                }
            }
        }
    </script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        body { font-family: 'Montserrat', Arial, sans-serif; -webkit-font-smoothing: antialiased; }
        .auth-ambient {
            background:
                radial-gradient(circle at 22% 30%, rgba(190,164,226,.34), transparent 27%),
                radial-gradient(circle at 72% 65%, rgba(126,105,157,.42), transparent 32%),
                radial-gradient(circle at 55% 12%, rgba(169,149,199,.22), transparent 28%);
            filter: blur(34px);
            animation: authAmbientDrift 13s ease-in-out infinite alternate;
        }
        @keyframes authAmbientDrift {
            0% { transform: translate3d(-4%, -2%, 0) rotate(-4deg) scale(1); }
            50% { transform: translate3d(3%, 3%, 0) rotate(3deg) scale(1.08); }
            100% { transform: translate3d(-1%, 5%, 0) rotate(-2deg) scale(1.03); }
        }
        @media (prefers-reduced-motion: reduce) { .auth-ambient { animation: none; } }
        :root { scroll-behavior: smooth; }

        a, button, input, select, textarea, [role="button"] {
            transition: color .24s cubic-bezier(.22,.61,.36,1),
                        background-color .24s cubic-bezier(.22,.61,.36,1),
                        border-color .24s cubic-bezier(.22,.61,.36,1),
                        box-shadow .28s cubic-bezier(.22,.61,.36,1),
                        transform .28s cubic-bezier(.22,.61,.36,1),
                        opacity .24s ease;
        }
        button:active, [role="button"]:active { transform: scale(.985); }
        button:hover, a[role="button"]:hover { transform: translateY(-2px); box-shadow: 0 12px 24px rgba(40,35,49,.12); }
        button, a { transition-timing-function: cubic-bezier(.22,.61,.36,1); }
        a:focus-visible, button:focus-visible, input:focus-visible,
        select:focus-visible, textarea:focus-visible {
            outline: 3px solid rgba(169,149,199,.42); outline-offset: 2px;
        }

        /*
         * Auth editorial copy: each text line enters and exits softly.
         * Delays are assigned by JavaScript so the sequence remains staggered.
         */
        .auth-motion-item {
            opacity:  0;
            transform: translate3d(0, 20px, 0);
            will-change: opacity, transform;
            animation: authTextCycle 8.4s cubic-bezier(.22,.61,.36,1) infinite both;
        }
        @keyframes authTextCycle {
            0%   { opacity: 0; transform: translate3d(0, 20px, 0); }
            9%   { opacity: 1; transform: translate3d(0, 0, 0); }
            72%  { opacity: 1; transform: translate3d(0, 0, 0); }
            88%  { opacity: 0; transform: translate3d(0, -12px, 0); }
            100% { opacity: 0; transform: translate3d(0, -12px, 0); }
        }

        .auth-form-card {
            animation: authCardIn .85s cubic-bezier(.22,.61,.36,1) both;
        }
        @keyframes authCardIn {
            from { opacity: 0; transform: translate3d(0, 18px, 0) scale(.99); }
            to   { opacity: 1; transform: translate3d(0, 0, 0) scale(1); }
        }

        @media (prefers-reduced-motion: reduce) {
            html { scroll-behavior: auto; }
            *, *::before, *::after {
                animation-duration: .01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: .01ms !important;
            }
            .auth-motion-item { opacity: 1; transform: none; animation: none; }
            .auth-form-card { animation: none; }
        }
    </style>
</head>
<body class="bg-velvoria-cream text-velvoria-ink antialiased min-h-screen flex flex-col justify-center">
    <div class="min-h-screen flex">
        <!-- Brand panel -->
        <aside class="hidden lg:flex lg:w-1/2 bg-velvoria-plum text-white flex-col justify-between p-12 relative overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-br from-velvoria-plum via-velvoria-ink to-velvoria-plum opacity-90 z-0"></div>
            <div class="auth-ambient absolute -inset-[35%] z-0 pointer-events-none"></div>
            <div class="absolute -right-20 -bottom-20 w-96 h-96 rounded-full bg-velvoria-lilac/10 blur-3xl z-0"></div>
            <div class="absolute -left-20 -top-20 w-96 h-96 rounded-full bg-velvoria-sage/10 blur-3xl z-0"></div>

            <div class="relative z-10">
                <a href="<?= base_url('/') ?>" class="inline-flex items-center space-x-3 group">
                    <div class="w-10 h-10 rounded-xl bg-white/10 text-white flex items-center justify-center backdrop-blur">
                        <i data-lucide="sparkles" class="w-5 h-5 text-velvoria-lilac"></i>
                    </div>
                    <span class="text-xl font-bold tracking-tight text-white">VELVORIA LAUNDRY</span>
                </a>
            </div>

            <div class="relative z-10 max-w-md my-auto space-y-6" id="authEditorial">
                <div class="auth-motion-item inline-flex items-center px-3 py-1 rounded-full bg-white/10 text-velvoria-lilac text-xs font-semibold tracking-wide uppercase">
                    Premium Fabric Care
                </div>

                <h1 class="text-4xl font-bold tracking-tight leading-tight text-white">
                    <span class="auth-motion-item block">Baju Bersih Sempurna,</span>
                    <span class="auth-motion-item block">Tanpa Menguras Waktu</span>
                    <span class="auth-motion-item block">Anda.</span>
                </h1>

                <p class="auth-motion-item text-velvoria-cream/80 text-sm leading-relaxed">
                    Nikmati kemudahan layanan laundry kiloan dan satuan dengan penjemputan praktis, pelacakan real-time, dan pembayaran digital tanpa repot.
                </p>

                <div class="pt-4 grid grid-cols-2 gap-4 text-xs">
                    <div class="auth-motion-item flex items-center space-x-2">
                        <i data-lucide="check" class="w-4 h-4 text-velvoria-sage"></i><span>Harga Transparan</span>
                    </div>
                    <div class="auth-motion-item flex items-center space-x-2">
                        <i data-lucide="check" class="w-4 h-4 text-velvoria-sage"></i><span>Detergen Ramah Serat</span>
                    </div>
                    <div class="auth-motion-item flex items-center space-x-2">
                        <i data-lucide="check" class="w-4 h-4 text-velvoria-sage"></i><span>Layanan Express 24 Jam</span>
                    </div>
                    <div class="auth-motion-item flex items-center space-x-2">
                        <i data-lucide="check" class="w-4 h-4 text-velvoria-sage"></i><span>Midtrans Digital Pay</span>
                    </div>
                </div>
            </div>

            <div class="relative z-10 text-xs text-white/50 auth-motion-item">
                &copy; <?= date('Y') ?> Velvoria Laundry. Hak Cipta Dilindungi.
            </div>
        </aside>

        <!-- Form panel -->
        <main class="w-full lg:w-1/2 flex items-center justify-center p-6 sm:p-12">
            <div class="w-full max-w-md auth-form-card">
                <div class="lg:hidden text-center mb-8">
                    <a href="<?= base_url('/') ?>" class="inline-flex items-center space-x-2">
                        <div class="w-9 h-9 rounded-xl bg-velvoria-plum text-white flex items-center justify-center">
                            <i data-lucide="sparkles" class="w-4 h-4 text-velvoria-lilac"></i>
                        </div>
                        <span class="text-xl font-bold tracking-tight text-velvoria-ink">VELVORIA</span>
                    </a>
                </div>

                <?= $this->renderSection('content') ?>
            </div>
        </main>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        if (window.lucide) window.lucide.createIcons();

        const items = Array.from(document.querySelectorAll('#authEditorial .auth-motion-item'));
        const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

        if (reducedMotion) {
            items.forEach(item => {
                item.style.opacity = '1';
                item.style.transform = 'none';
                item.style.animation = 'none';
            });
            return;
        }

        // A short stagger gives each phrase its own smooth entrance.
        items.forEach((item, index) => {
            item.style.animationDelay = (index * 0.42) + 's';
        });
    });
    </script>
    <?= $this->renderSection('scripts') ?>
</body>
</html>
