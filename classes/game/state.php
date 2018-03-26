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
class state {
    public $turn;
    public $aimovescount;
    /** @var array $board */
    public $board;
    public $result;

    /**
     * State constructor.
     * @param state|null $oldstate
     */
    public function __construct($oldstate = null) {
        if ($oldstate === null) {
            $this->turn = 'X'; // X = human, O = ai
            $this->aimovescount = 0;
            $this->board = ['E', 'E', 'E', 'E', 'E', 'E', 'E', 'E', 'E'];
            $this->result = null;
        } else {
            $this->board = $oldstate->board;
            $this->aimovescount = $oldstate->aimovescount;
            $this->result = $oldstate->result;
            $this->turn = $oldstate->turn;
        }
    }

    public function advance_turn() {
        if ($this->turn === 'X') {
            $this->turn = 'O';
        } else {
            ++$this->aimovescount;
            $this->turn = 'X';
        }
    }

    public function get_empty_cells() {
        $indxs = [];
        for ($itr = 0; $itr < 9; $itr++) {
            if ($this->board[$itr] === 'E') {
                $indxs[] = $itr;
            }
        }

        return $indxs;
    }

    public function has_finished() {
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

        $available = $this->get_empty_cells();
        if (count($available) === 0) {
            // The game is draw.
            $this->result = 'draw'; // Update the state result.
            return true;
        }

        return false;
    }

    public function to_object() {
        return get_object_vars($this);
    }
}
