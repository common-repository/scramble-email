/**
 * Convert scrambled email into a mailto link
 *
 * @since		1.0.0
 *
 * @param		string				eml		Base64 encoded Email address
 * @param		string				ttl		Base64 encoded link's text content
 * @param		null|string		clss	Optional. Class to add to the link element.
 *															Specify multiple classes separated by a comma (,).
 * @param		null|string	sbj			Optional. A default subject to add to the mailto link.
 * @return	bool
 */
function scem_unscramble( eml, ttl, attrs ) {

	/**
	 * Helper fonction to test if variable is set and not null
	 *
	 * @since		1.0.0
	 *
	 * @param	 	mixed	variable
	 * @return	bool
	 */
	var _isset = function( variable ) {

		if ( undefined === variable ) { return false; }
		else if ( null === variable ) { return false; }

		return true;
	};

	/**
	 * Determine whether a variable is empty
	 *
	 * @since		1.0.0
	 *
	 * @param		mixed	variable	Variable to be checked
	 * @return	bool						Returns FALSE if var exists and has a non-empty, non-zero value. Otherwise returns TRUE.
	 */
	var _empty = function( variable ) {

		if ( !_isset(variable) )						{ return true; }
		else if ( 0 === variable )				{ return true; }
		else if ( 0 === variable.length )	{ return true; }

		return false;
	};

	// User, domain and tld are mandatory
	if ( _empty(eml) ) {
		return;
	}

	var a = document.createElement("a");

	a.href = 'mailto:'+ window.atob(eml);
	a.textContent = window.atob(ttl);



	if ( !_empty(attrs) ) {

		attrs = JSON.parse( attrs );


		if ( !_empty(attrs.subject) ) {
			a.href += '?subject='+ encodeURIComponent(attrs.subject);
			delete attrs.subject;
		}

		for (var attr in attrs) {
			a.setAttribute( attr, attrs[attr] );
		}
	}

	if ( document.currentScript ) {
		document.currentScript.parentElement.replaceChild( a, document.currentScript );
	}
}
