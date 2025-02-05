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

use core_privacy\local\metadata\collection;

/**
 * Privacy provider for block_alphabees.
 *
 * This block does not store any personal user data in Moodle but communicates with the Alphabees backend.
 */
class provider implements \core_privacy\local\metadata\provider {

    /**
     * Declare external data communication.
     *
     * @param collection $collection The metadata collection to update.
     * @return collection The updated metadata collection.
     */
    public static function get_metadata(collection $collection): collection {
        $collection->add_external_location_link(
            'alphabees_backend',
            [
                'api_key' => 'The API key provided by the user to authenticate with the Alphabees backend.',
                'bot_list_request' => 'Retrieves the list of AI tutors configured by the user in the Alphabees platform.',
                'chat_messages' => 'Messages sent by the user are processed temporarily during an active session for AI-based responses. These messages are not stored after the session ends.',
            ],
            'The Alphabees plugin exchanges API keys and retrieves AI tutors from the Alphabees backend. User messages are processed in-session but are not stored permanently.'
        );

        return $collection;
    }

    /**
     * Get the reason why this plugin stores no personal user data.
     *
     * @return string
     */
    public static function get_reason(): string {
        return get_string('privacy:metadata', 'block_alphabees');
    }
}
