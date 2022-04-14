<?php
/**
 * Plugin Name: keima | Redirect Page
 * Description:  This will add a Redirect page template to the page. This plugin needs "Advanced Custom Fields" to run. Please download and activate it before.
 * Version: 1.0.1
 * Plugin URI: 
 * Author: keima.co
 * Author URI: https://www.keima.co/
 * Text Domain: keima-redirect-page
*/

if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly
}

define( 'KEIMA_REDIRECT_PAGE_FILE', __FILE__ );
define( 'KEIMA_REDIRECT_PAGE_DIR', plugin_dir_path( __FILE__ ) );
define( 'KEIMA_REDIRECT_PAGE_VER', '1.0.0' );

if ( ! class_exists( 'KEIMA_REDIRECT_PAGE' ) ) :

  class KEIMA_REDIRECT_PAGE {

    function __construct() {
      // Do nothing.
    }

    function initialize () {

      add_action( 'plugins_loaded', function () {
        load_plugin_textdomain( 'keima-redirect-page', false, 'keima-redirect-page/languages/' );
      });

      if ( ! function_exists( 'get_plugins' ) ) {
        require_once ABSPATH . 'wp-admin/includes/plugin.php';
      }
      if (
        array_key_exists( 'advanced-custom-fields/acf.php', get_plugins() )
        && is_plugin_active( 'advanced-custom-fields/acf.php' )
      ) {
        $this->activation();
      } else {
        $this->deactivation();
      }
    }

    function add_language () {
      load_plugin_textdomain( 'keima-redirect-page', false, 'keima-redirect-page/languages/' );
    }

    function activation () {
      $this->add_page_template();
      require_once KEIMA_REDIRECT_PAGE_DIR . 'includes/krp-generate-field.php';
    }

    function add_page_template () {
      // Set template name.
      add_filter ('theme_page_templates', function ($templates) {
        $templates['keima-redirect-page.php'] = __('Redirect page', 'keima-redirect-page');
        return $templates;
      });
      // Set template place.
      add_filter ('page_template', function ($template) {
        if ('keima-redirect-page.php' == basename ($template))
          $template = KEIMA_REDIRECT_PAGE_DIR . 'includes/krp-page-template.php';
        return $template;
      });
      // Apply template for user site.
      add_filter( 'template_include', function( $template ) {
        global $template;

        if( is_page() && is_page_template('keima-redirect-page.php') ) {
          $fileTemplate = KEIMA_REDIRECT_PAGE_DIR . 'includes/krp-page-template.php';
          if( file_exists($fileTemplate) ) {
            $template = $fileTemplate;
          }
        }
        return $template;
      }, 99 );
    }

    function deactivation () {
      if ( ! array_key_exists( 'advanced-custom-fields/acf.php', get_plugins() ) ) {
        add_action( 'admin_notices', function () {
          echo "<div class=\"error notice\"><p>";
          echo __( 'This plugin needs "Advanced Custom Fields" to run. Please <a href="https://www.advancedcustomfields.com" target="_blank">download</a> and activate it before.', 'keima-redirect-page' );
          echo "</p></div>";
        } );
      } else {
        if ( ! is_plugin_active( 'advanced-custom-fields/acf.php' ) ) {
          add_action( 'admin_notices', function () {
            echo "<div class=\"error notice\"><p>";
            echo __( 'This plugin needs "Advanced Custom Fields" to run. Please activate it before.', 'keima-redirect-page' );
            echo "</p></div>";
          } );
        }
      }

      if ( isset( $_GET['activate'] ) )
        unset( $_GET['activate'] );

      deactivate_plugins( plugin_basename( __FILE__ ) );
    }

  }

  function keima_redirect_page() {
    global $keima_redirect_page;

    // Instantiate only once.
    if ( ! isset( $keima_redirect_page ) ) {
      $keima_redirect_page = new KEIMA_REDIRECT_PAGE();
      $keima_redirect_page->initialize();
    }
    return $keima_redirect_page;
  }

  // Instantiate.
  keima_redirect_page();

endif; // class_exists check

