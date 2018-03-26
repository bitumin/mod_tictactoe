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
 * Game state exporter class
 *
 * @package    mod_tictactoe
 * @copyright  2018 Mitxel Moriana <moriana.mitxel@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace mod_tictactoe\external;

defined('MOODLE_INTERNAL') || die();

use core\external\exporter;
use mod_tictactoe\game\action;
use mod_tictactoe\game\state;
use renderer_base;

/**
 * Class for exporting game state data after a player move.
 *
 * @copyright  2018 Mitxel Moriana <moriana.mitxel@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class submit_player_move_exporter extends exporter {
    protected static function define_related() {
        return array(
            'context' => 'context',
            'state' => 'false|\mod_tictactoe\game\state',
        );
    }

    protected static function define_other_properties() {
        return array(
            'validmove' => array(
                'type' => PARAM_BOOL,
                'description' => 'Human move was a valid move?',
            ),
            'aimove' => array(
                'type' => PARAM_INT,
                'description' => 'If game has not finished yet, this is the next AI\'s move.',
                'optional' => true,
            ),
            'state' => array(
                'type' => game_state_exporter::read_properties_definition(),
                'optional' => true,
            ),
        );
    }

    protected function get_other_values(renderer_base $output) {
        // We can get the first passed param (data) to the exporter using $this->data
        // $data = $this->data;

        // We can get the context params (related) passed to the exported using $this->related['therelated'];
        $context = $this->related['context'];
        /** @var state $newstate */
        $newstate = $this->related['state'];
        /** @var action $aiaction */
        $aiaction = $this->related['aiaction'];

        $values = array();
        if ($newstate === false) {
            $values['validmove'] = false;
        } else {
            $values['validmove'] = true;
            $values['aimove'] = $aiaction->moveposition;
        }
        $exporter = new game_state_exporter(null, ['context' => $context, 'state' => $newstate]);
        $values['state'] = $exporter->export($output);

        return $values;
    }
}
