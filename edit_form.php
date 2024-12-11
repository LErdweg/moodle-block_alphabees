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
 * Edit form for block_alphabees instances.
 *
 * @package   block_alphabees
 * @copyright 2024 Alphabees
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

define('BLOCK_ALPHABEES_API_BASE_URL', 'https://lassedesk.top/al/tutors/tutor/');

class block_alphabees_edit_form extends block_edit_form {

    /**
     * Define specific elements for the edit form.
     *
     * @param MoodleQuickForm $mform The form being defined.
     * @return void
     */
    protected function specific_definition($mform) {
        global $CFG;

        debugging('[alphabees] Loading block instance edit form.', DEBUG_DEVELOPER);

        // Add a header for instance settings.
        $mform->addElement('header', 'config_header', get_string('blocksettings', 'block_alphabees'));

        // Dropdown for selecting a bot.
        $mform->addElement('select', 'config_bot_id', get_string('botid', 'block_alphabees'), $this->get_bot_options());
        $mform->setType('config_bot_id', PARAM_TEXT);
    }

    /**
     * Retrieve options for bots from the external API.
     *
     * @return array An associative array of bot options with bot IDs as keys.
     */
    private function get_bot_options() {
        $api_key = get_config('block_alphabees', 'api_key');
        if (empty($api_key)) {
            debugging('[alphabees] API Key is missing.', DEBUG_DEVELOPER);
            return ['' => get_string('apikeymissing', 'block_alphabees')];
        }

        $url = BLOCK_ALPHABEES_API_BASE_URL . "moodle-list/" . urlencode($api_key);
        debugging('[alphabees] Fetching bots from API: ' . $url, DEBUG_DEVELOPER);

        $curl = new curl();
        $response = $curl->get($url);

        if (!$response) {
            debugging('[alphabees] Failed to fetch bots. API response was empty.', DEBUG_DEVELOPER);
            return ['' => get_string('nobotsavailable', 'block_alphabees')];
        }

        $response_data = json_decode($response, true);
        if (empty($response_data['data'])) {
            debugging('[alphabees] No bots available in the API response.', DEBUG_DEVELOPER);
            return ['' => get_string('nobotsavailable', 'block_alphabees')];
        }

        $options = ['' => get_string('selectabot', 'block_alphabees')];
        foreach ($response_data['data'] as $bot) {
            $options[$bot['id']] = $bot['name'];
        }

        return $options;
    }
}
