<?php
/**
 * @package oembedinput
 */
class oEmbedLoadEmbedProcessor extends modProcessor {
    public $link = '';

    public function checkPermissions()
    {
        return $this->modx->hasPermission('frames');
    }

    /**
     * @return bool|string
     */
    public function initialize() {
        $this->link = $this->getProperty('url');
        $this->link = trim($this->link);
        if (empty($this->link)) {
            return '';
        }

        if (substr($this->link, 0, 4) !== 'http') {
            $this->link = 'http://' . $this->link;
        }

        // Make sure we're dealing with a valid url
        if (filter_var($this->link, FILTER_VALIDATE_URL) === false) {
            return '';
        }

        return true;
    }

    /**
     * @return bool
     */
    public function process() {
        $url = $this->modx->getOption('oembedinput.endpoint', null, 'https://noembed.com/embed?nowrap=on&url=') . urlencode($this->link);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($ch);
        if( curl_errno( $ch ) ){
            return $this->failure('An error occured communicating to the server: ' . curl_error($ch));
        }
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if( $http_code !== 200 ){
            return $this->failure('Uh oh, something went wrong retrieving embed information. Received data: ' . $result);
        }
        curl_close($ch);

        $result = $this->modx->fromJSON($result);

        if (!isset($result['error'])) {
            return $this->success('', $result);
        }
        return $this->failure('Could not load information about this embed. Received error: ' . $result['error']);
    }
}

return 'oEmbedLoadEmbedProcessor';
