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

    // holds the state of the initial controls visibility
    ui.intialControlsVisible = true;

    // holds the setInterval handle for the robot flickering
    ui.robotFlickeringHandle = 0;

    // holds the current visible view
    ui.currentView = "";

    /**
     * Switches the view on the UI depending on who's turn it switches
     * @param turn [String]: the player to switch the view to
     */
    ui.switchViewTo = function (turn) {

        //helper function for async calling
        function _switch(_turn) {
            ui.currentView = "#" + _turn;
            $(ui.currentView).show();
        }

        if (ui.intialControlsVisible) {
            // if the game is just starting
            ui.intialControlsVisible = false;
        } else {
            // if the game is in an intermediate state
            if (ui.currentView !== '') {
                $(ui.currentView).hide();
            }
            _switch(turn);
        }
    };

    /**
     * places X or O in the specified place in the board
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
