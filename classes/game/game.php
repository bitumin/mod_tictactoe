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
 * @package    mod_tictactoe
 * @copyright  2018 Mitxel Moriana <moriana.mitxel@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace mod_tictactoe\game;

defined('MOODLE_INTERNAL') || die();

/**
 * @copyright  2018 Mitxel Moriana <moriana.mitxel@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class game {
    public $autoPlayer;
    public $currentState;
    public $status;

    /**
     * Game constructor.
     * @param $autoPlayer
     * @param state $currentState
     * @param string[] $board
     * @param string $turn
     * @param string $status
     */
    public function __construct($autoPlayer, $currentState = null, $board = null, $turn = 'X', $status = 'beginning') {
        $this->autoPlayer = $autoPlayer;
        $this->currentState = (null === $currentState) ? new state() : $currentState;
        // "E" stands for empty board cell.
        $this->currentState->board = (null === $board) ? ['E', 'E', 'E', 'E', 'E', 'E', 'E', 'E', 'E'] : $board;
        $this->currentState->turn = $turn;
        $this->status = $status;
    }

    /**
     * Calculates the score of the x player in a given terminal state
     * @param state $_state The state in which the score is calculated
     * @return int the score calculated for the human player
     */
    public static function score($_state) {
        if ($_state->result === 'X-won') {
            // The x player won.
            return 10 - $_state->oMovesCount;
        }

        if ($_state->result === 'O-won') {
            // The x player lost.
            return -10 + $_state->oMovesCount;
        }

        // It's a draw.
        return 0;
    }
}
