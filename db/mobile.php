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
 * Moodle App integration handlers for the Alphabees block.
 *
 * Declares the mobile handlers consumed by the Moodle App, registering the
 * block view (CoreBlockDelegate) and a course menu entry (CoreCourseOptionsDelegate)
 * that both invoke \block_alphabees\output\mobile::mobile_ws_marker().
 *
 * @package   block_alphabees
 * @category  output
 * @copyright 2025 Alphabees
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$addons = [
    'block_alphabees' => [
        'handlers' => [
            // Auto-run when the block is present on the page.
            'alphabees_block' => [
                'delegate'    => 'CoreBlockDelegate',
                'method'      => 'mobile_ws_marker',
                'displaydata' => [
                    'title' => 'pluginname',
                    'class' => 'block_alphabees',
                    'icon'  => 'chatbubble-ellipses',
                ],
            ],

            // Course menu fallback: visible entry in the course options.
            'alphabees_course_option' => [
                'delegate'       => 'CoreCourseOptionsDelegate',
                'method'         => 'mobile_ws_marker',
                'displaydata'    => [
                    'title' => 'pluginname',
                    'class' => 'block_alphabees',
                    'icon'  => 'chatbubble-ellipses',
                ],
                'ismenuhandler'  => true,
            ],
        ],

        // Language strings used by the mobile handlers.
        'lang' => [
            ['pluginname', 'block_alphabees'],
        ],
    ],
];
