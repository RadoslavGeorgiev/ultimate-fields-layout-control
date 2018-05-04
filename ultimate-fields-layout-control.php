<?php
use Ultimate_Fields\Autoloader;
use Ultimate_Fields\Layout_Control\Layout_Control;

/**
 * Plugin name: Ultimate Fields: Layout Control
 * Author: Radoslav Georgiev
 * License: GPLv2
 * Description: This plugin allows the values of repeatable fields (repeater & layout) to be saved as layout and reused later.
 * Version: 1.0
 */

/**
 * Extends Ultimate Fields with the layout field.
 *
 * @since 1.0
 */
add_action( 'uf.extend', 'uf_lc_extend' );
function uf_lc_extend() {
	// Use the Ultimate Fields autoloader for the layout control
	new Autoloader( 'Ultimate_Fields\\Layout_Control', __DIR__ . '/classes' );

	// Let the base class add all necessary hooks
	new Layout_Control( __FILE__, '1.0' );
}
