<?php
namespace Ultimate_Fields\Layout_Control;

use Ultimate_Fields\UI\Field_Helper as Base_Field_Helper;
use Ultimate_Fields\Field;
use Ultimate_Fields\Template;

/**
 * Handles the field in the UI.
 *
 * @since 1.0
 */
class Field_Helper extends Base_Field_Helper {
	/**
	 * Returns the title of the field, as displayed in the type dropdown.
	 *
	 * @since 1.0
	 *
	 * @return string
	 */
	public static function get_title() {
		return __( 'Layout Control', 'ultimate-fields' );
	}

	/**
	 * Returns the UI editor fields.
	 *
	 * @since 1.0
	 *
	 * @param Ultimate_Fields\Fields_Collection $existing The existing fields within the editor.
	 * @return mixed[] A combination of field arrays for the different tabs/sections.
	 */
	public static function get_fields() {
		$fields = array(
			Field::create( 'field_selector', 'layout_control_field', __( 'Field', 'uf-layout-control' ) )
				->set_description( __( 'Please select the field to control.', 'uf-layout-control' ) )
				->add_type( 'Repeater' )
				->add_type( 'Layout' )
				->add_type( 'Complex' ),
			Field::create( 'complex', 'layout_control_strings', __( 'Strings', 'uf-layout-control' ) )
				->merge()
				->add_fields([
					Field::create( 'text', 'layout_control_save_text', __( 'Save Text', 'uf-layout-control' ) )
					 	->set_width( 50 )
						->set_placeholder( __( 'Save Layout', 'uf-layout-control' ) ),
					Field::create( 'text', 'layout_control_load_text', __( 'Load Text', 'uf-layout-control' ) )
					 	->set_width( 50 )
						->set_placeholder( __( 'Load Layout', 'uf-layout-control' ) ),
					Field::create( 'text', 'layout_control_placeholder_text', __( 'Placeholder Text', 'uf-layout-control' ) )
					 	->set_width( 50 )
						->set_placeholder( __( 'Enter layout name...', 'uf-layout-control' ) ),
					Field::create( 'text', 'layout_control_dropdown_title', __( 'Dropdown Title', 'uf-layout-control' ) )
					 	->set_width( 50 )
						->set_placeholder( __( 'Select Layout', 'uf-layout-control' ) )
					])
		);

		return array(
			'general' => $fields
		);
	}

	/**
	 * Sets the field up.
	 *
	 * @since 1.0
	 *
	 * @return Field
	 */
	public function setup_field() {
		$field = parent::setup_field();
		return $field;
	}

	/**
	 * Imports some meta into the class.
	 *
	 * @since 1.0
	 *
	 * @param mixed[] $field_data The settings of the field.
	 */
	public function import( $field_data ) {
		parent::import( $field_data );
	}

	/**
	 * Prepares data for import.
	 *
	 * @since 1.0
	 *
	 * @param mixed[] $data The data that has been already generated + source data.
	 * @return mixed[]
	 */
	public static function prepare_field_data( $data ) {
		return $data;
	}

	/**
	 * Enqueues the scripts and templates for the field.
	 *
	 * @since 1.0
	 */
	public static function enqueue() {
		wp_enqueue_script( 'uf-field-layout-control' );
		wp_enqueue_style( 'uf-field-layout-control' );

		Template::add( 'layout-control', 'field/layout-control' );
		Template::add( 'layout-chooser', 'field/layout-chooser' );

		// ultimate_fields()->localize( 'repeater-basic-placeholder-multiple', __( 'Drag an item here to create a new entry.', 'ultimate-fields' ) );
	}
}
