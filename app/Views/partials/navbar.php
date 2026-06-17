<nav id="navbar">
    <ul>
        <li id="navbar-PIPA-clickable">
            <a href=<?= url_to('index') ?>>
                <img src=<?= base_url("assets/icons/kite-origami-paper-svgrepo-com.svg") ?> alt="icone do pipa">PIPA
            </a>
        </li>
        <?php if(session()->get('loggedin')): ?>
            <li>
                <a id="logout" href="<?= url_to('logout') ?>">
                    <img src="<?= base_url('assets/icons/logout-svgrepo-com.svg') ?>" alt="Logout">
                </a>
            </li>
        <?php endif; ?>
    </ul>
</nav>