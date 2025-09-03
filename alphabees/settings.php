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
 * Settings for the Alphabees AI Tutor block.
 *
 * @package   block_alphabees
 * @copyright 2025 Alphabees
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

if ($ADMIN->fulltree) {
    // Add a heading for general settings.
    $settings->add(new admin_setting_heading(
        'block_alphabees/general_settings',
        get_string('generalsettings', 'block_alphabees'),
        get_string('generalsettings_desc', 'block_alphabees')
    ));

    // Add the API Key setting.
    $settings->add(new admin_setting_configtext(
        'block_alphabees/apikey', // The unique identifier for the setting.
        get_string('apikey', 'block_alphabees'), // The setting's display name.
        get_string('apikey_desc', 'block_alphabees'), // The description shown below the field.
        clean_param('', PARAM_TEXT), // Default value sanitized.
        PARAM_TEXT // Input validation: Text only.
    ));
}
