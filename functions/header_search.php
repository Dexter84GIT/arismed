<?php
add_action('rest_api_init', function () {
  register_rest_route('myshop/v1', '/search', [
    'methods'  => 'GET',
    'callback' => function (WP_REST_Request $req) {
      $q = trim((string) $req->get_param('q'));
      $limit = (int) $req->get_param('limit');
      if ($limit <= 0) $limit = 8;
      if ($limit > 20) $limit = 20;

      if (mb_strlen($q) < 3) {
        return new WP_REST_Response(['products' => [], 'categories' => []], 200);
      }

      $q1 = new WP_Query([
        'post_type'      => 'product',
        'post_status'    => 'publish',
        's'              => $q,
        'posts_per_page' => $limit,
        'no_found_rows'  => true,
        'fields'         => 'ids',
      ]);

      $q2 = new WP_Query([
        'post_type'      => 'product',
        'post_status'    => 'publish',
        'posts_per_page' => $limit,
        'no_found_rows'  => true,
        'fields'         => 'ids',
        'meta_query'     => [
          [
            'key'     => 'article',   
            'value'   => $q,
            'compare' => 'LIKE',
          ],
        ],
      ]);

      $ids = array_values(array_unique(array_merge($q1->posts, $q2->posts)));
      $ids = array_slice($ids, 0, $limit);

      $products = array_map(function ($id) {
        return [
          'title' => get_the_title($id),
          'url'   => get_permalink($id),
        ];
      }, $ids);

      $terms = get_terms([
        'taxonomy'   => 'product_cat',
        'hide_empty' => true,
        'number'     => $limit,
        'name__like' => $q,
      ]);

      $categories = [];
      if (!is_wp_error($terms)) {
        foreach ($terms as $t) {
          $categories[] = [
            'name' => $t->name,
            'url'  => get_term_link($t),
          ];
        }
      }

      return new WP_REST_Response([
        'products'   => $products,
        'categories' => $categories,
      ], 200);
    },
    'permission_callback' => '__return_true',
  ]);
});
