const path = require( 'path' );

module.exports = {
	mode: 'development',
	entry: {
		editor: './assets/js/editor/editor.js',
	},
	output: {
		filename: '[name]/[name].bundle.js',
		path: path.resolve( __dirname, 'assets/js' ),
	},
	module: {
		rules: [
			{
				test: /\.m?js$/,
				exclude: /(node_modules|bower_components)/,
				use: {
					loader: 'babel-loader',
					options: {
						presets: ['@babel/preset-env'],
						plugins: ["@babel/plugin-proposal-class-properties"],
					}
				}
			}
		]
	},
};
