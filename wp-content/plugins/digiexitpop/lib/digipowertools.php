<?php

if ( !class_exists( 'DigiPowerTools' ) ) {
  class DigiPowerTools {
    public static $methods = array();

    // add a method definition, or replace an older version
    public static function add_method( $method_name, $callback, $version ) {
      if ( !isset( self::$methods[ $method_name ] ) || self::$methods[ $method_name ][ 'version' ] < $version )
        self::$methods[ $method_name ] = array( 'callback' => $callback, 'version' => $version );
    }

    public function __call( $method_name, $args = array() ) {
      return call_user_func_array( self::$methods[ $method_name ][ 'callback' ], $args );
    }
  }
}

if ( !function_exists( 'dpt_fetch_sidebar_ad_v1' ) ) {
  function dpt_fetch_sidebar_ad_v1( $plugin_name ) {
    // bail if the needed functions aren't available
    if ( !function_exists( 'json_decode' ) || !ini_get( 'allow_url_fopen' ) ) return false;

    // try to fetch the ad list from the server
    $url = 'http://www.digipowertools.com/adserver/sidebar/' . strtolower( $plugin_name ) . '.php';
    $raw = @file_get_contents( $url );
    if ( $raw === false ) return false;

    // try to decode the server response
    $ads = json_decode( $raw, true );
    if ( $ads === null ) return false;

    // ignore ads for already installed plugins
    foreach ( get_plugins() as $dir => $plugin ) {
      $slug = substr( $dir, 0, stripos( $dir, '/' ) );
      unset( $ads[ $slug ] );
    }
    if ( empty( $ads ) ) return false;

    // take the first ad left
    return reset( $ads );
  }

  DigiPowerTools::add_method( 'fetch_sidebar_ad', 'dpt_fetch_sidebar_ad_v1', 1 );
}

if ( !function_exists( 'dpt_handle_sidebar_ad_ajax_request' ) ) {
  function dpt_handle_sidebar_ad_ajax_request() {
    if ( isset( $_GET[ 'plugin' ] ) ) {
      header( 'Content-type: application/json' );

      $dpt = new DigiPowerTools();
      $ad = $dpt->fetch_sidebar_ad( $_GET[ 'plugin' ] );

      if ( $ad !== false ) echo json_encode( $ad );
    }

    die();
  }

  add_action( 'wp_ajax_dpt_sidebar_ad', 'dpt_handle_sidebar_ad_ajax_request' );
}

if ( !function_exists( 'dpt_enqueue_script_v1' ) ) {
  function dpt_enqueue_script_v1() {
    if ( is_admin() ) {
      $js_url = plugins_url( 'lib/digipowertools.js', dirname( __FILE__ ) );
      wp_enqueue_script( 'digipowertools', $js_url, array( 'jquery' ) );
    }
  }

  DigiPowerTools::add_method( 'enqueue_script', 'dpt_enqueue_script_v1', 1 );
}

if ( !function_exists( 'dpt_enqueue_script_action' ) ) {
  function dpt_enqueue_script_action() {
    $dpt = new DigiPowerTools();
    $dpt->enqueue_script();
  }

  add_action( 'init', 'dpt_enqueue_script_action' );
}
