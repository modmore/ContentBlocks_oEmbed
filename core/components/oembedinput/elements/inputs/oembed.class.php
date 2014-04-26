<?php
/**
 * Class oEmbedInput
 * 
 * Integrates with noembed.com to provide a simple embed-a-lot-of-different-things input type for ContentBlocks.
 */
class oEmbedInput extends cbBaseInput {
    public $defaultIcon = 'chunk_C';
    public $defaultTpl = '<div class="oembed-container">[[+html]]</div>';

    public function getCss()
    {
        $assetsUrl = $this->modx->getOption('oembedinput.assets_url', null, MODX_ASSETS_URL . 'components/oembedinput/');
        return array(
            $assetsUrl . 'css/oembed.css',
        );

    }

    /**
     * @return array
     */
    public function getJavaScripts()
    {
        $assetsUrl = $this->modx->getOption('oembedinput.assets_url', null, MODX_ASSETS_URL . 'components/oembedinput/');
        return array(
            $assetsUrl . 'js/oembed.input.js',
        );
    }

    /**
     * @return array
     */
    public function getTemplates()
    {
        $tpls = array();

        // Grab the template
        $corePath = $this->modx->getOption('oembedinput.core_path', null, MODX_CORE_PATH . 'components/oembedinput/');
        $template = file_get_contents($corePath . 'templates/oembedinput.tpl');

        // Add the connector url to the manager page
        $url = $this->modx->getOption('oembedinput.assets_url', null, MODX_ASSETS_URL . 'components/oembedinput/');
        $url .= 'connector.php';

        if ($this->modx->controller) {
            $this->modx->controller->addHtml('<script type="text/javascript">
                var oEmbedInputConnectorUrl = "'.$url.'";
            </script>');
            $this->modx->controller->addLexiconTopic('oembedinput:default');
        }

        $template = str_replace('[[+url]]', $url, $template);

        $tpls[] = $this->contentBlocks->wrapInputTpl('oembedinput', $template);
        return $tpls;
    }

    public function getName()
    {
        return $this->modx->lexicon('oembedinput');
    }

    public function getDescription()
    {
        return $this->modx->lexicon('oembedinput.description');
    }
}
