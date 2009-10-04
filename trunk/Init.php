<?php
/*
    Copyright 2005 The Board of Regents of the University of Wisconsin System,
    Eric Larson, Nathan Vack

    This file is part of the Library Statistics Database (Libstats).
    Libstats is free software; you may redistribute it and/or modify
    it under the terms of version 2 of the GNU General Public License 
    as published by the Free Software Foundation.

    Libstats is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.


    You should have received a copy of the GNU General Public License
    along with Libstats; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

/**
 *
 * START Libstats configuration
 *	v	v	v	v	v	v	v	v	v	v	v	v	v	v
 */

/**
 * Set PHP error reporting
 */
error_reporting(E_ALL);

/*
 * uncomment the two lines below to include/add the path to the PEAR::DB
 * @pear_include_path	= path to the db.php file, can be found on a linux 
 * 							system by typing "locate db.php"
 */
#$pear_include_path = '/usr/share/php';
#set_include_path(get_include_path(). PATH_SEPARATOR . $pear_include_path);


/**
 * Database connection
 * @dbHost		= host name of the database. eg: localhost, http://xxx.xxx.xxx.xxx
 * @dbName		= name of the database. eg: libstats
 * @dbUser		=	username used to connect to the database with
 * @dbPass		= password used to connect to the database with
 */
$dbHost = 'localhost';
$dbName = 'libstats';
$dbUser = 'libstats';
$dbPass = 'libstats';

/**
 * LDAP Connection information.  If you want to set up LDAP authentication,
 * fill in the information below
 * @ENABLED_LDAP	= whether to user LDAP authentication; default is FALSE
 * @host		= the LDAP server
 * @port		= 389 is the default port, 636 if you are using secure connection
 * @baseDN		= where your users are located in the LDAP
 */
define('ENABLE_LDAP','false');
$ldapConfig = array ('host'		=> 'ldap.myuniversity.edu',
					 'port'		=> '389',
					 'baseDN'	=> 'o=myuniversity.edu');

/**
 * Set the site name here
 * This will affect page titles and the like
 * @SITE_NAME	= name of the site. standard is 'Library Stats'
 */
define('SITE_NAME','Library Stats');


/**
 * Port Strip
 * Set this if your PHP server is really running on a port other than :80; usually not the case
 * @STRIP_PORT = FALSE, don't strip port (runs on standard port 80);
 * 							 TRUE, strip port (web server runs on something besides port 80)
 */
define('STRIP_PORT', false);


/**
 * Admin Personals
 * These values show up on the error page; change to your own info
 * @DEV_NAME		= name of your administrator
 * @DEV_EMAIL		= email address of the administrator
 * @DEV_3SPN		= third person singular pronoun. eg: he, she
 * @DEV_3OPN		= third person objective pronoun. eg: him, her
 * @DEV_PPN			= third person possessive pronoun. eg: his, hers
 */
define('DEV_NAME', 'Your Admin');
define('DEV_EMAIL', 'admin@example.com');
define('DEV_3SPN', 'he');
define('DEV_3OPN', 'him');
define('DEV_PPN', 'his');


/**
 * Site Maintanence
 * Can put site in maintanence mode displaying a "Maintanence" page during upgrade, debugging or
 * maintanence
 * @SITE_MAINTANENCE = FALSE, run site as normal
 * 										 TRUE, put site in maintanence mode
 */
define('SITE_MAINTANENCE', false);


/**
 * Site Maintanence Pass
 * This IP address will really be able to log in
 * @DEBUG_IP		= IP address of machine that CAN log in when SITE_MAINANENCE is enabled
 */
define('DEBUG_IP', "");


/**
 * Auto Admin Login
 * Set this to true if you want to skip authentication and make everyone an admin. 
 * Useful for things like the W3C Validators
 * @AUTO_ADMIN_LOGON = FALSE, force all users to log in
 * 										 TRUE, automatically log in as Administrator skipping login screen
 */
define('AUTO_ADMIN_LOGON', false);


/**
 * Calendar widget
 * This is for enabling the DHTML Calendar. This Calendar can be obtained at
 * 	http://www.dynarch.com/projects/calendar/ by Mihai Bazon 
 * 	GNU Lesser General Public License
 * 1) Download http://www.dynarch.com/static/jscalendar-1.0.zip (tested 20090507)
 * 2) Unpack jscalendar-1.0.zip into a directory called jscalendar-1.0
 * 	Path should be: libstats/addons/jscalendar-1.0 
 * 3) Enable calendar widget as true
 * @CAL_WIDGET	=	FALSE, will not display/use the calendar widget
 * 							= TRUE, display the calendat widget
 */
define('CAL_WIDGET', false);


/**
 *
 * 	^	^	^	^	^	^	^	^	^	^	^	^	^	
 * END Libstats configuration
 */


/**
 *
 *	==========================================================
 *
 *	DO NOT EDIT THE SETTINGS BELOW THIS LINE UNLESS YOU MEAN TO
 *
 *	-----------------------------------------------------------
 *
 *	Modifying the below information may cause LibStats not to run properly
 *
 */

/* This is autogenerated. Change from mysql if you're feeling adventurous */
define('DSN', "mysql://$dbUser:$dbPass@$dbHost/$dbName");

// Disable magic quotes with prejudice
if (get_magic_quotes_gpc()) {
    $_POST = array_map('stripslashes_deep', $_POST);
    $_GET = array_map('stripslashes_deep', $_GET);
    $_COOKIE = array_map('stripslashes_deep', $_COOKIE);
	$_REQUEST = array_map('stripslashes_deep', $_REQUEST);
}

// Fix order of variables in $_REQUEST; cookie variables should have less
// importance.
$_REQUEST = array_merge($_COOKIE, $_GET, $_POST);

function stripslashes_deep($value) {
    $value = is_array($value) ?
        array_map('stripslashes_deep', $value) :
        stripslashes($value);
    return $value;
}

session_start();
if (!isset($_SESSION['loggedIn'])) { $_SESSION['loggedIn'] = false; }

function registerAll($pattern) {
     foreach (glob($pattern) as $file) {
         require_once($file);
     }
}

if (STRIP_PORT) {
  // Pull the port number off $_SERVER['HTTP_HOST']
  $portPos = strpos($_SERVER['HTTP_HOST'], ":");
  if ($portPos) {
      $_SERVER['HTTP_HOST'] = substr($_SERVER['HTTP_HOST'], 0, $portPos);
  }  
}

require_once 'DB.php';
require_once 'Utils.php';

//loop through subclasses--tight code! San Dimas High-School Football rules!
registerAll('actions/*Action.php');
registerAll('reports/*Report.php');
registerAll('finders/*Finder.php');

// Clear this, in case PageErrorAction somehow doesn't set it.
$rInfo['renderer'] = '';
$db = DB::connect(DSN);
$_REQUEST['db'] = $db;
if (DB::isError($db)) {
    // This will be a fatal error, head to a bad page
    $act = new PageErrorAction();
    $rInfo = $act->perform();
    include $rInfo['renderer'];

    die;
}

// Let's try and share the database connection throughout the application;
// reopening it all the time is a HUGE PAIN.
$_REQUEST['db']->setFetchMode(DB_FETCHMODE_ASSOC);

?>
