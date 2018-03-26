/**
 * @module mod_tictactoe/ui
 */
define([
    'jquery'
], function ($) {
    var ui = {};

    // current visible view
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
        var $targetCell = $('.cell[data-indx="' + indx + '"]');

        if ($targetCell.length !== 1) {
            return false;
        }
        if ($targetCell.hasClass('occupied')) {
            return false;
        }

        $targetCell.html(symbol);
        $targetCell.css({color: symbol === "X" ? "green" : "red"});
        $targetCell.addClass('occupied');

        return true;
    };

    return ui;
});
