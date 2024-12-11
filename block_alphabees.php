<?php
// This file is part of Moodle - http://moodle.org/
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle. If not, see <http://www.gnu.org/licenses/>.

/**
 * Block definition for Alphabees AI Tutor block.
 *
 * @package   block_alphabees
 * @copyright 2024 Alphabees
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

class block_alphabees extends block_base {

    /**
     * Initialize the block.
     *
     * @return void
     */
    public function init() {
        $this->title = get_string('pluginname', 'block_alphabees');
        debugging('[Alphabees] Initializing block.', DEBUG_DEVELOPER);
    }

    /**
     * Check if the block has a global configuration.
     *
     * @return bool True if the block has a global configuration.
     */
    public function has_config() {
        return true;
    }

    /**
     * Allow instance-level configuration for this block.
     *
     * @return bool True if instance-level configuration is allowed.
     */
    public function instance_allow_config() {
        return true;
    }

    /**
     * Allow multiple instances of this block in the same context.
     *
     * @return bool True if multiple instances are allowed.
     */
    public function instance_allow_multiple() {
        return true;
    }

    /**
     * Generate the block content.
     *
     * @return stdClass|null The content object or null.
     */
    public function get_content() {
        if ($this->content !== null) {
            return $this->content;
        }

        debugging('[Alphabees] Rendering block content.', DEBUG_DEVELOPER);

        $api_key = get_config('block_alphabees', 'api_key');
        if (empty($api_key)) {
            $this->content = new stdClass();
            $this->content->text = get_string('apikeymissing', 'block_alphabees');
            return $this->content;
        }

        $bot_id = isset($this->config->bot_id) ? $this->config->bot_id : null;
        if (empty($bot_id)) {
            $this->content = new stdClass();
            $this->content->text = get_string('nobotselected', 'block_alphabees');
            return $this->content;
        }

        $primary_color = $this->fetch_primary_color($api_key, $bot_id);

        $this->content = new stdClass();
        $this->content->text = $this->generate_chat_widget_script($api_key, $bot_id, $primary_color);

        return $this->content;
    }

    /**
     * Fetch the primary color for the selected bot.
     *
     * @param string $api_key The API key.
     * @param string $bot_id The bot ID.
     * @return string The primary color, or a fallback color if unavailable.
     */
    private function fetch_primary_color(string $api_key, string $bot_id): string {
        $url = BLOCK_ALPHABEES_API_BASE_URL . "moodle-list/" . urlencode($api_key);
        $curl = new curl();
        $response = $curl->get($url);

        if (!$response) {
            debugging('[Alphabees] Failed to fetch primary color. Using fallback.', DEBUG_DEVELOPER);
            return '#72AECF'; // Fallback color.
        }

        $response_data = json_decode($response, true);

        if (!empty($response_data['data'])) {
            foreach ($response_data['data'] as $bot) {
                if ($bot['id'] === $bot_id && !empty($bot['primaryColor'])) {
                    return $bot['primaryColor'];
                }
            }
        }

        debugging('[Alphabees] Primary color not found in response. Using fallback.', DEBUG_DEVELOPER);
        return '#72AECF'; // Fallback color.
    }

    /**
     * Generate the chat widget JavaScript snippet.
     *
     * @param string $api_key The API key.
     * @param string $bot_id The bot ID.
     * @param string $primary_color The primary color for the chat widget.
     * @return string The JavaScript snippet.
     */
    private function generate_chat_widget_script(string $api_key, string $bot_id, string $primary_color): string {
        $escaped_api_key = htmlspecialchars($api_key, ENT_QUOTES, 'UTF-8');
        $escaped_bot_id = htmlspecialchars($bot_id, ENT_QUOTES, 'UTF-8');
        $escaped_primary_color = htmlspecialchars($primary_color, ENT_QUOTES, 'UTF-8');

        return "
        <script>
            var e = document,
                t = e.createElement('script');
            t.src = 'https://dn1t41q06556o.cloudfront.net/development/chat-widget.js';
            t.onload = function() {
                _loadAlChat({
                    apiKey: '{$escaped_api_key}',
                    botId: '{$escaped_bot_id}',
                    primaryColor: '{$escaped_primary_color}',
                    development: true
                });
            };
            e.body.appendChild(t);
        </script>
        ";
    }
}
