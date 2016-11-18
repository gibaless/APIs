<?php
/**
 * An example of a project-specific implementation.
 * 
 * After registering this autoload function with SPL, the following line
 * would cause the function to attempt to load the \DocuSign\eSign\Baz\Qux class
 * from /path/to/project/src/Baz/Qux.php:
 * 
 *      new \DocuSign\eSign\Baz\Qux;
 *      
 * @param string $class The fully-qualified class name.
 * @return void
 */

 
spl_autoload_register(function ($class) {
	

    // project-specific namespace prefix
    $prefix = 'DocuSign\\eSign\\';

    // base directory for the namespace prefix
    //$base_dir = __DIR__ . '/src/';

	$thisFolder = str_replace('\\', '/', __DIR__);
	$backOneFolder = substr($thisFolder, 0, strrpos ( $thisFolder , "/" ));
	$backTwoFolders = substr($backOneFolder, 0, strrpos ( $backOneFolder , "/" ));
	$base_dir = $backTwoFolders . "/dashboard/src/";

	//$base_dir = "../../dashboard/src/";
	//$base_dir = dirname(__DIR__, 2) . "/dashboard/src/";

    // does the class use the namespace prefix?
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        // no, move to the next registered autoloader
        return;
    }

    // get the relative class name
    $relative_class = substr($class, $len);

    // replace the namespace prefix with the base directory, replace namespace
    // separators with directory separators in the relative class name, append
    // with .php
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

	
   // print_r($file); exit;
    // if the file exists, require it
    if (file_exists($file)) {
        require $file;
    }
    
 
});