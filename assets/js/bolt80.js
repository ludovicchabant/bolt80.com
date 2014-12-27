/**
 * RequireJS configuration.
 *
 * We need to alias/shim some of the libraries.
 */
require.config({
    //urlArgs: "bust=" + (new Date()).getTime(),
    paths: {
        jquery: 'js/jquery-1.5.min'
    },
    shim: {
        'jquery': {
            exports: '$'
        }
    }
});

//-------------------------------------------------------------//

/**
 * Entry point.
 */
require([
        ],
    function() {
        console.log("Bolt80 is running!");
});

