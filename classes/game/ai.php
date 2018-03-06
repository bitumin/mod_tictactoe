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
    private $levelOfIntelligence;
    private $game;
    private $Game;
    private $ui;
    private $AIAction;

    /**
     * ai constructor.
     * @param string $levelOfIntelligence
     * @param game $Game
     * @param ui $ui
     * @param AIAction $IAction
     */
    public function __construct($levelOfIntelligence, $Game, $ui, $AIAction) {
        $this->levelOfIntelligence = $levelOfIntelligence;
        $this->Game = $Game;
        $this->game = new \stdClass();
        $this->ui = $ui;
        $this->AIAction = $AIAction;
    }

    private function minimaxValue($state) {
        if ($state->isTerminal()) {
            return $this->Game->score($state);
        }

        $stateScore = null;
        if ($state->turn === 'X') {
            $stateScore = -1000;
        } else {
            $stateScore = 1000;
        }

        $availablePositions = $state->emptyCells();

        // Enumerate next available states using the info form available positions
        $availableNextStates = array_map(function ($pos) use ($state) {
            $action = new AIAction($pos);
            return $action->applyTo($state);
        }, $availablePositions);

        // Calculate the minimax value for all available next states and evaluate the current state's value.
        foreach ($availableNextStates as $nextState) {
            $nextScore = $this->minimaxValue($nextState);
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
     * private function: make the ai player take a blind move
     * that is: choose the cell to place its symbol randomly
     * @param turn [String]: the player to play, either X or O
     */
    private function takeABlindMove($turn) {
        $available = $this->game->currentState->emptyCells();
        $randomCell = $available[(int) floor(mt_rand() * count($available))];
        $action = new AIAction($randomCell);
        $next = $action->applyTo($this->game->currentState);
        $this->ui->insertAt($randomCell, $turn);
        $this->game->advanceTo($next);
    }

    /**
     * private function: make the ai player take a novice move,
     * that is: mix between choosing the optimal and suboptimal minimax decisions
     * @param $turn [String]: the player to play, either X or O
     */
    private function takeANoviceMove($turn) {
        $available = $this->game->currentState->emptyCells();

        // Enumerate and calculate the score for each available actions to the ai player.
        $availableActions = array_map(function ($pos) {
            $action = new AIAction($pos); //create the action object
            $nextState = $action->applyTo($this->game->currentState); // Get next state by applying the action.
            $action->minimaxVal = $this->minimaxValue($nextState); // Calculate and set the action's minimax value.

            return $action;
        }, $available);

        // Sort the enumerated actions list by score.
        if ($turn === 'X') {
            // X maximizes --> sort the actions in a descending manner to have the action with maximum minimax at first.
            $availableActions->sort($this->AIAction->DESCENDING);
        } else {
            // O minimizes --> sort the actions in an ascending manner to have the action with minimum minimax at first.
            $availableActions->sort($this->AIAction->ASCENDING);
        }

        // Take the optimal action 40% of the time, and take the 1st suboptimal action 60% of the time
        $chosenAction = null;
        if (mt_rand() * 100 <= 40) {
            $chosenAction = $availableActions[0];
        } else {
            if ($availableActions->length >= 2) {
                //if there is two or more available actions, choose the 1st suboptimal
                $chosenAction = $availableActions[1];
            } else {
                //choose the only available actions
                $chosenAction = $availableActions[0];
            }
        }
        $next = $chosenAction->applyTo($this->game->currentState);
        $this->ui->insertAt($chosenAction->movePosition, $turn);
        $this->game->advanceTo($next);
    }

    /**
     * private function: make the ai player take a master move,
     * that is: choose the optimal minimax decision
     * @param string $turn the player to play, either X or O
     */
    private function takeAMasterMove($turn) {
        $available = $this->game->currentState->emptyCells();

        // Enumerate and calculate the score for each avaialable actions to the ai player
        $availableActions = array_map(function ($pos) {
            $action = new AIAction($pos); //create the action object
            $next = $action->applyTo($this->game->currentState); //get next state by applying the action
            $action->minimaxVal = $this->minimaxValue($next); //calculate and set the action's minmax value
            return $action;
        }, $available);

        // Sort the enumerated actions list by score
        if ($turn === 'X') {
            // X maximizes --> sort the actions in a descending manner to have the action with maximum minimax at first.
            $availableActions->sort($this->AIAction->DESCENDING);
        } else {
            // O minimizes --> sort the actions in an ascending manner to have the action with minimum minimax at first.
            $availableActions->sort($this->AIAction->ASCENDING);
        }

        // Take the first action as it's the optimal.
        $chosenAction = $availableActions[0];
        $next = $chosenAction->applyTo($this->game->currentState);

        $this->ui->insertAt($chosenAction->movePosition, $turn);

        $this->game->advanceTo($next);
    }

    /**
     * Specify the game the ai player will play
     * @param $_game [Game] the game the ai will play
     */
    public function plays($_game) {
        $this->game = $_game;
    }

    /**
     * Notify the ai player that it's its turn
     * @param $turn [String]: the player to play, either X or O
     */
    public function notify($turn) {
        switch ($this->levelOfIntelligence) {
            //invoke the desired behavior based on the level chosen
            case 'blind':
                $this->takeABlindMove($turn);
                break;
            case 'novice':
                $this->takeANoviceMove($turn);
                break;
            case 'master':
                $this->takeAMasterMove($turn);
                break;
        }
    }
}
