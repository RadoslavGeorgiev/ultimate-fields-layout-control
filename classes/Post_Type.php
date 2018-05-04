<?php
namespace Ultimate_Fields\Layout_Control;

/**
 * Handles the actions, related to the post type where layouts will be saved.
 *
 * @since 1.0
 */
class Post_Type {
	/**
	 * The slug for the post type.
	 *
	 * @since 1.0
	 * @var string
	 */
	protected $slug;

	/**
	 * Generates an instance of the class if one does not exist.
	 *
	 * @since 1.0
	 */
	public static function instance() {
		static $instance;

		if( is_null( $instance ) ) {
			$instance = new self;
		}

		return $instance;
	}

	/**
	 * Registers a private post type.
	 *
	 * @since 1.0
	 */
	private function __construct() {
		register_post_type( $this->get_slug(), array(
			'show_ui' => false,
			'public'  => false
		));
	}

	/**
	 * Returns the slug of the post type.
	 *
	 * @since 1.0
	 *
	 * @return string
	 */
	public function get_slug() {
		if( ! is_null( $this->slug ) ) {
			return $this->slug;
		}

		/**
		 * Allows the slug, used for layout control to be overwritten.
		 *
		 * @since 1.0
		 *
		 * @param string $slug The slug to change.
		 * @return string
		 */
		return $this->slug = apply_filters( 'uf.layout_control.slug', 'uf-layout' );
	}

	/**
	 * Saves a layout as a post.
	 *
	 * @since 1.0
	 *
	 * @param string $name  The name (title) to use for the layout.
	 * @param array  $data  The repeatable data that should be saved.
	 * @param string $field The name of the field which the save works for.
	 */
	public function save( $name, $data, $field ) {
		$layout_id = wp_insert_post(array(
			'post_type'    => $this->get_slug(),
			'post_status'  => 'publish',
			'post_title'   => esc_html( $name ),
			'post_content' => ' '
		));

		update_post_meta( $layout_id, '_layout', $data );
		update_post_meta( $layout_id, '_field', $field );

		return $layout_id;
	}

	/**
	 * Returns all available layouts for a specific field.
	 *
	 * @since 1.0
	 *
	 * @param string $field The name of the field the layouts should work for.
	 * @return array
	 */
	public function get_all( $field ) {
		$raw = get_posts(array(
			'post_type'      => $this->get_slug(),
			'order'          => 'ASC',
			'orderby'        => 'post_title',
			'posts_per_page' => -1,
			'meta_key'       => '_field',
			'meta_value'     => $field
		));

		$layouts = array();

		foreach( $raw as $post ) {
			$layouts[] = array(
				'id'      => $post->ID,
				'name'    => $post->post_title,
				'content' => get_post_meta( $post->ID, '_layout', true )
			);
		}

		return $layouts;
	}

	/**
	 * Deletes a layout.
	 *
	 * @since 1.0
	 *
	 * @param mixed  $id    The ID of the layout to delete.
	 * @param string $field The name of the field the layout belongs to.
	 */
	public function delete( $id, $field ) {
		$existing = get_post( $id );

		if( $existing ) {
			if( $this->get_slug() == $existing->post_type ) {
				if( $field == get_post_meta( $existing->ID, '_field', true ) ) {
					wp_delete_post( $existing->ID, true );
					return true;
				}
			}
		}

		return false;
	}
}
