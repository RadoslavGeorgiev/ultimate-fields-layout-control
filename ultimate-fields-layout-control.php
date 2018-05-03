<?php
use Ultimate_Fields\Autoloader;
use Ultimate_Fields\Layout_Control\Layout_Control;

/**
 * Plugin name: Ultimate Fields: Layout Control
 * Author: Radoslav Georgiev
 * License: GPLv2
 */

add_action( 'uf.extend', 'uf_lc_extend' );
function uf_lc_extend() {
	new Autoloader( 'Ultimate_Fields\\Layout_Control', __DIR__ . '/classes' );
	new Layout_Control( __FILE__ );
}
