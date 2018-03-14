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
 * Submit player move exporter class
 *
 * @package    mod_tictactoe
 * @copyright  2018 Mitxel Moriana <moriana.mitxel@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace mod_tictactoe\external;

defined('MOODLE_INTERNAL') || die();

use core\external\exporter;
use renderer_base;

/**
 * Class for exporting game state data after a player move.
 *
 * @copyright  2017 SM - CV&A Consulting <mmoriana@cvaconsulting.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class submit_player_move_exporter extends exporter {
    protected static function define_related() {
        return array(
            'context' => 'context',
            'state' => 'string',
        );
    }

    protected static function define_other_properties() {
//        return array(
//            'submitted' => array(
//                'type' => PARAM_BOOL,
//                'description' => 'Team activity has been delivered.',
//            ),
//            'submissiondate' => array(
//                'type' => PARAM_TEXT,
//                'description' => 'Formatted delivery date (Y-m-d).',
//                'optional' => true,
//            ),
//            'submissiondatestring' => array(
//                'type' => PARAM_TEXT,
//                'description' => 'Human-readable delivery date.',
//                'optional' => true,
//            ),
//        );
    }

    protected function get_other_values(renderer_base $output) {
//        $submissiontimestamp = $this->related['submissiontimestamp'];

        $values = array();
        $values['test'] = 'hola';

//        if ($submissiontimestamp < 1) {
//            $values['submitted'] = false;
//
//            return $values;
//        }
//
//        $submissiondate = strftime('%Y-%m-%d', $submissiontimestamp);
//        $submissiondatestringformat = get_string('strftimedate', 'langconfig');
//        $submissiondatestring = userdate($submissiontimestamp, $submissiondatestringformat);
//
//        $values['submitted'] = true;
//        $values['submissiondate'] = $submissiondate;
//        $values['submissiondatestring'] = $submissiondatestring;

        return $values;
    }
}
