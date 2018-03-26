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

namespace mod_tictactoe;

use mod_tictactoe\game\action;
use mod_tictactoe\game\ai;
use mod_tictactoe\game\state;
use mod_tictactoe\persistent\tictactoe;
use mod_tictactoe\persistent\tictactoe_game;

defined('MOODLE_INTERNAL') || die();

require_once(__DIR__ . '/../locallib.php');

/**
 * Class mod_tictactoe_api
 */
class api {
    /**
     * @param tictactoe $tictactoe
     * @return tictactoe_game|false
     * @throws \coding_exception
     * @throws \core\invalid_persistent_exception
     */
    public static function get_tictactoe_game($tictactoe) {
        global $USER;

        if ($tictactoegame = $tictactoe->get_game_by_userid($USER->id)) {
            return $tictactoegame;
        }

        // If no game has been created for this user yet, create a new one.
        $tictactoegame = new tictactoe_game(0, (object) [
            'tictactoeid' => $tictactoe->get('id'),
            'userid' => $USER->id,
            'state' => new state()
        ]);
        $tictactoegame->create();

        return $tictactoegame;
    }

    /**
     * @param tictactoe_game $tictactoegame
     * @param int $playermove
     * @return array
     * @throws \coding_exception
     */
    public static function process_player_move($tictactoegame, $playermove) {
        $previousstate = $tictactoegame->get('state');
        $playeraction = new action($playermove);

        if (!$afterplayeractionstate = $playeraction->apply_to($previousstate)) {
            return [false, false];
        }

        $ai = new ai($tictactoegame->get('level'), $afterplayeractionstate);
        if (!$aiaction = $ai->calculate_move($afterplayeractionstate->turn)) {
            return [false, false];
        }

        if (!$newstate = $aiaction->apply_to($afterplayeractionstate)) {
            return [false, false];
        }

        return [$aiaction, $newstate];
    }
}
