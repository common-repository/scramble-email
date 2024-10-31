// var scem = window.scem || {};

(function() {

	/**
	 * Helper fonction to test if variable is set and not null
	 *
	 * @since 1.0.0
	 *
	 * @param	 mixed variable
	 * @return	bool
	 */
	var isset = function( variable ) {

		if ( undefined === variable ) { return false; }
		else if ( null === variable ) { return false; }

		return true;
	};

	/**
	 * Determine whether a variable is empty
	 *
	 * @since 1.0.0
	 *
	 * @param mixed variable	Variable to be checked
	 * @return bool						Returns FALSE if var exists and has a non-empty, non-zero value. Otherwise returns TRUE.
	 */
	var empty = function( variable ) {

		if ( !isset(variable) )						{ return true; }
		else if ( 0 === variable )				{ return true; }
		else if ( 0 === variable.length )	{ return true; }

		return false;
	};


	window.wp.mce.views.register( 'scem', {
		initialize: function() {

			var attrs	= this.shortcode.attrs.named,
					link	= 'mailto:'+ attrs.email +( !empty(attrs.subject) ? '?subject='+ attrs.subject : '' ),
					html	= '<a href="'+ link +'" class="scem-view scem-view--link">'+ attrs.title +'</a>';

			this.render( html );
		},
		edit: function( text, update ) {
			window.scem.mce_win( scem.editor, this.shortcode.attrs.named, update );
		}
	});

})();
