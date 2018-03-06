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
class State {
    public $turn = '';
    public $oMovesCount = 0;
    public $result = 'still running';
    public $board = [];

    /**
     * State constructor.
     * @param State|null $old
     */
    public function __construct($old = null) {
        if ($old !== null) {
            // If the state is constructed using a copy of another state.
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
        $B = $this->board;

        // Check rows.
        for ($i = 0; $i <= 6; $i += 3) {
            if ($B[$i] !== 'E' && $B[$i] === $B[$i + 1] && $B[$i + 1] === $B[$i + 2]) {
                $this->result = $B[$i] . '-won'; // Update the state result.
                return true;
            }
        }

        // Check columns.
        for ($k = 0; $k <= 2; $k++) {
            if ($B[$k] !== 'E' && $B[$k] === $B[$k + 3] && $B[$k + 3] === $B[$k + 6]) {
                $this->result = $B[$k] . '-won'; // Update the state result.
                return true;
            }
        }

        // Check diagonals.
        for ($m = 0, $p = 4; $m <= 2; $m += 2, $p -= 2) {
            if ($B[$m] !== 'E' && $B[$m] === $B[$m + $p] && $B[$m + $p] === $B[$m + 2 * $p]) {
                $this->result = $B[$m] . '-won'; // Update the state result.
                return true;
            }
        }

        $available = $this->emptyCells();
        if (count($available) === 0) {
            // The game is draw.
            $this->result = 'draw'; // Update the state result.
            return true;
        }

        return false;
    }
}
