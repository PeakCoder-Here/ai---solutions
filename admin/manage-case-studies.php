<?php
/**
 * admin/manage-case-studies.php — Case Studies CMS (create / edit / delete)
 */
$pageTitle  = 'Manage Case Studies';
$activePage = 'manage-case-studies';

require_once(__DIR__ . '/../includes/config.php');
require_once(__DIR__ . '/../includes/auth.php');
require_once(__DIR__ . '/../includes/db.php');

$notice = '';
$error  = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'delete' && !empty($_POST['id'])) {
        try {
            $db->case_studies->deleteOne(['_id' => new MongoDB\BSON\ObjectId($_POST['id'])]);
            header('Location: manage-case-studies.php?deleted=1');
            exit;
        } catch (Exception $e) {
            $error = 'Could not delete that case study.';
        }
    }

    if ($action === 'save') {
        $id        = $_POST['id'] ?? '';
        $industry  = trim($_POST['industry']  ?? '');
        $company   = trim($_POST['company']   ?? '');
        $challenge = trim($_POST['challenge'] ?? '');
        $solution  = trim($_POST['solution']  ?? '');
        $img       = trim($_POST['img']       ?? '');
        $sortOrder = (int) ($_POST['sort_order'] ?? 0);
        $stats = [];
        for ($i = 1; $i <= 3; $i++) {
            $val = trim($_POST["stat{$i}_value"] ?? '');
            $lbl = trim($_POST["stat{$i}_label"] ?? '');
            if ($val !== '' || $lbl !== '') {
                $stats[] = ['value' => $val, 'label' => $lbl];
            }
        }

        if (empty($industry) || empty($company) || empty($challenge) || empty($solution)) {
            $error = 'Industry, company, challenge and solution are all required.';
        } else {
            $doc = [
                'industry'   => $industry,
                'company'    => $company,
                'challenge'  => $challenge,
                'solution'   => $solution,
                'img'        => $img,
                'stats'      => $stats,
                'sort_order' => $sortOrder,
            ];
            try {
                if ($id) {
                    $db->case_studies->updateOne(['_id' => new MongoDB\BSON\ObjectId($id)], ['$set' => $doc]);
                    header('Location: manage-case-studies.php?saved=1');
                } else {
                    $doc['created_at'] = new MongoDB\BSON\UTCDateTime();
                    $db->case_studies->insertOne($doc);
                    header('Location: manage-case-studies.php?created=1');
                }
                exit;
            } catch (Exception $e) {
                $error = 'Could not save that case study.';
            }
        }
    }
}

if (isset($_GET['saved']))   $notice = 'Case study updated.';
if (isset($_GET['created'])) $notice = 'Case study added.';
if (isset($_GET['deleted'])) $notice = 'Case study deleted.';

$mode = 'list';
$editing = null;
if (isset($_GET['new'])) {
    $mode = 'form';
} elseif (!empty($_GET['edit'])) {
    $mode = 'form';
    $editing = $db->case_studies->findOne(['_id' => new MongoDB\BSON\ObjectId($_GET['edit'])]);
}

$cases = iterator_to_array($db->case_studies->find([], ['sort' => ['sort_order' => 1, 'created_at' => 1]]));
$editStats = $editing['stats'] ?? [];

require_once(__DIR__ . '/partials/head.php');
?>

<?php if ($notice): ?>
    <div class="alert alert-success" style="margin-bottom:1.5rem;"><i class="fa fa-check-circle"></i> <?= htmlspecialchars($notice) ?></div>
