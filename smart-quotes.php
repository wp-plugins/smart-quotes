<?php
/*
Plugin Name: Smart Quotes
Plugin URI: http://ten-fingers-and-a-brain.com/wordpress-plugins/smart-quotes/
Version: 0.4
Description: Change the quotation marks, that are automatically rendered as smart or curly quotes inside your content, from the default English style (&#8220;&#8230;&#8221;) to anything you like, e.g. to Croatian/Hungarian/Polish/Romanian style quotation marks (&#8222;&#8230;&#8221;), Czech or German style (&#8222;&#8230;&#8220;), Danish style (&#187;&#8230;&#171;), Finnish or Swedish style (&#8221;&#8230;&#8221;), French style (&#171;&nbsp;&#8230;&nbsp;&#187; &ndash; with spaces), Greek/Italian/Norwegian/Portuguese/Russian/Spanish/Swiss style (&#171;&#8230;&#187; &ndash; without spaces), Japanese or Traditional Chinese style (&#12300;&#8943;&#12301;), or actually to any arbitrary character combination of your choice. Of course you can turn off curly quotes entirely by picking the so-called &quot;dumb&quot; quotes (&quot;&#8230;&quot;).
Author: Martin Lormes
Author URI: http://ten-fingers-and-a-brain.com/
Text Domain: smart-quotes
*/
/*
Copyright (c) 2011-2013 Martin Lormes

This program is free software; you can redistribute it and/or modify it under 
the terms of the GNU General Public License as published by the Free Software 
Foundation; either version 3 of the License, or (at your option) any later 
version.

This program is distributed in the hope that it will be useful, but WITHOUT 
ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS 
FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with 
this program. If not, see <http://www.gnu.org/licenses/>.
*/
/** Smart Quotes (WordPress Plugin) */

/**
 * get out of the symlink dilemma with __FILE__
 *
 * thanks for inspiration to:
 * - http://alexking.org/blog/2011/12/15/wordpress-plugins-and-symlinks
 * - https://gist.github.com/1482350
 *
 * @since 0.4
 */
define( 'TFNAB_SMART_QUOTES_FILE', ( isset( $plugin ) ) ? $plugin : ( ( isset( $mu_plugin ) ) ? $mu_plugin : ( ( isset( $network_plugin ) ) ? $network_plugin : __FILE__ ) ) );

/**
 * get smart quotes chosen by user from options
 *
 * @since 0.2
 */
function tfnab_smart_quotes_get_option ()
{
  static $quote1, $quote2;
  static $quotes_set = false;
  if ( empty( $quote1 ) )
  {
    $quotes_set = ( false !== ( $option = get_option( 'smart-quotes' ) ) );
    $quote1 = ( is_array( $option ) && isset( $option['opening'] ) ) ? trim( $option['opening'] ) : '&#8220;';
    $quote2 = ( is_array( $option ) && isset( $option['closing'] ) ) ? trim( $option['closing'] ) : '&#8221;';
  }
  return array( $quote1, $quote2, $quotes_set );
} // function tfnab_smart_quotes_get_option

/** return user-defined smart quotes instead of those set in the language file */
function tfnab_smart_quotes_gettext_with_context ( $s, $original, $context, $domain )
{
  if ( 'default' != $domain ) return $s;
  list( $quote1, $quote2, $quotes_set ) = tfnab_smart_quotes_get_option();
  if ( !$quotes_set ) return $s;
  if ( ( 'opening curly double quote' == $context || 'opening curly quote' == $context ) && '&#8220;' == $original ) return str_replace( array( '<', '>' ), array( '&lt;', '&gt;' ), $quote1 );
  if ( ( 'closing curly double quote' == $context || 'closing curly quote' == $context ) && '&#8221;' == $original ) return str_replace( array( '<', '>' ), array( '&lt;', '&gt;' ), $quote2 );
  return $s;
} // function tfnab_smart_quotes_gettext_with_context
add_filter( 'gettext_with_context', 'tfnab_smart_quotes_gettext_with_context', 10, 4 );

/**
 * insert styles for the <q> element in head
 *
 * @since 0.2
 */
function tfnab_smart_quotes_wp_head ()
{
  list( $quote1, $quote2, $quotes_set ) = tfnab_smart_quotes_get_option();
  if ( $quotes_set ) :
  ?>
  <style type="text/css">/*<![CDATA[*/
    q:before {
      content: "<?php echo str_replace( '"', '\\"', $quote1 ); ?>";
    }
    q:after {
      content: "<?php echo str_replace( '"', '\\"', $quote2 ); ?>";
    }
  /*]]>*/</style>
  <?php
  endif;
} // function tfnab_smart_quotes_wp_head
add_action( 'wp_head', 'tfnab_smart_quotes_wp_head' );

