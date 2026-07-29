<?php if ( ! isset($page_title) ) { $page_title = 'Artifact'; } ?>

<!DOCTYPE html>

<html lang="en">
  <head>
    
    <title>
      <?php echo h($page_title); ?> - Artifact
    </title>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#30395c">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">

    <link rel="shortcut icon" type="image/jpg" href="<?php echo url_for('favicon.ico') ?>">
    <link rel="manifest" href="<?php echo url_for('manifest.json') ?>">
    <link rel="apple-touch-icon" href="<?php echo url_for('assets/icon-192x192.png') ?>">

    <link rel="stylesheet" media="all" href="../../style.css?v=31" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-P3N6C9C37N"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());

      gtag('config', 'G-P3N6C9C37N');
    </script>

  </head>

  <body class="<?php echo is_guest() ? 'guest-mode' : 'signed-in-mode'; ?>">
    <header class="site-header">
      <div class="site-header-inner">
        <div class="site-brand">
          <a class="header-link" href="/">
            <img class="site-logo" src="<?php echo url_for('/assets/logo.svg'); ?>" width="36" height="36" alt="" aria-hidden="true">
            <span class="site-wordmark">Artifact</span>
          </a>
          <p class="site-tagline">Track what you own. Use what you keep.</p>
        </div>

        <div class="site-status">
          <?php
          if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true && isset($_SESSION['username'])) {
            ?>
            <a class="site-status-link desktop-only" href="<?php echo url_for('/settings/edit'); ?>">
              <?php echo h($_SESSION['username']); ?>
            </a>
            <?php
          } elseif (is_guest()) {
            ?>
            <span class="site-status-pill desktop-only">Guest</span>
            <?php
          }
          ?>
          <button class="burger-btn" aria-label="Toggle menu" aria-expanded="false">
            <span class="burger-icon"></span>
          </button>
        </div>
      </div>
    </header>

    <nav class="site-nav hideOnPrint" aria-label="Main">
      <div class="site-nav-inner">
        <?php
        if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true) {
          ?>
          <div class="nav-group nav-group-primary" aria-label="Primary">
            <a class="nav-link nav-link-primary" href="<?php echo url_for('/artifacts/useby'); ?>">Interact&nbsp;By&nbsp;Date</a>
            <a class="nav-link nav-link-primary" href="<?php echo url_for('/uses/record-new'); ?>">Record&nbsp;Interaction</a>
          </div>

          <div class="nav-group nav-group-secondary" aria-label="Browse">
            <a class="nav-link" href="<?php echo url_for('/uses/interactions'); ?>">Interactions</a>
            <a class="nav-link" href="<?php echo url_for('/artifacts'); ?>">Artifacts</a>
            <a class="nav-link" href="<?php echo url_for('/artifacts/to-get-rid-of'); ?>">To&nbsp;Get&nbsp;Rid&nbsp;Of</a>
            <a class="nav-link" href="<?php echo url_for('/analysis'); ?>">Analysis</a>
          </div>

          <div class="nav-group nav-group-more">
            <details class="nav-more">
              <summary class="nav-link nav-more-summary" aria-haspopup="menu">More</summary>
              <div class="nav-more-panel" role="menu" aria-label="More destinations">
                <a class="nav-link" role="menuitem" href="<?php echo url_for('/types'); ?>">Types</a>
                <a class="nav-link" role="menuitem" href="<?php echo url_for('/users'); ?>">Users</a>
                <a class="nav-link" role="menuitem" href="<?php echo url_for('/support'); ?>">Support</a>
                <a class="nav-link" role="menuitem" href="<?php echo url_for('/settings/edit'); ?>">Settings</a>
                <a class="nav-link" role="menuitem" href="<?php echo url_for('logout'); ?>">Logout</a>
              </div>
            </details>
          </div>
          <?php

        } elseif (is_guest()) {
          ?>
          <div class="nav-group nav-group-primary" aria-label="Primary">
            <a class="nav-link nav-link-primary" href="<?php echo url_for('/artifacts/useby'); ?>">Interact&nbsp;By&nbsp;Date</a>
          </div>

          <div class="nav-group nav-group-secondary" aria-label="Browse">
            <a class="nav-link" href="<?php echo url_for('/uses/interactions'); ?>">Interactions</a>
            <a class="nav-link" href="<?php echo url_for('/artifacts'); ?>">Artifacts</a>
            <a class="nav-link" href="<?php echo url_for('/artifacts/to-get-rid-of'); ?>">To&nbsp;Get&nbsp;Rid&nbsp;Of</a>
            <a class="nav-link" href="<?php echo url_for('/analysis'); ?>">Analysis</a>
          </div>

          <div class="nav-group nav-group-more">
            <details class="nav-more">
              <summary class="nav-link nav-more-summary" aria-haspopup="menu">More</summary>
              <div class="nav-more-panel" role="menu" aria-label="More destinations">
                <a class="nav-link" role="menuitem" href="<?php echo url_for('/types'); ?>">Types</a>
                <a class="nav-link" role="menuitem" href="<?php echo url_for('/login.php?action=logout'); ?>">Exit&nbsp;Guest&nbsp;Mode</a>
              </div>
            </details>
          </div>
          <?php
        }
      ?>
      </div>
    </nav>

    <?php if (is_guest()) { ?>
      <div class="guest-banner">
        You are browsing as a guest.
        <a href="<?php echo url_for('/register.php'); ?>">Create an account</a> to track your own artifacts,
        or <a href="<?php echo url_for('/login.php?action=logout'); ?>">exit guest mode</a>.
      </div>
    <?php } ?>

    <script>
      (function() {
        var btn = document.querySelector('.burger-btn');
        var nav = document.querySelector('.site-nav');
        var header = document.querySelector('.site-header');
        var more = document.querySelector('.nav-more');
        var moreSummary = more ? more.querySelector('.nav-more-summary') : null;

        function updateHeaderHeight() {
          if (header) {
            document.documentElement.style.setProperty('--header-height', header.offsetHeight + 'px');
          }
        }

        function closeMore(returnFocus) {
          if (!more || !more.open) return;
          more.removeAttribute('open');
          if (returnFocus && moreSummary) {
            moreSummary.focus();
          }
        }

        function closeMobileNav(returnFocus) {
          if (!nav || !nav.classList.contains('nav-open')) return;
          nav.classList.remove('nav-open');
          if (btn) {
            btn.setAttribute('aria-expanded', 'false');
            if (returnFocus) btn.focus();
          }
        }

        updateHeaderHeight();
        window.addEventListener('resize', updateHeaderHeight);

        if (btn && nav) {
          btn.addEventListener('click', function() {
            updateHeaderHeight();
            var open = nav.classList.toggle('nav-open');
            btn.setAttribute('aria-expanded', open ? 'true' : 'false');
            if (!open) {
              closeMore(false);
            }
          });
        }

        // Close the More panel when clicking outside (desktop).
        document.addEventListener('click', function(e) {
          if (!more || !more.open) return;
          if (!more.contains(e.target)) {
            closeMore(false);
          }
        });

        document.addEventListener('keydown', function(e) {
          if (e.key === 'Escape') {
            if (more && more.open) {
              closeMore(true);
              e.preventDefault();
              return;
            }
            if (nav && nav.classList.contains('nav-open')) {
              closeMobileNav(true);
              e.preventDefault();
            }
          }
        });

        // When More opens, move focus to the first menu link for keyboard users.
        if (more) {
          more.addEventListener('toggle', function() {
            if (!more.open) return;
            var first = more.querySelector('.nav-more-panel a');
            if (first) first.focus();
          });
        }
      })();
    </script>

    <?php echo display_session_message(); ?>
