class ElementorCustomPluginFrontend extends elementorModules.ViewModule {
	onInit() {
		super.onInit();

		this.config = {

		};

		this.modules = {};
	}

	bindEvents() {
		jQuery( window ).on( 'elementor/frontend/init', this.onElementorFrontendInit.bind( this ) );
	}

	initModules() {
		const handlers = {
			// devTools: require( 'modules/devTools/assets/js/frontend/frontend' ),
		};

		jQuery.each( handlers, ( moduleName, ModuleClass ) => {
			this.modules[ moduleName ] = new ModuleClass( jQuery );
		} );
	}

	onElementorFrontendInit() {
		this.initModules();
	}
}

window.eementorCustomPluginFrontend = new ElementorCustomPluginFrontend();
