<?php
/**
 * admin/partials/foot.php — Shared admin dashboard shell (closing half)
 *
 * Reconstructed file (see admin/partials/head.php for context). Closes
 * the .admin-content / .admin-main / .admin-layout tags opened there,
 * wires up the mobile sidebar toggle, and outputs any page-specific
 * $extraScripts queued by the including page (e.g. Chart.js init in
 * admin/dashboard.php).
 */
?>
        </main>
    </div>
</div>

<script>
document.getElementById('sidebarToggle')?.addEventListener('click', function () {
    document.getElementById('adminSidebar')?.classList.toggle('is-open');
});
</script>
<?= $extraScripts ?? '' ?>
</body>
</html>
