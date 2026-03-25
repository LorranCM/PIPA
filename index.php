<?php
    session_start();
    // compute base URL for assets (strip trailing slash)
    $base = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <base href="<?= $base ?>/">
    <link rel="icon" type="image/svg+xml" href="assets/icons/kite-origami-paper-svgrepo-com.svg">
    <link rel="stylesheet" href="colors.css">
    <link rel="stylesheet" href="styles/landing_style.css">
    <link rel="stylesheet" href="styles/navbar.css">
    <link rel="stylesheet" href="styles/footer.css">
    <title>PIPA</title>

</head>

<body>
    <nav id="navbar">
        <ul>
            <li id="navbar-PIPA-clickable">
                <a href="index.php">
                    <img src="assets/icons/kite-origami-paper-svgrepo-com.svg" alt="icone do pipa">PIPA
                </a>
            </li>

            <li>
                <a href="">QUEM SOMOS?</a>
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

    <footer>
        <section id="footer-section1">
            <figure>
                <svg fill="#FFFFFF" width="800px" height="800px" viewBox="0 0 64 64" xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M33.66,4.6c-.96-.8-2.36-.8-3.32,0L9.86,21.63c-.96,.8-1.19,2.2-.54,3.27L30.45,59.14c.25,.41,.66,.7,1.13,.81,.14,.03,.28,.05,.42,.05,.33,0,.66-.09,.95-.27,.24-.15,.45-.35,.59-.59L54.68,24.9c.66-1.06,.43-2.47-.53-3.27L33.66,4.6Zm-.66,2.05l18.76,15.59h-18.76V6.65Zm-2,0v15.59H12.24L31,6.65Zm-.79,17.59l-9.47,15.34-9.47-15.34H30.21Zm-8.29,17.25l9.09-14.72v29.44l-9.09-14.72Zm11.09,14.76V24.25h19.73l-19.73,32.01Z" />
                </svg>
                <h2><strong>PIPA</strong><br>Portal de Informação Professor-Aluno</h2>
            </figure>
            <div id="contacts-info">
                <div>
                    <h2>Fale conosco</h2>
                    <p><strong>Email:</strong> pipaifes@gmail.com</p>
                    <p><strong>Telefone:</strong> (11) 1234-5678</p>
                </div>
                <div>
                    <h2>Nos acompanhe</h2>
                    <ul id="social-media-icon-list">
                        <li>
                            <a href="https://www.facebook.com/pipaifes" target="_blank">
                                <svg width="800px" height="800px" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M20 1C21.6569 1 23 2.34315 23 4V20C23 21.6569 21.6569 23 20 23H4C2.34315 23 1 21.6569 1 20V4C1 2.34315 2.34315 1 4 1H20ZM20 3C20.5523 3 21 3.44772 21 4V20C21 20.5523 20.5523 21 20 21H15V13.9999H17.0762C17.5066 13.9999 17.8887 13.7245 18.0249 13.3161L18.4679 11.9871C18.6298 11.5014 18.2683 10.9999 17.7564 10.9999H15V8.99992C15 8.49992 15.5 7.99992 16 7.99992H18C18.5523 7.99992 19 7.5522 19 6.99992V6.31393C19 5.99091 18.7937 5.7013 18.4813 5.61887C17.1705 5.27295 16 5.27295 16 5.27295C13.5 5.27295 12 6.99992 12 8.49992V10.9999H10C9.44772 10.9999 9 11.4476 9 11.9999V12.9999C9 13.5522 9.44771 13.9999 10 13.9999H12V21H4C3.44772 21 3 20.5523 3 20V4C3 3.44772 3.44772 3 4 3H20Z"
                                        fill="#FFFFFF" />
                                </svg>
                            </a>
                        </li>
                        <li>
                            <a href="https://www.twitter.com/pipaifes" target="_blank">
                                <svg width="800px" height="800px" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M19.7828 3.91825C20.1313 3.83565 20.3743 3.75444 20.5734 3.66915C20.8524 3.54961 21.0837 3.40641 21.4492 3.16524C21.7563 2.96255 22.1499 2.9449 22.4739 3.11928C22.7979 3.29366 23 3.6319 23 3.99986C23 5.08079 22.8653 5.96673 22.5535 6.7464C22.2911 7.40221 21.9225 7.93487 21.4816 8.41968C21.2954 11.7828 20.3219 14.4239 18.8336 16.4248C17.291 18.4987 15.2386 19.8268 13.0751 20.5706C10.9179 21.3121 8.63863 21.4778 6.5967 21.2267C4.56816 20.9773 2.69304 20.3057 1.38605 19.2892C1.02813 19.0108 0.902313 18.5264 1.07951 18.109C1.25671 17.6916 1.69256 17.4457 2.14144 17.5099C3.42741 17.6936 4.6653 17.4012 5.6832 16.9832C5.48282 16.8742 5.29389 16.7562 5.11828 16.6346C4.19075 15.9925 3.4424 15.1208 3.10557 14.4471C2.96618 14.1684 2.96474 13.8405 3.10168 13.5606C3.17232 13.4161 3.27562 13.293 3.40104 13.1991C2.04677 12.0814 1.49999 10.5355 1.49999 9.49986C1.49999 9.19192 1.64187 8.90115 1.88459 8.71165C1.98665 8.63197 2.10175 8.57392 2.22308 8.53896C2.12174 8.24222 2.0431 7.94241 1.98316 7.65216C1.71739 6.3653 1.74098 4.91284 2.02985 3.75733C2.1287 3.36191 2.45764 3.06606 2.86129 3.00952C3.26493 2.95299 3.6625 3.14709 3.86618 3.50014C4.94369 5.36782 6.93116 6.50943 8.78086 7.18568C9.6505 7.50362 10.4559 7.70622 11.0596 7.83078C11.1899 6.61019 11.5307 5.6036 12.0538 4.80411C12.7439 3.74932 13.7064 3.12525 14.74 2.84698C16.5227 2.36708 18.5008 2.91382 19.7828 3.91825ZM10.7484 9.80845C10.0633 9.67087 9.12171 9.43976 8.09412 9.06408C6.7369 8.56789 5.16088 7.79418 3.84072 6.59571C3.86435 6.81625 3.89789 7.03492 3.94183 7.24766C4.16308 8.31899 4.5742 8.91899 4.94721 9.10549C5.40342 9.3336 5.61484 9.8685 5.43787 10.3469C5.19827 10.9946 4.56809 11.0477 3.99551 10.9046C4.45603 11.595 5.28377 12.2834 6.66439 12.5135C7.14057 12.5929 7.49208 13.0011 7.49986 13.4838C7.50765 13.9665 7.16949 14.3858 6.69611 14.4805L5.82565 14.6546C5.95881 14.7703 6.103 14.8838 6.2567 14.9902C6.95362 15.4727 7.65336 15.6808 8.25746 15.5298C8.70991 15.4167 9.18047 15.6313 9.39163 16.0472C9.60278 16.463 9.49846 16.9696 9.14018 17.2681C8.49626 17.8041 7.74425 18.2342 6.99057 18.5911C6.63675 18.7587 6.24134 18.9241 5.8119 19.0697C6.14218 19.1402 6.48586 19.198 6.84078 19.2417C8.61136 19.4594 10.5821 19.3126 12.4249 18.6792C14.2614 18.0479 15.9589 16.9385 17.2289 15.2312C18.497 13.5262 19.382 11.1667 19.5007 7.96291C19.51 7.71067 19.6144 7.47129 19.7929 7.29281C20.2425 6.84316 20.6141 6.32777 20.7969 5.7143C20.477 5.81403 20.1168 5.90035 19.6878 5.98237C19.3623 6.04459 19.0272 5.94156 18.7929 5.70727C18.0284 4.94274 16.5164 4.43998 15.2599 4.77822C14.6686 4.93741 14.1311 5.28203 13.7274 5.89906C13.3153 6.52904 13 7.51045 13 8.9999C13 9.28288 12.8801 9.5526 12.6701 9.74221C12.1721 10.1917 11.334 9.92603 10.7484 9.80845Z"
                                        fill="#FFFFFF" />
                                </svg>
                            </a>
                        </li>
                        <li>
                            <a href="https://www.instagram.com/pipaifes" target="_blank">
                                <svg width="800px" height="800px" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M12 18C15.3137 18 18 15.3137 18 12C18 8.68629 15.3137 6 12 6C8.68629 6 6 8.68629 6 12C6 15.3137 8.68629 18 12 18ZM12 16C14.2091 16 16 14.2091 16 12C16 9.79086 14.2091 8 12 8C9.79086 8 8 9.79086 8 12C8 14.2091 9.79086 16 12 16Z"
                                        fill="#FFFFFF" />
                                    <path
                                        d="M18 5C17.4477 5 17 5.44772 17 6C17 6.55228 17.4477 7 18 7C18.5523 7 19 6.55228 19 6C19 5.44772 18.5523 5 18 5Z"
                                        fill="#FFFFFF" />
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M1.65396 4.27606C1 5.55953 1 7.23969 1 10.6V13.4C1 16.7603 1 18.4405 1.65396 19.7239C2.2292 20.8529 3.14708 21.7708 4.27606 22.346C5.55953 23 7.23969 23 10.6 23H13.4C16.7603 23 18.4405 23 19.7239 22.346C20.8529 21.7708 21.7708 20.8529 22.346 19.7239C23 18.4405 23 16.7603 23 13.4V10.6C23 7.23969 23 5.55953 22.346 4.27606C21.7708 3.14708 20.8529 2.2292 19.7239 1.65396C18.4405 1 16.7603 1 13.4 1H10.6C7.23969 1 5.55953 1 4.27606 1.65396C3.14708 2.2292 2.2292 3.14708 1.65396 4.27606ZM13.4 3H10.6C8.88684 3 7.72225 3.00156 6.82208 3.0751C5.94524 3.14674 5.49684 3.27659 5.18404 3.43597C4.43139 3.81947 3.81947 4.43139 3.43597 5.18404C3.27659 5.49684 3.14674 5.94524 3.0751 6.82208C3.00156 7.72225 3 8.88684 3 10.6V13.4C3 15.1132 3.00156 16.2777 3.0751 17.1779C3.14674 18.0548 3.27659 18.5032 3.43597 18.816C3.81947 19.5686 4.43139 20.1805 5.18404 20.564C5.49684 20.7234 5.94524 20.8533 6.82208 20.9249C7.72225 20.9984 8.88684 21 10.6 21H13.4C15.1132 21 16.2777 20.9984 17.1779 20.9249C18.0548 20.8533 18.5032 20.7234 18.816 20.564C19.5686 20.1805 20.1805 19.5686 20.564 18.816C20.7234 18.5032 20.8533 18.0548 20.9249 17.1779C20.9984 16.2777 21 15.1132 21 13.4V10.6C21 8.88684 20.9984 7.72225 20.9249 6.82208C20.8533 5.94524 20.7234 5.49684 20.564 5.18404C20.1805 4.43139 19.5686 3.81947 18.816 3.43597C18.5032 3.27659 18.0548 3.14674 17.1779 3.0751C16.2777 3.00156 15.1132 3 13.4 3Z"
                                        fill="#FFFFFF" />
                                </svg>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </section>
        <section id="footer-section2">
            <ul>
                <li><a href="">Institutos Federais parceiros</a></li>
                <li><a href="">Método de trabalho</a></li>
                <li><a href="">Mais funcionalidades</a></li>
                <li><a href="">Nossa história</a></li>
            </ul>
            <div>
                <p>Av. dos Sabiás, 330 - Morada de Laranjeiras, Serra - ES, 29166-630</p>
                <p>&copy 2025 - PIPA</p>
            </div>
        </section>
    </footer>
</body>

</html>