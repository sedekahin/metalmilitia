<?php

// add pages to menu
add_menu_page( 'Digi Exit Pop', // title of page
               'Digi Exit Pop', // label in menu
               'manage_options', // permissions required
               DEP_OPTIONS_KEY, // slug
               'dep_options_page' ); // function to render page

// add settings link to plugin listing
add_filter( 'plugin_action_links', 'dep_add_action_link', 10, 2 );

function dep_add_action_link( $links, $file ) {
  if ( dirname( WP_PLUGIN_DIR . '/' . $file ) == dirname( __FILE__ ) ) {
    $settings_link = '<a href="' . DEP_ADMIN_URL . '">' . __( 'Settings' ) . '</a>';
    array_unshift( $links, $settings_link );
  }

  return $links;
}

register_setting( DEP_OPTIONS_KEY, DEP_OPTIONS_KEY );

function dep_options_page() {
  $values = get_option( DEP_OPTIONS_KEY, array() );
?>

<script type="text/javascript">
//<![CDATA[
  function digiexitpop_preview() {
    window.alert( jQuery( '#dep_message' ).val() );
  }

  jQuery( function() {
    jQuery( '#poststuff .inner-sidebar' )
      .keep_in_view( 20 )
      .append_sidebar_ad( 'digiexitpop' );
    jQuery( '<a class="preview button dep-preview-button">Preview</a>' )
      .click( digiexitpop_preview )
      .insertAfter( '#publishing-action' );
  } );
//]]>
</script>

<link rel="stylesheet" type="text/css"
      href="<?php echo DEP_PLUGIN_URL; ?>/styles/admin.css" />

<div class="wrap">
  <div id="icon-options-general" class="icon32"><br /></div>
  <h2>Digi Exit Pop &mdash; Options</h2>

  <?php if ( ( isset( $_GET[ 'updated' ] ) && $_GET[ 'updated' ] == 'true' )
          || ( isset( $_GET[ 'settings-updated' ] ) && $_GET[ 'settings-updated' ] == 'true' ) ) : ?>
    <div id="setting-error-settings_updated" class="updated settings-error">
      <p><strong>Settings saved.</strong></p>
    </div>
  <?php endif; ?>

  <form method="post" action="options.php">
    <?php settings_fields( DEP_OPTIONS_KEY ); ?>
    <?php do_settings_sections( DEP_OPTIONS_KEY ); ?>

    <div id="poststuff" class="metabox-holder has-right-sidebar">
      <div class="inner-sidebar">

        <div class="stuffbox">
          <h3>Save</h3>
          <div class="submitbox">
            <div id="major-publishing-actions">
              <div id="publishing-action">
                <input type="submit" class="button-primary" value="Save Settings" />
              </div>
              <div class="clear"></div>
            </div>
          </div>
        </div>

        <div class="stuffbox">
          <h3>Help</h3>
          <div class="submitbox">
            <div class="inside">
              <p><a href="http://www.digiexitpop.com/manual" target="_blank">
                Download the Digi Exit Pop manual</a></p>
              <p><a href="mailto:support@digiexitpop.com">
                Email our support team</a></p>
            </div>
          </div>
        </div>

      </div>
      <div id="post-body">
        <div id="post-body-content">

          <div class="stuffbox">
            <h3>Default exit popup settings</h3>
            <div class="inside">
              <table class="form-table" style="width: 100%;" cellspacing="2" cellpadding="5">
                <tr class="form-field">
                  <th valign="top" scope="row" style="padding-top: 14px;">
                    Enable
                  </th>
                  <td>
                    <input type="checkbox" id="dep_active" name="digiexitpop_options[active]"
                           <?php if ( $values[ 'active' ] ) echo 'checked="checked"'; ?>
                           style="width: auto;" />
                    <label for="dep_active">Show an exit popup by default on every page of my site</label>
                  </td>
                </tr>
                <tr class="form-field">
                  <th valign="top" scope="row" style="padding-top: 14px;">
                    <label for="dep_message">Message</label>
                  </th>
                  <td>
                    <textarea name="digiexitpop_options[message]" id="dep_message" style="width:98%;" rows="7"
                      ><?php echo htmlspecialchars( $values[ 'message' ] ); ?></textarea>
                  </td>
                </tr>
                <tr class="form-field">
                  <th valign="top" scope="row" style="padding-top: 14px;">
                    <label for="dep_url">Redirect URL</label>
                  </th>
                  <td>
                    <input type="text" name="digiexitpop_options[url]" id="dep_url" style="width:98%;"
                           value="<?php echo htmlspecialchars( $values[ 'url' ] ); ?>" />
                  </td>
                </tr>
              </table>
            </div>
          </div>

        </div>
      </div>
    </div>
  </form>
</div>
<?php
}

