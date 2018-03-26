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
 * Prints a particular instance of tictactoe
 *
 * @package    mod_tictactoe
 * @copyright  2018 Mitxel Moriana <moriana.mitxel@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/*
 * Little explanation concerning Moodle views:
 *
 * view.php is the entry point for out activity instance view.
 *
 * A typical "modern" Moodle view has about 8 parts:
 * - Parameters fetching
 * - Login, context and capabilities checks (security)
 * - Page view event triggers (recommended so admins can keep track of possible errors, unwanted accesses...)
 * - Page setup (self-url, title, heading, >>> layout selection <<<, front-end libraries dependencies...)
 * - Controller or business logic (fetching all necessary data to actually print the view)
 * - echo $OUTPUT->header(); (tells the layout the view printing has started)
 * - Renderer instantiation and renderer methods to finally render our page content
 * - echo $OUTPUT->footer(); (tells the layout the view printing has finished)
 *
 * Notice that the page layout will take care of printing the header and footer of the page, while
 * our renderer and rendering methods will just take care of the "page-specific" main content.
 */

require_once(__DIR__ . '/../../config.php');
require_once(__DIR__ . '/lib.php');

$cmid = optional_param('id', 0, PARAM_INT); // Course_module ID.
$tictactoeid = optional_param('t', 0, PARAM_INT); // Tictactoe instance ID.
$gameid = optional_param('g', 0, PARAM_INT); // Tictactoe game ID.

if ($gameid) {
    $tictactoegame = new \mod_tictactoe\persistent\tictactoe_game($gameid);
    $tictactoe = $tictactoegame->get_tictactoe();
    $cm = $tictactoe->get_cm();
    $course = $tictactoe->get_course_record();
} else if ($tictactoeid) {
    $tictactoe = new \mod_tictactoe\persistent\tictactoe($tictactoeid);
    $cm = $tictactoe->get_cm();
    $course = $tictactoe->get_course_record();
    $tictactoegame = \mod_tictactoe\api::get_tictactoe_game($tictactoe);
} else if ($cmid) {
    list($course, $cm) = get_course_and_cm_from_cmid($cmid);
    $tictactoe = new \mod_tictactoe\persistent\tictactoe($cm->instance);
    $tictactoegame = \mod_tictactoe\api::get_tictactoe_game($tictactoe);
} else {
    print_error('missingparameter');
}

require_login($course, true, $cm);

$context = context_module::instance($cm->id);

$event = \mod_tictactoe\event\course_module_viewed::create(array(
    'objectid' => $PAGE->cm->instance,
    'context' => $PAGE->context,
));
$event->add_record_snapshot('course', $PAGE->course);
$event->add_record_snapshot($PAGE->cm->modname, $tictactoe->to_record());
$event->trigger();

$PAGE->set_url('/mod/tictactoe/view.php', array('id' => $cm->id));
$PAGE->set_title(format_string($tictactoe->get('name')));
$PAGE->set_heading(format_string($course->fullname));

/*
 * Other things you may want to set - remove if not needed.
 * $PAGE->set_cacheable(false);
 * $PAGE->set_focuscontrol('some-html-id');
 * $PAGE->add_body_class('tictactoe-'.$somevar);
 * $PAGE->set_pagelayout('my_custom_layout');
 */

// Output starts here.
echo $OUTPUT->header();

if (!empty($tictactoe->get('intro'))) {
    echo $OUTPUT->box(format_module_intro('tictactoe', $tictactoe, $cm->id), 'generalbox mod_introbox', 'tictactoeintro');
}
echo $OUTPUT->heading(format_string($tictactoe->get('name')));

/** @var mod_tictactoe\output\renderer $renderer */
$renderer = $PAGE->get_renderer('mod_tictactoe');
// The page object takes care of fetching the data for the view.
$page = new \mod_tictactoe\output\view_page($context, $tictactoe, $tictactoegame);
echo $renderer->render_view_page($page); // The renderer and its methods takes care of passing the page data to a template.

// Ideally, the template takes care of all the front-end logic (libraries, strings, styling and so on...).
// If possible, it's better to avoid handling any front-end operations from this file.

// Output finishes here.
echo $OUTPUT->footer();
