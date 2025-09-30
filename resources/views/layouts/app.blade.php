<!DOCTYPE html>
<html lang="pt" data-bs-theme="light">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Sistema de Gestão ZALALA BEACH BAR - Restaurante e Bar">
    <meta name="author" content="ZALALA BEACH BAR">

    <title>ZALALA BEACH BAR - @yield('title', 'Sistema de Gestão')</title>

    <!-- Por: -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- FontAwesome - usar CDN -->
    <link rel="stylesheet" href="{{ asset('assets/vendors/font-awesome/css/font-awesome.min.css') }}">
    <!-- Por: -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- MDI - manter local (já está correto): -->
    <link rel="stylesheet" href="{{ asset('assets/vendors/mdi/css/materialdesignicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/font-awesome/css/font-awesome.min.css') }}">

    <!-- CSS Plugins -->
    <link rel="stylesheet" href="{{ asset('assets/vendors/select2/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/jquery-toast-plugin/jquery.toast.min.css') }}">

    {{-- custom --}}
    <link rel="stylesheet" href="{{ asset('assets/css/pos.css') }}">
    <style>
        :root {
            /* Cores do tema praia/beach */
            --primary-color: #0891b2;
            --primary-dark: #0e7490;
            --secondary-color: #f59e0b;
            --secondary-dark: #d97706;
            --accent-color: #06b6d4;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
            --info-color: #3b82f6;
            --dark-color: #1f2937;
            --light-color: #f8fafc;

            /* Gradientes */
            --primary-gradient: linear-gradient(135deg, #0891b2 0%, #06b6d4 100%);
            --secondary-gradient: linear-gradient(135deg, #f59e0b 0%, #fbbf24 100%);
            --beach-gradient: linear-gradient(135deg, #0891b2 0%, #06b6d4 50%, #fbbf24 100%);
            --sunset-gradient: linear-gradient(135deg, #f59e0b 0%, #fb923c 50%, #f97316 100%);

            /* Sombras */
            --shadow-soft: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --shadow-md: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            --shadow-lg: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            --shadow-xl: 0 25px 50px -12px rgba(0, 0, 0, 0.25);

            /* Outros */
            --border-radius: 12px;
            --border-radius-lg: 16px;
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            --sidebar-width: 320px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #c5f1fc 0%, #06b6d4 50%, #fbbf24 100%);
            min-height: 100vh;
            overflow-x: hidden;
            position: relative;
        }

        /* Background Pattern */
        /*body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image:
                radial-gradient(circle at 20% 50%, rgba(8, 145, 178, 0.03) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(245, 158, 11, 0.03) 0%, transparent 50%),
                radial-gradient(circle at 40% 80%, rgba(6, 182, 212, 0.03) 0%, transparent 50%);
            z-index: -1;
        }*/
        .body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('{{ asset('assets/images/restaurant-bg.jpg') }}');
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
            opacity: 0.25;
            z-index: -1;
            filter: brightness(0.9);
            pointer-events: none;
        }

        /* ===== SIDEBAR STYLES ===== */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: var(--sidebar-width);
            height: 100vh;
            background: var(--beach-gradient);
            box-shadow: var(--shadow-xl);
            z-index: 1040;
            transition: transform 0.3s ease;
            overflow-y: auto;
            overflow-x: hidden;
            display: flex;
            flex-direction: column;
        }

        .sidebar::-webkit-scrollbar {
            width: 4px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.3);
            border-radius: 2px;
        }

        .sidebar.collapsed {
            transform: translateX(-100%);
        }

        /* Sidebar Brand */
        .sidebar-brand {
            background: rgba(0, 0, 0, 0.15);
            padding: 1rem;
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.15);
            position: sticky;
            flex-shrink: 1;
            height: 150px;
        }

        .sidebar-brand::before {
            content: '';
            position: sticky;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, #0891b2 0%, #06b6d4 50%, #ffd900 100%);
            opacity: 0.3;
            border-bottom-color: solid 1px orange;
        }

        .brand-logo {
            position: relative;
            display: inline-block;
            margin-bottom: 1rem;
        }

        .brand-logo .logo-icon {
            width: 60px;
            height: 60px;
            background: rgba(255, 255, 255, 0.15);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            border: 3px solid rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
        }

        .brand-title {
            font-family: 'Montserrat', sans-serif;
            font-size: 1.4rem;
            font-weight: 700;
            color: white;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
            margin: 0;
            line-height: 1.2;
        }

        .brand-subtitle {
            color: rgba(255, 255, 255, 0.9);
            font-size: 0.8rem;
            font-weight: 500;
            margin-top: 0.5rem;
        }

        .business-info {
            background: rgba(0, 0, 0, 0.1);
            padding: 1rem;
            margin-top: 1rem;
            border-radius: var(--border-radius);
            font-size: 0.7rem;
            color: rgba(255, 255, 255, 0.8);
            text-align: left;
        }

        .business-info .info-item {
            display: flex;
            align-items: center;
            margin-bottom: 0.5rem;
        }

        .business-info .info-item:last-child {
            margin-bottom: 0;
        }

        .business-info i {
            width: 16px;
            margin-right: 0.5rem;
            color: var(--secondary-color);
            font-size: 0.8rem;
        }

        /* Navigation */
        .sidebar-nav {
            padding: 1.5rem 1rem;
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .nav-section {
            margin-bottom: 1.5rem;
        }

        .nav-section:last-child {
            margin-bottom: 0;
        }

        .nav-section-title {
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.7rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 0.75rem;
            padding: 0 0.5rem;
            display: flex;
            align-items: center;
        }

        .nav-section-title i {
            margin-right: 0.5rem;
            font-size: 0.8rem;
        }

        .nav-pills .nav-link {
            color: rgba(255, 255, 255, 0.85);
            border-radius: var(--border-radius);
            margin: 0.2rem 0;
            padding: 0.75rem 0.875rem;
            transition: var(--transition);
            font-weight: 500;
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
            font-size: 0.9rem;
        }

        .nav-pills .nav-link::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.1);
            transition: left 0.3s ease;
            z-index: 0;
        }

        .nav-pills .nav-link:hover::before {
            left: 0;
        }

        .nav-pills .nav-link:hover {
            color: white;
            background: rgba(255, 255, 255, 0.1);
            transform: translateX(3px);
        }

        .nav-pills .nav-link.active {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            box-shadow: var(--shadow-md);
            border-left: 3px solid var(--secondary-color);
        }

        .nav-pills .nav-link i {
            width: 20px;
            text-align: center;
            margin-right: 0.75rem;
            position: relative;
            z-index: 1;
            font-size: 1rem;
        }

        .nav-pills .nav-link span {
            position: relative;
            z-index: 1;
            flex-grow: 1;
        }

        .nav-pills .nav-link .badge {
            position: relative;
            z-index: 1;
            font-size: 0.65rem;
            padding: 0.2rem 0.4rem;
            min-width: 20px;
            text-align: center;
        }

        /* User Profile Section */
        .user-profile-section {
            margin-top: auto;
            padding-top: 1rem;
            border-top: 1px solid rgba(255, 255, 255, 0.15);
        }

        .user-profile-card {
            background: rgba(0, 0, 0, 0.15);
            border-radius: var(--border-radius);
            padding: 1rem;
            text-align: center;
        }

        .user-avatar {
            margin-bottom: 0.75rem;
        }

        .avatar-circle {
            width: 50px;
            height: 50px;
            background: rgba(255, 255, 255, 0.15);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto;
            border: 2px solid rgba(255, 255, 255, 0.2);
            position: relative;
        }

        .avatar-circle i {
            font-size: 1.8rem;
            color: rgba(255, 255, 255, 0.9);
        }

        .status-dot {
            position: absolute;
            bottom: 2px;
            right: 2px;
            width: 10px;
            height: 10px;
            background: var(--success-color);
            border: 2px solid rgba(0, 0, 0, 0.3);
            border-radius: 50%;
            animation: pulse 2s ease-in-out infinite;
        }

        .user-info {
            margin-bottom: 0.75rem;
        }

        .user-name {
            font-weight: 600;
            font-size: 0.9rem;
            color: white;
            margin-bottom: 0.25rem;
        }

        .user-role {
            font-size: 0.75rem;
            color: rgba(255, 255, 255, 0.7);
        }

        .user-actions {
            width: 100%;
        }

        .btn-logout {
            width: 100%;
            background: rgba(239, 68, 68, 0.2);
            color: rgba(255, 255, 255, 0.9);
            border: 1px solid rgba(239, 68, 68, 0.3);
            border-radius: var(--border-radius);
            padding: 0.5rem 1rem;
            font-size: 0.8rem;
            transition: var(--transition);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .btn-logout:hover {
            background: rgba(239, 68, 68, 0.3);
            color: white;
            transform: translateY(-1px);
        }

        .user-info .user-name {
            font-weight: 600;
            font-size: 0.9rem;
        }

        .user-info .user-role {
            font-size: 0.75rem;
            color: rgba(255, 255, 255, 0.7);
        }

        /* ===== MAIN CONTENT ===== */
        .main-content {
            margin-left: var(--sidebar-width);
            transition: margin-left 0.3s ease;
            min-height: 100vh;
        }

        .main-content.expanded {
            margin-left: 0;
        }

        /* ===== TOP NAVBAR ===== */
        .top-navbar {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            backdrop-filter: blur(20px);
            box-shadow: var(--shadow-lg);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            padding: 0.75rem 0;
            position: sticky;
            top: 0;
            z-index: 1030;
        }

        .navbar-content {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
        }

        /* Left Section */
        .navbar-left {
            display: flex;
            align-items: center;
            gap: 1rem;
            flex: 1;
        }

        .sidebar-toggle {
            border: 1px solid rgba(255, 255, 255, 0.2);
            background: rgba(255, 255, 255, 0.1);
            color: white;
            padding: 0.5rem;
            border-radius: var(--border-radius);
            transition: var(--transition);
        }

        .sidebar-toggle:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: translateY(-1px);
        }

        .page-title {
            display: flex;
            align-items: center;
            color: white;
        }

        .page-title i {
            font-size: 1.5rem;
        }

        .page-title h1 {
            font-size: 1.25rem;
            font-weight: 600;
            margin: 0;
            color: white;
        }

        .page-title small {
            font-size: 0.875rem;
            opacity: 0.9;
        }

        /* Center Section */
        .navbar-center {
            flex: 1;
            max-width: 400px;
        }

        .search-container {
            position: relative;
            width: 100%;
        }

        .search-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            z-index: 2;
        }

        .search-input {
            padding-left: 2.5rem;
            border: 1px solid rgba(255, 255, 255, 0.2);
            background: rgba(255, 255, 255, 0.9);
            border-radius: 50px;
            transition: var(--transition);
        }

        .search-input:focus {
            background: white;
            box-shadow: 0 0 0 3px rgba(255, 255, 255, 0.3);
        }

        .search-results {
            width: 100%;
            max-height: 300px;
            overflow-y: auto;
            border: 1px solid rgba(0, 0, 0, 0.1);
            box-shadow: var(--shadow-lg);
        }

        .search-placeholder {
            padding: 1rem;
            text-align: center;
            color: var(--text-muted);
        }

        .search-placeholder i {
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
            display: block;
        }

        /* Right Section */
        .navbar-right {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            flex: 1;
            justify-content: flex-end;
        }

        /* Quick Actions */
        .quick-actions-btn {
            background: rgba(255, 255, 255, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: white;
            border-radius: 50px;
            padding: 0.5rem 1rem;
            transition: var(--transition);
        }

        .quick-actions-btn:hover {
            background: rgba(255, 255, 255, 0.25);
            transform: translateY(-1px);
        }

        /* Notifications */
        .notifications-btn {
            position: relative;
            border: 1px solid rgba(255, 255, 255, 0.2);
            background: linear-gradient(135deg, #f59e0b 0%, #fb923c 50%, #f97316 100%);
            color: white;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: var(--transition);
        }

        .notifications-btn:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        .notifications-badge {
            position: absolute;
            top: -5px;
            right: -10px;
            font-size: 0.7rem;
            padding: 0.25rem 0.4rem;
            min-width: 18px;
            text-align: center;
        }

        .notifications-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem 1rem;
        }

        .btn-mark-read {
            background: none;
            border: none;
            color: var(--primary-color);
            font-size: 0.8rem;
            cursor: pointer;
        }

        .notifications-loading {
            padding: 2rem;
            text-align: center;
        }

        .notifications-footer {
            padding: 0.75rem;
        }

        /* User Dropdown */
        .user-btn {
            border: 1px solid rgba(255, 255, 255, 0.2);
            background: rgba(255, 255, 255, 0.1);
            color: white;
            border-radius: 50px;
            padding: 0.1rem 0.75rem;
            transition: var(--transition);
        }

        .user-btn:hover {
            background: rgba(255, 145, 0, 0.2);
        }

        .user-avatar {
            width: 32px;
            height: 32px;
            background: var(--secondary-color);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 0.9rem;
        }

        .user-name {
            font-weight: 500;
        }

        .user-menu {
            min-width: 250px;
        }

        .user-header {
            padding: 1rem;
            text-align: center;
        }

        .user-avatar-large {
            width: 48px;
            height: 48px;
            background: var(--primary-gradient);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 1.2rem;
            margin: 0 auto 0.75rem;
        }

        .user-info {
            line-height: 1.4;
        }

        /* Dropdown Improvements */
        .dropdown-menu {
            border: 1px solid rgba(0, 0, 0, 0.1);
            box-shadow: var(--shadow-xl);
            backdrop-filter: blur(20px);
            background: rgba(255, 255, 255, 0.95);
        }

        .dropdown-item {
            padding: 0.75rem 1rem;
            transition: var(--transition);
            display: flex;
            align-items: center;
        }

        .dropdown-item:hover {
            background: rgba(var(--primary-color-rgb), 0.1);
        }

        .dropdown-header {
            padding: 0.75rem 1rem;
            font-weight: 600;
            color: var(--dark-color);
        }

        /*pagina de erros*/
        .error-page {
            min-height: 70vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .error-icon {
            opacity: 0.9;
        }

        .error-card {
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            border: 1px solid #e2e8f0;
        }

        @media (max-width: 768px) {
            .error-card .card-body {
                padding: 2rem 1.5rem;
            }

            .display-4 {
                font-size: 3rem;
            }

            .display-1 {
                font-size: 4rem;
            }
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .navbar-content {
                gap: 0.5rem;
            }

            .navbar-center {
                display: none !important;
            }

            .quick-actions-dropdown {
                display: none !important;
            }

            .page-title h1 {
                font-size: 1.1rem;
            }

            .user-name {
                display: none !important;
            }

            .notifications-menu {
                min-width: 450px !important;
                max-width: 90vw;
                overflow: auto;
            }

            .navbar-right {
                gap: 0.5rem;
            }
        }

        @media (max-width: 576px) {
            .top-navbar {
                padding: 0.5rem 0;
            }

            .navbar-left {
                gap: 0.5rem;
            }

            .page-title i {
                font-size: 1.25rem;
            }

            .user-btn {
                padding: 0.100rem;
            }

            .notifications-btn {
                width: 36px;
                height: 36px;
            }
        }

        /* Animation for notifications */
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .dropdown-menu.show {
            animation: slideIn 0.2s ease-out;
        }

        /* ===== CARDS ===== */
        .card {
            border: none;
            border-radius: var(--border-radius-lg);
            box-shadow: var(--shadow-soft);
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            transition: var(--transition);
            overflow: hidden;
            position: relative;
        }

        .card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--beach-gradient);
            transform: scaleX(0);
            transition: transform 0.4s ease;
        }

        .card:hover::before {
            transform: scaleX(1);
        }

        .card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-lg);
        }

        /* ===== BUTTONS ===== */
        .btn {
            border-radius: var(--border-radius);
            font-weight: 500;
            transition: var(--transition);
            padding: 0.75rem 1.5rem;
        }

        .btn-primary {
            background: var(--primary-gradient);
            border: none;
            color: white;
        }

        .btn-primary:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        .btn-secondary {
            background: var(--secondary-gradient);
            border: none;
            color: white;
        }

        .btn-secondary:hover {
            background: var(--secondary-dark);
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        /* ===== STATS CARDS ===== */
        .stats-card {
            background: white;
            border-radius: var(--border-radius-lg);
            padding: 2rem;
            box-shadow: var(--shadow-soft);
            transition: var(--transition);
            position: relative;
            overflow: hidden;
        }

        .stats-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--beach-gradient);
        }

        .stats-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-lg);
        }

        .stats-card .stats-icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }

        .stats-card.primary .stats-icon {
            background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
            color: white;
        }

        .stats-card.secondary .stats-icon {
            background: var(--secondary-gradient);
            color: white;
        }

        .stats-card.success .stats-icon {
            background: linear-gradient(135deg, var(--success-color), #34d399);
            color: white;
        }

        /* ===== ANIMATIONS ===== */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes pulse {

            0%,
            100% {
                transform: scale(1);
                opacity: 1;
            }

            50% {
                transform: scale(1.1);
                opacity: 0.8;
            }
        }

        .fade-in {
            animation: fadeIn 0.6s ease-out;
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 991.98px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            .sidebar-overlay {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0, 0, 0, 0.5);
                z-index: 1040 !important;
                opacity: 0;
                visibility: hidden;
                transition: all 0.3s ease;
            }

            .sidebar-overlay.show {
                opacity: 1;
                visibility: visible;
            }
        }

        /* ===== BREADCRUMB ===== */
        .breadcrumb {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(10px);
            border-radius: var(--border-radius-lg);
            padding: 1rem 1.5rem;
            margin-bottom: 2rem;
            box-shadow: var(--shadow-soft);
        }

        /* ===== ALERTS ===== */
        .alert {
            border: none;
            border-radius: var(--border-radius-lg);
            padding: 1rem 1.5rem;
            box-shadow: var(--shadow-soft);
            backdrop-filter: blur(10px);
        }

        /* ===== BADGES ===== */
        .badge {
            font-weight: 500;
            padding: 0.5rem 0.75rem;
            border-radius: var(--border-radius);
            font-size: 0.75rem;
        }

        .badge.bg-success {
            background: var(--success-color) !important;
        }

        .badge.bg-warning {
            background: var(--warning-color) !important;
        }

        .badge.bg-danger {
            background: var(--danger-color) !important;
        }

        .badge.bg-info {
            background: var(--info-color) !important;
        }

        /* ===== CUSTOM SCROLLBAR ===== */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb {
            background: var(--primary-color);
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--primary-dark);
        }
    </style>

    @stack('styles')
