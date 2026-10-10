<?php
/**
 * Admin Panel Shortcut Entry Point
 * Direct access to CodeIgniter Backend Admin
 */

$_SERVER['REQUEST_URI'] = str_replace('/admin.php', '/admin', $_SERVER['REQUEST_URI'] ?? '/admin');
require_once __DIR__ . '/index_ci_backup.php';
