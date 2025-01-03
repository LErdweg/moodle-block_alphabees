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
 * @copyright 2025 Alphabees
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
        //debugging('[block_alphabees] Initializing block.', DEBUG_DEVELOPER);
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

    public function applicable_formats() {
        return array('all' => true);
    }
    
    /**
     * Generate the block content.
     *
     * @return stdClass|null The content object or null.
     * @throws coding_exception
     */

    public function get_content(): ?stdClass {
        global $PAGE, $OUTPUT;

        if ($this->content !== null) {
            return $this->content;
        }

        // Fetch API key and bot ID
        $apikey = get_config('block_alphabees', 'apikey');
        if (empty($apikey)) {
            $this->content = new stdClass();
            $this->content->text = get_string('apikeymissing', 'block_alphabees');
            return $this->content;
        }

        $botid = $this->config->botid ?? null;
        if (empty($botid)) {
            $this->content = new stdClass();
            $this->content->text = get_string('nobotselected', 'block_alphabees');
            return $this->content;
        }

        $primarycolor = $this->fetch_primary_color($apikey, $botid);
        $apikey_escaped = htmlspecialchars(s($apikey), ENT_QUOTES, 'UTF-8');
        $botid_escaped = htmlspecialchars(s($botid), ENT_QUOTES, 'UTF-8');
        $primarycolor_escaped = htmlspecialchars(s($primarycolor), ENT_QUOTES, 'UTF-8');
        $scripturl = 'https://dn1t41q06556o.cloudfront.net/production/chat-widget.js';

        // Load external script via Moodle's system
        $PAGE->requires->js(new moodle_url($scripturl), true);

        // Inline JavaScript for widget initialization
        $init_script = <<<JS
        window.alphabeesChatInitialized = window.alphabeesChatInitialized || false;

        function initAlChat() {
            if (window.alphabeesChatInitialized) {
                console.warn("Chat widget already initialized.");
                return;
            }

            if (typeof _loadAlChat === "function") {
                _loadAlChat({
                    apiKey: "{$apikey_escaped}",
                    botId: "{$botid_escaped}",
                    primaryColor: "{$primarycolor_escaped}",
                    development: true
                });
                window.alphabeesChatInitialized = true; 
            } else {
                console.error("Chat widget script not ready. Retrying...");
                setTimeout(initAlChat, 500); 
            }
        }

        // Use window.onload to ensure all scripts are fully loaded
        window.onload = initAlChat;
        JS;

        // Add inline script to Moodle's page
        $PAGE->requires->js_amd_inline($init_script);

        // Prepare block content
        $this->content = new stdClass();
        $this->content->text = '';

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
            return '#72AECF'; 
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
        return '#72AECF'; 
    }
}
