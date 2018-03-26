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
class action {
    public $moveposition;
    public $minimaxval = 0;

    /**
     * AIAction constructor.
     * @param $pos
     */
    public function __construct($pos) {
        $this->moveposition = $pos;
    }

    /**
     * @param state $state
     * @return state|false
     */
    public function apply_to($state) {
        $next = new state($state);

        $available = $next->get_empty_cells();
        if (!in_array($this->moveposition, $available, true)) {
            return false;
        }

        // Put the move on the board.
        $next->board[$this->moveposition] = $state->turn;

        $next->advance_turn();

        return $next;
    }
}
