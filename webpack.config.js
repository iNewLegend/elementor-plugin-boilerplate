const path = require( 'path' );

module.exports = {
	devtool: 'source-map',
	mode: 'development',
	entry: {
		editor: './assets/src/js/editor.js',
	},
	output: {
		filename: '../../js/[name].js',
		path: path.resolve( __dirname, 'assets/src/js' ),
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