// only run the rest of the plugin if we're in the WordPress admin
if ( is_admin() )
{
  // i18n/L10n
  load_plugin_textdomain ( 'smart-quotes', '', basename ( dirname ( __FILE__ ) ) );
  
  /** Smart Quotes (WordPress Plugin) functions wrapped in a class. (namespacing pre PHP 5.3) */
  class tfnab_smart_quotes
  {
    
    /**
     *
     */
    function settings_field ()
    {
      $option = get_option( 'smart-quotes' );
      $quote1 = ( is_array( $option ) && isset( $option['opening'] ) ) ? trim( $option['opening'] ) : '&#8220;';
      $quote2 = ( is_array( $option ) && isset( $option['closing'] ) ) ? trim( $option['closing'] ) : '&#8221;';
      ?>
      <input type="text" name="smart-quotes[opening]" id="smart-quotes-opening" class="small-text" value="<?php esc_attr_e( $quote1 ); ?>"/>
      &#8943;
      <input type="text" name="smart-quotes[closing]" id="smart-quotes-closing" class="small-text" value="<?php esc_attr_e( $quote2 ); ?>"/>
      <br/><span class="description">
      <?php _e( 'Enter any characters you would like to use for smart quotes; click on any of the examples below to use it (hover for more information)', 'smart-quotes' ); ?>
      </span>
      <script type="text/javascript">
        function set_smart_quotes(q1,q2)
        {
          document.getElementById('smart-quotes-opening').value=q1;
          document.getElementById('smart-quotes-closing').value=q2;
        }
      </script>
      <ul id="smart-quotes-examples">
        <li><a href="#" onclick="set_smart_quotes('&#8220;','&#8221;');return false;" title="<?php _e( 'English, WordPress default', 'smart-quotes' ); ?>">&#8220;&#8230;&#8221;</a></li>
        <li><a href="#" onclick="set_smart_quotes('&#8222;','&#8221;');return false;" title="<?php _e( 'Croatian, Hungarian, Polish, Romanian', 'smart-quotes' ); ?>">&#8222;&#8230;&#8221;</a></li>
        <li><a href="#" onclick="set_smart_quotes('&#8222;','&#8220;');return false;" title="<?php _e( 'Czech, German', 'smart-quotes' ); ?>">&#8222;&#8230;&#8220;</a></li>
        <li><a href="#" onclick="set_smart_quotes('&#187;','&#171;');return false;" title="<?php _e( 'Danish', 'smart-quotes' ); ?>">&#187;&#8230;&#171;</a></li>
        <li><a href="#" onclick="set_smart_quotes('&#8221;','&#8221;');return false;" title="<?php _e( 'Finnish, Swedish', 'smart-quotes' ); ?>">&#8221;&#8230;&#8221;</a></li>
        <li><a href="#" onclick="set_smart_quotes('&#171;&nbsp;','&nbsp;&#187;');return false;" title="<?php _e( 'French (with spaces)', 'smart-quotes' ); ?>">&#171;&nbsp;&#8230;&nbsp;&#187;</a></li>
        <li><a href="#" onclick="set_smart_quotes('&#171;','&#187;');return false;" title="<?php _e( 'Greek, Italian, Norwegian, Portuguese, Russian, Spanish, Swiss (without spaces)', 'smart-quotes' ); ?>">&#171;&#8230;&#187;</a></li>
        <li><a href="#" onclick="set_smart_quotes('&#12300;','&#12301;');return false;" title="<?php _e( 'Japanese, Traditional Chinese', 'smart-quotes' ); ?>">&#12300;&#8943;&#12301;</a></li>
        <li><a href="#" onclick="set_smart_quotes('&quot;','&quot;');return false;" title="<?php _e( '&quot;dumb quotes&quot;', 'smart-quotes' ); ?>">&quot;&#8230;&quot;</a></li>
      </ul>
      <?php
    } // function settings_field
    
    /**
     *
     */
    function sanitize ( $setting )
    {
      return array(
        'opening' => ( is_array( $setting ) && isset( $setting['opening'] ) ) ? trim( $setting['opening'] ) : '',
        'closing' => ( is_array( $setting ) && isset( $setting['closing'] ) ) ? trim( $setting['closing'] ) : '',
      );
    } // function sanitize
    
    /**
     *
     */
    function admin_init ()
    {
      add_settings_field( 'smart-quotes', __( 'Smart Quotes', 'smart-quotes' ), array( 'tfnab_smart_quotes', 'settings_field' ), 'writing' );
      register_setting( 'writing', 'smart-quotes', array( 'tfnab_smart_quotes', 'sanitize' ) );
      add_action( 'admin_print_styles-options-writing.php', array( 'tfnab_smart_quotes', 'admin_print_styles__options__writing' ) );
      add_filter ( 'plugin_action_links_' . plugin_basename ( TFNAB_SMART_QUOTES_FILE ), array ( 'tfnab_smart_quotes', 'plugin_action_links' ) );
    } // function admin_init
    
    /**
     *
     */
    function admin_print_styles__options__writing ()
    {
      wp_register_style( 'smart-quotes-stylesheet', plugins_url( 'smart-quotes.css', TFNAB_SMART_QUOTES_FILE ), false, '0.1' );
      wp_enqueue_style( 'smart-quotes-stylesheet' );
    } // function admin_print_styles__options__writing
    
    /**
     * hooked to {@link http://codex.wordpress.org/Plugin_API/Filter_Reference WordPress filter}: {@link http://codex.wordpress.org/Plugin_API/Filter_Reference/plugin_action_links plugin_action_links}
     * @since 0.2
     */
    function plugin_action_links ( $links )
    {
      $links[] = sprintf ( '<a href="options-writing.php">%s</a>', __( 'Settings' ) ); // 'Settings' is in the default domain!
      return $links;
    } // function plugin_action_links
    
  } // class tfnab_smart_quotes
  
  // GO!
  add_action( 'admin_init', array( 'tfnab_smart_quotes', 'admin_init' ) );
}
