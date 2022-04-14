<?php

add_action('acf/init', function () {
  add_action('admin_init', function() {
    $args_location = array(
      array(
        array(
          'param'    => 'post_template',
          'operator' => '==',
          'value'    => 'keima-redirect-page.php',
        )
      )
    );

    $post_templates = get_fields('option')['redirect_post_template'];

    if( is_array( $post_templates ) ) {
      foreach( $post_templates as $val ) {
        array_push( $args_location,
          array(
            array(
              'param'    => 'post_template',
              'operator' => '==',
              'value'    => $val,
            )
          )
        );
      }
    }

    acf_add_local_field_group(array(
      'key' => 'group_5f2509bf09c97',
      'title' => __('Redirect', 'keima-redirect-page'),
      'fields' => array(
        array(
          'key' => 'field_5f2509cabc552',
          'label' => __('Redirect URL', 'keima-redirect-page'),
          'name' => 'redirect_url',
          'type' => 'link',
          'required' => 1,
          'conditional_logic' => 0,
          'return_format' => 'array',
        ),
      ),
      'location' => $args_location
    ));
  });
});
