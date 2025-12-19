<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@12/swiper-bundle.min.css" />

    <title><?php the_title(); ?></title>
    <?php wp_head(); ?>
</head>

<body class="page">
    <main>
        <div class="topPatterns bg">
            <div class="pattern_001 desktop">
                <img src="<?php bloginfo('template_directory'); ?>/img/plus_001.png" alt="plus">
            </div>
            <div class="pattern_002 desktop">
                <img src="<?php bloginfo('template_directory'); ?>/img/plus_002.png" alt="plus">
            </div>
        </div>
        <?php include get_template_directory() . '/inc/mobile-menu.php'; ?>
        <?php include get_template_directory() . '/inc/shared/added.php'; ?>

        <header class="header">
            <div class="container df fdc gap40">
                <div class="row top df aic jcsb">
                    <div class="openMenu mobile">
                        <span></span>
                    </div>
                    <a href="/" class="logo">
                        <img src="<?php bloginfo('template_directory'); ?>/img/logo_blue.svg" alt="logo">
                    </a>
                    <div class="info df aic gap60">
                        <a href="tel:89639004208" target="_blank" class="phone df aic gap15">
                            <div class="img iconLink tpr">
                                <svg width="14" height="14" viewBox="0 0 14 14" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M2.655 1.5C2.7 2.1675 2.8125 2.82 2.9925 3.4425L2.0925 4.3425C1.785 3.4425 1.59 2.49 1.5225 1.5H2.655ZM10.05 10.515C10.6875 10.695 11.34 10.8075 12 10.8525V11.97C11.01 11.9025 10.0575 11.7075 9.15 11.4075L10.05 10.515ZM3.375 0H0.75C0.3375 0 0 0.3375 0 0.75C0 7.7925 5.7075 13.5 12.75 13.5C13.1625 13.5 13.5 13.1625 13.5 12.75V10.1325C13.5 9.72 13.1625 9.3825 12.75 9.3825C11.82 9.3825 10.9125 9.2325 10.0725 8.955C9.9975 8.925 9.915 8.9175 9.84 8.9175C9.645 8.9175 9.4575 8.9925 9.3075 9.135L7.6575 10.785C5.535 9.6975 3.795 7.965 2.715 5.8425L4.365 4.1925C4.575 3.9825 4.635 3.69 4.5525 3.4275C4.275 2.5875 4.125 1.6875 4.125 0.75C4.125 0.3375 3.7875 0 3.375 0Z" />
                                </svg>
                            </div>
                            <p>8 (963) 900-42-08</p>
                        </a>
                        <div class="links df aic gap20">
                            <a href="#" class="iconLink tpr">
                                <svg width="14" height="14" viewBox="0 0 14 14" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M9.375 8.25H8.7825L8.5725 8.0475C9.3075 7.1925 9.75 6.0825 9.75 4.875C9.75 2.1825 7.5675 0 4.875 0C2.1825 0 0 2.1825 0 4.875C0 7.5675 2.1825 9.75 4.875 9.75C6.0825 9.75 7.1925 9.3075 8.0475 8.5725L8.25 8.7825V9.375L12 13.1175L13.1175 12L9.375 8.25ZM4.875 8.25C3.0075 8.25 1.5 6.7425 1.5 4.875C1.5 3.0075 3.0075 1.5 4.875 1.5C6.7425 1.5 8.25 3.0075 8.25 4.875C8.25 6.7425 6.7425 8.25 4.875 8.25Z" />
                                </svg>
                            </a>
                            <?php include get_template_directory() . '/inc/shared/miniCart.php'; ?>
                            <?php include get_template_directory() . '/inc/shared/miniAccount.php'; ?>
                        </div>
                    </div>
                </div>
                <div class="row bottom">
                    <div class="menu df aic gap10">
                        <a href="/catalog" class="link">Каталог</a>
                        <a href="/about" class="link">Компания</a>
                        <a href="/docs" class="link">Документы</a>
                        <a href="/delivery" class="link">Оплата и доставка</a>
                        <a href="/sales" class="link">Акции</a>
                        <a href="/news" class="link">Новости</a>
                        <a href="/contacts" class="link">Контакты</a>
                    </div>
                </div>
                <?php include get_template_directory() . '/inc/shared/breadcrumbs.php'; ?>
            </div>
        </header>