<?php
namespace Ultimate_Fields\Layout_Control;

class Layout_Control {
	protected $plugin_file;

	public function __construct( $plugin_file ) {
		$this->plugin_file = $plugin_file;

		new Post_Type;

		add_action( 'uf.ui.fields', array( $this, 'register_fields' ) );
		add_filter( 'uf.field.class', array( $this, 'generate_field_class' ), 10, 2 );
		add_action( 'uf.register_scripts', array( $this, 'register_scripts' ) );
	}

	/**
	 * Allows the class that should be used for a field to be generated.
	 *
	 * @since 3.0
	 *
	 * @param string $class_name The class name that would be used for the field.
	 * @param string $type       The expected field type (ex. `text`).
	 * @return string
	 */
	public function generate_field_class( $class_name, $type ) {
		if( 'layout_control' === $type ) {
			return Field::class;
		} else {
			return $class_name;
		}
	}

	/**
	 * Registers all fields within the editor in the interface.
	 *
	 * @since 3.0
	 *
	 * @param Ultimate_Fields\UI\Field_Editor $editor The editor, which handles fields.
	 */
	public function register_fields( $editor ) {
		$editor->add_type( 'advanced', 'Layout_Control', Field_Helper::class );
	}

	public function register_scripts() {
		wp_register_script( 'uf-field-layout-control', plugins_url( 'assets/field-layout-control.js', $this->plugin_file ), array( 'uf-field' ), '3.0' );

	}
}
