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
			var that = this,
				tmpl = UltimateFields.template('layout-control');

			// Start with the base
			this.$el.html( tmpl( this.model.toJSON() ) );
			this.$name = this.$el.find( '.layout-control__name' );
			this.$saveSpinner = this.$el.find( '.layout-control__save-spinner' );

			// Add buttons and etc
			var saveButton = new UltimateFields.Button({
				text: 'Save Layout',
				icon: 'dashicons-migrate',
				callback: _.bind( this.saveClicked, this )
			});

			this.$saveSpinner.before( saveButton.$el );
			saveButton.render();

			var loadButton = new UltimateFields.Button({
				text: 'Load Layout',
				type: 'primary',
				icon: 'dashicons-category'
			});

			loadButton.$el.appendTo( this.$el.find( '.layout-control__load' ) );
			loadButton.render();
		},

		/**
		 * Initiates the process of saving a layout.
		 */
		saveClicked: function() {
			var that = this,
				data = this.model.datastore.get( this.model.get( 'field' ) );

			if( ! data || ! data.length ) {
				return this.showError( 'Empty layouts cannot be saved.' );
			}

			if( ! this.$name.val().trim().length ) {
				return this.showError( 'Please enter a name for the layout first.' );
			}

			// Clear other errors
			this.clearErrors();

			// Do it
			this.$saveSpinner.addClass( 'is-active' );

			$.ajax({
				url:      window.location.href,
				type:     'post',
				dataType: 'json',
				data:     {
					uf_action: 'save_layout_' + this.model.get( 'name' ),
					name:      this.$name.val(),
					layout:    data,
					nonce:     this.model.get( 'nonce' ),
					uf_ajax:   true
				},
				success:  function() {
					that.$saveSpinner.removeClass( 'is-active' );
					that.$name.val( '' );
				}
			});
		},

		clearErrors() {
			this.$el.children( '.uf-field-validation-message' ).remove();
		},

		/**
		 * Shows an error message.
		 */
		showError( message ) {
			this.clearErrors();

			var classes = [
				'uf-field-validation-message',
				'uf-field-validation-message-shown',
				'uf-field-validation-message-visible'
			].join( ' ' );

			var $error = $( '<div class="' + classes + '" />' );
			var $p = $( '<p />' ).text( message );
			$error.append( $p );
			this.$el.append( $error );
		}
	});

})( jQuery );
