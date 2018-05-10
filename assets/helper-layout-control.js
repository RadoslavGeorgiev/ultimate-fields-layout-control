(function( $ ){

	var ui        = window.UltimateFields.UI,
		field     = ui.Field;

	/**
	 * Add functionality for the layout control field.
	 */
	field.Helper.Layout_Control = field.Helper.extend({
		/**
		 * Sets the model for the preview up.
		 */
		setupPreview: function( args ) {
			var attributes = args.data.get( 'layout_control_strings' ) || {};

			args.model.set({
				save_text:        attributes.layout_control_save_text || 'Save Layout',
				load_text:        attributes.layout_control_load_text || 'Load Layout',
				placeholder_text: attributes.layout_control_placeholder_text || 'Enter layout name...',
				dropdown_title:   attributes.layout_control_dropdown_title || 'Select Layout'
			});
		}
	});


})( jQuery );
