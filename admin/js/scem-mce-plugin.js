var scem = window.scem || {};

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

	/* Register the buttons */
	tinymce.create('tinymce.plugins.ScemButtons', {
		init	: function(ed, url) {
				scem.editor = ed;

				ed.addButton( 'scem_mce_button', {
					title		: 'Scramble Email',
					image		: url +'/../images/scramble-email-icon.png',
					onclick	: function() { scem.mce_win( ed ); }
				});
		}
	});

	/* Add the buttons to TinyMCE */
	tinymce.PluginManager.add( 'scem_mce_plugin', tinymce.plugins.ScemButtons );


	/**
	 *
	 * @since		1.0.0
	 */
	scem.mce_win = function( editor, attrs, update ) {

		attrs = attrs || {};

		if ( isset(attrs.attrs) ) {
			attrs.attrs = JSON.parse( attrs.attrs.replace(/##/g, '"') );
		}

		editor.windowManager.open({
			title			: 'Scramble Email shortcode',
			classes		: 'scem',
			body			: [
				{ type: 'textbox', name: 'email',		label: 'Email',		value: isset(attrs.email)		? attrs.email		: '', style: 'width: 250px;' },
				{ type: 'textbox', name: 'title',		label: 'Title',		value: isset(attrs.title)		? attrs.title		: '', style: 'width: 250px;' },
				{ type: 'textbox', name: 'classes',	label: 'Classes',	value: (isset(attrs.attrs) && isset(attrs.attrs.classes))	? attrs.attrs.classes	: '', style: 'width: 250px;', placeholder: 'Optional' },
				{ type: 'textbox', name: 'subject',	label: 'Email subject',	value: (isset(attrs.attrs) && isset(attrs.attrs.subject))	? attrs.attrs.subject	: '', style: 'width: 250px;', placeholder: 'Optional' },
			],
			onsubmit	: function( event ) {

				var attrs = {};

				for (var attr in event.data) {
					if ( event.data[attr] !== '' && ['email', 'title'].indexOf( attr ) == -1 ) {
						attrs[attr] = event.data[attr];
					}
				}

				var shortcode = '[scem email="'+ event.data.email +'" title="'+ event.data.title +'" attrs="'+ JSON.stringify(attrs).replace(/"/g, '##') +'"/]';

				if ( isset(update) )	update( shortcode );
				else									editor.insertContent( shortcode );
			}
		});
	};
})();
