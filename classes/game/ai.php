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
    private $game;

    /**
     * ai constructor.
     * @param string $level
     * @param game $Game
     * @param action $AIAction
     */
    public function __construct($level, $Game, $AIAction) {
        $this->level = $level;
        $this->game = new \stdClass();
    }

    /**
     * @param state $state
     * @return int|null
     */
    private function minimax_value($state) {
        if ($state->is_terminal()) {
            return game::score($state);
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
                // X wants to maximize --> update stateScore iff nextScore is larger
                if ($nextScore > $stateScore) {
                    $stateScore = $nextScore;
                }
            } else {
                // O wants to minimize --> update stateScore iff nextScore is smaller
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
     * @return state
     */
    private function take_a_blind_move() {
        $available = $this->game->currentState->emptyCells();
        $randomCell = $available[(int) floor(mt_rand() * count($available))];
        $action = new action($randomCell);

        return $action->apply_to($this->game->currentState);
    }

    /**
     * Make the ai player take a novice move,
     * that is: mix between choosing the optimal and suboptimal minimax decisions
     * @param string $turn the player to play, either X or O
     * @return state
     */
    private function take_a_novice_move($turn) {
        $available = $this->game->currentState->emptyCells();

        // Enumerate and calculate the score for each available actions to the ai player.
        /** @var action[] $availableActions */
        $availableActions = array_map(function ($pos) {
            $action = new action($pos); //create the action object
            $nextState = $action->apply_to($this->game->currentState); // Get next state by applying the action.
            $action->minimaxVal = $this->minimax_value($nextState); // Calculate and set the action's minimax value.

            return $action;
        }, $available);

        // Sort the enumerated actions list by score.
        if ($turn === 'X') {
            // X maximizes --> sort the actions in a descending manner to have the action with maximum minimax at first.
            usort($availableActions, action::descending());
        } else {
            // O minimizes --> sort the actions in an ascending manner to have the action with minimum minimax at first.
            usort($availableActions, action::ascending());
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

        return $chosenAction->apply_to($this->game->currentState);
    }

    /**
     * Make the ai player take a master move,
     * that is: choose the optimal minimax decision
     * @param string $turn the player to play, either X or O
     * @return state
     */
    private function take_a_master_move($turn) {
        $available = $this->game->currentState->emptyCells();

        // Enumerate and calculate the score for each avaialable actions to the ai player
        /** @var action[] $availableActions */
        $availableActions = array_map(function ($pos) {
            $action = new action($pos); //create the action object
            $next = $action->apply_to($this->game->currentState); //get next state by applying the action
            $action->minimaxVal = $this->minimax_value($next); //calculate and set the action's minmax value
            return $action;
        }, $available);

        // Sort the enumerated actions list by score
        if ($turn === 'X') {
            // X maximizes --> sort the actions in a descending manner to have the action with maximum minimax at first.
            usort($availableActions, action::descending());
        } else {
            // O minimizes --> sort the actions in an ascending manner to have the action with minimum minimax at first.
            usort($availableActions, action::ascending());
        }

        // Take the first action as it's the optimal.
        $chosenAction = $availableActions[0];

        return $chosenAction->apply_to($this->game->currentState);
    }

    /**
     * Specify the game the ai player will play
     * @param game $_game the game the ai will play
     */
    public function plays($_game) {
        $this->game = $_game;
    }

    /**
     * Notify the ai player that it's its turn
     * @param string $turn the player to play, either X or O
     */
    public function notify($turn) {
        switch ($this->level) {
            //invoke the desired behavior based on the level chosen
            case 'blind':
                $this->take_a_blind_move();
                break;
            case 'novice':
                $this->take_a_novice_move($turn);
                break;
            case 'master':
                $this->take_a_master_move($turn);
                break;
        }
    }
}
