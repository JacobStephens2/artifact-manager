<?php 
require_once('../private/initialize.php');
require_login();
$page_title = 'Archive';
include(SHARED_PATH . '/header.php');
?>

<main>
  <div id="main-menu">

      <h1>Archived Pages</h1>

      <p>Historical legacy interaction data was migrated into the modern
        <a href="<?php echo url_for('/uses/interactions.php'); ?>">interactions</a> list,
        so the old 1:1 Uses, Object Uses, and Objects pages have been removed.
        See <code>database/migrations/migrate-responses-to-uses.sql</code> and
        <code>migrate-use_table-to-uses.sql</code> for the record.</p>

      <ul class="list-2">
        <li>
          <a href="<?php echo url_for('/aversions/index.php'); ?>">
            Aversions (Archived Dec 4 2022)
          </a>
        </li>
        <li>
          <a href="<?php echo url_for('/aversions/new.php'); ?>">
            Record&nbsp;Aversion (Archived Dec 4 2022)
          </a>
        </li>
      </ul>

      <h2>Pages to be updated to record interactions with multiple people</h2>

      <a href="<?php echo url_for('/playgroup/index.php'); ?>">
        Group
      </a>

      <a href="<?php echo url_for('/playgroup/choose.php'); ?>">
        Choose&nbsp;for&nbsp;Group
      </a>

      <a href="<?php echo url_for('/explore/uses-by-artifact.php'); ?>">
        Uses By Artifact
      </a>


  </div>

</main>

<?php include(SHARED_PATH . '/footer.php'); ?>
