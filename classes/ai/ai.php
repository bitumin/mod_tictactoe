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
class ai {
    private $levelOfIntelligence;
    private $game;
    private $Game;

    /**
     * ai constructor.
     * @param $levelOfIntelligence
     */
    public function __construct($levelOfIntelligence, $Game) {
        $this->levelOfIntelligence = $levelOfIntelligence;
        $this->Game = $Game;
        $this->game = new \stdClass();
    }

    private function minimaxValue($state) {
        if ($state.isTerminal()) {
            return $this->Game->score($state);
        }

        $stateScore = null;
        if ($state->turn === 'X') {
            $stateScore = -1000;
        } else {
            $stateScore = 1000;
        }

        $availablePositions = $state->emptyCells();

        //enumerate next available states using the info form available positions
        $availableNextStates = array_map(function ($pos) {
            $action = new AIAction($pos);
            return $action->applyTo($state);
        }, $availablePositions);

        /* calculate the minimax value for all available next states
         * and evaluate the current state's value */
        foreach ($availableNextStates as $nextState) {
            $nextScore = $this->minimaxValue($nextState);
            if ($state->turn === "X") {
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
            $available = $game->currentState->emptyCells();
            $randomCell = $this->available[(int) floor(mt_rand() * count($available))];
            $action = new AIAction(randomCell);
            $next = $action->applyTo($game->currentState);
            $ui->insertAt($randomCell, $turn);
            $game->advanceTo($next);
    }

    /**
     * private function: make the ai player take a novice move,
     * that is: mix between choosing the optimal and suboptimal minimax decisions
     * @param $turn [String]: the player to play, either X or O
     */
    private function takeANoviceMove($turn) {
//    var available = game.currentState.emptyCells();
//
//    //enumerate and calculate the score for each available actions to the ai player
//    var availableActions = available.map(function (pos) {
//            var action = new AIAction(pos); //create the action object
//            var nextState = action.applyTo(game.currentState); //get next state by applying the action
//
//            action.minimaxVal = minimaxValue(nextState); //calculate and set the action's minimax value
//
//            return action;
//        });
//
//    //sort the enumerated actions list by score
//    if (turn === "X") {
//        //X maximizes --> sort the actions in a descending manner to have the action with maximum minimax at first
//        availableActions.sort(AIAction.DESCENDING);
//    }
//    else {
//        //O minimizes --> sort the actions in an ascending manner to have the action with minimum minimax at first
//        availableActions.sort(AIAction.ASCENDING);
//    }
//
//    /*
//     * take the optimal action 40% of the time, and take the 1st suboptimal action 60% of the time
//     */
//    var chosenAction;
//    if (Math.random() * 100 <= 40) {
//        chosenAction = availableActions[0];
//    }
//    else {
//        if (availableActions.length >= 2) {
//            //if there is two or more available actions, choose the 1st suboptimal
//            chosenAction = availableActions[1];
//        }
//        else {
//            //choose the only available actions
//            chosenAction = availableActions[0];
//        }
//    }
//    var next = chosenAction.applyTo(game.currentState);
//
//    ui.insertAt(chosenAction.movePosition, turn);
//
//    game.advanceTo(next);
    }

    /**
     * private function: make the ai player take a master move,
     * that is: choose the optimal minimax decision
     * @param turn [String]: the player to play, either X or O
     */
    private function takeAMasterMove($turn) {
//    var available = game.currentState.emptyCells();
//
//    //enumerate and calculate the score for each avaialable actions to the ai player
//    var availableActions = available.map(function (pos) {
//            var action = new AIAction(pos); //create the action object
//            var next = action.applyTo(game.currentState); //get next state by applying the action
//
//            action.minimaxVal = minimaxValue(next); //calculate and set the action's minmax value
//
//            return action;
//        });
//
//    //sort the enumerated actions list by score
//    if (turn === "X") {
//        //X maximizes --> sort the actions in a descending manner to have the action with maximum minimax at first
//        availableActions.sort(AIAction.DESCENDING);
//    } else {
//        //O minimizes --> sort the actions in an ascending manner to have the action with minimum minimax at first
//        availableActions.sort(AIAction.ASCENDING);
//    }
//
//    //take the first action as it's the optimal
//    var chosenAction = availableActions[0];
//    var next = chosenAction.applyTo(game.currentState);
//
//    ui.insertAt(chosenAction.movePosition, turn);
//
//    game.advanceTo(next);
    }


    /**
     * public method to specify the game the ai player will play
     * @param [Game] $_game the game the ai will play
     */
    public function plays($_game) {
        //    game = _game;
    }

    /**
     * public function: notify the ai player that it's its turn
     * @param $turn [String]: the player to play, either X or O
     */
    public function notify($turn) {
        //    switch (levelOfIntelligence) {
        //        //invoke the desired behavior based on the level chosen
        //        case "blind":
        //            takeABlindMove(turn);
        //            break;
        //        case "novice":
        //            takeANoviceMove(turn);
        //            break;
        //        case "master":
        //            takeAMasterMove(turn);
        //            break;
        //    }
        //};
    }
}
