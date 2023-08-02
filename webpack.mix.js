const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */
mix.js('resources/js/app.js', 'public/js/app.min.js')
    .sass('resources/css/app.scss', 'public/css', [
        //
    ]);


// var third_party_assets = {
//     css_js: [
//         {
//             "name": "flatpickr",
//             "assets": ["./node_modules/flatpickr/dist/flatpickr.min.js",
//                 "./node_modules/flatpickr/dist/flatpickr.min.css"
//             ]
//         }
//     ]
// }

// //copying third party assets
// lodash(third_party_assets).forEach(function(assets, type) {
//     if (type == "css_js") {
//         lodash(assets).forEach(function(plugin) {
//             var name = plugin['name'],
//                 assetlist = plugin['assets'],
//                 css = [],
//                 js = [];
//             lodash(assetlist).forEach(function(asset) {
//                 var ass = asset.split(',');
//                 for (let i = 0; i < ass.length; ++i) {
//                     if (ass[i].substr(ass[i].length - 3) == ".js") {
//                         js.push(ass[i]);
//                     } else {
//                         css.push(ass[i]);
//                     }
//                 };
//             });
//             if (js.length > 0) {
//                 mix.combine(js, folder.dist_assets + "/libs/" + name + "/" + name + ".min.js");
//             }
//             if (css.length > 0) {
//                 mix.combine(css, folder.dist_assets + "/libs/" + name + "/" + name + ".min.css");
//             }
//         });
//     }
// });
