<?php

namespace Zemez\Amp\Helper;

class Video extends \Magento\Framework\App\Helper\AbstractHelper
{
    const VIDEO_TYPE_YOUTUBE = 'youtube';
    const VIDEO_TYPE_VIMEO = 'vimeo';

    /**
     * List of patterns for detecting video ID
     * @var array
     */
    protected $_patternCollection = [
        self::VIDEO_TYPE_YOUTUBE => [
            '/youtube\.com\/watch\?v=([^\&\?\/]+)/',
            '/youtube\.com\/embed\/([^\&\?\/]+)/',
            '/youtube\.com\/v\/([^\&\?\/]+)/',
            '/youtu\.be\/([^\&\?\/]+)/',
        ],
        self::VIDEO_TYPE_VIMEO => [
            '/(?:https?:\/\/)?(?:www.)?(?:player.)?vimeo.com\/(?:[a-z]*\/)*([0-9]*)[?]?.*/',
        ],
    ];


    /**
     * Retrieve video data array by URL
     * @param  string $url
     * @return array|false
     */
    public function getVideoData($url)
    {
        if (!empty($url)) {
            foreach ($this->_patternCollection as $provider => $patterns) {
                foreach ($patterns as $pattern) {
                    $result = preg_match($pattern, $url, $matches);

                    if ($result && !empty($matches[1])) {
                        return [
                            'type' => $provider,
                            'id' => $matches[1],
                            'autoplay' => $provider == self::VIDEO_TYPE_YOUTUBE,
                        ];
                    }
                }
            }
        }

        return false;
    }
}
