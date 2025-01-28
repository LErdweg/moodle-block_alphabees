/**
 * Main AMD module for loading and initializing the Alphabees Chat Widget.
 *
 * @package   block_alphabees
 */
define([
    'block_alphabees/config',
    'al-chat-widget'
], function(config, externalIgnore) {
    "use strict";

    // Even though the external script does define("al-chat-widget", []) { return {} },
    // it also sets window._loadAlChat. So we just read it from the global:
    const _loadAlChat = window._loadAlChat;

    return {
        /**
         * Initialize the chat widget with API key, bot ID, and primary color.
         *
         * @param {string} apiKey
         * @param {string} botId
         * @param {string} primaryColor
         */
        init: function(apiKey, botId, primaryColor) {
            if (typeof _loadAlChat === "function") {
                console.log("Chat widget is loaded. Initializing...");
                _loadAlChat({
                    apiKey: apiKey,
                    botId: botId,
                    primaryColor: primaryColor
                });
            } else {
                console.error("Failed to initialize: _loadAlChat is not defined.");
            }
        }
    };
});
