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
 * Page module capability definition
 *
 * @package    mod_blending
 * @copyright  2009 Petr Skoda (http://skodak.org)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;


// RISK_SPAM - user can add visible content to site, send messages to other users; originally protected by !isguest()
// RISK_PERSONAL - access to private personal information - ex: backups with user details, non public information in profile (hidden email), etc.; originally protected by isteacher()
// RISK_XSS - user can submit content that is not cleaned (both HTML with active content and unprotected files); originally protected by isteacher()
// RISK_CONFIG - user can change global configuration, actions are missing sanity checks
// RISK_MANAGETRUST - manage trust bitmasks of other users
// RISK_DATALOSS - can destroy large amounts of information that cannot easily be recovered.




$capabilities = [

    'mod/blending:addinstance' => [
        'riskbitmask' => RISK_XSS,

        'captype' => 'write',
        'contextlevel' => CONTEXT_COURSE,
        'archetypes' => [
            'editingteacher' => CAP_ALLOW,
            'manager' => CAP_ALLOW
        ],
        'clonepermissionsfrom' => 'moodle/course:manageactivities'
    ],


    // this just simulates the old (pre 1.7) role-based system
    // admin / author / teacher / tutor / student / (guest)


    'mod/blending:admin' => array(
        'captype'      => 'read',
        'contextlevel' => CONTEXT_MODULE,
        'archetypes' => array(
            'guest'          => CAP_PREVENT,
            'student'        => CAP_PREVENT,
            'teacher'        => CAP_PREVENT,
            'editingteacher' => CAP_PREVENT,
            'coursecreator'  => CAP_PREVENT,
            'manager'        => CAP_ALLOW
        )
    ),


    'mod/blending:author' => array(
        'captype'      => 'read',
        'contextlevel' => CONTEXT_MODULE,
        'archetypes' => array(
            'guest'          => CAP_PREVENT,
            'student'        => CAP_PREVENT,
            'teacher'        => CAP_PREVENT,
            'editingteacher' => CAP_PREVENT,
            'coursecreator'  => CAP_ALLOW,
            'manager'        => CAP_ALLOW
        )
    ),

    'mod/blending:teacher' => array(
        'captype'      => 'read',
        'contextlevel' => CONTEXT_MODULE,
        'archetypes' => array(
            'guest'          => CAP_PREVENT,
            'student'        => CAP_PREVENT,
            'teacher'        => CAP_PREVENT,
            'editingteacher' => CAP_ALLOW,
            'coursecreator'  => CAP_ALLOW,
            'manager'        => CAP_ALLOW
        )
    ),

    // tutor is (non-editing) teacher
    'mod/blending:tutor' => array(
        'captype'      => 'read',
        'contextlevel' => CONTEXT_MODULE,
        'archetypes' => array(
            'guest'          => CAP_PREVENT,
            'student'        => CAP_PREVENT,
            'teacher'        => CAP_ALLOW,
            'editingteacher' => CAP_ALLOW,
            'coursecreator'  => CAP_ALLOW,
            'manager'        => CAP_ALLOW
        )
    ),

    'mod/blending:student' => array(
        'captype'      => 'read',
        'contextlevel' => CONTEXT_MODULE,
        'archetypes' => array(
            'guest'          => CAP_PREVENT,
            'student'        => CAP_ALLOW,
            'teacher'        => CAP_ALLOW,
            'editingteacher' => CAP_ALLOW,
            'coursecreator'  => CAP_ALLOW,
            'manager'        => CAP_ALLOW
        )
    ),

];
