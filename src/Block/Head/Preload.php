<?php

namespace M2Boilerplate\LinkPreload\Block\Head;

use Magento\Framework\View\Element\AbstractBlock;
use Magento\Framework\View\Element\Context;

/**
 * @method bool hasLinkTemplate()
 * @method void setLinkTemplate(string $template)
 * @method string getLinkTemplate()
 */
class Preload extends AbstractBlock
{
    const PATTERN_ATTRS = ':attributes:';
    const PATTERN_URL   = ':path:';
    const AS_ATTRIBUTE   = ':as:';

    protected $assets = [];

    public function __construct(
        Context $context,
        array $data = []
    ) {
        parent::__construct($context, $data);
        if (isset($data['assets']) && is_array($data['assets'])) {
            foreach ($data['assets'] as $asset) {
                if (!isset($asset['url']) || !isset($asset['as'])) {
                    continue;
                }
                $attributes = isset($asset['attributes']) && is_array($asset['attributes'])
                    ? $asset['attributes']
                    : [];
                $this->addPreloadAsset((string) $asset['url'], (string) $asset['as'], $attributes);
            }
        }
    }

    public function addPreloadAsset(string $url, string $as, array $attributes = [])
    {
        $this->assets[$url] = [
            'url' => $url,
            'as' => $as,
            'attributes' => $attributes
        ];
    }

    public function getPreloadAssets(): array
    {
        return $this->assets;
    }

    /**
     * Produce and return block's html output
     *
     * @return string
     */
    protected function _toHtml() {
        $html = '';
        $assets = $this->getPreloadAssets();

        if (empty($assets)) {
            return "\n<!-- Preload: No assets provided -->\n";
        }

        if (!$this->hasLinkTemplate()) {
            return "\n<!-- Preload: No template defined -->\n";
        }

        foreach ($assets as $asset) {
            $attributesHtml = '';
            foreach ($asset['attributes'] as $attributeName => $attributeValue) {
                $attributesHtml .= sprintf(' %s="%s"',
                    $this->escapeHtmlAttr($attributeName),
                    $this->escapeHtmlAttr($attributeValue)
                );
            }
            if (
                strpos($asset['url'], '//') === 0 ||
                strpos($asset['url'], 'http:') === 0 ||
                strpos($asset['url'], 'https:') === 0
            ) {
                $url =  $asset['url'];
            } else {
                $url =  $this->_assetRepo->getUrl($asset['url']);
            }

            $html .= $this->renderLinkTemplate($url, $asset['as'], trim($attributesHtml))."\n";
        }

        return $html;
    }

    /**
     * @param string $assetUrl
     * @param string $as
     * @param string $additionalAttributes
     *
     * @return string
     */
    private function renderLinkTemplate($assetUrl, $as, $additionalAttributes)
    {
        return str_replace(
            [self::PATTERN_URL, self::AS_ATTRIBUTE, self::PATTERN_ATTRS],
            [$assetUrl, $as, $additionalAttributes],
            $this->getLinkTemplate()
        );
    }
}
