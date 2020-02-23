import * as modules from '../../../modules/assets/editor/';

export default class ElementorPluginEditor extends Marionette.Application {
	constructor( options ) {
		super( options );

		this.config = {};
		this.modules = [];

		console.log( 'custom-plugin: editor.js module loaded' );
	}

	initialize( options ) {
		super.initialize( options );

		setTimeout( () => {
			Object.values( modules ).forEach( ( Module ) => {
				this.modules.push( new Module() );
			} );
		} )
	}
}

window.elementorPluginEditor = new ElementorPluginEditor();
window.elementorPluginEditor.start();
