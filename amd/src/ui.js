/**
 * @module mod_tictactoe/ai
 */
define([
    'jquery'
], function ($) {
    /*
     * ui object encloses all UI related methods and attributes
     */
    var ui = {};

    // holds the current visible view
    ui.currentView = '';

    /**
     * Switches the view on the UI depending on who's turn it switches
     * @param {String} turn The player to switch the view to (X or O)
     */
    ui.switchViewTo = function (turn) {
        if (ui.currentView !== '') {
            // If the game is in an intermediate state hide the previous view.
            $(ui.currentView).hide();
        }

        // Show the next view.
        ui.currentView = "#" + turn;
        $(ui.currentView).show();
    };

    /**
     * Places X or O in the specified place in the board
     * @param {Number} indx row number (0-indexed)
     * @param {String} symbol X or O
     */
    ui.insertAt = function (indx, symbol) {
        var board = $('.cell');
        var targetCell = $(board[indx]);

        if (!targetCell.hasClass('occupied')) {
            targetCell.html(symbol);
            targetCell.css({
                color: symbol === "X" ? "green" : "red"
            });
            targetCell.addClass('occupied');
        }
    };

    return ui;
});
