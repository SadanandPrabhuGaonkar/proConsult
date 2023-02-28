const webpack = require('webpack');
const path = require('path');
const CopyWebpackPlugin = require('copy-webpack-plugin');
const CleanWebpackPlugin = require('clean-webpack-plugin');
const UglifyJsPlugin = require('uglifyjs-webpack-plugin');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const cssnano = require('cssnano');
const OptimizeCSSAssetsPlugin = require('optimize-css-assets-webpack-plugin');

module.exports = function(env, argv) {
  /*
   * boolean variable for development mode
   */
  const devMode = argv.mode === 'development';

  const mods = {
    watch: devMode,
    devtool: devMode ? 'source-map' : 'cheap-module-source-map',
    entry: {
      app: ['./src/js/app.js', './src/scss/main.scss'],
    },
    output: {
      path: path.join(__dirname, 'dist'),
      filename: 'js/[name].min.js',
    },
    module: {
      rules: [
        /*
         * Handle ES6 transpilation
         */
        {
          test: /\.js$/,
          exclude: /(node_modules|bower_components)/,
          use: {
            loader: 'babel-loader',
            options: {
              presets: ['@babel/preset-env'],
            },
          },
        },
        /*
         * Handle SCSS transpilation
         */
        {
          test: /\.css$/,
          use: [{ loader: 'style-loader' }, { loader: 'css-loader' }],
        },
        {
          test: /\.(sc|sa)ss$/,
          use: [
            'style-loader',
            MiniCssExtractPlugin.loader,
            'css-loader',
            'sass-loader',
          ],
        },
        /*
         * Handle Fonts
         */
        {
          test: /\.(woff(2)?|ttf|eot|svg)(\?v=\d+\.\d+\.\d+)?$/,
          exclude: path.join(__dirname, 'src/images'),
          use: [
            {
              loader: 'file-loader',
              options: {
                include: path.join(__dirname, 'src/fonts'),
                name: '[name].[ext]',
                outputPath: 'fonts',
                publicPath: '../fonts',
              },
            },
          ],
        },
        /*
         * Handle Images Referenced in CSS
         */
        {
          test: /\.(gif|png|jpe?g|svg)$/i,
          exclude: path.join(__dirname, 'src/fonts'),
          use: [
            {
              loader: 'file-loader',
              options: {
                include: path.join(__dirname, 'src/images'),
                name: '[name].[ext]',
                outputPath: 'images',
                publicPath: '../images',
              },
            },
            'image-webpack-loader',
          ],
        },
      ],
    },
    /*
     * NOTE: Optimization will only run on production mode
     */
    optimization: {
      /*
       * Split imported npm packages into a single file
       */
      splitChunks: {
        cacheGroups: {
          commons: {
            name: 'vendors',
            test: /[\\/]node_modules[\\/]/,
            chunks: 'async',
          },
        },
      },
      minimizer: [
        /*
         * Minimize javascript
         */
        new UglifyJsPlugin({
          ...(!devMode && {
            uglifyOptions: {
              compress: {
                drop_console: true,
              },
            },
          }),
        }),
        new OptimizeCSSAssetsPlugin({
          cssProcessor: cssnano,
          cssProcessorOptions: {
            discardComments: {
              removeAll: true,
            },
          },
        }),
      ],
    },
    plugins: [
      /*
       * Automatically load jquery instead of having to import it everywhere
       */
      new webpack.ProvidePlugin({
        $: 'jquery',
        jQuery: 'jquery',
        'window.jQuery': 'jquery',
      }),
      /*
       * Extract app CSS and npm package CSS into two separate files
       */
      new MiniCssExtractPlugin({
        filename: 'css/[name].min.css',
        chunkFilename: 'css/[id].min.css',
      }),
      /*
       * copy all images to the dist folder
       * watch: true - removes all the images from dist
       * Will not work on less copyUnmodified: true in copywebpack plugin
       * watch: false - removes all images from dist once - when running ./run_dev or ./run_build
       * Will work with copyUmodified: true
       * Problem with this is you need to re-run run_dev or run_build before you push your changes
       * to clean the dist/images directory
       * Will stick to the initial config - watch: true, copyUnmodified: false
       */
      new CleanWebpackPlugin(['dist/images'], {
        watch: true,
      }),
      new CopyWebpackPlugin(
        [
          {
            from: 'src/images',
            to: 'images',
          },
        ],
        {
          copyUnmodified: true,
        },
      ),
    ],
  };
  /*
   * Minimize CSS if not devMode
   */
  if (!devMode) {
    mods.plugins.push(
      new OptimizeCSSAssetsPlugin({
        cssProcessor: cssnano,
        cssProcessorOptions: {
          discardComments: {
            removeAll: true,
          },
        },
      }),
    );
  }
  return mods;
};
