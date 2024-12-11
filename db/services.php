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
 * External service definitions for the Alphabees AI Tutor block.
 *
 * @package   block_alphabees
 * @copyright 2024 Alphabees
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

// Define external functions provided by the plugin.
$functions = [
    'block_alphabees_sync_courses' => [
        'classname'    => 'block_alphabees_external', // Fully qualified class name.
        'methodname'   => 'sync_courses',            // Method to be called.
        'classpath'    => 'blocks/alphabees/classes/external.php', // Path to the external class.
        'description'  => 'Synchronize courses with the external API.', // Description of the function.
        'type'         => 'write',                   // Type: 'read' or 'write'.
        'ajax'         => true,                      // Supports AJAX.
        'capabilities' => 'moodle/site:config',      // Required capability.
    ],
];
