/**
 * Grunt configuration for building the Alphabees Chat Widget AMD module.
 *
 * @package   block_alphabees
 */

module.exports = function (grunt) {
    grunt.initConfig({
        requirejs: {
            compile: {
                options: {
                    // Directory where your AMD "src" files live.
                    baseUrl: 'amd/src',

                    // Name of the module to optimize. This matches the filename (without .js).
                    // Moodle automatically identifies 'chat_widget.js' as 'block_alphabees/chat_widget'.
                    name: 'chat_widget',

                    // Where to place the optimized file.
                    out: 'amd/build/chat_widget.min.js',

                    // No external dependencies to bundle.
                    paths: {
                        'block_alphabees/config': 'config',
                        'al-chat-widget': 'empty:' 
                    },

                    // You can choose 'none' if you want unminified output or 'uglify2' for minified.
                    optimize: 'uglify2',

                    // Don’t preserve license comments in the minified output.
                    preserveLicenseComments: false,

                    // Turn off source maps if not needed. Set to true if you do want them.
                    generateSourceMaps: false
                }
            }
        }
    });

    grunt.loadNpmTasks('grunt-contrib-requirejs');

    // The default task will run the RequireJS optimizer.
    grunt.registerTask('default', ['requirejs']);
    grunt.registerTask('amd', ['requirejs']);
};
