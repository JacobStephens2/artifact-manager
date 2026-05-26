<?php 
require_once('../private/initialize.php');
require_login();
$page_title = 'Archive';
include(SHARED_PATH . '/header.php');
?>

<main>
  <div id="main-menu">

      <h1>Archived Pages</h1>

      <ul>
        <li>
          <a href="<?php echo url_for('/uses/index.php'); ?>">
            1:1 Uses (Archived Jul 16, 2023)
          </a>
        </li>

        <li>
          <a href="<?php echo url_for('/uses/new.php'); ?>">
            Record 1:1 &nbsp;Use (Archived Jul 16, 2023)
          </a>
        </li>
        
        <li>
          <a href="<?php echo url_for('/uses/create.php');?>">
            Record Use (Archived Jan 11, 2022)
          </a>
        </li>
      </ul>

      <ul>
        <li class="main-menu">
          <a href="<?php echo url_for('/objects/index.php'); ?>">
            Objects
          </a>
        </li>
      </ul>

      <ul class="list-2">
        <li>
          <a href="<?php echo url_for('/object_uses/new.php'); ?>">
            Record Object Use
          </a>
        </li>
        <li>
          <a href="<?php echo url_for('/object_uses/index.php'); ?>">
            Object Uses
          </a>
        </li>
        <li>
          <a href="<?php echo url_for('/objects/useby.php'); ?>">
            Use Objects by Date List
          </a>
        </li>
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
