<!DOCTYPE html>
<?php
    require_once('../config.php');
?>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="Generator" content="Drupal 7 (http://drupal.org)" />
    <link rel="shortcut icon" href="/icon.png" type="image/png" />
    <title>Home | The Wallich Catalogue</title>
    <meta name="MobileOptimized" content="width">
    <meta name="HandheldFriendly" content="true">
    <meta name="viewport" content="width=device-width">

    <style>
    @import url("style/normalize.css");
    @import url("style/misc.css");
    @import url("style/responsive.css");
    @import url("style/wallich.css");
    </style>
    <script src="js/main.js"></script>


</head>

<body class="html front not-logged-in no-sidebars page-node page-node- page-node-61201 node-type-page">
    <p id="skip-link">
        <a href="#main-menu" class="element-invisible element-focusable">Jump to navigation </a>
    </p>

    <div id="page">

        <header class="header" id="header" role="banner">

            <a href="/" title="Home" rel="home" class="header__logo" id="logo"><img src="style/logo.png" alt="Home"
                    class="header__logo-image" /></a>

            <div class="header__name-and-slogan" id="name-and-slogan">
                <h1 class="header__site-name" id="site-name">
                    <a href="/" title="Home" class="header__site-link" rel="home"><span>The Wallich Catalogue</span></a>
                </h1>

                <div class="header__site-slogan" id="site-slogan">Recreating a 19th Century Herbarium</div>
            </div>


            <div class="header__region region region-header">
            </div>

        </header>

        <div id="main">
            <div id="content" style="position:relative; width: 100%" role="main">
                <div id="overlay" style="display:none;">
                    <div id="overlay_box" style="background-color: white; z-index: 2;">
                        <p style=" float: right; margin-top: -.8em; "><span
                                style=" text-decoration: none; font-weight: bold; cursor: pointer;" href="#"
                                onclick="popDownDescription(this)">X</span></p>
                        <div id="overlay_content">Place holder</div>
                        <p style="float: right; margin-top: -.8em;"><span
                                style="text-decoration: underline; font-weight: bold; cursor: pointer;"
                                onclick="popDownDescription(this)">close</span>
                    </div>
                </div>
                <?php
                    switch (@$_GET['section']) {
                        case 'about':
                            require_once('include/about.php');
                            break;
                        case 'notation':
                            require_once('include/notation.php');
                            break;
                        case 'pages':
                            require_once('include/pages.php');
                            break;
                        case 'entries':
                            require_once('include/entries.php');
                            break;
                        case 'feedback':
                            require_once('include/feedback.php');
                            break;
                        case 'search':
                            require_once('include/search.php');
                            break;
                        default:
                            require_once('include/home.php');
                            break;
                    }
                    
                ?>


            </div>

            <div id="navigation">
                <nav id="main-menu" role="navigation" tabindex="-1">
                    <h2 class="element-invisible">Main menu</h2>
                    <ul class="links inline clearfix">
                        <li class="menu-218 first active"><a href="/" title="" class="active">Home</a></li>
                        <li class="menu-584"><a href="index.php?section=about">About</a></li>
                        <li class="menu-636"><a href="index.php?section=notation">Edinburgh Notation</a></li>
                        <li class="menu-437"><a href="index.php?section=pages"
                                title="Page through the catalogue page by page">Catalogue
                                Pages</a></li>
                        <li class="menu-314"><a href="/index.php?section=entries"
                                title="All the logical entries and subentries in the catalogue">Catalogue Entries</a>
                        </li>
                        <li class="menu-314"><a href="/index.php?section=search"
                                title="Search the entries and sub-entries">Search</a>
                        </li>
                        <li class="menu-646 last"><a href="index.php?section=feedback"
                                title="Feedback on the website and data">Feedback</a></li>
                    </ul>
                </nav>

            </div>


        </div>
        <div style="clear: both; height: 5em;">&nbsp;</div>
        <footer id="footer" class="region region-footer">
            <div id="block-block-2" class="block block-block first last odd">


                <p><img alt="Creative Commons License" src="https://i.creativecommons.org/l/by-nc-sa/4.0/88x31.png"
                        style="border-width:0" /> This work is licensed under a <a
                        href="http://creativecommons.org/licenses/by-nc-sa/4.0/" rel="license">Creative Commons
                        Attribution-NonCommercial-ShareAlike 4.0 International License</a>.</p>
            </div>
        </footer>

    </div>

</body>

</html>