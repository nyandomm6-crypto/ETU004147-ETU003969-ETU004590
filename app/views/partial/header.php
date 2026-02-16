<?php
/**
 * Header partial — uses Flight base_url
 */
?>
<div class="topbar">
    <div class="brand">
        <span class="logo">BNGRC</span>
        <h1>Tableau de bord — Suivi des dons</h1>
    </div>
    <nav>
        <a href="<?php echo \Flight::get('base_url'); ?>/formBesoin">📋 Saisir un besoin</a>
        <a href="<?php echo \Flight::get('base_url'); ?>/showFormDon">🎁 Saisir un don</a>
        <a href="<?php echo \Flight::get('base_url'); ?>/showFormDispatch">⚡ Dispatcher</a>
    </nav>
</div>