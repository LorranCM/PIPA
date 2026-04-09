<?php
    session_start();
    // compute base URL for assets (strip trailing slash)
    $base = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="stylesheet" href="colors.css">
    <link rel="stylesheet" href="styles/landing_style.css">
    <link rel="stylesheet" href="styles/navbar.css"> <title>PIPA</title>
</head>
<body>
    <nav id="navbar">
    <ul>
        <li id="navbar-PIPA-clickable">
            <a href="index.php">
                <img src="assets/icons/kite-origami-paper-svgrepo-com.svg" alt="icone do pipa">PIPA
            </a>
        </li>
    </ul>
    </nav>
    <section class="hero">
        <header>
            <h1 id="header-title">A organização pessoal do estudante do <strong>Instituto Federal</strong> nunca foi tão
                fácil</h1>
            <a href="./login.php" class="select-button">SELECIONAR IF</a>
            <h3 id="header-description">
                Uma plataforma didática que conecta professores e alunos, centralizando informações acadêmicas e
                facilitando a organização do aluno.
            </h3>
        </header>

        <figure id="hero-figure1">
            <img src="assets/images/girl-holding-book.png" alt="figura1">
            <canvas id="graphic-el-1"></canvas>
            <canvas id="graphic-el-2"></canvas>
        </figure>

        <figure id="hero-figure2">
            <img src="assets/images/five-students-group.png" alt="figura2">
        </figure>

        <article>
            <h2 id="article-paragraph">Somos um grupo de 5 programadores, estudantes do <strong>Instituto
                    Federal</strong> do Espírito Santo - Campus Serra. Atualmente
                estamos cursando o sexto período de Informática para Internet.</h2>
        </article>

    </section>

    <section class="section-more-about">
        <h2>Nossos Objetivos</h2>

        <ul id="objectives-description">
            <li>
                <svg width="800px" height="800px" viewBox="0 0 24 24" id="Layer_1" data-name="Layer 1"
                    xmlns="http://www.w3.org/2000/svg">
                    <defs>
                        <style>
                            .cls-1 {
                                fill: none;
                                stroke-miterlimit: 10;
                                stroke-width: 1.91px;
                            }
                        </style>
                    </defs>
                    <line class="cls-1" x1="8.18" y1="22.5" x2="9.14" y2="17.73" />
                    <line class="cls-1" x1="5.32" y1="22.5" x2="18.68" y2="22.5" />
                    <rect class="cls-1" x="1.5" y="1.5" width="21" height="16.23" rx="1.91" />
                    <line class="cls-1" x1="15.75" y1="22.5" x2="14.8" y2="17.73" />
                    <line class="cls-1" x1="1.5" y1="13.91" x2="22.5" y2="13.91" />
                </svg>
                <h3>Organização<br>extra-sala</h3>
            </li>
            <li>
                <svg width="800px" height="800px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 8V12L14.5 14.5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    <path
                        d="M22 12C22 16.714 22 19.0711 20.5355 20.5355C19.0711 22 16.714 22 12 22C7.28595 22 4.92893 22 3.46447 20.5355C2 19.0711 2 16.714 2 12C2 7.28595 2 4.92893 3.46447 3.46447C4.92893 2 7.28595 2 12 2C16.714 2 19.0711 2 20.5355 3.46447C twenty-one point five zero nine three four three eight two one two one point eight three five six five point eight zero six five five two one point nine four four nine eight"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                <h3>Facilidade no Atendimento com Professor</h3>
            </li>
            <li>
                <svg width="800px" height="800px" viewBox="0 0 8.4666669 8.4666669" id="svg8" version="1.1"
                    xmlns="http://www.w3.org/2000/svg" xmlns:cc="http://creativecommons.org/ns#"
                    xmlns:dc="http://purl.org/dc/elements/1.1/"
                    xmlns:inkscape="http://www.inkscape.org/namespaces/inkscape"
                    xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
                    xmlns:sodipodi="http://sodipodi.sourceforge.net/DTD/sodipodi-0.dtd"
                    xmlns:svg="http://www.w3.org/2000/svg">
                    <defs id="defs2" />
                    <g id="layer1" transform="translate(0,-288.53332)">
                        <path
                            d="M 5 1 A 1.0000999 1.0000999 0 0 0 4 2 L 4 30 A 1.0000999 1.0000999 0 0 0 5 31 L 27 31 A 1.0000999 1.0000999 0 0 0 28 30 L 28 9 A 1.0000999 1.0000999 0 0 0 27.707031 8.2929688 L 20.707031 1.2929688 A 1.0000999 1.0000999 0 0 0 20 1 L 5 1 z M 6 3 L 19.001953 3 L 19.001953 8.9980469 C 18.99982 9.5505004 19.445584 9.9999878 19.998047 10.001953 L 26 10.001953 L 26 29 L 6 29 L 6 3 z M 21.001953 4.4160156 L 24.587891 8.0019531 L 21.001953 8.0019531 L 21.001953 4.4160156 z "
                            id="rect996"
                            style="color:#000000;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;font-size:medium;line-height:normal;font-family:sans-serif;font-variant-ligatures:normal;font-variant-position:normal;font-variant-caps:normal;font-variant-numeric:normal;font-variant-alternates:normal;font-feature-settings:normal;text-indent:0;text-align:start;text-decoration:none;text-decoration-line:none;text-decoration-style:solid;text-decoration-color:#000000;letter-spacing:normal;word-spacing:normal;text-transform:none;writing-mode:lr-tb;direction:ltr;text-orientation:mixed;dominant-baseline:auto;baseline-shift:baseline;text-anchor:start;white-space:normal;shape-padding:0;clip-rule:nonzero;display:inline;overflow:visible;visibility:visible;opacity:1;isolation:auto;mix-blend-mode:normal;color-interpolation:sRGB;color-interpolation-filters:linearRGB;solid-color:#000000;solid-opacity:1;vector-effect:none;fill:#ffffff;fill-opacity:1;fill-rule:nonzero;stroke:none;stroke-width:1.99999988;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:4;stroke-dasharray:none;stroke-dashoffset:0;stroke-opacity:1;paint-order:stroke fill markers;color-rendering:auto;image-rendering:auto;shape-rendering:auto;text-rendering:auto;enable-background:accumulate"
                            transform="matrix(0.26458333,0,0,0.26458333,0,288.53332)" />
                        <path
                            d="m 2.8847656,291.70898 a 0.26495432,0.26495432 0 0 0 0.025391,0.5293 h 2.6464844 a 0.26465,0.26465 0 1 0 0,-0.5293 H 2.9101562 a 0.26460981,0.26460981 0 0 0 -0.025391,0 z"
                            id="path1009"
                            style="color:#000000;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;font-size:medium;line-height:normal;font-family:sans-serif;font-variant-ligatures:normal;font-variant-position:normal;font-variant-caps:normal;font-variant-numeric:normal;font-variant-alternates:normal;font-feature-settings:normal;text-indent:0;text-align:start;text-decoration:none;text-decoration-line:none;text-decoration-style:solid;text-decoration-color:#000000;letter-spacing:normal;word-spacing:normal;text-transform:none;writing-mode:lr-tb;direction:ltr;text-orientation:mixed;dominant-baseline:auto;baseline-shift:baseline;text-anchor:start;white-space:normal;shape-padding:0;clip-rule:nonzero;display:inline;overflow:visible;visibility:visible;opacity:1;isolation:auto;mix-blend-mode:normal;color-interpolation:sRGB;color-interpolation-filters:linearRGB;solid-color:#000000;solid-opacity:1;vector-effect:none;fill:#ffffff;fill-opacity:1;fill-rule:nonzero;stroke:none;stroke-width:0.5291667;stroke-linecap:round;stroke-linejoin:miter;stroke-miterlimit:4;stroke-dasharray:none;stroke-dashoffset:0;stroke-opacity:1;color-rendering:auto;image-rendering:auto;shape-rendering:auto;text-rendering:auto;enable-background:accumulate" />
                        <path
                            d="m 2.9101562,293.29492 a 0.26465,0.26465 0 1 0 0,0.5293 h 2.6464844 a 0.26465,0.26465 0 1 0 0,-0.5293 z"
                            id="path1011"
                            style="color:#000000;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;font-size:medium;line-height:normal;font-family:sans-serif;font-variant-ligatures:normal;font-variant-position:normal;font-variant-caps:normal;font-variant-numeric:normal;font-variant-alternates:normal;font-feature-settings:normal;text-indent:0;text-align:start;text-decoration:none;text-decoration-line:none;text-decoration-style:solid;text-decoration-color:#000000;letter-spacing:normal;word-spacing:normal;text-transform:none;writing-mode:lr-tb;direction:ltr;text-orientation:mixed;dominant-baseline:auto;baseline-shift:baseline;text-anchor:start;white-space:normal;shape-padding:0;clip-rule:nonzero;display:inline;overflow:visible;visibility:visible;opacity:1;isolation:auto;mix-blend-mode:normal;color-interpolation:sRGB;color-interpolation-filters:linearRGB;solid-color:#000000;solid-opacity:1;vector-effect:none;fill:#ffffff;fill-opacity:1;fill-rule:nonzero;stroke:none;stroke-width:0.5291667;stroke-linecap:round;stroke-linejoin:miter;stroke-miterlimit:4;stroke-dasharray:none;stroke-dashoffset:0;stroke-opacity:1;color-rendering:auto;image-rendering:auto;shape-rendering:auto;text-rendering:auto;enable-background:accumulate" />
                        <path
                            d="m 2.9101562,294.88281 a 0.26465,0.26465 0 1 0 0,0.5293 h 2.6464844 a 0.26465,0.26465 0 1 0 0,-0.5293 z"
                            id="path1013"
                            style="color:#000000;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;font-size:medium;line-height:normal;font-family:sans-serif;font-variant-ligatures:normal;font-variant-position:normal;font-variant-caps:normal;font-variant-numeric:normal;font-variant-alternates:normal;font-feature-settings:normal;text-indent:0;text-align:start;text-decoration:none;text-decoration-line:none;text-decoration-style:solid;text-decoration-color:#000000;letter-spacing:normal;word-spacing:normal;text-transform:none;writing-mode:lr-tb;direction:ltr;text-orientation:mixed;dominant-baseline:auto;baseline-shift:baseline;text-anchor:start;white-space:normal;shape-padding:0;clip-rule:nonzero;display:inline;overflow:visible;visibility:visible;opacity:1;isolation:auto;mix-blend-mode:normal;color-interpolation:sRGB;color-interpolation-filters:linearRGB;solid-color:#000000;solid-opacity:1;vector-effect:none;fill:#ffffff;fill-opacity:1;fill-rule:nonzero;stroke:none;stroke-width:0.5291667;stroke-linecap:round;stroke-linejoin:miter;stroke-miterlimit:4;stroke-dasharray:none;stroke-dashoffset:0;stroke-opacity:1;color-rendering:auto;image-rendering:auto;shape-rendering:auto;text-rendering:auto;enable-background:accumulate" />
                    </g>
                </svg>
                <h3>Informações Centralizadas</h3>
            </li>
        </ul>

        <a href="./login.php" class="select-button">SELECIONAR IF</a>

    </section>

    <?php include 'footer.php'; ?>
</body>

</html>