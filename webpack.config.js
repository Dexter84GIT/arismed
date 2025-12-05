import path from 'path';
import MiniCssExtractPlugin from 'mini-css-extract-plugin';
import postcssPresetEnv from 'postcss-preset-env';

export default {
  entry: {
    main: './src/index.js',
    style: './css/style.scss'
  },
  output: {
    path: path.resolve(process.cwd(), 'dist'),
    filename: '[name].js'
  },
  module: {
    rules: [
      {
        test: /\.s[ac]ss$/i,
        use: [
          MiniCssExtractPlugin.loader,
          'css-loader',
          {
            loader: 'postcss-loader',
            options: {
              postcssOptions: {
                plugins: [postcssPresetEnv()]
              }
            }
          },
          'sass-loader'
        ]
      }
    ]
  },
  plugins: [
    new MiniCssExtractPlugin({
      filename: 'bundle.css'
    })
  ],
  devtool: process.env.NODE_ENV === 'production' ? false : 'source-map',
  performance: { hints: false },
  stats: 'minimal'
};
