<h3 class="layout-chooser__title"><?php _e( 'Select layout', 'uf-layout-control' ) ?></h3>

<div class="layout-chooser__body">
	<% if( layouts.length ) { %>
	<ul class="layout-chooser__list">
		<% _.forEach( layouts, function( layout ){ %>
		<li class="layout-chooser__option">
			<a href="#" data-id="<%= layout.id %>" class="layout-chooser__delete">
				<span class="dashicons dashicons-trash"></span>
			</a>

			<a href="#" data-id="<%= layout.id %>" class="layout-chooser__item"><%= layout.name %></a>
		</li>
		<% }) %>
	</ul>
	<% } else { %>
	<p class="layout-chooser__empty"><?php _e( 'No layouts found...', 'uf-layout-control' ) ?></p>
	<% } %>
</div>

<div class="layout-chooser__footer">
	<button type="button" class="button-secondary layout-chooser__cancel"><?php _e( 'Cancel', 'uf-layout-control' ) ?></button>
</div>
