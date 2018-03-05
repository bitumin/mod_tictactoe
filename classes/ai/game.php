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
class Game {

}

///**
// * @module mod_tictactoe/game
// */
//define([
//    'mod_tictactoe/ui',
//    'mod_tictactoe/state'
//], function (ui, State) {
//    /**
//     * Constructs a game object to be played
//     * @param autoPlayer [AIPlayer] : the AI player to be play the game with
//     */
//    var Game = function (autoPlayer) {
//
//        // public : initialize the ai player for this game
//        this.ai = autoPlayer;
//
//        // public : initialize the game current state to empty board configuration
//        this.currentState = new State();
//
//        // public: "E" stands for empty board cell
//        this.currentState.board = [
//            "E", "E", "E",
//            "E", "E", "E",
//            "E", "E", "E"
//        ];
//
//        // public: X plays first
//        this.currentState.turn = "X";
//
//        // public: initialize game status to beginning
//        this.status = "beginning";
//
//        /**
//         * public function that advances the game to a new state
//         * @param _state [State]: the new state to advance the game to
//         */
//        this.advanceTo = function (_state) {
//            this.currentState = _state;
//            if (_state.isTerminal()) {
//                this.status = "ended";
//
//                if (_state.result === "X-won") {
//                    //X won
//                    ui.switchViewTo("won");
//                } else if (_state.result === "O-won") {
//                    //X lost
//                    ui.switchViewTo("lost");
//                } else {
//                    //it's a draw
//                    ui.switchViewTo("draw");
//                }
//            } else {
//                //the game is still running
//                if (this.currentState.turn === "X") {
//                    ui.switchViewTo("human");
//                } else {
//                    ui.switchViewTo("ai");
//                    //notify the AI player its turn has come up
//                    this.ai.notify("O");
//                }
//            }
//        };
//
//        /**
//         * starts the game
//         */
//        this.start = function () {
//            // if (this.status = "beginning") {
//            if (this.status === "beginning") {
//                // invoke advanceTo with the initial state
//                this.advanceTo(this.currentState);
//                this.status = "running";
//            }
//        };
//    };
//
//    /**
//     * public static function that calculates the score of the x player in a given terminal state
//     * @param _state [State]: the state in which the score is calculated
//     * @return {Number} the score calculated for the human player
//     */
//    Game.score = function (_state) {
//        if (_state.result === "X-won") {
//            // the x player won
//            return 10 - _state.oMovesCount;
//        }
//        else if (_state.result === "O-won") {
//            //the x player lost
//            return -10 + _state.oMovesCount;
//        }
//        else {
//            //it's a draw
//            return 0;
//        }
//    };
//
//    return Game;
//});
