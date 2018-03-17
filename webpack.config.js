var Encore = require('@symfony/webpack-encore');

Encore
    // the project directory where compiled assets will be stored
    .setOutputPath('public/build/')
    // the public path used by the web server to access the previous directory
    .setPublicPath('/build')
    .cleanupOutputBeforeBuild()
    .enableSourceMaps(!Encore.isProduction())
    .addEntry('app', './assets/js/app.js')

    .enableSassLoader()

    .autoProvidejQuery()
;

module.exports = Encore.getWebpackConfig();
