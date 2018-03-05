/**
 * @module mod_tictactoe/control
 */
define([
    'jquery',
    'mod_tictactoe/game',
    'mod_tictactoe/ui',
    'mod_tictactoe/ai',
    'mod_tictactoe/state'
], function ($, Game, ui, AI, State) {
    return {
        init: function (level) {
            var globals = {};

            globals.level = level;

            if (typeof globals.level !== "undefined") {
                var aiPlayer = new AI(globals.level);
                globals.game = new Game(aiPlayer);
                aiPlayer.plays(globals.game);
                globals.game.start();
            }

            /*
             * click on cell (onclick div.cell) behavior and control
             * if an empty cell is clicked when the game is running and its the human player's turn
             * get the indices of the clicked cell, create the next game state, update the UI, and
             * advance the game to the new created state
             */
            $(".cell").each(function () {
                var $this = $(this);
                $this.click(function () {
                    if (
                        globals.game.status === "running"
                        && globals.game.currentState.turn === "X"
                        && !$this.hasClass('occupied')
                    ) {
                        var indx = parseInt($this.data("indx"));
                        var next = new State(globals.game.currentState);
                        next.board[indx] = "X";
                        ui.insertAt(indx, "X");
                        next.advanceTurn();
                        globals.game.advanceTo(next);
                    }
                });
            });
        }
    };
});
