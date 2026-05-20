<?php  // require login
  require_once('../../private/initialize.php');
  require_login_or_guest();
?>

<?php // load header
  $page_title = 'Interact By';
  include(SHARED_PATH . '/header.php');
  include(SHARED_PATH . '/dataTable.html'); 
?>
<script defer src="/shared/filter_button.js"></script>
<script defer src="useby.js?v=7"></script>

<?php // process form submission and initialize variables
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['type'])) {
      $type = $_POST['type'];
    } else {
      $type = [];
    }
  } else {
    if (isset($_SESSION['type']) && count($_SESSION['type']) > 0) {
      $type = $_SESSION['type'];
    } else {
      include(SHARED_PATH . '/artifact_type_array.php'); 
      global $typesArray;
      $type = $typesArray;
    }
  }

  $user_id = $_SESSION['user_id'];
  $_SESSION['type'] = $type;
  $sweetSpot = $_POST['sweetSpot'] ?? '';
  $minimumAge = $_POST['minimumAge'] ?? 0;
  $shelfSort = $_POST['shelfSort'] ?? 'no';
  $showAttributes = $_POST['showAttributes'] ?? 'no';
  $showInterval = $_POST['showInterval'] ?? 'no';
  $typeArray = $_SESSION['type'] ?? [];
  $default_use_interval = singleValueQuery("SELECT default_use_interval
    FROM users
    WHERE id = '$user_id'
  ");
  $interval = $_POST['interval'] ?? $default_use_interval;
  $artifact_set = use_by($type, $interval, $sweetSpot, $minimumAge, $shelfSort);
  $total_overdue = 0;
?>

<main>

  <meta id="apiOrigin" content="<?php echo API_ORIGIN; ?>">

  <div class="page-header-row">
    <h1>
      <a class="hideOnPrint" target="_blank"
        href="<?php echo url_for('/objects/about-useby.php'); ?>"
        >
        Interact with by date
      </a>
    </h1>
    <div class="page-header-actions">
      <?php if (!is_guest()) { ?><button id="send_use_email" data-userid="<?php echo $user_id; ?>">Send Interact Email</button><?php } ?>
      <div id="view_toggle" class="view-toggle" role="group" aria-label="View mode">
        <button type="button" class="view-toggle-btn" data-view="table" aria-pressed="false">Table</button>
        <button type="button" class="view-toggle-btn" data-view="cards" aria-pressed="false">Cards</button>
      </div>
      <button id="display_filters">Show filters</button>
    </div>
  </div>

  <form action="<?php echo url_for('/artifacts/useby.php'); ?>"
    method="post"
    style="display: none"
    >
    <?php echo csrf_input(); ?>
    <div class="hideOnPrint">

      <label for="artifactType">Artifact type</label>
      <section id="artifactType" style="display: flex; flex-wrap: wrap">
        <?php require_once SHARED_PATH . '/artifact_type_checkboxes.php'; ?>
      </section>

      <label for="sweetSpot">Sweet Spot</label>
      <input type="number" name="sweetSpot" id="sweetSpot" value="<?php echo $sweetSpot; ?>">

      <label for="minimumAge">Minimum Age</label>
      <input type="number" name="minimumAge" id="minimumAge" value="<?php echo $minimumAge; ?>">
      
      <label for="shelfSort">Shelf Sort (Instead of Interact By Sort)</label>
      <input type="hidden" name="shelfSort" value="no">
      <input type="checkbox" name="shelfSort" id="shelfSort" value="yes"
        <?php 
          if ($shelfSort === 'yes') {
            echo ' checked ';
          }
        ?>
      >
      
      <label for="showAttributes">Show artifact attributes</label>
      <input type="hidden" name="showAttributes" value="no">
      <input type="checkbox" name="showAttributes" id="showAttributes" value="yes"
        <?php
          if ($showAttributes === 'yes') {
            echo ' checked ';
          }
        ?>
      >

      <label for="showInterval">Show interval column</label>
      <input type="hidden" name="showInterval" value="no">
      <input type="checkbox" name="showInterval" id="showInterval" value="yes"
        <?php
          if ($showInterval === 'yes') {
            echo ' checked ';
          }
        ?>
      >

    </div>

    <div class="displayOnPrint">
      <label for="interval">Interval in days from most recent or to upcoming use</label>
      <input type="number" step="0.1" name="interval" id="interval" value="<?php echo $interval ?>">
    </div>
    
    <input type="submit" value="Submit" class="hideOnPrint"/>
  
    <section id="legend">
      <p>U stands for used at recommended user count or used fully through at non-recommended count</p>
    </section>
  </form>

  <p class="copied_message" style="display: none"></p>
  <div id="useby-toast" class="toast" role="status" aria-live="polite"></div>

  <?php if (!is_guest()) { ?>
  <?php
    $modal_default_setting = singleValueQuery(
      "SELECT note FROM uses WHERE user_id = '" . (int) $_SESSION['user_id'] . "' ORDER BY id DESC LIMIT 1"
    );
  ?>
  <div id="record-modal" class="modal" hidden aria-hidden="true">
    <div class="modal-backdrop" data-modal-close></div>
    <div class="modal-panel" role="dialog" aria-modal="true" aria-labelledby="record-modal-title">
      <button type="button" class="modal-close" data-modal-close aria-label="Close">&times;</button>
      <h2 id="record-modal-title" class="modal-title">Record interaction</h2>
      <p id="record-modal-artifact" class="modal-subtitle"></p>
      <form id="record-modal-form" method="post" action="<?php echo url_for('/uses/1-n-new.php'); ?>">
        <?php echo csrf_input(); ?>
        <input type="hidden" name="artifact[id]" id="record-modal-artifact-id">
        <input type="hidden" name="artifact[name]" id="record-modal-artifact-name">
        <input type="hidden" name="user[0][id]" value="<?php echo h($_SESSION['player_id'] ?? ''); ?>">
        <input type="hidden" name="user[0][name]" value="<?php echo h($_SESSION['FullName'] ?? ''); ?>">

        <label for="record-modal-date">Date</label>
        <input type="date" name="useDate" id="record-modal-date" required>

        <label for="record-modal-setting">Setting</label>
        <input type="text" name="Note" id="record-modal-setting" value="<?php echo h($modal_default_setting ?? ''); ?>">

        <label for="record-modal-notes">Notes</label>
        <textarea name="NotesTwo" id="record-modal-notes" rows="3"></textarea>

        <div class="modal-actions">
          <a class="modal-link" id="record-modal-fullform-link" href="#" target="_blank">Open full form</a>
          <button type="button" class="modal-cancel" data-modal-close>Cancel</button>
          <button type="submit" class="modal-save">Save</button>
        </div>
      </form>
    </div>
  </div>
  <?php } ?>

  <table id="useBy" class="list" data-page-length='100'>
    <thead>
      <tr id="headerRow">
        <th>Name (<?php echo $artifact_set->num_rows; ?>)</th>
        <th>Interact By</th>
        <?php if (!is_guest()) { ?><th>Record</th><?php } ?>
        <th>Type</th>
        <?php
          if ($showAttributes === 'yes') {
            ?>
            <th>SwS</th>
            <th>AvgT</th>
            <th>Age</th>
            <th>SwS's</th>
            <th>MnP</th>
            <th>MxP</th>
            <th>C</th>
            <?php
          } else {
            ?>
            <?php
          }
        ?>
        <?php if (!is_guest()) { ?><th class="hideOnPrint">Get Rid Of</th><?php } ?>
        <th>Overdue (<span id="totalOverdue"></span>)</th>
        <th class="hideOnPrint">Recent Interaction</th>
        <th>Tracking Start</th>
        <?php if ($showInterval === 'yes') { ?><th>Interval</th><?php } ?>
      </tr>
    </thead>

    <tbody>
      <?php while($artifact = mysqli_fetch_assoc($artifact_set)) { 
        $id = h(u($artifact['id']));
        if ($artifact['interaction_frequency_days'] !== null) {
          $this_interval = $artifact['interaction_frequency_days'];
        } else {
          $this_interval = $interval;
        }
        ?>
        <tr>
          <td class="name artifact edit" data-label="Name">
            <div>
              <a id="artifact_id_<?php echo $id; ?>"
                class="action edit"
                href="<?php echo url_for('/artifacts/' . (is_guest() ? 'show' : 'edit') . '.php?id=' . $id); ?>"
                ><?php echo h($artifact['Title']);
              ?></a>
              <img class="clipboard"
                id="artifact_id_copy_<?php echo $id; ?>"
                src="/assets/copy.png"
                alt="A clipboard icon for copying"
              >

              <script>
                document
                  .querySelector('img#artifact_id_copy_<?php echo $id; ?>')
                  .addEventListener('click', function() {
                    let text = document.querySelector('a#artifact_id_<?php echo $id; ?>').innerHTML;
                    navigator.clipboard.writeText(text);
                    var copied_message = document.querySelector('p.copied_message');
                    copied_message.innerText = text + ' copied';
                    copied_message.style.display = 'block';
                    setTimeout(() => {
                      copied_message.innerText = '';
                      copied_message.style.display = 'none';
                    }, 1500);
                  }
                );

              </script>
            </div>
          </td>

          <?php
              date_default_timezone_set('America/New_York');
              $DateTimeNow = new DateTime(date('Y-m-d'));
              $DateTimeMostRecentUse = new DateTime(substr($artifact['MostRecentUseOrResponse'],0,10));
              $DateTimeAcquisition = new DateTime(substr($artifact['Acq'],0,10));

              $intervalInHours = $this_interval * 24;

              if ($DateTimeMostRecentUse < $DateTimeAcquisition || $artifact['MostRecentUseOrResponse'] === NULL) {
                $DateInterval = DateInterval::createFromDateString("$intervalInHours hour");
                $useByDate = date_add($DateTimeAcquisition, $DateInterval);
              } else {
                $doubledInterval = $intervalInHours * 2;
                $DateInterval = DateInterval::createFromDateString("$doubledInterval hour");
                $useByDate = date_add($DateTimeMostRecentUse, $DateInterval);
              }
          ?>

          <td class="useByDate date<?php if ($useByDate < $DateTimeNow) echo ' overdue-past'; ?>" data-label="Interact by"><?php print_r($useByDate->format('Y-m-d')); ?></td>

            <?php if (!is_guest()) { ?>
            <td class="record" data-label="Record">
              <a href="/uses/1-n-new?artifact_id=<?php echo $id; ?>"
                target="_blank"
                >
                Record
              </a>
            </td>
            <?php } ?>

          <td class="type" data-label="Type"><?php echo h($artifact['type']); ?></td>

          <?php
          if ($showAttributes === 'yes') {
            ?>
            <td class="SwS" data-label="SwS">
              <?php
                // find the first number without leading zeros
                preg_match(
                  '/([1-9][0-9])|[1-9]/',
                  $artifact['ss'],
                  $match
                );
                echo h($match[0]);
              ?>
            </td>

            <td class="AvgT" data-label="AvgT"><?php echo (h($artifact['mnt']) + h($artifact['mxt'])) / 2; ?></td>
            <td class="Age" data-label="Age"><?php echo h($artifact['age']); ?></td>
            <td class="SwSs" data-label="SwS's"><?php echo h($artifact['ss']); ?></td>
            <td class="MnP" data-label="MnP"><?php echo h($artifact['mnp']); ?></td>
            <td class="MxP" data-label="MxP"><?php echo h($artifact['mxp']); ?></td>

            <td class="candidate" data-label="Candidate">
              <?php
              if ( strlen($artifact['Candidate']) > 0 ) {
                echo 'Yes';
              }
              ?>
            </td>
            <?php
          }
          ?>

          <?php if (!is_guest()) { ?>
          <td class="get-rid-of hideOnPrint" data-label="Actions">
            <form method="post" action="<?php echo url_for('/artifacts/mark-get-rid-of.php'); ?>" class="get-rid-of-form" style="margin:0;">
              <?php echo csrf_input(); ?>
              <input type="hidden" name="artifact_id" value="<?php echo $id; ?>">
              <input type="hidden" name="artifact_name" value="<?php echo h($artifact['Title']); ?>">
              <input type="hidden" name="return_to" value="useby">
              <button type="submit" class="get-rid-of-btn">Get Rid Of</button>
            </form>
            <form method="post" action="<?php echo url_for('/artifacts/set-tracked.php'); ?>" class="untrack-form" style="margin:0;">
              <?php echo csrf_input(); ?>
              <input type="hidden" name="artifact_id" value="<?php echo $id; ?>">
              <input type="hidden" name="artifact_name" value="<?php echo h($artifact['Title']); ?>">
              <input type="hidden" name="value" value="0">
              <input type="hidden" name="return_to" value="useby">
              <button type="submit" class="untrack-btn">Remove</button>
            </form>
          </td>
          <?php } ?>

          <td class="overdue" data-label="Overdue"
            <?php
                if ($useByDate < $DateTimeNow) {
                  echo 'style="color: red;"';
                }
            ?>
            >
            <?php
                if ($useByDate < $DateTimeNow) {
                  $total_overdue++;
                  echo 'Yes';
                } else {
                  echo 'No';
                }
              ?>
          </td>

          <td class="mostRecentUse date hideOnPrint" data-label="Last interacted">
            <?php
              $mostRecent = substr($artifact['MostRecentUseOrResponse'] ?? '', 0, 10);
              echo $mostRecent !== '' ? h($mostRecent) : '—';
            ?>
          </td>

          <td class="acquisitionDate" data-label="Tracking start"><?php echo h($artifact['Acq']); ?></td>
          <?php if ($showInterval === 'yes') { ?>
          <td class="interval" data-label="Interval"><?php echo $this_interval; ?></td>
          <?php } ?>
        </tr>
      <?php } ?>
    </tbody>
  </table>

  <?php mysqli_free_result($artifact_set); ?>
  <script>
    document.querySelector('span#totalOverdue').innerText = '<?php echo $total_overdue; ?>';
    <?php
      // Compute column indices based on which columns are actually rendered
      // so the DataTable order config does not reference missing columns
      // (e.g. Record / Get Rid Of are hidden in guest mode, Interval is
      // hidden unless its filter is on).
      $colIdx = [];
      $col = 0;
      $colIdx['name'] = $col++;
      $colIdx['interactBy'] = $col++;
      if (!is_guest()) { $colIdx['record'] = $col++; }
      $colIdx['type'] = $col++;
      if ($showAttributes === 'yes') {
        $colIdx['sws'] = $col++;
        $colIdx['avgt'] = $col++;
        $colIdx['age'] = $col++;
        $colIdx['swss'] = $col++;
        $colIdx['mnp'] = $col++;
        $colIdx['mxp'] = $col++;
        $colIdx['candidate'] = $col++;
      }
      if (!is_guest()) { $colIdx['getRidOf'] = $col++; }
      $colIdx['overdue'] = $col++;
      $colIdx['recentUse'] = $col++;
      $colIdx['acq'] = $col++;
      if ($showInterval === 'yes') { $colIdx['interval'] = $col++; }
    ?>
    let table = new DataTable('#useBy', {
      // options
      <?php
        if ($shelfSort === 'yes' && $showAttributes === 'yes') {
          ?>
          order: [
            [ <?php echo $colIdx['type']; ?>, 'asc'],
            [ <?php echo $colIdx['sws']; ?>, 'asc'],
            [ <?php echo $colIdx['avgt']; ?>, 'asc'],
            [ <?php echo $colIdx['age']; ?>, 'asc'],
            [ <?php echo $colIdx['swss']; ?>, 'asc'],
            [ <?php echo $colIdx['mnp']; ?>, 'asc'],
            [ <?php echo $colIdx['mxp']; ?>, 'asc'],
            [ <?php echo $colIdx['recentUse']; ?>, 'desc'],
            [ <?php echo $colIdx['candidate']; ?>, 'desc'],
          ]
          <?php
        } elseif ($showAttributes === 'yes') {
          ?>
          order: [
            [ <?php echo $colIdx['interactBy']; ?>, 'asc'],
            [ <?php echo $colIdx['avgt']; ?>, 'asc'],
            [ <?php echo $colIdx['age']; ?>, 'asc'],
          ]
          <?php
        } else {
          ?>
          order: [
            [ <?php echo $colIdx['interactBy']; ?>, 'asc'],
            [ <?php echo $colIdx['recentUse']; ?>, 'asc'],
            [ <?php echo $colIdx['acq']; ?>, 'asc'],
          ]
          <?php
        }
      ?>
    });

    document.addEventListener('keypress', function(event) {
      if (event.key === 'Enter') {
        event.preventDefault();
        document.querySelector('form').submit();
      }
    });

    (function () {
      var toastEl = document.getElementById('useby-toast');
      var toastTimer = null;
      function showToast(message, kind) {
        if (!toastEl) { alert(message); return; }
        toastEl.textContent = message;
        toastEl.classList.remove('toast-success', 'toast-error', 'is-visible');
        toastEl.classList.add(kind === 'error' ? 'toast-error' : 'toast-success');
        void toastEl.offsetWidth;
        toastEl.classList.add('is-visible');
        if (toastTimer) clearTimeout(toastTimer);
        toastTimer = setTimeout(function () {
          toastEl.classList.remove('is-visible');
        }, 3500);
      }

      var overdueSpan = document.querySelector('span#totalOverdue');

      var recordModal = document.getElementById('record-modal');
      var recordForm = document.getElementById('record-modal-form');
      var modalArtifactInput = document.getElementById('record-modal-artifact-id');
      var modalArtifactNameInput = document.getElementById('record-modal-artifact-name');
      var modalArtifactDisplay = document.getElementById('record-modal-artifact');
      var modalDateInput = document.getElementById('record-modal-date');
      var modalSettingInput = document.getElementById('record-modal-setting');
      var modalNotesInput = document.getElementById('record-modal-notes');
      var modalSaveBtn = recordForm ? recordForm.querySelector('.modal-save') : null;
      var modalFullFormLink = document.getElementById('record-modal-fullform-link');
      var currentRecordRow = null;

      function todayLocal() {
        var d = new Date();
        return d.getFullYear() + '-'
          + String(d.getMonth() + 1).padStart(2, '0') + '-'
          + String(d.getDate()).padStart(2, '0');
      }

      function openRecordModal(artifactId, artifactName, tr) {
        if (!recordModal) return;
        currentRecordRow = tr;
        modalArtifactInput.value = artifactId;
        modalArtifactNameInput.value = artifactName;
        modalArtifactDisplay.textContent = artifactName;
        modalDateInput.value = todayLocal();
        modalNotesInput.value = '';
        if (modalSaveBtn) { modalSaveBtn.disabled = false; modalSaveBtn.textContent = 'Save'; }
        if (modalFullFormLink) {
          modalFullFormLink.href = '/uses/1-n-new.php?artifact_id=' + encodeURIComponent(artifactId);
        }
        recordModal.hidden = false;
        recordModal.setAttribute('aria-hidden', 'false');
        document.body.classList.add('modal-open');
        window.setTimeout(function () { modalDateInput.focus(); }, 30);
      }

      function closeRecordModal() {
        if (!recordModal) return;
        recordModal.hidden = true;
        recordModal.setAttribute('aria-hidden', 'true');
        document.body.classList.remove('modal-open');
        currentRecordRow = null;
      }

      if (recordModal) {
        recordModal.querySelectorAll('[data-modal-close]').forEach(function (el) {
          el.addEventListener('click', closeRecordModal);
        });
        document.addEventListener('keydown', function (event) {
          if (event.key === 'Escape' && !recordModal.hidden) closeRecordModal();
        });
      }

      document.querySelectorAll('table#useBy td.record a').forEach(function (link) {
        link.addEventListener('click', function (event) {
          if (!recordModal) return;
          event.preventDefault();
          var tr = link.closest('tr');
          var idMatch = (link.getAttribute('href') || '').match(/artifact_id=(\d+)/);
          var artifactId = idMatch ? idMatch[1] : null;
          var titleAnchor = tr ? tr.querySelector('td.name a') : null;
          var artifactName = titleAnchor ? titleAnchor.textContent.trim() : '';
          if (!artifactId) return;
          openRecordModal(artifactId, artifactName, tr);
        });
      });

      if (recordForm) {
        recordForm.addEventListener('submit', function (event) {
          event.preventDefault();
          if (modalSaveBtn) { modalSaveBtn.disabled = true; modalSaveBtn.textContent = 'Saving…'; }
          fetch(recordForm.action, {
            method: 'POST',
            credentials: 'include',
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
            body: new FormData(recordForm),
          })
            .then(function (response) {
              return response.json().then(function (data) { return { ok: response.ok, data: data }; });
            })
            .then(function (result) {
              if (result.ok && result.data && result.data.ok) {
                handleRecordSuccess(result.data, currentRecordRow);
                closeRecordModal();
                showToast(result.data.message || 'Interaction recorded.', 'success');
              } else {
                var msg = (result.data && result.data.message) || ('Request failed (HTTP ' + (result.ok ? 'OK' : 'error') + ')');
                showToast(msg, 'error');
                if (modalSaveBtn) { modalSaveBtn.disabled = false; modalSaveBtn.textContent = 'Save'; }
              }
            })
            .catch(function (error) {
              showToast('Network error: ' + error.message, 'error');
              if (modalSaveBtn) { modalSaveBtn.disabled = false; modalSaveBtn.textContent = 'Save'; }
            });
        });
      }

      function handleRecordSuccess(data, tr) {
        if (!tr) return;
        var wasOverdue = false;
        var overdueCell = tr.querySelector('td.overdue');
        if (overdueCell) wasOverdue = overdueCell.textContent.trim() === 'Yes';

        if (!data.is_overdue) {
          if (typeof table !== 'undefined' && table) {
            table.row(tr).remove().draw(false);
          } else {
            tr.remove();
          }
          if (wasOverdue) {
            var overdueSpan = document.querySelector('span#totalOverdue');
            if (overdueSpan) {
              var n = parseInt(overdueSpan.textContent, 10);
              if (!isNaN(n) && n > 0) overdueSpan.textContent = (n - 1);
            }
          }
          return;
        }

        var useByCell = tr.querySelector('td.useByDate');
        if (useByCell && data.new_use_by_date) useByCell.textContent = data.new_use_by_date;
        var recentCell = tr.querySelector('td.mostRecentUse');
        if (recentCell) recentCell.textContent = data.most_recent_use_date || '—';
      }

      function wireRowRemovalForm(form, options) {
        form.addEventListener('submit', function (event) {
          event.preventDefault();
          var btn = form.querySelector('button');
          var originalLabel = btn ? btn.textContent : '';
          var tr = form.closest('tr');
          var wasOverdue = tr && tr.querySelector('td.overdue')
            && tr.querySelector('td.overdue').textContent.trim() === 'Yes';

          if (btn) { btn.disabled = true; btn.textContent = options.pendingLabel || 'Removing…'; }
          fetch(form.action, {
            method: 'POST',
            credentials: 'include',
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
            body: new FormData(form),
          })
            .then(function (response) {
              return response.json().then(function (data) {
                return { ok: response.ok, data: data };
              });
            })
            .then(function (result) {
              if (result.ok && result.data && result.data.ok) {
                if (typeof table !== 'undefined' && table && tr) {
                  table.row(tr).remove().draw(false);
                } else if (tr) {
                  tr.remove();
                }
                if (wasOverdue && overdueSpan) {
                  var n = parseInt(overdueSpan.textContent, 10);
                  if (!isNaN(n) && n > 0) overdueSpan.textContent = (n - 1);
                }
                showToast(result.data.message || options.successFallback, 'success');
              } else {
                var msg = (result.data && result.data.message) || ('Request failed (HTTP ' + (result.ok ? 'OK' : 'error') + ')');
                showToast(msg, 'error');
                if (btn) { btn.disabled = false; btn.textContent = originalLabel; }
              }
            })
            .catch(function (error) {
              showToast('Network error: ' + error.message, 'error');
              if (btn) { btn.disabled = false; btn.textContent = originalLabel; }
            });
        });
      }

      document.querySelectorAll('table#useBy td.get-rid-of form.get-rid-of-form').forEach(function (form) {
        wireRowRemovalForm(form, { pendingLabel: 'Removing…', successFallback: 'Marked to get rid of.' });
      });

      document.querySelectorAll('table#useBy td.get-rid-of form.untrack-form').forEach(function (form) {
        wireRowRemovalForm(form, { pendingLabel: 'Removing…', successFallback: 'Removed from tracked collection.' });
      });
    })();

    (function () {
      var table = document.querySelector('#useBy');
      var toggle = document.querySelector('#view_toggle');
      if (!table || !toggle) return;
      var segments = toggle.querySelectorAll('.view-toggle-btn');

      var stored = null;
      try { stored = localStorage.getItem('usebyView'); } catch (e) {}
      var initial = stored || (window.innerWidth <= 750 ? 'cards' : 'table');
      applyView(initial, false);

      segments.forEach(function (segment) {
        segment.addEventListener('click', function () {
          var next = segment.dataset.view;
          if (next === currentView()) return;
          try { localStorage.setItem('usebyView', next); } catch (e) {}
          applyView(next, true);
        });
      });

      function currentView() {
        return table.classList.contains('cards-view') ? 'cards' : 'table';
      }

      function applyView(view, animate) {
        if (animate) {
          table.classList.add('view-switching');
          window.setTimeout(function () {
            table.classList.remove('view-switching');
          }, 220);
        }
        if (view === 'cards') {
          table.classList.add('cards-view');
        } else {
          table.classList.remove('cards-view');
        }
        segments.forEach(function (segment) {
          var active = segment.dataset.view === view;
          segment.classList.toggle('is-active', active);
          segment.setAttribute('aria-pressed', active ? 'true' : 'false');
        });
      }
    })();
  </script>

  <a href="https://www.flaticon.com/free-icons/copy" title="copy icons">Copy icons created by Anggara - Flaticon</a>

</main>

<?php include(SHARED_PATH . '/footer.php'); ?>