<?php endif; ?>
<?php if ($error): ?>
    <div class="alert alert-error" style="margin-bottom:1.5rem;"><i class="fa fa-exclamation-circle"></i> <?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<?php if ($mode === 'list'): ?>

    <div class="admin-card">
        <div class="admin-card__header">
            <div class="admin-card__title"><i class="fa fa-briefcase"></i> Case Studies (<?= count($cases) ?>)</div>
            <a href="manage-case-studies.php?new=1" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> Add Case Study</a>
        </div>
        <div class="table-wrap">
            <table>
                <thead><tr><th>Preview</th><th>Company</th><th>Industry</th><th>Sort Order</th><th style="text-align:right;">Actions</th></tr></thead>
                <tbody>
                <?php if (empty($cases)): ?>
                    <tr><td colspan="5" style="text-align:center;color:var(--grey-text);padding:2.5rem;">No case studies yet.</td></tr>
                <?php else: foreach ($cases as $c): ?>
                    <tr>
                        <td><?php if (!empty($c['img'])): ?><img src="<?= htmlspecialchars($c['img']) ?>" alt="" style="width:64px;height:44px;object-fit:cover;border-radius:6px;"><?php endif; ?></td>
                        <td><?= htmlspecialchars($c['company']) ?></td>
                        <td><?= htmlspecialchars($c['industry']) ?></td>
                        <td><?= (int) ($c['sort_order'] ?? 0) ?></td>
                        <td style="text-align:right;white-space:nowrap;">
                            <a href="manage-case-studies.php?edit=<?= $c['_id'] ?>" class="btn btn-outline btn-sm" title="Edit"><i class="fa fa-pen"></i></a>
                            <form method="POST" style="display:inline;" onsubmit="return confirm('Delete this case study permanently?');">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" value="<?= $c['_id'] ?>">
                                <button type="submit" class="btn btn-danger btn-sm" title="Delete"><i class="fa fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; endif; ?>
                </tbody>
            </table>
        </div>
    </div>

<?php else: ?>

    <div class="admin-card">
        <div class="admin-card__header">
            <div class="admin-card__title"><i class="fa fa-pen"></i> <?= $editing ? 'Edit Case Study' : 'Add Case Study' ?></div>
            <a href="manage-case-studies.php" class="btn btn-outline btn-sm"><i class="fa fa-arrow-left"></i> Back to List</a>
        </div>

        <form method="POST">
            <input type="hidden" name="action" value="save">
            <?php if ($editing): ?><input type="hidden" name="id" value="<?= $editing['_id'] ?>"><?php endif; ?>

            <div class="form-row">
                <div class="form-group">
                    <label for="industry">Industry *</label>
                    <input type="text" id="industry" name="industry" class="form-control" required placeholder="e.g. Healthcare"
                           value="<?= htmlspecialchars($editing['industry'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label for="company">Company *</label>
                    <input type="text" id="company" name="company" class="form-control" required
                           value="<?= htmlspecialchars($editing['company'] ?? '') ?>">
                </div>
            </div>

            <div class="form-group">
                <label for="challenge">Challenge *</label>
                <textarea id="challenge" name="challenge" class="form-control" rows="2" required><?= htmlspecialchars($editing['challenge'] ?? '') ?></textarea>
            </div>

            <div class="form-group">
                <label for="solution">Solution *</label>
                <textarea id="solution" name="solution" class="form-control" rows="2" required><?= htmlspecialchars($editing['solution'] ?? '') ?></textarea>
            </div>

            <div class="form-group">
                <label for="img">Image URL</label>
                <input type="text" id="img" name="img" class="form-control" placeholder="https://..."
                       value="<?= htmlspecialchars($editing['img'] ?? '') ?>">
            </div>

            <label style="display:block;margin-bottom:.5rem;font-weight:600;">Result Stats (up to 3)</label>
            <?php for ($i = 1; $i <= 3; $i++): $s = $editStats[$i - 1] ?? ['value' => '', 'label' => '']; ?>
            <div class="form-row">
                <div class="form-group">
                    <label for="stat<?= $i ?>_value">Stat <?= $i ?> Value</label>
                    <input type="text" id="stat<?= $i ?>_value" name="stat<?= $i ?>_value" class="form-control" placeholder="e.g. 73%"
                           value="<?= htmlspecialchars($s['value'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label for="stat<?= $i ?>_label">Stat <?= $i ?> Label</label>
                    <input type="text" id="stat<?= $i ?>_label" name="stat<?= $i ?>_label" class="form-control" placeholder="e.g. Query Deflection"
                           value="<?= htmlspecialchars($s['label'] ?? '') ?>">
                </div>
            </div>
            <?php endfor; ?>

            <div class="form-group">
                <label for="sort_order">Sort Order</label>
                <input type="number" id="sort_order" name="sort_order" class="form-control"
                       value="<?= (int) ($editing['sort_order'] ?? 0) ?>">
            </div>

            <?php if (!empty($editing['img'])): ?>
                <img src="<?= htmlspecialchars($editing['img']) ?>" alt="" style="max-width:240px;border-radius:8px;margin-bottom:1rem;display:block;">
            <?php endif; ?>

            <button type="submit" class="btn btn-primary btn-lg">
                <i class="fa fa-save"></i> <?= $editing ? 'Save Changes' : 'Add Case Study' ?>
            </button>
        </form>
    </div>

<?php endif; ?>

<?php require_once(__DIR__ . '/partials/foot.php'); ?>
