<?php

/**
 * Local Configuration Override for DEVELOPMENT MODE.
 *
 * This configuration override file is for providing configuration to use while
 * in development mode. Run:
 *
 * <code>
 * $ composer development-enable
 * </code>
 *
 * from the project root to copy this file to development.local.php and enable
 * the settings it contains.
 *
 * You may also create files matching the glob pattern `{,*.}{global,local}-development.php`.
 */

return [
    'view_manager' => [
        'display_exceptions' => true,
    ],
    'db' => [
    	'username' => 'root',  # change this to match your database's username 
    	'password' => '22222222'       # change this to match your database's password
    ]
];
