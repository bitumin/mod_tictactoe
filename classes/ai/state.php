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

namespace mod_tictactoe\ai;

defined('MOODLE_INTERNAL') || die();

/**
 * @copyright  2018 Mitxel Moriana <moriana.mitxel@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class State {
    public $turn = '';
    public $oMovesCount = 0;
    public $result = 'still running';
    public $board = [];

    /**
     * State constructor.
     * @param $old
     */
    public function __construct($old) {
        if ($old !== null) {
            // if the state is constructed using a copy of another state
            $len = count($old->board);
            for ($itr = 0; $itr < $len; $itr++) {
                $this->board[$itr] = $old->board[$itr];
            }
            $this->oMovesCount = $old->oMovesCount;
            $this->result = $old->result;
            $this->turn = $old->turn;
        }
    }

    public function advanceTurn() {
        $this->turn = $this->turn === 'X' ? 'O' : 'X';
    }

    public function emptyCells() {
        $indxs = [];
        for ($itr = 0; $itr < 9; $itr++) {
            if ($this->board[$itr] === 'E') {
                $indxs[] = $itr;
            }
        }

        return $indxs;
    }

    public function isTerminal() {
//            var B = this.board;
//
//            //check rows
//            for (var i = 0; i <= 6; i = i + 3) {
//                if (B[i] !== "E" && B[i] === B[i + 1] && B[i + 1] == B[i + 2]) {
//                    this.result = B[i] + "-won"; //update the state result
//                    return true;
//                }
//            }
//
//            //check columns
//            for (var k = 0; k <= 2; k++) {
//                if (B[k] !== "E" && B[k] === B[k + 3] && B[k + 3] === B[k + 6]) {
//                    this.result = B[k] + "-won"; //update the state result
//                    return true;
//                }
//            }
//
//            //check diagonals
//            for (var m = 0, p = 4; m <= 2; m = m + 2, p = p - 2) {
//                if (B[m] !== "E" && B[m] == B[m + p] && B[m + p] === B[m + 2 * p]) {
//                    this.result = B[m] + "-won"; //update the state result
//                    return true;
//                }
//            }
//
//            var available = this.emptyCells();
//            if (available.length == 0) {
//                //the game is draw
//                this.result = "draw"; //update the state result
//                return true;
//            }
//            else {
//                return false;
//            }
    }
}
