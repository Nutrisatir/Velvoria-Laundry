<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<div class="max-w-7xl mx-auto px-4 py-8">
    <div class="mb-6">
        <p class="text-xs uppercase tracking-widest text-velvoria-muted">Audit sistem</p>
        <h1 class="text-2xl font-bold text-velvoria-ink">Activity Logs</h1>
        <p class="text-sm text-velvoria-muted mt-1">Catatan aktivitas admin dan perubahan data penting.</p>
    </div>

    <div class="overflow-x-auto rounded-2xl border border-velvoria-border bg-white">
        <table class="w-full text-sm">
            <thead class="bg-velvoria-cream text-left">
                <tr>
                    <th class="p-4">Waktu</th>
                    <th class="p-4">Pengguna</th>
                    <th class="p-4">Aksi</th>
                    <th class="p-4">Objek</th>
                    <th class="p-4">Keterangan</th>
                    <th class="p-4">IP</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($logs)): ?>
                    <tr>
                        <td colspan="6" class="p-8 text-center text-velvoria-muted">
                            Belum ada aktivitas tercatat.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($logs as $log): ?>
                        <tr class="border-t border-velvoria-border hover:bg-velvoria-cream/40">
                            <td class="p-4 whitespace-nowrap"><?= esc($log['created_at'] ?? '-') ?></td>
                            <td class="p-4"><?= esc($log['user_name'] ?? 'Sistem') ?></td>
                            <td class="p-4"><?= esc($log['action'] ?? '-') ?></td>
                            <td class="p-4"><?= esc(($log['entity_type'] ?? '-') . ' #' . ($log['entity_id'] ?? '-')) ?></td>
                            <td class="p-4 min-w-64"><?= esc($log['description'] ?? '-') ?></td>
                            <td class="p-4 whitespace-nowrap"><?= esc($log['ip_address'] ?? '-') ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php
    // Avoid rendering the framework's pager markup when only one page exists.
    // This also prevents an orphaned page marker below a single-page table.
    $pageCount = method_exists($pager, 'getPageCount') ? (int) $pager->getPageCount() : 1;
    ?>
    <?php if ($pageCount > 1): ?>
        <nav class="mt-6 flex justify-center" aria-label="Navigasi halaman activity logs">
            <?= $pager->links('default', 'default_full') ?>
        </nav>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>
