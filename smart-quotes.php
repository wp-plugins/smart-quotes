<?php
/*
Plugin Name: Smart Quotes
Plugin URI: http://ten-fingers-and-a-brain.com/wordpress-plugins/smart-quotes/
Version: 0.2
Description: Change the quotation marks, that are automatically rendered as smart or curly quotes inside your content, from the default English style (&#8220;&#8230;&#8221;) to anything you like, e.g. to Croatian/Hungarian/Polish/Romanian style quotation marks (&#8222;&#8230;&#8221;), Czech or German style (&#8222;&#8230;&#8220;), Danish style (&#187;&#8230;&#171;), Finnish or Swedish style (&#8221;&#8230;&#8221;), French style (&#171;&nbsp;&#8230;&nbsp;&#187; &ndash; with spaces), Greek/Italian/Norwegian/Potuguese/Russian/Spanish/Swiss style (&#171;&#8230;&#187; &ndash; without spaces), Japanese or Traditional Chinese style (&#12300;&#8943;&#12301;), or actually to any arbitrary character combination of your choice. Of course you can turn off curly quotes entirely by picking the so-called &quot;dumb&quot; quotes (&quot;&#8230;&quot;).
Author: Martin Lormes
Author URI: http://ten-fingers-and-a-brain.com/
Text Domain: smart-quotes
*/
/*
Copyright (c) 2011-2012 Martin Lormes

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
 * get smart quotes chosen by user from options
 *
 * @since 0.2
 */
function tfnab_smart_quotes_get_option ()
{
  static $quote1, $quote2;
  if ( empty( $quote1 ) )
  {
    $option = get_option( 'smart-quotes' );
    $quote1 = ( is_array( $option ) && isset( $option['opening'] ) ) ? trim( $option['opening'] ) : '&#8220;';
    $quote2 = ( is_array( $option ) && isset( $option['closing'] ) ) ? trim( $option['closing'] ) : '&#8221;';
  }
  return array( $quote1, $quote2 );
} // function tfnab_smart_quotes_get_option

/** return user-defined smart quotes instead of those set in the language file */
function tfnab_smart_quotes_gettext_with_context ( $s, $original, $context, $domain )
{
  if ( 'default' != $domain ) return $s;
  list( $quote1, $quote2 ) = tfnab_smart_quotes_get_option();
  if ( 'opening curly quote' == $context && '&#8220;' == $original ) return str_replace( array( '<', '>' ), array( '&lt;', '&gt;' ), $quote1 );
  if ( 'closing curly quote' == $context && '&#8221;' == $original ) return str_replace( array( '<', '>' ), array( '&lt;', '&gt;' ), $quote2 );
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
  list( $quote1, $quote2 ) = tfnab_smart_quotes_get_option();
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
} // function tfnab_smart_quotes_wp_head
add_action( 'wp_head', 'tfnab_smart_quotes_wp_head' );

// only run the rest of the plugin if we're in the WordPress admin
if ( is_admin() )
{
  // i18n/l10n
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
      Enter any characters <!-- or HTML entities --> you would like to use for smart quotes; click on any of the examples below to use it (hover for more information)
      </span>
      <script type="text/javascript">
        function set_smart_quotes(q1,q2)
        {
          document.getElementById('smart-quotes-opening').value=q1;
          document.getElementById('smart-quotes-closing').value=q2;
        }
      </script>
      <ul id="smart-quotes-examples">
        <li><a href="#" onclick="set_smart_quotes('&#8220;','&#8221;');return false;" title="English, WordPress default">&#8220;&#8230;&#8221;</a></li>
        <li><a href="#" onclick="set_smart_quotes('&#8222;','&#8221;');return false;" title="Croatian, Hungarian, Polish, Romanian">&#8222;&#8230;&#8221;</a></li>
        <li><a href="#" onclick="set_smart_quotes('&#8222;','&#8220;');return false;" title="Czech, German">&#8222;&#8230;&#8220;</a></li>
        <li><a href="#" onclick="set_smart_quotes('&#187;','&#171;');return false;" title="Danish">&#187;&#8230;&#171;</a></li>
        <li><a href="#" onclick="set_smart_quotes('&#8221;','&#8221;');return false;" title="Finnish, Swedish">&#8221;&#8230;&#8221;</a></li>
        <li><a href="#" onclick="set_smart_quotes('&#171;&nbsp;','&nbsp;&#187;');return false;" title="French (with spaces)">&#171;&nbsp;&#8230;&nbsp;&#187;</a></li>
        <li><a href="#" onclick="set_smart_quotes('&#171;','&#187;');return false;" title="Greek, Italian, Norwegian, Potuguese, Russian, Spanish, Swiss (without spaces)">&#171;&#8230;&#187;</a></li>
        <li><a href="#" onclick="set_smart_quotes('&#12300;','&#12301;');return false;" title="Japanese, Traditional Chinese">&#12300;&#8943;&#12301;</a></li>
        <li><a href="#" onclick="set_smart_quotes('&quot;','&quot;');return false;" title="&quot;dumb quotes&quot;">&quot;&#8230;&quot;</a></li>
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
      add_filter ( 'plugin_action_links_' . plugin_basename ( __FILE__ ), array ( 'tfnab_smart_quotes', 'plugin_action_links' ) );
    } // function admin_init
    
    /**
     *
     */
    function admin_print_styles__options__writing ()
    {
      wp_register_style( 'smart-quotes-stylesheet', plugins_url( 'smart-quotes.css', __FILE__ ), false, '0.1' );
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
    }
    
  } // class tfnab_smart_quotes
  
  // GO!
  add_action( 'admin_init', array( 'tfnab_smart_quotes', 'admin_init' ) );
}
