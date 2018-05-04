# Ultimate Fields: Layout Control Field

This is an extension for [Ultimate Fields](https://www.ultimate-fields.com/) that introduces an additional field type, called "Layout Control".

The Layout Control field does not save its own values, but allows the values of "Repeater" and "Layout" fields to be saved as templates.

## Installation
Make sure that you are using Ultimate Fields 3.0.2+ and clone this repository as a plugin.

## Usage

In the user interface, simply add the Layout Control field next to a repeater or a layout field and select the field that you want to control.

In PHP, do it like this:

```php
use Ultimate_Fields\Container;
use Ultimate_Fields\Field;

Container::create( 'post-layout' )
	->add_fields(array(
		Field::create( 'repeater', 'blocks' )
			// ->add_group( ... )
			// ->add_group( ... )
			,
		Field::create( 'layout_control', 'blocks_control', 'Layout Control' ) )
			->set_field( 'blocks' )
	))
```

## Storage

All layouts are stored within an ui-less custom post type called `uf-layout`.
