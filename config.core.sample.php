<?php
/**
 * This config.core.php file assumes a directory structure like this:
 *
 * - modx/
 * -- assets/
 * -- core/
 * -- connectors/
 * -- manager/
 * -- config.core.php
 * -- index.php
 * - project1/
 * - project2/
 * - project3/
 * - config.core.php -> pointing to modx/config.core.php
 *
 * where modx/ is the MODX Revolution install used for testing the various modMore packages.
 * The config.core.php is set up to include the modx/config.core.php file. The various
 * independent packages can then have their own config.core.php files with the contents
 * as shown below.
 *
 * Be sure to copy this config.core.sample.php file to config.core.php and to NOT include
 * it in the git repo.
 */

include dirname(dirname(__FILE__)) . '/config.core.php';
