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
 * Class containing data for gallery assignment view page
 *
 * @package    mod_tictactoe
 * @copyright  2018 Mitxel Moriana <moriana.mitxel@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace mod_tictactoe\output;

defined('MOODLE_INTERNAL') || die();

global $CFG;
require_once($CFG->dirroot . '/webservice/externallib.php');
require_once(__DIR__ . '/../../locallib.php');

use context;
use mod_tictactoe\external\view_page_exporter;
use renderable;
use stdClass;
use templatable;
use renderer_base;

/**
 * Class containing data for index page
 *
 * @copyright  2018 Mitxel Moriana <moriana.mitxel@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class view_page implements renderable, templatable {
    private $context;

    /**
     * gallery_assignment_page constructor.
     *
     * @param context $context
     */
    public function __construct($context) {
        $this->context = $context;
    }

    /**
     * Export this data so it can be used as the context for a mustache template.
     *
     * @param renderer_base $output
     * @return array|stdClass
     * @throws \coding_exception
     */
    public function export_for_template(renderer_base $output) {
        $exporter = new view_page_exporter(null, [
            'context' => $this->context,
        ]);

        return $exporter->export($output);
    }
}
