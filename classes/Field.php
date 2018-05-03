<?php
namespace Ultimate_Fields\Layout_Control;

use Ultimate_Fields\Field as Base_Field;
use Ultimate_Fields\Template;

class Field extends Base_Field {
	protected $field;

	public function enqueue_scripts() {
		wp_enqueue_script( 'uf-field-layout-control' );
		wp_enqueue_style( 'uf-field-layout-control' );

		Template::add( 'layout-control', 'field/layout-control' );
	}

	public function export_field() {
		$data = parent::export_field();
		$data[ 'type' ] = 'Layout_Control';
		$data[ 'field' ] = $this->field;
		$data[ 'nonce' ] = wp_create_nonce( $this->get_nonce_action() );
		return $data;
	}

	public function set_field( $field ) {
		$this->field = $field;
		return $this;
	}

	public function get_field() {
		return $this->field;
	}

	/**
	 * Imports the field.
	 *
	 * @since 3.0
	 *
	 * @param mixed[] $data The data for the field.
	 */
	public function import( $data ) {
		parent::import( $data );

		$this->proxy_data_to_setters( $data, array(
			'layout_control_field' => 'set_field'
		));
	}

	/**
	 * Generates the data for file exports.
	 *
	 * @since 3.0
	 *
	 * @return mixed[]
	 */
	public function export() {
		$settings = parent::export();

		$this->export_properties( $settings, array(
			'field' => array( 'layout_control_field', null )
		));

		return $settings;
	}

	public function get_type() {
		return 'Layout_Control';
	}

	/**
	 * Returns the action for a nonce field.
	 *
	 * @since 3.0
	 *
	 * @return string
	 */
	protected function get_nonce_action() {
		return 'uf_layout_control_' . $this->name;
	}

	/**
	 * Performs AJAX.
	 *
	 * @since 3.0
	 *
	 * @param string $action The action that is being performed.
	 * @param mixed  $item   The item that is being edited.
	 */
	public function perform_ajax( $action, $item ) {
		if( 'save_layout_' . $this->name != $action ) {
			return;
		}

		$layout = wp_insert_post(array(
			'post_type'    => 'uf-layout',
			'post_status'  => 'publish',
			'post_title'   => esc_html( $_POST['name'] ),
			'post_content' => ' '
		));

		update_post_meta( $layout, '_layout', $_POST['layout'] );

		echo json_encode( array(
			'success' => true
		));
		exit;
	}
}
