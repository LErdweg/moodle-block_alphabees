/**
 * AMD module to configure RequireJS for the Alphabees Chat Widget.
 *
 * @package   block_alphabees
 */
define([], function() {
    "use strict";

    // Configure RequireJS paths and shims for external dependencies.
    window.requirejs.config({
        paths: {
            // External chat widget URL 
            "al-chat-widget": "https://chat.alphabees.de/production/chat-widget.amd.js?v=1"
        }
    });

    return {};
});
