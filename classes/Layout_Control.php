<?php
namespace Ultimate_Fields\Layout_Control;

use Ultimate_Fields\Template;

/**
 * A base class for the extension, which adds and overwrites all necessary classes.
 *
 * @since 1.0
 */
class Layout_Control {
	/**
	 * Holds the path of the plugin file for the layout control in order to load assets properly.
	 *
	 * @since 1.0
	 * @var string
	 */
	protected $plugin_file;

	/**
	 * The version of the plugin, used for assets.
	 *
	 * @since 1.0
	 * @var string
	 */
	protected $version;

	/**
	 * Class constructor, instantiates all necessary functionality.
	 *
	 * @since 1.0
	 * @param string $plugin_file The path to the main plugin file.
	 * @param string $version     A version to be used for assets and etc.
	 */
	public function __construct( $plugin_file, $version ) {
		$this->plugin_file = $plugin_file;
		$this->version     = $version;

		Post_Type::instance();
		Template::instance()->add_path( dirname( $plugin_file ) . '/templates/' );

		add_action( 'uf.ui.fields', array( $this, 'register_fields' ) );
		add_filter( 'uf.field.class', array( $this, 'generate_field_class' ), 10, 2 );
		add_action( 'uf.register_scripts', array( $this, 'register_scripts' ) );
	}

	/**
	 * Allows the class that should be used for a field to be generated.
	 *
	 * @since 1.0
	 *
	 * @param string $class_name The class name that would be used for the field.
	 * @param string $type       The expected field type (ex. `text`).
	 * @return string
	 */
	public function generate_field_class( $class_name, $type ) {
		if( 'layout_control' === strtolower( $type ) ) {
			return Field::class;
		} else {
			return $class_name;
		}
	}

	/**
	 * Registers all fields within the editor in the interface.
	 *
	 * @since 1.0
	 *
	 * @param Ultimate_Fields\UI\Field_Editor $editor The editor, which handles fields.
	 */
	public function register_fields( $editor ) {
		$editor->add_type( 'advanced', 'Layout_Control', Field_Helper::class );
	}

	/**
	 * Registers the necessary assets.
	 *
	 * @since 1.0
	 */
	public function register_scripts() {
		$assets = plugins_url( 'assets/', $this->plugin_file );
		$v      = $this->version;

		wp_register_script( 'uf-field-layout-control', $assets . 'field-layout-control.js', array( 'uf-field' ), $v );
		wp_register_style( 'uf-field-layout-control', $assets . 'layout-control.css', array( 'ultimate-fields-css' ), $v );
	}
}
