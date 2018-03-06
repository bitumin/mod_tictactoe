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
class AIAction {
    public $movePosition;
    public $minimaxVal = 0;

    /**
     * AIAction constructor.
     */
    public function __construct($pos) {
        $this->movePosition = $pos;
    }

    /**
     * @param State $state
     * @return State
     */
    public function applyTo($state) {
        $next = new State($state);

        // Put the letter on the board.
        $next->board[$this->movePosition] = $state->turn;

        if ($state->turn === 'O') {
            $next->oMovesCount++;
        }

        $next->advanceTurn();

        return $next;
    }

    /**
     * Defines a rule for sorting AIActions in ascending manner
     * @param $firstAction [AIAction] : the first action in a pairwise sort
     * @param $secondAction [AIAction]: the second action in a pairwise sort
     * @return int {Number} -1, 1, or 0
     */
    public static function ASCENDING($firstAction, $secondAction) {
        if ($firstAction->minimaxVal < $secondAction->minimaxVal) {
            return -1; // Indicates that firstAction goes before secondAction.
        }

        if ($firstAction->minimaxVal > $secondAction->minimaxVal) {
            return 1; // Indicates that secondAction goes before firstAction.
        }

        return 0; // Indicates a tie.
    }

    /**
     * Defines a rule for sorting AIActions in descending manner
     * @param $firstAction [AIAction] : the first action in a pairwise sort
     * @param $secondAction [AIAction]: the second action in a pairwise sort
     * @return int {Number} -1, 1, or 0
     */
    public static function DESCENDING($firstAction, $secondAction) {
        if ($firstAction->minimaxVal > $secondAction->minimaxVal) {
            return -1; // Indicates that firstAction goes before secondAction.
        }

        if ($firstAction->minimaxVal < $secondAction->minimaxVal) {
            return 1; // Indicates that secondAction goes before firstAction.
        }

        return 0; // Indicates a tie.
    }
}
