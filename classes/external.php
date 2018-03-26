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
 * This is the external API for this plugin.
 *
 * @package    mod_tictactoe
 * @copyright  2018 Mitxel Moriana <moriana.mitxel@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace mod_tictactoe;

defined('MOODLE_INTERNAL') || die();

use coding_exception;
use core_renderer;
use dml_exception;
use external_api as core_external_api;
use external_function_parameters;
use external_single_structure;
use external_value;
use invalid_parameter_exception;
use mod_tictactoe\external\game_state_exporter;
use mod_tictactoe\persistent\tictactoe_game;
use moodle_exception;
use restricted_context_exception;
use stdClass;

global $CFG;
require_once($CFG->libdir . '/externallib.php');
require_once($CFG->dirroot . '/webservice/externallib.php');
require_once(__DIR__ . '/../locallib.php');

/**
 * This is the external API for this plugin.
 *
 * @copyright  2018 Mitxel Moriana <moriana.mitxel@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class external extends core_external_api {
    /**
     * @return external_function_parameters
     */
    public static function submit_player_move_parameters() {
        return new external_function_parameters(array(
            'tictactoe' => new external_single_structure(array(
                'gameid' => new external_value(PARAM_INT),
                'playermove' => new external_value(PARAM_ALPHANUMEXT)
            ))
        ));
    }

    /**
     * @param $tictactoe
     * @return stdClass
     * @throws dml_exception
     * @throws moodle_exception
     * @throws coding_exception
     * @throws invalid_parameter_exception
     * @throws restricted_context_exception
     */
    public static function submit_player_move($tictactoe) {
        global $USER, $PAGE;

        $params = self::validate_parameters(self::submit_player_move_parameters(), array('tictactoe' => $tictactoe));
        $gameid = (int) $params['tictactoe']['gameid'];
        $playermove = (int) $params['tictactoe']['playermove'];

        // Extra param validation.
        if ($gameid < 1) {
            throw new invalid_parameter_exception('Unexpected tictactoe game id value: ' . $gameid);
        }
        if ($playermove < 0 || $playermove > 8) {
            throw new invalid_parameter_exception('Unexpected player move index value: ' . $playermove);
        }

        // Context validation.
        $tictactoegame = new tictactoe_game($gameid);
        $tictactoe = $tictactoegame->get_tictactoe();
        $context = $tictactoe->get_context();
        self::validate_context($context);

        // Check user capabilities
        require_capability('mod/tictactoe:submit', $context);

        // Check user is the current game player
        $player = $tictactoegame->get_player();
        if ((int) $player->id !== (int) $USER->id) {
            throw new moodle_exception('accessdenied', 'admin');
        }

        // Submit move and return new tictactoe game state.
        list($aiaction, $newstate) = api::process_player_move($tictactoegame, $playermove);

        /** @var core_renderer $output */
        $output = $PAGE->get_renderer('core');
        /** @var game_state_exporter $exporter */
        $exporter = new game_state_exporter($newstate, ['context' => $context, 'aiaction' => $aiaction]);

        return $exporter->export($output);
    }

    /**
     * @return external_single_structure
     */
    public static function submit_player_move_returns() {
        return game_state_exporter::get_read_structure();
    }
}
