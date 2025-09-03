<?php
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
 * Edit form for block_alphabees instances.
 *
 * @package   block_alphabees
 * @copyright 2025 Alphabees
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/blocks/edit_form.php');

/**
 * Class block_alphabees_edit_form
 *
 * Defines the form for configuring individual instances of the Alphabees block.
 */
class block_alphabees_edit_form extends block_edit_form {

    /**
     * Define specific elements for the edit form.
     *
     * @param \MoodleQuickForm $mform The form being defined.
     * @return void
     * @throws coding_exception
     */
    protected function specific_definition($mform): void {
        debugging('[block_alphabees] Loading block instance edit form.', DEBUG_DEVELOPER);

        // Add a header for instance settings.
        $mform->addElement('header', 'config_header', get_string('blocksettings', 'block_alphabees'));

        // Dropdown for selecting a bot.
        $botoptions = $this->get_bot_options();
        $mform->addElement('select', 'config_botid', get_string('botid', 'block_alphabees'), $botoptions);
        $mform->setType('config_botid', PARAM_TEXT);
    }

    /**
     * Retrieve options for bots from the external API.
     *
     * @return array<string, string> An associative array of bot options with bot IDs as keys.
     * @throws moodle_exception
     */
    private function get_bot_options(): array {
        $apikey = get_config('block_alphabees', 'apikey');
        if (empty($apikey)) {
            debugging('[block_alphabees] API Key is missing.', DEBUG_DEVELOPER);
            return ['' => get_string('apikeymissing', 'block_alphabees')];
        }

        // Sanitize API key.
        $apikey = clean_param($apikey, PARAM_TEXT);

        $url = 'https://api.alphabees.de/al/tutors/tutor/moodle-list/' . urlencode($apikey);

        debugging('[block_alphabees] Fetching bots from API: ' . $url, DEBUG_DEVELOPER);

        $curl = new curl(['timeout' => 10]);
        $response = $curl->get($url);

        if (!$response) {
            debugging('[block_alphabees] Failed to fetch bots. API response was empty.', DEBUG_DEVELOPER);
            return ['' => get_string('nobotsavailable', 'block_alphabees')];
        }

        // Decode the response safely.
        $responsedata = json_decode($response, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            debugging('[block_alphabees] Failed to decode JSON response.', DEBUG_DEVELOPER);
            return ['' => get_string('nobotsavailable', 'block_alphabees')];
        }

        // Prepare bot options.
        $options = ['' => get_string('selectabot', 'block_alphabees')];
        foreach ($responsedata['data'] ?? [] as $bot) {
            if (isset($bot['id'], $bot['name'])) {
                $options[clean_param($bot['id'], PARAM_TEXT)] = clean_param($bot['name'], PARAM_TEXT);
            }
        }

        return $options;
    }
}
