(function( $ ){

	var uf                 = window.UltimateFields,
		field              = uf.Field,
		layoutControlField = field.Layout_Control = {};

	/**
	 * A basic model for the "field" with some default values.
	 */
	layoutControlField.Model = field.Model.extend({});

	/**
	 * A view, which will do most of the heavy lifting
	 */
	layoutControlField.View = field.View.extend({
		/**
		 * Renders the input of the field.
		 */
		render: function() {
			var that = this;

			var saveButton = new UltimateFields.Button({
				text: 'Save Layout',
				icon: 'dashicons-migrate',
				callback: _.bind( this.saveClicked, this )
			});

			saveButton.$el.appendTo( this.$el );
			saveButton.render();

			this.$el.append( '<span>&nbsp;</span>' );

			var loadButton = new UltimateFields.Button({
				text: 'Load Layout',
				type: 'primary',
				icon: 'dashicons-category'
			});

			loadButton.$el.appendTo( this.$el );
			loadButton.render();

			this.$meta = $( '<div />' );
			this.$el.append( this.$meta );
		},

		/**
		 * Initiates the process of saving a layout.
		 */
		saveClicked: function() {
			var that = this,
				data = this.model.datastore.get( this.model.get( 'field' ) );

			if( true || ! data || ! data.length ) {
				var $error = $( '<div class="uf-field-validation-message uf-field-validation-message-shown uf-field-validation-message-visible"><p>Empty layouts cannot be saved.</p></div>' );
				this.$meta.empty().append( $error );

				setTimeout(function() {
					that.$meta.empty();
				}, 2500);

				return;
			}
		}
	});

})( jQuery );
