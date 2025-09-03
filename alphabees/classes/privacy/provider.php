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
 * Privacy API implementation for the Alphabees block.
 *
 * @package   block_alphabees
 * @copyright 2025 Alphabees
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace block_alphabees\privacy;

use core_privacy\local\metadata\null_provider;

/**
 * Privacy provider for block_alphabees.
 *
 * This block does not store any personal user data in Moodle but communicates with the Alphabees backend.
 */
class provider implements null_provider {

    /**
     * Get the reason why this plugin stores no personal user data.
     *
     * @return string A language string identifier.
     */
    public static function get_reason(): string {
        return 'privacy:metadata';
    }
}
