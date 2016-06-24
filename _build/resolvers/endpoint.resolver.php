<?php

if ($object->xpdo) {
    switch ($options[xPDOTransport::PACKAGE_ACTION]) {
        case xPDOTransport::ACTION_INSTALL:
        case xPDOTransport::ACTION_UPGRADE:
            /** @var modX $modx */
            $modx =& $object->xpdo;

            $current = $modx->getOption('oembedinput.endpoint');
            // If we're using the standard http, update to https
            if ($current == 'http://noembed.com/embed?nowrap=on&url=') {
                $setting = $modx->getObject('modSystemSetting', array('key' => 'oembedinput.endpoint'));
                if ($setting) {
                    $setting->set('value', 'https://noembed.com/embed?nowrap=on&url='); // Set it to 10MB instead
                    if ($setting->save()) {
                        $modx->log(modX::LOG_LEVEL_INFO, 'Updated the oembedinput.endpoint setting to use HTTPS.');
                    }
                }
            }

            break;
    }
}
return true;