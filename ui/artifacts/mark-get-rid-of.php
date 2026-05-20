<?php
  require_once('../../private/initialize.php');
  require_login();

  $artifact_id = $_REQUEST['artifact_id'] ?? null;
  $value = isset($_REQUEST['value']) ? (int) $_REQUEST['value'] : 1;
  $return_to = $_REQUEST['return_to'] ?? 'useby';

  if ($artifact_id === null) {
    $_SESSION['message'] = 'No artifact specified.';
    redirect_to(url_for('/artifacts/useby.php'));
  }

  $artifact_record = find_artifact_by_id($artifact_id);
  $artifact_name = $artifact_record['Title'] ?? ($_REQUEST['artifact_name'] ?? 'Artifact');

  $result = set_artifact_to_get_rid_of($artifact_id, $value);

  if ($result) {
    if ($value === 1) {
      $_SESSION['message'] = h($artifact_name) . ' marked to get rid of.';
    } else {
      $_SESSION['message'] = h($artifact_name) . ' restored to collection.';
    }
  } else {
    $_SESSION['message'] = 'Failed to update artifact.';
  }

  if ($return_to === 'to-get-rid-of') {
    redirect_to(url_for('/artifacts/to-get-rid-of.php'));
  } elseif ($return_to === 'dashboard') {
    redirect_to(url_for('/index.php') . '#priority-queue');
  } else {
    redirect_to(url_for('/artifacts/useby.php'));
  }
?>
