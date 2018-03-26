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
class ai {
    private $level;
    private $state;

    /**
     * ai constructor.
     * @param string $level
     * @param state $state
     */
    public function __construct($level, $state) {
        $this->level = $level;
        $this->state = $state;
    }

    /**
     * @param state $state
     * @return int|null
     */
    private function minimax_value($state) {
        if ($state->has_finished()) {
            return $this->get_finished_score($state);
        }

        $stateScore = null;
        if ($state->turn === 'X') {
            $stateScore = -1000;
        } else {
            $stateScore = 1000;
        }

        $availablePositions = $state->get_empty_cells();

        // Enumerate next available states using the info form available positions
        $availableNextStates = array_map(function ($pos) use ($state) {
            $action = new action($pos);
            return $action->apply_to($state);
        }, $availablePositions);

        // Calculate the minimax value for all available next states and evaluate the current state's value.
        foreach ($availableNextStates as $nextState) {
            $nextScore = $this->minimax_value($nextState);
            if ($state->turn === 'X') {
                // X wants to maximize --> update stateScore if nextScore is larger
                if ($nextScore > $stateScore) {
                    $stateScore = $nextScore;
                }
            } else {
                // O wants to minimize --> update stateScore if nextScore is smaller
                if ($nextScore < $stateScore) {
                    $stateScore = $nextScore;
                }
            }
        }

        return $stateScore;
    }

    /**
     * Function: make the ai player take a blind move
     * that is: choose the cell to place its symbol randomly
     * @return action
     */
    private function take_a_blind_move() {
        $available = $this->state->get_empty_cells();
        $randomCell = $available[(int) floor(mt_rand() * count($available))];

        return new action($randomCell);
    }

    /**
     * Make the ai player take a novice move,
     * that is: mix between choosing the optimal and suboptimal minimax decisions
     * @param string $turn the player to play, either X or O
     * @return action
     */
    private function take_a_novice_move($turn) {
        $available = $this->state->get_empty_cells();

        // Enumerate and calculate the score for each available actions to the ai player.
        /** @var action[] $availableActions */
        $availableActions = array_map(function ($pos) {
            $action = new action($pos); //create the action object
            $nextState = $action->apply_to($this->state); // Get next state by applying the action.
            $action->minimaxval = $this->minimax_value($nextState); // Calculate and set the action's minimax value.

            return $action;
        }, $available);

        // Sort the enumerated actions list by score.
        if ($turn === 'X') {
            // X maximizes --> sort the actions in a descending manner to have the action with maximum minimax first.
            usort($availableActions, self::descending());
        } else {
            // O minimizes --> sort the actions in an ascending manner to have the action with minimum minimax first.
            usort($availableActions, self::ascending());
        }

        // Take the optimal action 40% of the time, and take the 1st suboptimal action 60% of the time
        $chosenAction = null;
        if (mt_rand() * 100 <= 40) {
            $chosenAction = $availableActions[0];
        } else {
            if (count($availableActions) >= 2) {
                //if there is two or more available actions, choose the 1st suboptimal
                $chosenAction = $availableActions[1];
            } else {
                //choose the only available actions
                $chosenAction = $availableActions[0];
            }
        }

        return $chosenAction;
    }

    /**
     * Make the ai player take a master move,
     * that is: choose the optimal minimax decision
     * @param string $turn the player to play, either X or O
     * @return action
     */
    private function take_a_master_move($turn) {
        $available = $this->state->get_empty_cells();

        // Enumerate and calculate the score for each avaialable actions to the ai player
        /** @var action[] $availableActions */
        $availableActions = array_map(function ($pos) {
            $action = new action($pos); //create the action object
            $next = $action->apply_to($this->state); //get next state by applying the action
            $action->minimaxval = $this->minimax_value($next); //calculate and set the action's minmax value
            return $action;
        }, $available);

        // Sort the enumerated actions list by score
        if ($turn === 'X') {
            // X maximizes --> sort the actions in a descending manner to have the action with maximum minimax first.
            usort($availableActions, self::descending());
        } else {
            // O minimizes --> sort the actions in an ascending manner to have the action with minimum minimax first.
            usort($availableActions, self::ascending());
        }

        // Take the first action as it's the optimal.
        return $availableActions[0];
    }

    /**
     * Notify the ai player that it's its turn
     * @param string $turn the player to play, either X or O
     * @return action|false
     */
    public function calculate_move($turn) {
        switch ($this->level) {
            case 'blind':
                return $this->take_a_blind_move();
            case 'novice':
                return $this->take_a_novice_move($turn);
            case 'master':
                return $this->take_a_master_move($turn);
        }

        return false;
    }

    /**
     * Calculates the score of the x player in a given terminal state
     * @param state $state The state in which the score is calculated
     * @return int the score calculated for the human player
     */
    private function get_finished_score($state) {
        if ($state->result === 'X-won') {
            // The x player won.
            return 10 - $state->aimovescount;
        }

        if ($state->result === 'O-won') {
            // The x player lost.
            return -10 + $state->aimovescount;
        }

        // It's a draw.
        return 0;
    }

    /**
     * Defines a rule for sorting ai actions in ascending manner
     * @return \Closure
     */
    public static function ascending() {
        return function($firstAction, $secondAction) {
            if ($firstAction->minimaxVal < $secondAction->minimaxVal) {
                return -1; // Indicates that firstAction goes before secondAction.
            }

            if ($firstAction->minimaxVal > $secondAction->minimaxVal) {
                return 1; // Indicates that secondAction goes before firstAction.
            }

            return 0; // Indicates a tie.
        };
    }

    /**
     * Defines a rule for sorting ai actions in descending manner
     * @return \Closure
     */
    public static function descending() {
        return function ($firstAction, $secondAction) {
            if ($firstAction->minimaxVal > $secondAction->minimaxVal) {
                return -1; // Indicates that firstAction goes before secondAction.
            }

            if ($firstAction->minimaxVal < $secondAction->minimaxVal) {
                return 1; // Indicates that secondAction goes before firstAction.
            }

            return 0; // Indicates a tie.
        };
    }
}
