(function( $ ){

	var uf                 = window.UltimateFields,
		field              = uf.Field,
		layoutControlField = field.Layout_Control = {};

	/**
	 * A basic model for the "field" with some default values.
	 */
	layoutControlField.Model = field.Model.extend({
		ajax: function( action, args ) {
			var options = _.extend( {
				url:      window.location.href,
				type:     'post',
				dataType: 'json'
			}, args, {
				data: _.extend({
					nonce:     this.get( 'nonce' ),
					uf_ajax:   true,
					uf_action: action + '_' + this.get( 'name' )
				}, args.data || {} )
			});

			return $.ajax( options );
		}
	});

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
			this.$loadSpinner = this.$el.find( '.layout-control__load-spinner' );

			// Add buttons and etc
			var saveButton = new UltimateFields.Button({
				text: this.model.get( 'save_text' ),
				icon: 'dashicons-migrate',
				callback: _.bind( this.saveClicked, this )
			});

			this.$saveSpinner.before( saveButton.$el );
			saveButton.render();

			var loadButton = new UltimateFields.Button({
				text: this.model.get( 'load_text' ),
				type: 'primary',
				icon: 'dashicons-category',
				callback: _.bind( this.loadClicked, this )
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

			// Hide the chooser first
			if( this.chooser ) {
				this.chooser.remove();
				this.chooser = null;
			}

			if( ! data || ( ( 'length' in data ) && ! data.length ) ) {
				return this.showError( 'Empty layouts cannot be saved.' );
			}

			if( ! this.$name.val().trim().length ) {
				return this.showError( 'Please enter a name for the layout first.' );
			}

			// Clear other errors
			this.clearErrors();

			// Do it
			this.$saveSpinner.addClass( 'is-active' );

			this.model.ajax( 'save_layout', {
				data: {
					name:   this.$name.val(),
					layout: data,
				},
				success:  function() {
					that.$saveSpinner.removeClass( 'is-active' );
					that.$name.val( '' );
				}
			});
		},

		/**
		 * Clears all currently displayed errors.
		 */
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
		},

		/**
		 * Once the "Load" button is clicked, this performs an AJAX call and displays the chooser.
		 */
		loadClicked() {
			var that = this;

			this.$loadSpinner.addClass( 'is-active' );

			this.model.ajax( 'load_layouts', {
				success: function( layouts ) {
					that.$loadSpinner.removeClass( 'is-active' );

					// Clear the old ones
					if( that.chooser ) {
						that.chooser.remove();
						that.chooser = null;
					}

					// Create a new chooser
					var chooser = new layoutControlField.ChooserView({
						model: that.model,
						layouts: layouts
					});

					that.$el.append( chooser.$el );
					chooser.render();

					// Save a handle
					that.chooser = chooser;
				}
			});
		}
	});

	/**
	 * Handles the selection of layouts.
	 */
	layoutControlField.ChooserView = Backbone.View.extend({
		className: 'layout-chooser',

		events: {
			'click .layout-chooser__cancel': 'remove',
			'click .layout-chooser__item': 'selected',
			'click .layout-chooser__delete': 'delete'
		},

		initialize( args ) {
			Backbone.View.prototype.initialize.apply( this, arguments );
			this.layouts = args.layouts;
		},

		render() {
			var that = this,
				tmpl = UltimateFields.template( 'layout-chooser' );

			this.$el.html( tmpl({
				dropdown_title: this.model.get( 'dropdown_title' ),
				layouts: this.layouts
			}));
		},

		selected: function( e ) {
			e.preventDefault();

			// Locate the layout
			var id = e.target.dataset.id;
			var layout = this.layouts.find( function( layout ) {
				return layout.id == id;
			});

			if( ! layout ) {
				return;
			}

			// Update the data
			var data = layout.content;
			this.model.datastore.set( this.model.get( 'field' ), data );
			this.model.datastore.trigger( 'value-replaced', this.model.get( 'field' ) );

			// Hide the chooser
			this.remove();
		},

		delete: function( e ) {
			e.preventDefault();

			this.model.ajax( 'delete_layout', {
				data: {
					layout_id: $( e.target ).closest( 'a' ).data( 'id' )
				}
			});

			$( e.target ).closest( 'li' ).remove();

			// Re-render if needed
			if( ! this.$el.find( 'li' ).length ) {
				this.layouts = [];
				this.render();
			}
		}
	});

})( jQuery );
