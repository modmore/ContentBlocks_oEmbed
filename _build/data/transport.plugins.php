<?php
$plugins = array();

/** create the plugin object */
$plugins[0] = $modx->newObject('modPlugin');
$plugins[0]->set('name','oEmbedInput');
$plugins[0]->set('description','An oEmbed input type for the ContentBlocks package.');
$plugins[0]->set('plugincode', getSnippetContent($sources['plugins'] . 'oembedinput.plugin.php'));

$events = include $sources['data'].'transport.plugin.events.php';
if (is_array($events) && !empty($events)) {
    $plugins[0]->addMany($events);
    $modx->log(xPDO::LOG_LEVEL_INFO,'Packaged in '.count($events).' Plugin Events for oEmbedInput.'); flush();
} else {
    $modx->log(xPDO::LOG_LEVEL_ERROR,'Could not find plugin events for oEmbedInput!');
}
unset($events);

return $plugins;
