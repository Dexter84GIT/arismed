<?php
add_action('rest_api_init', function () {
  register_rest_route('mydocs/v1', '/search', [
    'methods'  => 'GET',
    'callback' => function (WP_REST_Request $req) {

      $q = trim((string) $req->get_param('q'));
      $limit = (int) $req->get_param('limit');
      if ($limit <= 0) $limit = 200;     // документов обычно немного
      if ($limit > 500) $limit = 500;

      if (mb_strlen($q) < 3) {
        return new WP_REST_Response(['count' => 0, 'items' => []], 200);
      }

      $query = new WP_Query([
        'post_type'      => 'company_docs',
        'post_status'    => 'publish',
        's'              => $q,
        'posts_per_page' => $limit,
        'orderby'        => 'title',
        'order'          => 'ASC',
        'no_found_rows'  => true,
      ]);

      $items = [];

      if ($query->have_posts()) {
        while ($query->have_posts()) {
          $query->the_post();

          $title = get_field('title');   
          $type  = get_field('type');
          $file  = get_field('file');

          if (!$file) continue;

          $items[] = [
            'title' => (string) $title,
            'type'  => (string) $type,
            'file'  => esc_url_raw($file),
          ];
        }
        wp_reset_postdata();
      }

      return new WP_REST_Response([
        'count' => count($items),
        'items' => $items,
      ], 200);
    },
    'permission_callback' => '__return_true',
  ]);
});