<?php
namespace Ultimate_Fields\Layout_Control;

use Ultimate_Fields\Field as Base_Field;
use Ultimate_Fields\Helper\Template;

class Field extends Base_Field {
	protected $field;

	public function enqueue_scripts() {
		wp_enqueue_script( 'uf-field-layout-control' );

		Template::add()
	}

	public function export_field() {
		$data = parent::export_field();
		$data[ 'type' ] = 'Layout_Control';
		$data[ 'field' ] = $this->field;
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
}
