<?php

/**
* Be sure to set the correct values here and rename or copy this file and name it config.php
*/

//Github API token
define('APITOKEN', 'YOURGITTOKENHERE');
//API Repo owner username
define('OWNER',    'REPOOWNER');
//Repository name
define('REPO',     'howtobuybitcoinsinfo-website');
//User who will be sending the PRs
define('GITUSER',  'Example user');
//Email address for the above user
define('EMAIL',    'bob@example.org');


//Wether we are in development or not. Allows for unlimited sending of PRs et cetera.
define('DEVELOPMENT',    FALSE);

//Select the update interval that users can send requests at. (In Seconds)
define('UPDATEINTERVAL', 1800);


?>