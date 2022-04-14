<?php
/**
 * Template Name: Redirect Page
 * Template Post Type: page
 *
 * The template for redirecting to other page.
 *
 * @package WordPress
 * @subpackage Keima
 * @since 1.0
 * @version 1.0
 */

$redirect_url = get_field( 'redirect_url' );
if ( is_array($redirect_url) ) {
  wp_redirect( $redirect_url['url'] );
}

if ( $url = $redirect_url['url'] ) {
  echo '<p>' . __('Processing to redirect to the URL below.<br>If the page does not switch after 5 seconds, click the link URL directly.', 'keima-redirect-page') . '</p>';
  echo '<a href="' . $url . '">' . $url . '</a>';
}

if ( WP_DEBUG )
  echo '<!--page-redirect.php-->';
