<?php
namespace Ultimate_Fields\Layout_Control;

use Ultimate_Fields\Field as Base_Field;
use Ultimate_Fields\Template;

/**
 * Handles the display of the field, which will control the layout.
 *
 * @since 1.0
 */
class Field extends Base_Field {
	/**
	 * The name of the field that is to be controlled.
	 *
	 * @since 1.0
	 * @var string
	 */
	protected $field;

	/**
	 * Holds the text, which will be used for the "Save Layout" button.
	 *
	 * @since 1.1
	 * @var string
	 */
	protected $save_text;

	/**
	 * Holds the text, which will be used for the "Load Layout" button.
	 *
	 * @since 1.1
	 * @var string
	 */
	protected $load_text;

	/**
	 * Holds the placeholder text for the "Enter layout name..." field.
	 *
	 * @since 1.1
	 * @var string
	 */
	protected $placeholder_text;

	/**
	 * Holds the text for the header of the "Select Layout" dropdown.
	 *
	 * @since 1.1
	 * @var string
	 */
	protected $dropdown_title;

	/**
	 * Enqueues all scripts and templates, needed for the field.
	 *
	 * @since 1.0
	 */
	public function enqueue_scripts() {
		wp_enqueue_script( 'uf-field-layout-control' );
		wp_enqueue_style( 'uf-field-layout-control' );

		Template::add( 'layout-control', 'field/layout-control' );
		Template::add( 'layout-chooser', 'field/layout-chooser' );
	}

	/**
	 * Exports the settings of the field for usage in JavaScript.
	 *
	 * @since 1.0
	 *
	 * @return array
	 */
	public function export_field() {
		$data = parent::export_field();

		$save_text = $this->save_text
			? $this->save_text
			: __( 'Save Layout', 'uf-layout-control' );

		$load_text = $this->load_text
			? $this->load_text
			: __( 'Load Layout', 'uf-layout-control' );

		$placeholder_text = $this->placeholder_text
			? $this->placeholder_text
			: __( 'Enter layout name...', 'uf-layout-control' );

		$dropdown_title = $this->dropdown_title
			? $this->dropdown_title
			: __( 'Select Layout', 'uf-layout-control' );

		$data[ 'type' ]           = 'Layout_Control';
		$data[ 'field' ]          = $this->field;
		$data[ 'nonce' ]          = wp_create_nonce( $this->get_nonce_action() );
		$data['save_text']        = $save_text;
		$data['load_text']        = $load_text;
		$data['placeholder_text'] = $placeholder_text;
		$data['dropdown_title']   = $dropdown_title;

		return $data;
	}

	/**
	 * Allows the controlled field to be changed.
	 *
	 * @since 1.0
	 *
	 * @param string $field The name of the field.
	 * @return Field
	 */
	public function set_field( $field ) {
		$this->field = $field;
		return $this;
	}

	/**
	 * Returns the name of the controlled field.
	 *
	 * @since 1.0
	 *
	 * @return string
	 */
	public function get_field() {
		return $this->field;
	}

	/**
	 * Imports the field.
	 *
	 * @since 1.0
	 *
	 * @param mixed[] $data The data for the field.
	 */
	public function import( $data ) {
		parent::import( $data );

		$this->proxy_data_to_setters( $data, array(
			'layout_control_field'            => 'set_field',
			'layout_control_save_text'        => 'set_save_text',
			'layout_control_load_text'        => 'set_load_text',
			'layout_control_placeholder_text' => 'set_placeholder_text',
			'layout_control_dropdown_title'   => 'set_dropdown_title'
		));
	}

	/**
	 * Generates the data for file exports.
	 *
	 * @since 1.0
	 *
	 * @return mixed[]
	 */
	public function export() {
		$settings = parent::export();

		$this->export_properties( $settings, array(
			'field'            => array( 'layout_control_field', null ),
			'save_text'        => array( 'layout_control_save_text', null ),
			'load_text'        => array( 'layout_control_load_text', null ),
			'placeholder_text' => array( 'layout_control_placeholder_text', null ),
			'dropdown_title'   => array( 'layout_control_dropdown_title', null )
		));

		return $settings;
	}

	/**
	 * Returns the internal name of the field type.
	 *
	 * @since 1.0
	 *
	 * @return string
	 */
	public function get_type() {
		return 'Layout_Control';
	}

	/**
	 * Returns the action for a nonce field.
	 *
	 * @since 1.0
	 *
	 * @return string
	 */
	protected function get_nonce_action() {
		return 'uf_layout_control_' . $this->name;
	}

	/**
	 * Performs AJAX.
	 *
	 * @since 1.0
	 *
	 * @param string $action The action that is being performed.
	 * @param mixed  $item   The item that is being edited.
	 */
	public function perform_ajax( $action, $item ) {
		if( 'save_layout_' . $this->name === $action ) {
			Post_Type::instance()->save( $_POST['name'], $_POST['layout'], $this->field );
			die( json_encode( 1 ) );
		}

		if( 'load_layouts_' . $this->name === $action ) {
			$layouts = Post_Type::instance()->get_all( $this->field );
			die( json_encode( $layouts ) );
		}

		if( 'delete_layout_' . $this->name === $action ) {
			Post_Type::instance()->delete( $_POST['layout_id'], $this->field );
			die( json_encode( 1 ) );
		}
	}

	/**
	 * String setters and getters.
	 */

	public function set_save_text( $text ) {
		$this->save_text = $text;
		return $this;
	}

	public function get_save_text() {
		return $this->save_text;
	}

	public function set_load_text( $text ) {
		$this->load_text = $text;
		return $this;
	}

	public function get_load_text() {
		return $this->load_text;
	}

	public function set_placeholder_text( $text ) {
		$this->placeholder_text = $text;
		return $this;
	}

	public function get_placeholder_text() {
		return $this->placeholder_text;
	}

	public function set_dropdown_title( $text ) {
		$this->dropdown_title = $text;
		return $this;
	}

	public function get_dropdown_title() {
		return $this->dropdown_title;
	}

}
