<?php 
add_action('init', function () {
    add_rewrite_rule(
        '^catalog/([^/]+)/page/([0-9]+)/?$',
        'index.php?product_cat=$matches[1]&paged=$matches[2]',
        'top'
    );

    add_rewrite_rule(
        '^catalog/([^/]+)/([^/]+)/?$',
        'index.php?post_type=product&name=$matches[2]',
        'top'
    );

    add_rewrite_rule(
        '^catalog/([^/]+)/?$',
        'index.php?product_cat=$matches[1]',
        'top'
    );
}, 20);