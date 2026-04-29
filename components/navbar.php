<?php

function modular_nav($pipa_header = "index.php") {

    echo "
    <nav id=\"navbar\">
        <ul>
            <li id=\"navbar-PIPA-clickable\">
                <a href=\"$pipa_header\">
                    <img src=\"assets/icons/kite-origami-paper-svgrepo-com.svg\" alt=\"icone do pipa\">PIPA
                </a>
            </li>
        </ul>
    </nav>";
}