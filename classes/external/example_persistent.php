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
 * Example persistent exporter
 *
 * @package    mod_tictactoe
 * @copyright  2018 Mitxel Moriana <moriana.mitxel@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace mod_tictactoe\external;

defined('MOODLE_INTERNAL') || die();

use core\external\persistent_exporter;

/**
 * Class for exporting gallery assignment data.
 *
 * @copyright  2017 SM - CV&A Consulting <mmoriana@cvaconsulting.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class example_persistent_exporter extends persistent_exporter {
    protected static function define_class() {
        return '\\mod_tictactoe\\persistent\\example';
    }

    protected static function define_related() {
        return array(
            'context' => 'context',
        );
    }
}
