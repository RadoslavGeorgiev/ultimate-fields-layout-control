<?php
namespace Ultimate_Fields\Layout_Control;

class Post_Type {
	public function __construct() {
		register_post_type( 'uf-layout', array(
			'ui'     => false,
			'public' => false
		));
	}
}
