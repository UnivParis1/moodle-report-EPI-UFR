<?php

/**
 * Settings and links
 *
 * @package    local
 * @subpackage up1_reportepiufr
 * @author 		El-Miqui CHEMLALI
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

$ADMIN->add('reports',
        new admin_externalpage('local_up1reportepiufr',
                 get_string('pluginname', 'local_up1reportepiufr'),
                "$CFG->wwwroot/local/up1reportepiufr/index.php")
        );
// no report settings
$settings = null;
