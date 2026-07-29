<?php // initialize page

  $page_title = 'Support';

  require_once('../private/initialize.php');

  require_login_or_guest();

  include(SHARED_PATH . '/header.php');

?>

<main>
  <header class="page-header">
    <p class="section-label">Help</p>
    <h1><?php echo h($page_title); ?></h1>
    <p class="page-lede">Questions, bugs, or account issues - reach the maintainer directly.</p>
  </header>

  <div class="surface-panel">
    <p>
      Contact <?php echo h(DEV_NAME); ?> at
      <a href="mailto:<?php echo h(DEV_EMAIL); ?>"><?php echo h(DEV_EMAIL); ?></a>
      for support.
    </p>
  </div>
</main>

<?php include(SHARED_PATH . '/footer.php'); ?>
