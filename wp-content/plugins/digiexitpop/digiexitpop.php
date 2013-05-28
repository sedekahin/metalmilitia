<?php
/*
Plugin Name: Digi Exit Pop
Plugin URI: http://www.digiexitpop.com/
Description: Digi Exit Pop lets you add exit popups to your pages to rescue lost traffic and increase your conversions.
Version: 1.0.1
Author: DigiResults
Author URI: http://www.digiresults.com/
*/

define( 'DEP_OPTIONS_KEY', 'digiexitpop_options' );
define( 'DEP_ADMIN_URL', admin_url( 'admin.php?page=' . DEP_OPTIONS_KEY ) );
define( 'DEP_PLUGIN_BASE', substr( plugin_basename( __FILE__ ), 0, strpos( plugin_basename( __FILE__ ), '/' ) ) );
define( 'DEP_PLUGIN_URL', WP_PLUGIN_URL . '/' . DEP_PLUGIN_BASE );

include 'lib/digipowertools.php';

// make sure the default config is patched to current
dep_update_default_config();

function dep_update_default_config( $override = array() ) {
  $defaults = array_merge( array(
    'active' => false,
    'message' => "Are you sure you want to leave?\n\nStay on this site for a great offer…",
    'url' => 'http://'
  ), $override );

  $config = get_option( DEP_OPTIONS_KEY, array() );
  update_option( DEP_OPTIONS_KEY, array_merge( $defaults, $config ) );
}

// admin pages
add_action( 'admin_menu', create_function( '', "require( 'dep-admin.php' );" ) );

// register the script for later queuing
add_action( 'init', 'dep_echo_javascript' );
function dep_echo_javascript() {
  wp_enqueue_script( 'digiexitpop', DEP_PLUGIN_URL . '/exitpop.js', array( 'jquery' ) );
}

// echo out the data for the exit popup
add_action( 'wp_head', 'dep_echo_meta_tags' );
function dep_echo_meta_tags() {
  $options = get_option( DEP_OPTIONS_KEY );

  // check override settings for individual posts/pages
  global $post;
  if ( is_single() ) {
    $meta = get_post_meta( $post->ID, DEP_OPTIONS_KEY, true );
    if ( !is_array( $meta ) ) $meta = array( 'active' => 'default' );

    switch ( $meta[ 'active' ] ) {
      case 'custom' :
        $options = $meta;
        $options[ 'active' ] = 'on';
        break;
      case 'on' :
      case 'off' :
        $options[ 'active' ] = $meta[ 'active' ];
        break;
    }
  }

  // if exit popup is active output the message and url
  if ( $options[ 'active' ] === 'on' ) {
    $message = htmlspecialchars( $options[ 'message' ] );
    echo "<meta property=\"exitpopup:message\" content=\"{$message}\" />";

    $url = htmlspecialchars( $options[ 'url' ] );
    echo "<meta property=\"exitpopup:url\" content=\"{$url}\" />";
  }
}
