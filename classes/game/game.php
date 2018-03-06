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
class Game {
    public $autoPlayer;
    public $currentState;
    public $status; // Initialize game status to beginning.

    /**
     * Game constructor.
     * @param ui $ui
     * @param State $State
     */
    public function __construct($autoPlayer, $ui, $State, $ai) {
        $this->autoPlayer = $autoPlayer;
        $this->ui = $ui;
        $this->ai = $ai;
        $this->State = $State;
        $this->currentState = new State();
        // "E" stands for empty board cell.
        $this->currentState->board = [
            'E', 'E', 'E',
            'E', 'E', 'E',
            'E', 'E', 'E'
        ];
        // X plays first.
        $this->currentState->turn = 'X';
        $this->status = 'beginning';
    }

    /**
     * public function that advances the game to a new state
     * @param State $_state The new state to advance the game to
     */
    public function advanceTo($_state) {
        $this->currentState = $_state;
        if ($_state->isTerminal()) {
            $this->status = 'ended';

            if ($_state->result === 'X-won') {
                // X won.
                $this->ui->switchViewTo('won');
            } else if ($_state->result === 'O-won') {
                // X lost.
                $this->ui->switchViewTo('lost');
            } else {
                // It's a draw.
                $this->ui->switchViewTo('draw');
            }
        } else {
            // The game is still running.
            if ($this->currentState->turn === 'X') {
                $this->ui->switchViewTo('human');
            } else {
                $this->ui->switchViewTo('ai');
                // Notify the AI player its turn has come up.
                $this->ai->notify('O');
            }
        }
    }

    public function start() {
        if ($this->status === 'beginning') {
            // Invoke advanceTo with the initial state.
            $this->advanceTo($this->currentState);
            $this->status = 'running';
        }
    }

    /**
     * Calculates the score of the x player in a given terminal state
     * @param $_state [State]: the state in which the score is calculated
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
