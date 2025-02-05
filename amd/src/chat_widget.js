// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Main AMD module for loading and initializing the Alphabees Chat Widget.
 *
 * @package   block_alphabees
 * @copyright 2025 Alphabees
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
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
