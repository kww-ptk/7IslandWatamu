<?php
// Admin shared layout — include at top of each admin page after require_login()
// Sets: $pageTitle (required), $activeMenu (required)
$admin = current_admin();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= e($pageTitle ?? 'Admin') ?> — 7 Islands Admin</title>
  <link rel="stylesheet" href="/admin/assets/admin.css">
</head>
<body class="admin-body">

<!-- Mobile top bar -->
<div class="admin-topbar" id="adminTopbar">
  <button class="admin-topbar__burger" id="sidebarBurger" aria-label="Toggle menu" aria-expanded="false">
    <span></span><span></span><span></span>
  </button>
  <span class="admin-topbar__title">7 Islands Admin</span>
</div>

<!-- Sidebar overlay -->
<div class="sidebar-overlay" id="sidebarOverlay"></div>

<div class="admin-wrap">

  <!-- Sidebar -->
  <aside class="sidebar" id="adminSidebar">
    <div class="sidebar__logo">
      <img src="/assets/img/logo-white.png" alt="7 Islands">
    </div>
    <nav class="sidebar__nav">
      <a href="/admin/dashboard.php"    class="sidebar__link <?= ($activeMenu??'')==='dashboard'    ? 'is-active':'' ?>">
        <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/></svg>
        Dashboard
      </a>
      <a href="/admin/rooms.php"        class="sidebar__link <?= ($activeMenu??'')==='rooms'        ? 'is-active':'' ?>">
        <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 18V8h13a5 5 0 0 1 5 5v5M3 14h18M3 18v2M21 18v2"/><path d="M6 12h4a2 2 0 0 1 2 2"/></svg>
        Rooms
      </a>
      <a href="/admin/submissions.php"  class="sidebar__link <?= ($activeMenu??'')==='submissions'  ? 'is-active':'' ?>">
        <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16v16H4z"/><path d="M4 9h16M9 9v11"/></svg>
        Submissions
      </a>
      <a href="/admin/settings.php"     class="sidebar__link <?= ($activeMenu??'')==='settings'     ? 'is-active':'' ?>">
        <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
        Settings
      </a>
    </nav>
    <div class="sidebar__footer">
      <span><?= e($admin['email'] ?? '') ?></span>
      <a href="/admin/logout.php">Sign out</a>
    </div>
  </aside>

  <!-- Main content -->
  <main class="admin-main">
    <div class="admin-content">
