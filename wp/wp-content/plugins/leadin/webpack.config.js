const path = require('path');

module.exports = {
  entry: { leadin: './js/app.js' },
  output: {
    filename: '[name].js',
    path: path.resolve(__dirname, 'scripts'),
  },
  module: {
    rules: [
      {
        test: /\.js$/,
        loader: 'babel-loader',
        exclude: /node_modules/,
        query: {
          presets: ['@babel/preset-env'],
          plugins: ['transform-class-properties'],
        },
      },
    ],
  },
  devtool: 'source-map',
};
