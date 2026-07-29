<?php 
require_once('../private/initialize.php');
require_login();
$page_title = 'Archive';
include(SHARED_PATH . '/header.php');
?>

<main>
  <header class="page-header">
    <p class="section-label">Legacy</p>
    <h1>Archived pages</h1>
    <p class="page-lede">Historical routes kept for reference after the interactions migration.</p>
  </header>

  <div class="surface-panel">
    <p>Historical legacy interaction data was migrated into the modern
      <a href="<?php echo url_for('/uses/interactions.php'); ?>">interactions</a> list,
      so the old 1:1 Uses, Object Uses, and Objects pages have been removed.
      See <code>database/migrations/migrate-responses-to-uses.sql</code> and
      <code>migrate-use_table-to-uses.sql</code> for the record.</p>

    <ul class="list-2">
      <li>
        <a class="menu-link" href="<?php echo url_for('/aversions/index.php'); ?>">
          Aversions (Archived Dec 4 2022)
        </a>
      </li>
      <li>
        <a class="menu-link" href="<?php echo url_for('/aversions/new.php'); ?>">
          Record&nbsp;Aversion (Archived Dec 4 2022)
        </a>
      </li>
    </ul>

    <h2>Pages to update for multi-person interactions</h2>

    <div class="dashboard-actions">
      <a class="secondary-link" href="<?php echo url_for('/playgroup/index.php'); ?>">Group</a>
      <a class="secondary-link" href="<?php echo url_for('/playgroup/choose.php'); ?>">Choose for group</a>
      <a class="secondary-link" href="<?php echo url_for('/explore/uses-by-artifact.php'); ?>">Uses by artifact</a>
    </div>
  </div>
</main>

<?php include(SHARED_PATH . '/footer.php'); ?>
