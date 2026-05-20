<?php
  require_once('../../private/initialize.php');
  require_login();

  $artifact_id = $_REQUEST['artifact_id'] ?? null;
  $value = isset($_REQUEST['value']) ? (int) $_REQUEST['value'] : 0;
  $return_to = $_REQUEST['return_to'] ?? 'useby';
  $is_ajax = strtolower($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '') === 'xmlhttprequest';

  if ($artifact_id === null) {
    if ($is_ajax) {
      header('Content-Type: application/json');
      http_response_code(400);
      echo json_encode(['ok' => false, 'message' => 'No artifact specified.']);
      exit;
    }
    $_SESSION['message'] = 'No artifact specified.';
    redirect_to(url_for('/artifacts/useby.php'));
  }

  $artifact_record = find_artifact_by_id($artifact_id);
  $artifact_name = $artifact_record['Title'] ?? ($_REQUEST['artifact_name'] ?? 'Artifact');

  $result = set_artifact_tracked($artifact_id, $value);

  if ($result) {
    $message = $value === 1
      ? $artifact_name . ' restored to tracked collection.'
      : $artifact_name . ' removed from tracked collection.';
  } else {
    $message = 'Failed to update artifact.';
  }

  if ($is_ajax) {
    header('Content-Type: application/json');
    echo json_encode([
      'ok' => (bool) $result,
      'value' => $value,
      'artifact_id' => (int) $artifact_id,
      'artifact_name' => $artifact_name,
      'message' => $message,
    ]);
    exit;
  }

  $_SESSION['message'] = h($message);

  if ($return_to === 'dashboard') {
    redirect_to(url_for('/index.php') . '#priority-queue');
  } else {
    redirect_to(url_for('/artifacts/useby.php'));
  }
?>
