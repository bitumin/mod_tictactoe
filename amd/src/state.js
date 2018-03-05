/**
 * @module mod_tictactoe/state
 */
define([], function () {
    /**
     * Represents a state in the game
     * @param old [State]: old state to intialize the new state
     */
    var State = function (old) {
        /**
         * public : the player who has the turn to player
         */
        this.turn = "";

        /**
         * public : the number of moves of the AI player
         */
        this.oMovesCount = 0;

        /**
         * public : the result of the game in this State
         */
        this.result = "still running";

        /**
         * public : the board configuration in this state
         */
        this.board = [];

        /* Begin Object Construction */
        if (typeof old !== "undefined") {
            // if the state is constructed using a copy of another state
            var len = old.board.length;
            this.board = new Array(len);
            for (var itr = 0; itr < len; itr++) {
                this.board[itr] = old.board[itr];
            }

            this.oMovesCount = old.oMovesCount;
            this.result = old.result;
            this.turn = old.turn;
        }
        /* End Object Construction */

        /**
         * public : advances the turn in a the state
         */
        this.advanceTurn = function () {
            this.turn = this.turn === "X" ? "O" : "X";
        };

        /**
         * public function that enumerates the empty cells in state
         * @return {Array} indices of all empty cells
         */
        this.emptyCells = function () {
            var indxs = [];
            for (var itr = 0; itr < 9; itr++) {
                if (this.board[itr] === "E") {
                    indxs.push(itr);
                }
            }
            return indxs;
        };

        /**
         * public  function that checks if the state is a terminal state or not
         * the state result is updated to reflect the result of the game
         * @returns {Boolean} true if it's terminal, false otherwise
         */
        this.isTerminal = function () {
            var B = this.board;

            //check rows
            for (var i = 0; i <= 6; i = i + 3) {
                if (B[i] !== "E" && B[i] === B[i + 1] && B[i + 1] == B[i + 2]) {
                    this.result = B[i] + "-won"; //update the state result
                    return true;
                }
            }

            //check columns
            for (var k = 0; k <= 2; k++) {
                if (B[k] !== "E" && B[k] === B[k + 3] && B[k + 3] === B[k + 6]) {
                    this.result = B[k] + "-won"; //update the state result
                    return true;
                }
            }

            //check diagonals
            for (var m = 0, p = 4; m <= 2; m = m + 2, p = p - 2) {
                if (B[m] !== "E" && B[m] == B[m + p] && B[m + p] === B[m + 2 * p]) {
                    this.result = B[m] + "-won"; //update the state result
                    return true;
                }
            }

            var available = this.emptyCells();
            if (available.length == 0) {
                //the game is draw
                this.result = "draw"; //update the state result
                return true;
            }
            else {
                return false;
            }
        };
    };

    return State;
});