</head>

<body>
    {{-- @include('partials.footer') --}}
    <!-- Sidebar Overlay (Mobile) -->
    <div class="sidebar-overlay" id="sidebar-overlay"></div>

    <!-- Sidebar -->
    <nav class="sidebar" id="sidebar">
        <!-- Brand Section -->
        <div class="sidebar-brand">
            <div class="brand-logo text-center">
                <div class="logo-icon mb-2">
                    <img src="{{ asset('assets/images/logo-zalala.png') }}" alt="ZALALA Logo"
                        style="width: 60px; height: 60px; border-radius: 50%; object-fit: cover;">
                </div>
                <h1 class="brand-title">ZALALA BEACH BAR</h1>
                <div class="brand-subtitle">Restaurante • Bar • Gestão</div>
            </div>
        </div>

        <!-- Navigation Sections -->
        <div class="sidebar-nav">
            <!-- Dashboard -->
            <div class="nav-section">
                <div class="nav-section-title">
                    <i class="mdi mdi-menu"></i>
                    <span>Navegação</span>
                </div>
                <ul class="nav nav-pills flex-column">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                            href="{{ route('dashboard') }}">
                            <i class="mdi mdi-view-dashboard text-warning"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                </ul>
            </div>

            <!-- OPERACIONAL -->
            @canany(['view_products', 'create_sales', 'view_orders', 'manage_tables', 'manage_kitchen'])
                <div class="nav-section">
                    <div class="nav-section-title">
                        <i class="mdi mdi-store"></i>
                        <span>Operacional</span>
                    </div>
                    <ul class="nav nav-pills flex-column">
                        @canall(['view_products', 'create_sales'])
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('pos.*') ? 'active' : '' }}"
                                href="{{ route('pos.index') }}">
                                <i class="mdi mdi-point-of-sale"></i>
                                <span>PDV (Ponto de Venda)</span>
                                <span class="badge bg-success ms-auto">Live</span>
                            </a>
                        </li>
                        @endcanall

                        @can('view_orders')
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('orders.*') ? 'active' : '' }}"
                                    href="{{ route('orders.index') }}">
                                    <i class="mdi mdi-receipt"></i>
                                    <span>Pedidos</span>
                                    @php
                                        $pendingOrdersCount = \App\Models\Order::whereIn('status', [
                                            'active',
                                            'pending',
                                        ])->count();
                                    @endphp
                                    @if ($pendingOrdersCount > 0)
                                        <span class="badge ms-auto" style="background: var(--warning-color);">
                                            {{ $pendingOrdersCount }}
                                        </span>
                                    @endif
                                </a>
                            </li>
                        @endcan

                        @can('manage_kitchen')
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('kitchen.*') ? 'active' : '' }}"
                                    href="{{ route('kitchen.dashboard') }}">
                                    <i class="mdi mdi-chef-hat"></i>
                                    <span>Cozinha</span>
                                    @php
                                        $kitchenOrders = \App\Models\Order::where('status', 'active')->count();
                                        $pendingItems = \App\Models\OrderItem::whereHas('order', function ($q) {
                                            $q->where('status', 'active');
                                        })
                                            ->where('status', 'pending')
                                            ->count();
                                    @endphp
                                    @if ($kitchenOrders > 0)
                                        <span class="badge ms-auto"
                                            style="background: {{ $pendingItems > 0 ? 'var(--warning-color)' : 'var(--success-color)' }};"
                                            title="Pedidos ativos: {{ $kitchenOrders }} | Itens pendentes: {{ $pendingItems }}">
                                            {{ $kitchenOrders }}
                                        </span>
                                    @endif
                                </a>
                            </li>
                        @endcan

                        @canany(['view_orders', 'manage_tables'])
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('tables.*') ? 'active' : '' }}"
                                    href="{{ route('tables.index') }}">
                                    <i class="mdi mdi-table-chair"></i>
                                    <span>Mesas</span>
                                    @php
                                        $occupiedTables = \App\Models\Table::whereHas('orders', function ($query) {
                                            $query->whereIn('status', ['active', 'completed']);
                                        })->count();
                                        $totalTables = \App\Models\Table::count();
                                        $availableTables = $totalTables - $occupiedTables;
                                    @endphp
                                    <span class="badge ms-auto"
                                        title="Livre: {{ $availableTables }} | Ocupada: {{ $occupiedTables }}"
                                        style="background: {{ $availableTables > 0 ? 'var(--success-color)' : 'var(--danger-color)' }};">
                                        {{ $availableTables }}
                                    </span>
                                </a>
                            </li>
                        @endcanany
                    </ul>
                </div>
            @endcanany

            <!-- MENU & PRODUTOS -->
            @canany(['view_products', 'manage_categories', 'view_stock_movements'])
                <div class="nav-section">
                    <div class="nav-section-title">
                        <i class="mdi mdi-food-variant"></i>
                        <span>Menu & Produtos</span>
                    </div>
                    <ul class="nav nav-pills flex-column">
                        @can('view_products')
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}"
                                    href="{{ route('products.index') }}">
                                    <i class="mdi mdi-food"></i>
                                    <span>Produtos</span>
                                    @php
                                        $lowStockCount = \App\Models\Product::whereColumn(
                                            'stock_quantity',
                                            '<=',
                                            'min_stock_level',
                                        )->count();
                                    @endphp
                                    @if ($lowStockCount > 0)
                                        <span class="badge bg-warning ms-auto" title="Produtos com estoque baixo">
                                            {{ $lowStockCount }}
                                        </span>
                                    @endif
                                </a>
                            </li>
                        @endcan

                        @can('manage_categories')
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('categories.*') ? 'active' : '' }}"
                                    href="{{ route('categories.index') }}">
                                    <i class="mdi mdi-format-list-bulleted"></i>
                                    <span>Categorias</span>
                                </a>
                            </li>
                        @endcan

                        @can('view_products')
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('menu.*') ? 'active' : '' }}"
                                    href="{{ route('menu.index') }}">
                                    <i class="mdi mdi-book-open-variant"></i>
                                    <span>Cardápio Digital</span>
                                </a>
                            </li>
                        @endcan

                        @can('view_stock_movements')
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('stock-movements.*') ? 'active' : '' }}"
                                    href="{{ route('stock-movements.index') }}">
                                    <i class="mdi mdi-swap-vertical"></i>
                                    <span>Movimentos de Stock</span>
                                </a>
                            </li>
                        @endcan
                    </ul>
                </div>
            @endcanany

            <!-- VENDAS & FINANCEIRO -->
            @canany(['view_sales', 'view_reports', 'view_expenses', 'financial_reports'])
                <div class="nav-section">
                    <div class="nav-section-title">
                        <i class="mdi mdi-cash-multiple"></i>
                        <span>Vendas & Financeiro</span>
                    </div>
                    <ul class="nav nav-pills flex-column">
                        @can('view_sales')
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('sales.*') ? 'active' : '' }}"
                                    href="{{ route('sales.index') }}">
                                    <i class="mdi mdi-currency-usd"></i>
                                    <span>Vendas</span>
                                    @php
                                        $todaySalesCount = \App\Models\Sale::whereDate('created_at', today())->count();
                                    @endphp
                                    @if ($todaySalesCount > 0)
                                        <span class="badge bg-success ms-auto" title="Vendas hoje">
                                            {{ $todaySalesCount }}
                                        </span>
                                    @endif
                                </a>
                            </li>
                        @endcan

                        @can('view_expenses')
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('expenses.*') ? 'active' : '' }}"
                                    href="{{ route('expenses.index') }}">
                                    <i class="mdi mdi-cash-remove"></i>
                                    <span>Despesas</span>
                                </a>
                            </li>
                        @endcan

                        @can('view_reports')
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}"
                                    href="{{ route('reports.index') }}">
                                    <i class="mdi mdi-chart-line"></i>
                                    <span>Relatórios</span>
                                    @can('financial_reports')
                                        <span class="badge bg-info ms-auto" title="Relatórios Financeiros">Pro</span>
                                    @endcan
                                </a>
                            </li>
                        @endcan

                        @can('financial_reports')
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('reports.cash-flow') ? 'active' : '' }}"
                                    href="{{ route('reports.cash-flow') }}">
                                    <i class="mdi mdi-bank"></i>
                                    <span>Fluxo de Caixa</span>
                                </a>
                            </li>
                        @endcan
                    </ul>
                </div>
            @endcanany

            <!-- RELACIONAMENTO -->
            @can('view_clients')
                <div class="nav-section">
                    <div class="nav-section-title">
                        <i class="mdi mdi-account-group"></i>
                        <span>Relacionamento</span>
                    </div>
                    <ul class="nav nav-pills flex-column">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('clients.*') ? 'active' : '' }}"
                                href="{{ route('client.index') }}">
                                <i class="mdi mdi-account-heart"></i>
                                <span>Clientes</span>
                                @php
                                    $totalClients = \App\Models\Client::count();
                                @endphp
                                @if ($totalClients > 0)
                                    <span class="badge bg-primary ms-auto">
                                        {{ $totalClients }}
                                    </span>
                                @endif
                            </a>
                        </li>
                    </ul>
                </div>
            @endcan

            <!-- GESTÃO DE PESSOAS -->
            @canany(['view_employees', 'view_users'])
                <div class="nav-section">
                    <div class="nav-section-title">
                        <i class="mdi mdi-shield-account"></i>
                        <span>Gestão de Pessoas</span>
                    </div>
                    <ul class="nav nav-pills flex-column">
                        @can('view_employees')
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('employees.*') ? 'active' : '' }}"
                                    href="{{ route('employees.index') }}">
                                    <i class="mdi mdi-account-tie"></i>
                                    <span>Funcionários</span>
                                    @php
                                        $totalEmployees = \App\Models\Employee::count();
                                    @endphp
                                    <span class="badge bg-secondary ms-auto">
                                        {{ $totalEmployees }}
                                    </span>
                                </a>
                            </li>
                        @endcan

                        @role('admin')
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}"
                                    href="{{ route('users.index') }}">
                                    <i class="mdi mdi-account-key"></i>
                                    <span>Usuários do Sistema</span>
                                    @php
                                        $totalUsers = \App\Models\User::count();
                                    @endphp
                                    <span class="badge bg-danger ms-auto">
                                        {{ $totalUsers }}
                                    </span>
                                </a>
                            </li>
                        @endrole
                    </ul>
                </div>
            @endcanany

            <!-- SISTEMA -->
            @role('admin')
                <div class="nav-section">
                    <div class="nav-section-title">
                        <i class="mdi mdi-cog-outline"></i>
                        <span>Sistema</span>
                    </div>
                    <ul class="nav nav-pills flex-column">
                        <li class="nav-item">
                            <a class="nav-link" href="#"
                                onclick="showToast('Funcionalidade em desenvolvimento', 'info')">
                                <i class="mdi mdi-cog"></i>
                                <span>Configurações</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#"
                                onclick="showToast('Funcionalidade em desenvolvimento', 'success')">
                                <i class="mdi mdi-backup-restore"></i>
                                <span>Backup & Restore</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#"
                                onclick="showToast('Funcionalidade em desenvolvimento', 'info')">
                                <i class="mdi mdi-file-document-outline"></i>
                                <span>Logs de Auditoria</span>
                            </a>
                        </li>
                    </ul>
                </div>
            @endrole

            <!-- User Profile Section -->
            <div class="nav-section user-profile-section">
                <div class="user-profile-card">
                    @php
                        $roleInfo = [
                            'admin' => [
                                'icon' => 'mdi-shield-crown',
                                'color' => 'var(--danger-color)',
                                'name' => 'Administrador',
                            ],
                            'manager' => [
                                'icon' => 'mdi-account-star',
                                'color' => 'var(--warning-color)',
                                'name' => 'Gerente',
                            ],
                            'cashier' => [
                                'icon' => 'mdi-cash-register',
                                'color' => 'var(--success-color)',
                                'name' => 'Caixa',
                            ],
                            'waiter' => [
                                'icon' => 'mdi-account-tie',
                                'color' => 'var(--info-color)',
                                'name' => 'Garçom',
                            ],
                            'cooker' => [
                                'icon' => 'mdi-chef-hat',
                                'color' => 'var(--secondary-color)',
                                'name' => 'Cozinheiro',
                            ],
                        ];

                        $currentRole = $roleInfo['admin'];

                        if (auth()->check()) {
                            $userRole = auth()->user()->role;
                            $currentRole = $roleInfo[$userRole] ?? $roleInfo['admin'];
                        }
                    @endphp

                    @if (auth()->check())
                        <div class="user-info">
                            <div class="user-name">{{ auth()->user()->name }}</div>
                            <div class="user-role">{{ $currentRole['name'] }}</div>
                        </div>
                    @endif


                    <div class="user-avatar">
                        <div class="avatar-circle">
                            <i class="mdi mdi-account-circle"></i>
                            <div class="status-dot"></div>
                        </div>
                    </div>

                    @if(auth()->check())
                        <div class="user-info">
                            <div class="user-name">{{ auth()->user()->name }}</div>
                            <div class="user-role">{{ $currentRole['name'] }}</div>
                        </div>
                    @endif

                    <div class="user-actions">
                        <form method="POST" action="{{ route('logout') }}" class="w-100">
                            @csrf
                            <button type="submit" class="btn-logout">
                                <i class="mdi mdi-logout-variant"></i>
                                Sair
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </nav>


    <!-- Main Content -->
    <div class="main-content" id="main-content">
        <!-- Top Navbar -->
        <nav class="top-navbar">
            <div class="container-fluid">
                <div class="navbar-content">
                    <!-- Left Section -->
                    <div class="navbar-left">
                        <!-- Mobile Sidebar Toggle -->
                        <button class="btn btn-outline-primary sidebar-toggle d-lg-none" type="button"
                            id="sidebar-toggle">
                            <i class="mdi mdi-menu"></i>
                        </button>

                        <!-- Page Title -->
                        <div class="page-title">
                            <i class="mdi @yield('title-icon', 'mdi-view-dashboard') text-light me-2"></i>
                            <div>
                                <h1 class="mb-0">@yield('page-title', 'Dashboard')</h1>
                                {{-- <small class="text-muted d-none d-md-block">@yield('page-subtitle', 'Painel de Controle')</small> --}}
                            </div>
                        </div>
                    </div>

                    <!-- Center Section - Search -->
                    <div class="navbar-center d-none d-md-flex">
                        <div class="search-container">
                            <i class="mdi mdi-magnify search-icon"></i>
                            <input type="text" class="form-control search-input"
                                placeholder="Buscar produtos, pedidos, clientes..." id="global-search">
                            <div class="search-results dropdown-menu" id="search-results">
                                <div class="search-placeholder">
                                    <i class="mdi mdi-magnify"></i>
                                    <span>Digite para buscar...</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Section -->
                    <div class="navbar-right">
                        <!-- Quick Actions -->
                        <div class="dropdown quick-actions-dropdown d-none d-md-block">
                            <button class="btn btn-primary quick-actions-btn" type="button"
                                data-bs-toggle="dropdown">
                                <i class="mdi mdi-lightning-bolt me-1"></i>
                                Ações Rápidas
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <h6 class="dropdown-header">Ações Rápidas</h6>
                                </li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('pos.index') }}">
                                        <i class="mdi mdi-point-of-sale text-success me-2"></i>
                                        Abrir PDV
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('tables.index') }}">
                                        <i class="mdi mdi-plus-circle text-primary me-2"></i>
                                        Novo Pedido
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('products.create') }}">
                                        <i class="mdi mdi-food-variant text-warning me-2"></i>
                                        Adicionar Produto
                                    </a>
                                </li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('reports.index') }}">
                                        <i class="mdi mdi-chart-line text-info me-2"></i>
                                        Relatórios
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <!-- Notifications -->
                        <div class="dropdown notifications-dropdown">
                            <button class="btn btn-warning notifications-btn" type="button"
                                data-bs-toggle="dropdown" id="notifications-dropdown">
                                <i class="mdi mdi-bell-outline"></i>
                            </button>
                            <span class="notifications-badge badge bg-danger" id="notifications-badge"
                                style="position: absolute; top: 0; right: 0; transform: translate(50%, -50%);">
                                0
                            </span>
                            <div class="dropdown-menu dropdown-menu-end notifications-menu"
                                style="width: 450px !important;">
                                <div class="notifications-header">
                                    <span>Notificações</span>
                                    <button class="btn-mark-read" id="mark-all-read">
                                        <small>Marcar todas como lidas</small>
                                    </button>
                                </div>
                                <div class="dropdown-divider"></div>
                                <div id="notifications-list">
                                    <div class="notifications-loading">
                                        <div class="spinner-border text-primary" role="status">
                                            <span class="visually-hidden">Carregando...</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="dropdown-divider"></div>
                                <div class="notifications-footer">
                                    <a href="{{ route('notifications.index') }}"
                                        class="btn btn-sm btn-primary w-100">
                                        Ver Todas as Notificações
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- User Profile -->
                        <div class="dropdown user-dropdown">
                            <button class="btn btn-warning user-btn d-flex align-items-center rounded-pill px-2 py-1"
                                type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false"
                                style="gap: 6px;">

                                <!-- Avatar -->
                                <div class="user-avatar text-white fw-bold" style="margin: 0">
                                    {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                                </div>

                                <!-- Setinha -->
                                <i class="mdi mdi-chevron-down text-light" style="font-size: 1.2rem;"></i>
                            </button>

                            <ul class="dropdown-menu dropdown-menu-end user-menu">
                                <li>
                                    <div class="user-header">
                                        <div class="user-avatar-large">
                                            {{ substr(auth()->user()->name ?? 'A', 0, 1) }}
                                        </div>
                                        <div class="user-info">
                                            <div class="fw-semibold">{{ auth()->user()->name ?? 'Administrador' }}
                                            </div>
                                            <div class="text-muted small">
                                                {{ auth()->user()->email ?? 'admin@zalalabeachbar.com' }}</div>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                        <i class="mdi mdi-account-edit text-primary me-2"></i>
                                        Editar Perfil
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="#" onclick="toggleTheme()">
                                        <i class="mdi mdi-theme-light-dark text-secondary me-2"></i>
                                        Alternar Tema
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="#">
                                        <i class="mdi mdi-cog text-secondary me-2"></i>
                                        Configurações
                                    </a>
                                </li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}" class="mb-0">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger">
                                            <i class="mdi mdi-logout me-2"></i>
                                            Sair do Sistema
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Mobile Search -->
        <div class="container-fluid d-md-none py-3">
            <div class="search-container">
                <i class="mdi mdi-magnify search-icon"></i>
                <input type="text" class="form-control search-input"
                    placeholder="Buscar produtos, pedidos, clientes..." id="global-search">
                <div class="search-results dropdown-menu" id="search-results">
                    <div class="search-placeholder">
                        <i class="mdi mdi-magnify"></i>
                        <span>Digite para buscar...</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Page Content -->
        <div class="container-fluid p-4">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb" class="fade-in">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') ?? '#' }}" class="text-decoration-none">
                            <i class="mdi mdi-home"></i> Início
                        </a>
                    </li>
                    @yield('breadcrumbs')
                </ol>
            </nav>
            <!-- Content Area -->
            @yield('content')
        </div>
        <!-- Footer Compacto Melhorado -->
        <footer class="main-footer mt-5"
            style="background: var(--beach-gradient); color: white; box-shadow: var(--shadow-lg); border-radius: var(--border-radius-lg) var(--border-radius-lg) 0 0;margin-top: 1% !important; position: relative;">
            <div class="container-fluid py-4">
                <div class="row align-items-center">
                    <!-- Logo & Info -->
                    <div class="col-md-4 d-flex align-items-center mb-3 mb-md-0">
                        <img src="{{ asset('assets/images/logo-zalala.png') }}" alt="ZALALA Logo"
                            class="footer-logo me-3"
                            style="width: 48px; height: 48px; border-radius: 50%; box-shadow: var(--shadow-md); background: rgba(255,255,255,0.15);">
                        <div>
                            <div class="fw-bold" style="font-size: 1.2rem; letter-spacing: 1px;">ZALALA BEACH BAR
                            </div>
                            <div class="footer-subtitle" style="font-size: 0.9rem; color: rgba(255,255,255,0.85);">
                                Sistema de Gestão</div>
                        </div>
                    </div>
                    <!-- Contatos Rápidos -->
                    <div class="col-md-4 d-flex justify-content-center mb-3 mb-md-0">
                        <a href="https://wa.me/258847240296" target="_blank"
                            class="btn btn-success btn-sm rounded-pill me-2 d-flex align-items-center"
                            style="gap: 6px;" data-bs-toggle="tooltip" title="Suporte WhatsApp">
                            <i class="mdi mdi-whatsapp"></i> Suporte
                        </a>
                        <span class="btn btn-outline-light btn-sm rounded-pill d-flex align-items-center"
                            style="gap: 6px;" data-bs-toggle="tooltip" title="Telefone Principal">
                            <i class="mdi mdi-phone"></i> 84 688 5214
                        </span>
                    </div>
                    <!-- Informações Legais & Stats -->
                    <div class="col-md-4 text-md-end text-center">
                        <div class="mb-1" style="font-size: 0.95rem;">
                            © {{ date('Y') }} <span class="fw-semibold">ZALALA BEACH BAR</span>
                            <span class="mx-2">•</span>
                            <span class="developer" style="color: var(--secondary);">
                                Desenvolvido por {{ env('APP_AUTHOR', 'ZALALA BEACH BAR') }}
                            </span>
                        </div>
                        <div class="footer-stats" style="font-size: 0.85rem; color: rgba(255,255,255,0.8);">
                            <i class="mdi mdi-circle text-success"
                                style="font-size: 8px; vertical-align: middle;"></i>
                            Online • v2.0
                            <span class="mx-2">|</span>
                            <small class="tech-info">
                                Laravel {{ app()->version() }} | PHP {{ phpversion() }} |
                                <span id="load-time">{{ round((microtime(true) - LARAVEL_START) * 1000, 2) }}ms</span>
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
    </div>
    <!-- JS -->
    <script src="{{ asset('assets/js/pos.js') }}"></script>
    <!-- Bootstrap JS - CDN -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <!-- DataTables -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- jsPDF -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.31/jspdf.plugin.autotable.min.js"></script>

    <!-- XLSX -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

    <!-- Bootstrap JS (já deve estar via CDN) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // ===== SIDEBAR MANAGEMENT =====
        class SidebarManager {
            constructor() {
                this.sidebar = document.getElementById('sidebar');
                this.mainContent = document.getElementById('main-content');
                this.overlay = document.getElementById('sidebar-overlay');
                this.toggle = document.getElementById('sidebar-toggle');
                this.isDesktop = window.innerWidth >= 992;
                this.isOpen = false;

                this.init();
            }

            init() {
                this.setupEventListeners();
                this.handleResize();
                window.addEventListener('resize', () => this.handleResize());
            }

            setupEventListeners() {
                this.toggle?.addEventListener('click', (e) => {
                    e.stopPropagation();
                    this.toggleSidebar();
                });

                this.overlay?.addEventListener('click', () => {
                    this.closeSidebar();
                });

                document.addEventListener('keydown', (e) => {
                    if (e.key === 'Escape' && this.isOpen && !this.isDesktop) {
                        this.closeSidebar();
                    }
                });
            }

            toggleSidebar() {
                if (this.isOpen) {
                    this.closeSidebar();
                } else {
                    this.openSidebar();
                }
            }

            openSidebar() {
                if (!this.isDesktop) {
                    this.sidebar.classList.add('show');
                    this.overlay.classList.add('show');
                    document.body.style.overflow = 'hidden';
                } else {
                    this.sidebar.classList.remove('collapsed');
                    this.mainContent.classList.remove('expanded');
                }
                this.isOpen = true;
            }

            closeSidebar() {
                if (!this.isDesktop) {
                    this.sidebar.classList.remove('show');
                    this.overlay.classList.remove('show');
                    document.body.style.overflow = '';
                } else {
                    this.sidebar.classList.add('collapsed');
                    this.mainContent.classList.add('expanded');
                }
                this.isOpen = false;
            }

            handleResize() {
                const wasDesktop = this.isDesktop;
                this.isDesktop = window.innerWidth >= 992;

                if (wasDesktop !== this.isDesktop) {
                    this.sidebar.classList.remove('show', 'collapsed');
                    this.overlay.classList.remove('show');
                    this.mainContent.classList.remove('expanded');
                    document.body.style.overflow = '';
                    this.isOpen = false;
                }
            }
        }

        // ===== THEME TOGGLE =====
        function toggleTheme() {
            const html = document.documentElement;
            const currentTheme = html.getAttribute('data-bs-theme') || 'light';
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';

            html.setAttribute('data-bs-theme', newTheme);
            localStorage.setItem('theme', newTheme);

            showToast(`Tema ${newTheme === 'dark' ? 'escuro' : 'claro'} ativado`, 'info');
        }

        // ===== TOAST NOTIFICATION =====
        function showToast(message, type = 'success') {
            const iconMap = {
                success: 'mdi-check-circle',
                error: 'mdi-alert-circle',
                warning: 'mdi-alert-triangle',
                info: 'mdi-information'
            };

            const colorMap = {
                success: 'text-bg-success',
                error: 'text-bg-danger',
                warning: 'text-bg-warning',
                info: 'text-bg-info'
            };

            const toastHtml = `
        <div class="toast ${colorMap[type]} border-0" role="alert" data-bs-delay="4000">
            <div class="d-flex">
                <div class="toast-body">
                    <i class="mdi ${iconMap[type]} me-2"></i>
                    ${message}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
    `;

            let toastContainer = document.getElementById('toast-container');
            if (!toastContainer) {
                toastContainer = document.createElement('div');
                toastContainer.id = 'toast-container';
                toastContainer.className = 'toast-container position-fixed top-0 end-0 p-3';
                toastContainer.style.zIndex = '9999';
                document.body.appendChild(toastContainer);
            }

            toastContainer.insertAdjacentHTML('beforeend', toastHtml);
            const toastElement = toastContainer.lastElementChild;
            const toast = new bootstrap.Toast(toastElement);
            toast.show();

            toastElement.addEventListener('hidden.bs.toast', () => {
                toastElement.remove();
            });
        }

        // Auto-show toasts from session
        document.addEventListener('DOMContentLoaded', function() {
            @if (session('success'))
                showToast('{{ session('success') }}', 'success');
            @endif

            @if (session('error'))
                showToast('{{ session('error') }}', 'error');
            @endif

            @if (session('warning'))
                showToast('{{ session('warning') }}', 'warning');
            @endif

            @if (session('info'))
                showToast('{{ session('info') }}', 'info');
            @endif

            @if ($errors->any())
                @foreach ($errors->all() as $error)
                    showToast('{{ $error }}', 'error');
                @endforeach
            @endif
        });
        // ===== PROFESSIONAL SEARCH MANAGER =====
        class ProfessionalSearch {
            constructor() {
                this.searchInput = document.querySelector('.search-input');
                this.searchTimeout = null;
                this.init();
            }

            init() {
                if (!this.searchInput) return;

                this.searchInput.addEventListener('keydown', (e) => {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        this.performSearch(e.target.value);
                    }
                });

                this.searchInput.addEventListener('input', (e) => {
                    if (e.target.value.length > 2) {
                        this.debounceSearch(e.target.value);
                    }
                });
            }

            debounceSearch(query) {
                clearTimeout(this.searchTimeout);
                this.searchTimeout = setTimeout(() => {
                    this.performSearch(query);
                }, 500);
            }

            performSearch(query) {
                if (query.trim().length === 0) return;

                // Para Laravel, adapte a rota conforme necessário
                const searchUrl = window.location.origin + '/search?q=' + encodeURIComponent(query);
                window.location.href = searchUrl;
            }
        }

        // ===== INITIALIZATION =====
        document.addEventListener('DOMContentLoaded', function() {
            // Load saved theme
            const savedTheme = localStorage.getItem('theme') || 'light';
            document.documentElement.setAttribute('data-bs-theme', savedTheme);

            // Initialize components
            try {
                new SidebarManager();
                new ProfessionalSearch();
            } catch (error) {
                console.error('Error initializing components:', error);
            }

            // Initialize Bootstrap tooltips
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });

            // Auto-hide alerts after 5 seconds
            setTimeout(() => {
                document.querySelectorAll('.alert.show').forEach(alert => {
                    const bsAlert = new bootstrap.Alert(alert);
                    setTimeout(() => bsAlert?.close(), 5000);
                });
            }, 100);

            // Show welcome message
            setTimeout(() => {
                console.log('Bem-vindo ao ZALALA BEACH BAR!', 'info');
            }, 1000);
        });
        // ===== NOTIFICATION MANAGER =====
        class NotificationManager {
            constructor() {
                this.badge = document.getElementById('notifications-badge');
                this.dropdown = document.getElementById('notifications-dropdown');
                this.list = document.getElementById('notifications-list');
                this.markAllBtn = document.getElementById('mark-all-read');
                this.unreadCount = 0;

                this.init();
            }

            init() {
                this.loadNotifications();
                this.setupEventListeners();
                this.startPolling();
            }

            setupEventListeners() {
                // Marcar todas como lidas - usar formulário
                this.markAllBtn?.addEventListener('click', (e) => {
                    e.preventDefault();
                    this.submitMarkAllForm();
                });

                this.dropdown?.addEventListener('show.bs.dropdown', () => {
                    this.loadNotifications();
                });
            }

            submitMarkAllForm() {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{ route('notifications.markAllAsRead') }}';

                const csrf = document.createElement('input');
                csrf.type = 'hidden';
                csrf.name = '_token';
                csrf.value = '{{ csrf_token() }}';
                form.appendChild(csrf);

                document.body.appendChild(form);
                form.submit();
            }

            async loadNotifications() {
                try {
                    const response = await fetch('{{ route('notifications.list') }}');
                    const data = await response.json();

                    this.updateNotificationsList(data.notifications);
                    this.updateBadge(data.unread_count);
                } catch (error) {
                    console.error('Erro ao carregar notificações:', error);
                }
            }

            updateNotificationsList(notifications) {
                if (!this.list) return;

                if (notifications.length === 0) {
                    this.list.innerHTML = `
                <div class="text-center py-4 text-muted">
                    <i class="mdi mdi-bell-off-outline fs-1"></i>
                    <p class="mt-2 mb-0">Nenhuma notificação</p>
                </div>
            `;
                    return;
                }

                this.list.innerHTML = notifications.map(notification => `
            <div class="px-3 py-2 border-bottom notification-item ${notification.is_read ? '' : 'bg-light'}"
                data-notification-id="${notification.id}" style="cursor: default;">
                <div class="d-flex">
                    <div class="flex-shrink-0 me-3">
                        <div class="rounded-circle p-2 ${this.getNotificationColor(notification.type)}">
                            <i class="mdi ${this.getNotificationIcon(notification.type)} text-white"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1">
                        <h6 class="mb-1 small">${this.escapeHtml(notification.title)}</h6>
                        <p class="mb-1 small text-muted">${this.escapeHtml(notification.message)}</p>
                        <small class="text-muted">${this.formatTime(notification.created_at)}</small>
                    </div>
            <div class="flex-shrink-0 d-flex gap-1 align-items-center">
                ${!notification.is_read ? `
                            <form method="POST" action="{{ url('/notifications') }}/${notification.id}/read" style="display: inline;">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <button type="submit" class="btn btn-sm btn-success" title="Marcar como lida">
                                    <i class="mdi mdi-check-circle"></i>
                                </button>
                            </form>
                        ` : `
                            <span class="text-success" title="Lida">
                                <i class="mdi mdi-check-circle"></i>
                            </span>
                        `}
                <a href="{{ url('/notifications') }}" class="btn btn-sm btn-outline-secondary" title="Ver todas as notificações">
                    <i class="mdi mdi-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>
`).join('');
            }

            updateBadge(count) {
                this.unreadCount = count;
                if (this.badge) {
                    if (count > 0) {
                        this.badge.textContent = count > 99 ? '99+' : count;
                        this.badge.style.display = 'block';
                    } else {
                        this.badge.style.display = 'none';
                    }
                }
            }

            getNotificationColor(type) {
                const colors = {
                    'success': 'bg-success',
                    'warning': 'bg-warning',
                    'danger': 'bg-danger',
                    'info': 'bg-info'
                };
                return colors[type] || 'bg-primary';
            }

            getNotificationIcon(type) {
                const icons = {
                    'success': 'mdi-check-circle',
                    'warning': 'mdi-alert-circle',
                    'danger': 'mdi-alert-octagon',
                    'info': 'mdi-information'
                };
                return icons[type] || 'mdi-bell';
            }

            formatTime(dateString) {
                const date = new Date(dateString);
                const now = new Date();
                const diffMs = now - date;
                const diffMins = Math.floor(diffMs / 60000);
                const diffHours = Math.floor(diffMs / 3600000);
                const diffDays = Math.floor(diffMs / 86400000);

                if (diffMins < 1) return 'Agora';
                if (diffMins < 60) return `${diffMins} min atrás`;
                if (diffHours < 24) return `${diffHours} h atrás`;
                if (diffDays < 7) return `${diffDays} dias atrás`;

                return date.toLocaleDateString('pt-PT');
            }

            escapeHtml(unsafe) {
                return unsafe
                    .replace(/&/g, "&amp;")
                    .replace(/</g, "&lt;")
                    .replace(/>/g, "&gt;")
                    .replace(/"/g, "&quot;")
                    .replace(/'/g, "&#039;");
            }

            startPolling() {
                setInterval(() => {
                    this.loadNotifications();
                }, 30000);
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            new NotificationManager();
        });
    </script>

    @stack('scripts')
</body>

</html>
