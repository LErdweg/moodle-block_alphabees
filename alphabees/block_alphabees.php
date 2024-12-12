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

/**
 * Class block_alphabees
 *
 * Defines the Alphabees AI Tutor block.
 */
class block_alphabees extends block_base {

    /**
     * Initialize the block.
     *
     * @return void
     * @throws coding_exception
     */
    public function init(): void {
        $this->title = get_string('pluginname', 'block_alphabees');
        debugging('[block_alphabees] Initializing block.', DEBUG_DEVELOPER);
    }

    /**
     * Check if the block has a global configuration.
     *
     * @return bool True if the block has a global configuration.
     */
    public function has_config(): bool {
        return true;
    }

    /**
     * Allow instance-level configuration for this block.
     *
     * @return bool True if instance-level configuration is allowed.
     */
    public function instance_allow_config(): bool {
        return true;
    }

    /**
     * Allow multiple instances of this block in the same context.
     *
     * @return bool True if multiple instances are allowed.
     */
    public function instance_allow_multiple(): bool {
        return true;
    }

    /**
     * Generate the block content.
     *
     * @return stdClass|null The content object or null.
     * @throws coding_exception
     */
    public function get_content(): ?stdClass {
        if ($this->content !== null) {
            return $this->content;
        }

        debugging('[block_alphabees] Rendering block content.', DEBUG_DEVELOPER);

        $apikey = get_config('block_alphabees', 'apikey');
        if (empty($apikey)) {
            $this->content = new stdClass();
            $this->content->text = get_string('apikeymissing', 'block_alphabees');
            return $this->content;
        }

        $botid = $this->config->botid?? null;
        if (empty($botid)) {
            $this->content = new stdClass();
            $this->content->text = get_string('nobotselected', 'block_alphabees');
            return $this->content;
        }

        $primarycolor = $this->fetch_primary_color($apikey, $botid);

        $this->content = new stdClass();
        $this->content->text = $this->generate_chat_widget_script($apikey, $botid, $primarycolor);

        return $this->content;
    }

    /**
     * Fetch the primary color for the selected bot.
     *
     * @param string $apikey The API key.
     * @param string $botid The bot ID.
     * @return string The primary color, or a fallback color if unavailable.
     */
    private function fetch_primary_color(string $apikey, string $botid): string {
        $url = 'https://lassedesk.top/al/tutors/tutor/moodle-list/' . urlencode($apikey);
        $curl = new curl();
        $response = $curl->get($url);

        if (!$response) {
            debugging('[block_alphabees] Failed to fetch primary color. Using fallback.', DEBUG_DEVELOPER);
            return '#72AECF'; // Fallback color.
        }

        $responseData = json_decode($response, true);

        if (!empty($responseData['data'])) {
            foreach ($responseData['data'] as $bot) {
                // Decode primaryColor and convert to lowercase for internal use.
                if ($bot['id'] === $botid && !empty($bot['primaryColor'])) {
                    $primarycolor = strtolower($bot['primaryColor']);
                    debugging("[block_alphabees] Found primary color: {$primarycolor} for bot ID: {$botid}", DEBUG_DEVELOPER);
                    return $primarycolor;
                }
            }
        }

        debugging('[block_alphabees] Primary color not found in response. Using fallback.', DEBUG_DEVELOPER);
        return '#72AECF'; // Fallback color.
    }

    /**
     * Generate the chat widget JavaScript snippet.
     *
     * @param string $apikey The API key.
     * @param string $botid The bot ID.
     * @param string $primarycolor The primary color for the chat widget.
     * @return string The JavaScript snippet.
     */
    private function generate_chat_widget_script(string $apikey, string $botid, string $primarycolor): string {
        $escapedapikey = htmlspecialchars($apikey, ENT_QUOTES, 'UTF-8');
        $escapedbotid = htmlspecialchars($botid, ENT_QUOTES, 'UTF-8');
        $escapedprimarycolor = htmlspecialchars($primarycolor, ENT_QUOTES, 'UTF-8');

        return "
        <script>
            var e = document,
                t = e.createElement('script');
            t.src = 'https://dn1t41q06556o.cloudfront.net/development/chat-widget.js';
            t.onload = function() {
                _loadAlChat({
                    apiKey: '{$escapedapikey}',
                    botId: '{$escapedbotid}',
                    primaryColor: '{$escapedprimarycolor}',
                    development: true
                });
            };
            e.body.appendChild(t);
        </script>
        ";
    }
}