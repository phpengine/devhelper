<?php

/**
 * Pharaoh Tools Constants
 */

define('PHARAOH_APP', "ptdeploy") ;

if (in_array(PHP_OS, array("Windows", "WINNT"))) {
    $sd = getenv('SystemDrive') ;
    $pf = getenv('ProgramFiles') ;
    $pf = str_replace(" (x86)", "", $pf) ;
    $command = "where /R \"{$pf}\" *VBoxManage* " ;
    $outputArray = array();
    exec($command, $outputArray);
    define('SUDOPREFIX', "");
    define('VBOXMGCOMM', "\"{$outputArray[0]}\" ") ;
    define('PFILESDIR', $sd."\\PharaohTools\\") ;
    define('PTCCOMM', PFILESDIR.'ptconfigure.cmd"') ;
    define('PTBCOMM', PFILESDIR.'ptbuild.cmd"') ;
    define('PTDCOMM',  PFILESDIR."ptdeploy.cmd") ;
    define('PTVCOMM',  PFILESDIR."ptvirtualize.cmd") ;
    define('PTTRCOMM',  PFILESDIR."pttrack.cmd") ;
    define('PTTECOMM',  PFILESDIR."pttest.cmd") ;
    define('BOXDIR', $sd.'\\PharaohTools\boxes') ;
    define('PIPEDIR', $sd.'\\PharaohTools\pipes'.'\\') ;
    define("DS", "\\");
    define("BASE_TEMP_DIR", getenv("SystemDrive").'\Temp\\'); }
else if (in_array(PHP_OS, array("Linux", "Solaris", "FreeBSD", "OpenBSD", "Darwin"))) {
    $uname = exec('whoami');
    $isAdmin = ($uname == "root") ? true : false ;
    if ($isAdmin == true) { define('SUDOPREFIX', ""); }
    else { define('SUDOPREFIX', "sudo "); }
    define('PFILESDIR', "/opt/") ;
    if (PHP_OS == 'Darwin') {
        $prefix = '/usr/local/bin/' ;
        define('VBOXMGCOMM', "{$prefix}vboxmanage ") ;
        define('PTCCOMM', "{$prefix}ptconfigure ") ;
        define('PTBCOMM', "{$prefix}ptbuild ") ;
        define('PTDCOMM', "{$prefix}ptdeploy ") ;
        define('PTVCOMM', "{$prefix}ptvirtualize") ;
        define('PTTRCOMM', "{$prefix}pttrack") ;
        define('PTTECOMM', "{$prefix}pttest") ;
        define('PTMCOMM', "{$prefix}ptmanage") ;
        define('PTSCOMM', "{$prefix}ptsource") ;
    } else {
        define('VBOXMGCOMM', "vboxmanage ") ;
        define('PTCCOMM', "ptconfigure ") ;
        define('PTBCOMM', "ptbuild ") ;
        define('PTDCOMM', "ptdeploy ") ;
        define('PTVCOMM', "ptvirtualize") ;
        define('PTTRCOMM', "pttrack") ;
        define('PTTECOMM', "pttest") ;
        define('PTMCOMM',  "ptmanage") ;
        define('PTSCOMM', "ptsource") ;
    }
    define("DS", "/");
    define("BASE_TEMP_DIR", '/tmp/');
    define('BOXDIR', '/ptvirtualize/boxes'.'\\') ;
    define('PIPEDIR', '/opt/ptbuild/pipes') ; }

// LOG LEVELS
define('LOG_FAILURE_EXIT_CODE', 1) ;