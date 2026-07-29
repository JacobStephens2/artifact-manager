<?php 
require_once('../../private/initialize.php');
require_login();
$player_set = find_players_by_user_id();
$page_title = 'Users';
include(SHARED_PATH . '/header.php');
include(SHARED_PATH . '/dataTable.html');
?>

<main>
  <div class="objects listing">
    <header class="page-header page-header-row">
      <div>
        <p class="section-label">People</p>
        <h1>Users</h1>
        <p class="page-lede">People who appear on your interaction records.</p>
      </div>
      <div class="page-header-actions">
        <a class="prominent-link" href="<?php echo url_for('/users/new'); ?>">Create user</a>
      </div>
    </header>

    <?php if ($player_set->num_rows === 0) { ?>
      <div class="empty-state">
        <p class="section-label">Empty</p>
        <h2>No users yet</h2>
        <p>Add people so you can record who shared an interaction.</p>
        <a class="prominent-link" href="<?php echo url_for('/users/new'); ?>">Create user</a>
      </div>
    <?php } else { ?>
    <div class="table-scroll">
  	<table class="list" id="users" data-page-length='100'>

      <thead>
        <tr id="headerRow">
          <th>Name (<?php echo $player_set->num_rows; ?>)</th>
          <th>Gender</th>
          <th>Age</th>
          <th></th>
          <th>ID</th>
        </tr>
      </thead>

      <tbody>
        <?php while($player = mysqli_fetch_assoc($player_set)) { ?>
          <tr>
            <td>
              <a class="table-action" href="<?php echo url_for('/users/edit.php?id=' . h(u($player['id']))); ?>">
                <?php echo h($player['FirstName']) . ' ' . h($player['LastName']); ?>
              </a>
            </td>
            <td><?php echo h($player['G']); ?></td>
            <td><?php echo $player['birth_year'] ? (date('Y') - (int) $player['birth_year']) : ''; ?></td>
            <td><a class="table-action" href="<?php echo url_for('/users/delete.php?id=' . h(u($player['id']))); ?>">Delete</a></td>
            <td><?php echo h($player['id']); ?></td>
          </tr>
        <?php } ?>
      </tbody>

  	</table>
    </div>

    <script>
      let table = new DataTable('#users', {
        // options
        order: [[ 4, 'desc']] // most recent users first
      });
    </script>
    <?php } ?>

    <?php mysqli_free_result($player_set); ?>
  </div>

</main>

<?php include(SHARED_PATH . '/footer.php'); ?>
