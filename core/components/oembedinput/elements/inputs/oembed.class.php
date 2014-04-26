<?php
/**
 * Class oEmbedInput
 * 
 * Integrates with noembed.com to provide a simple embed-a-lot-of-different-things input type for ContentBlocks.
 */
class oEmbedInput extends cbBaseInput {
    public $defaultIcon = 'chunk_C';
    public $defaultTpl = '<div class="oembed-container">[[+html]]</div>';

    /**
     * Make sure the oembedinput lexicon is loaded
     *
     * @param ContentBlocks $contentBlocks
     * @param array $options
     */
    public function __construct(ContentBlocks $contentBlocks, array $options = array())
    {
        parent::__construct($contentBlocks, $options);
        $this->modx->lexicon->load('oembedinput:default');
    }

    /**
     * Load the oembed CSS, containing some mild styling stuff.
     *
     * @return array
     */
    public function getCss()
    {
        $assetsUrl = $this->modx->getOption('oembedinput.assets_url', null, MODX_ASSETS_URL . 'components/oembedinput/');
        return array(
            $assetsUrl . 'css/oembed.css',
        );

    }

    /**
     * Load the main input javascript.
     *
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
     * Load the template for the input, and also set a JS variable so the JS can find the connector.
     *
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

    /**
     * Return the name for the input from the lexicon.
     *
     * @return string
     */
    public function getName()
    {
        return $this->modx->lexicon('oembedinput');
    }

    /**
     * Return the description for the input from the lexicon.
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->modx->lexicon('oembedinput.description');
    }
}