// meta boxes for post and page edit screens
add_action( 'add_meta_boxes', 'dep_add_meta_box' );
add_action( 'save_post', 'dep_save_postdata' );

function dep_add_meta_box() {
  add_meta_box( 'digiexitpop', 'Digi Exit Pop', 'dep_meta_box', 'post', 'normal', 'high' );
  add_meta_box( 'digiexitpop', 'Digi Exit Pop', 'dep_meta_box', 'page', 'normal', 'high' );
}

function dep_meta_box( $post ) {
  wp_nonce_field( plugin_basename( __FILE__ ), 'digiexitpop_nonce' );

  $values = get_post_meta( $post->ID, DEP_OPTIONS_KEY, true );
  if ( empty( $values ) ) $values = array( 'active' => 'default', 'message' => '', 'url' => 'http://' );
?>
<script type="text/javascript">
//<![CDATA[
  function digiexitpop_preview() {
    window.alert( jQuery( '#dep_message' ).val() );
  }

  function dep_toggle_meta_fields() {
    var method = ( jQuery( this ).val() == 'custom' ? 'removeClass' : 'addClass' );
    jQuery( '#dep-meta-form-table tr + tr, #dep-meta-preview-button' )[ method ]( 'hidden' );
  }

  jQuery( function() {
    var container = jQuery( '<div id="dep-meta-preview-button"></div>' )
                      .insertAfter( '#dep-meta-form-table' );
    jQuery( '<a class="preview button dep-preview-button">Preview</a>' )
      .click( digiexitpop_preview )
      .appendTo( container );


    jQuery( '#dep_active' ).change( dep_toggle_meta_fields ).change();
  } );
//]]>
</script>

<link rel="stylesheet" type="text/css"
      href="<?php echo DEP_PLUGIN_URL; ?>/styles/admin.css" />

<table id="dep-meta-form-table" class="form-table" style="width: 100%;" cellspacing="2" cellpadding="5">
  <tr class="form-field">
    <th valign="top" scope="row" style="padding-top: 14px;">
      <label for="dep_active">Enable</label>
    </th>
    <td>
      <select id="dep_active" name="digiexitpop_options[active]">
        <?php
          $options = array(
            'Use default settings' => 'default',
            'Turned off' => 'off',
            'Turned on' => 'on',
            'Customised exit popup' => 'custom'
          );
          foreach ( $options as $k => $v ) {
            echo "<option value=\"${v}\"";
            if ( $values[ 'active' ] == $v ) echo ' selected="selected"';
            echo ">${k}</option>";
          }
        ?>
      </select>
    </td>
  </tr>
  <tr class="form-field">
    <th valign="top" scope="row" style="padding-top: 14px;">
      <label for="dep_message">Message</label>
    </th>
    <td>
      <textarea name="digiexitpop_options[message]" id="dep_message" style="width:98%;" rows="7"
        ><?php echo htmlspecialchars( $values[ 'message' ] ); ?></textarea>
    </td>
  </tr>
  <tr class="form-field">
    <th valign="top" scope="row" style="padding-top: 14px;">
      <label for="dep_url">Redirect URL</label>
    </th>
    <td>
      <input type="text" name="digiexitpop_options[url]" id="dep_url" style="width:98%;"
             value="<?php echo htmlspecialchars( $values[ 'url' ] ); ?>" />
    </td>
  </tr>
</table>
<?php
}

function dep_save_postdata( $post_id ) {
  // check the nonce
  if ( !isset( $_POST[ 'digiexitpop_nonce' ] ) )
    return $post_id;
  if ( !wp_verify_nonce( $_POST[ 'digiexitpop_nonce' ], plugin_basename( __FILE__ ) ) )
    return $post_id;

  // check this isn't an autosave
  if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
    return $post_id;

  // check the current user can edit this post
  if ( !current_user_can( "edit_{$_POST[ 'post_type' ]}", $post_id ) )
    return $post_id;

  // finally, update the meta
  update_post_meta( $post_id, DEP_OPTIONS_KEY, $_POST[ DEP_OPTIONS_KEY ] );

  return $post_id;
}
