<?php
/**
 * Header partial — topbar navigation BNGRC
 * Inclut les styles nécessaires pour la topbar.
 * Utilise Flight::get('flight.base_url') pour les liens.
 */
$burl = \Flight::get('flight.base_url');
?>
<style>
    .topbar {
        background: #ffffff;
        border-bottom: 1px solid #eef2f6;
        padding: 16px 32px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        position: sticky;
        top: 0;
        z-index: 100;
        backdrop-filter: blur(8px);
        background: rgba(255, 255, 255, 0.95);
    }
    .topbar .brand {
        display: flex;
        align-items: center;
        gap: 16px;
    }
    .topbar .brand .logo {
        background: linear-gradient(135deg, #2563eb, #1e40af);
        color: white;
        font-weight: 700;
        font-size: 14px;
        padding: 8px 16px;
        border-radius: 12px;
        letter-spacing: 0.5px;
        box-shadow: 0 4px 6px -1px rgba(37, 99, 235, 0.2);
    }
    .topbar .brand h1 {
        font-size: 20px;
        font-weight: 600;
        color: #0f172a;
    }
    .topbar nav {
        display: flex;
        gap: 8px;
    }
    .topbar nav a {
        color: #475569;
        text-decoration: none;
        padding: 10px 20px;
        border-radius: 12px;
        font-size: 14px;
        font-weight: 500;
        transition: all 0.2s;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
    }
    .topbar nav a:hover {
        background: #ffffff;
        border-color: #2563eb;
        color: #2563eb;
        transform: translateY(-1px);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }
    @media (max-width: 768px) {
        .topbar {
            flex-direction: column;
            gap: 16px;
            padding: 16px;
        }
        .topbar nav {
            width: 100%;
            flex-wrap: wrap;
            justify-content: center;
        }
        .topbar nav a {
            flex: 1;
            text-align: center;
            padding: 10px;
            min-width: 120px;
        }
    }
</style>
<div class="topbar">
    <div class="brand">
        <span class="logo">BNGRC</span>
        <h1>Suivi des dons</h1>
    </div>
    <nav>
        <a href="<?= $burl ?>/">🏠 Tableau de bord</a>
        <a href="<?= $burl ?>/formBesoin">📋 Besoin</a>
        <a href="<?= $burl ?>/showFormDon">🎁 Don</a>
        <a href="<?= $burl ?>/showFormDispatch">⚡ Dispatcher</a>
        <a href="<?= $burl ?>/listBesoinRestant">📊 Simulation d'achat</a>
        <a href="<?= $burl ?>/showTableauRecap">📈 Récapitulatif</a>
    </nav>
</div>