/**
 * Grunt configuration for building Alphabees AMD modules.
 *
 * @package   block_alphabees
 */
module.exports = function (grunt) {
    grunt.initConfig({
        requirejs: {
            chatwidget: {
                options: {
                    // Folder where your .js files reside.
                    baseUrl: 'amd/src',

                    // Main AMD module to bundle. This should match the module's `define` name minus .js
                    name: 'chat_widget',

                    // The output file after optimization.
                    out: 'amd/build/chat_widget.min.js',

                    // Mark external modules as empty if you don't want them inlined.
                    // If your chat_widget depends on config, either exclude it or rename paths as needed.
                    paths: {
                        // Because the module references define(['block_alphabees/config', 'al-chat-widget']), 
                        // you can decide to treat them as empty or exclude them. 
                        'block_alphabees/config': 'empty:',  // Tells r.js "don't inline config code here"
                        'al-chat-widget': 'empty:'
                    },

                    // Another approach: exclude the config so it’s not inlined:
                    exclude: ['block_alphabees/config'],

                    // Minify.
                    optimize: 'uglify2',
                    preserveLicenseComments: false,
                    generateSourceMaps: false
                }
            },
            configjs: {
                options: {
                    baseUrl: 'amd/src',
                    name: 'config',
                    out: 'amd/build/config.min.js',
                    optimize: 'uglify2',
                    preserveLicenseComments: false,
                    generateSourceMaps: false
                }
            }
        }
    });

    // Load the 'requirejs' plugin.
    grunt.loadNpmTasks('grunt-contrib-requirejs');

    // Register explicit tasks.
    grunt.registerTask('build_chatwidget', ['requirejs:chatwidget']);
    grunt.registerTask('build_configjs', ['requirejs:configjs']);

    // Default: build both.
    grunt.registerTask('default', ['build_chatwidget', 'build_configjs']);
};
