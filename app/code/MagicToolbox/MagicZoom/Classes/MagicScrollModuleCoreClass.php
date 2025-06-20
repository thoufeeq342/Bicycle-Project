<?php

namespace MagicToolbox\MagicZoom\Classes;

/**
 * MagicScrollModuleCoreClass
 *
 */
class MagicScrollModuleCoreClass extends \stdClass
{

    /**
     * MagicToolboxParamsClass class
     *
     * @var \MagicToolbox\MagicZoom\Classes\MagicToolboxParamsClass
     *
     */
    public $params;

    /**
     * Tool type
     *
     * @var   string
     *
     */
    public $type = 'category';

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct($reloadDefaults = true)
    {
        static $params = null;
        if ($params === null) {
            $params = new MagicToolboxParamsClass();
            $params->setScope('magicscroll');
            $params->setMapping([
                'width' => ['0' => 'auto'],
                'height' => ['0' => 'auto'],
                'step' => ['0' => 'auto'],
                'pagination' => ['Yes' => 'true', 'No' => 'false'],
                'scrollOnWheel' => ['turn on' => 'true', 'turn off' => 'false'],
                'lazy-load' => ['Yes' => 'true', 'No' => 'false'],
            ]);
            //NOTE: if the constructor is called for the first time, we load the defaults anyway
            $reloadDefaults = true;
        }
        $this->params = $params;

        //NOTE: do not load defaults, if they have already been loaded by MagicScroll module
        if ($reloadDefaults) {
            $this->loadDefaults();
        }
    }

    /**
     * Method to get headers string
     *
     * @param string $jsPath  Path to JS file
     * @param string $cssPath Path to CSS file
     *
     * @return string
     */
    public function getHeadersTemplate($jsPath = '', $cssPath = null, $linkModuleCss = true)
    {
        if ($cssPath == null) {
            $cssPath = $jsPath;
        }
        $headers = [];
        // add module version
        $headers[] = '<!-- Magic Zoom Magento 2 module version v1.7.8 [v1.6.97:v5.3.7] -->';
        $headers[] = '<script type="text/javascript">window["mgctlbx$Pltm"] = "Magento 2";</script>';
        // add tool style link
        $headers[] = '<link type="text/css" href="' . $cssPath . '/magicscroll.css" rel="stylesheet" media="screen" />';
        if ($linkModuleCss) {
            // add module style link
            $headers[] = '<link type="text/css" href="' . $cssPath . '/magicscroll.module.css" rel="stylesheet" media="screen" />';
        }
        // add script link
        $headers[] = '<script type="text/javascript" src="' . $jsPath . '/magicscroll.js"></script>';
        // add options
        $headers[] = $this->getOptionsTemplate();
        return "\r\n" . implode("\r\n", $headers) . "\r\n";
    }

    /**
     * Method to get options string
     *
     * @return string
     */
    public function getOptionsTemplate()
    {
        return "<script type=\"text/javascript\">\n\tMagicScrollOptions = {\n\t\t" . $this->params->serialize(true, ",\n\t\t") . "\n\t}\n</script>";
    }

    /**
     * Method to get MagicScroll HTML
     *
     * @param array $itemsData MagicScroll data
     * @param array $params Additional params
     *
     * @return string
     */
    public function getMainTemplate($itemsData, $params = [])
    {
        $id = '';
        $width = '';
        $height = '';

        $html = [];

        extract($params);

        if (empty($width)) {
            $width = '';
        } else {
            $width = " width=\"{$width}\"";
        }
        if (empty($height)) {
            $height = '';
        } else {
            $height = " height=\"{$height}\"";
        }

        if (empty($id)) {
            $id = '';
        } else {
            $id = ' id="' . addslashes($id) . '"';
        }

        // add div with tool className
        $additionalClasses = $this->params->getValue('scroll-extra-styles');
        if (empty($additionalClasses)) {
            $additionalClasses = '';
        } else {
            $additionalClasses = ' ' . $additionalClasses;
        }

        //NOTE: get personal options
        $options = $this->params->serialize();
        if (empty($options)) {
            $options = '';
        } else {
            $options = ' data-options="' . $options . '"';
        }

        $html[] = '<div' . $id . ' class="MagicScroll' . $additionalClasses . '"' . $width . $height . $options . '>';

        // add items
        foreach ($itemsData as $item) {

            $img = '';
            $img2x = '';
            $thumb = '';
            $thumb2x = '';
            $link = '';
            $target = '';
            $alt = '';
            $title = '';
            $description = '';
            $width = '';
            $height = '';
            $medium = '';
            $content = '';

            extract($item);

            // check big image
            if (empty($img)) {
                $img = '';
            }

            //NOTE: remove this?
            if (!empty($medium)) {
                $thumb = $medium;
            }

            // check thumbnail
            if (!empty($img) || empty($thumb)) {
                $thumb = $img;
            }
            if (!empty($img2x) || empty($thumb2x)) {
                $thumb2x = $img2x;
            }

            // check item link
            if (empty($link)) {
                $link = '';
            } else {
                // check target
                if (empty($target)) {
                    $target = '';
                } else {
                    $target = ' target="' . $target . '"';
                }
                $link = $target . ' href="' . addslashes($link) . '"';
            }

            // check item alt tag
            if (empty($alt)) {
                $alt = '';
            } else {
                $alt = htmlspecialchars(htmlspecialchars_decode($alt, ENT_QUOTES));
            }

            // check title
            if (empty($title)) {
                $title = '';
            } else {
                $title = htmlspecialchars(htmlspecialchars_decode($title, ENT_QUOTES));
                if (empty($alt)) {
                    $alt = $title;
                }
                if ($this->params->checkValue('show-image-title', 'No')) {
                    $title = '';
                }
            }

            // check description
            if (!empty($description) && $this->params->checkValue('show-image-title', 'Yes')) {
                //$description = preg_replace("/<(\/?)a([^>]*)>/is", "[$1a$2]", $description);
                //NOTICE: span or div?
                //NOTICE: scroll takes the first child after image and place it in span.mcs-caption
                if (empty($title)) {
                    $title = "<span class=\"mcs-description\">{$description}</span>";
                } else {
                    //NOTE: to wrap title in span for show with description
                    $title = "<span>{$title}<br /><span class=\"mcs-description\">{$description}</span></span>";
                }
            }

            if (empty($width)) {
                $width = '';
            } else {
                $width = " width=\"{$width}\"";
            }
            if (empty($height)) {
                $height = '';
            } else {
                $height = " height=\"{$height}\"";
            }

            if (!empty($thumb2x)) {
                //$thumb2x = ' srcset="' . $thumb2x . ' 2x"';
                //$thumb2x = ' srcset="' . $thumb . ' 1x, ' . $thumb2x . ' 2x"';
                $thumb2x = ' srcset="' . str_replace(' ', '%20', $thumb) . ' 1x, ' . str_replace(' ', '%20', $thumb2x) . ' 2x"';
            }

            // add item
            if (empty($content)) {
                $html[] = "<a{$link}><img{$width}{$height} src=\"{$thumb}\" {$thumb2x} alt=\"{$alt}\" />{$title}</a>";
            } else {
                $html[] = "<div class=\"mcs-content-container\">{$content}</div>";
            }
        }

        // close core div
        $html[] = '</div>';

        // create HTML string
        $html = implode('', $html);

        // return result
        return $html;
    }

    /**
     * Method to load defaults options
     *
     * @return void
     */
    public function loadDefaults()
    {
        $params = [
            "enable-effect"=>["id"=>"enable-effect","group"=>"General","order"=>"10","default"=>"Yes","label"=>"Enable Magic Zoom","type"=>"array","subType"=>"select","values"=>["Yes","No"],"scope"=>"module"],
            "template"=>["id"=>"template","group"=>"General","order"=>"20","default"=>"bottom","label"=>"Thumbnail layout","type"=>"array","subType"=>"select","values"=>["bottom","left","right","top"],"scope"=>"module"],
            "include-headers-on-all-pages"=>["id"=>"include-headers-on-all-pages","group"=>"General","order"=>"21","default"=>"No","label"=>"Include headers on all pages","description"=>"To be able to apply effect on any CMS page.","type"=>"array","subType"=>"radio","values"=>["Yes","No"],"scope"=>"module"],
            "magicscroll"=>["id"=>"magicscroll","group"=>"General","order"=>"22","default"=>"No","label"=>"Scroll thumbnails","description"=>"Powered by the versatile <a target=\"_blank\" href=\"https://marketplace.magento.com/magictoolbox-magicscroll.html\">Magic Scroll</a>. Buy Magic Scroll and upload the magicscroll.js file to your server to remove 'Magic Scroll trial version' message.","type"=>"array","subType"=>"select","values"=>["Yes","No"],"scope"=>"module"],
            "thumb-max-width"=>["id"=>"thumb-max-width","group"=>"Positioning and Geometry","order"=>"10","default"=>"550","label"=>"Maximum width of thumbnail (in pixels)","type"=>"num","scope"=>"module"],
            "thumb-max-height"=>["id"=>"thumb-max-height","group"=>"Positioning and Geometry","order"=>"11","default"=>"550","label"=>"Maximum height of thumbnail (in pixels)","type"=>"num","scope"=>"module"],
            "zoomWidth"=>["id"=>"zoomWidth","group"=>"Positioning and Geometry","order"=>"20","default"=>"auto","label"=>"Width of zoom window","description"=>"pixels or percentage, e.g. 400 or 100%.","type"=>"text","scope"=>"magiczoom"],
            "zoomHeight"=>["id"=>"zoomHeight","group"=>"Positioning and Geometry","order"=>"30","default"=>"auto","label"=>"Height of zoom window","description"=>"pixels or percentage, e.g. 400 or 100%.","type"=>"text","scope"=>"magiczoom"],
            "zoomPosition"=>["id"=>"zoomPosition","group"=>"Positioning and Geometry","order"=>"40","default"=>"right","label"=>"Position of zoom window","type"=>"array","subType"=>"radio","values"=>["top","right","bottom","left","inner"],"scope"=>"magiczoom"],
            "square-images"=>["id"=>"square-images","group"=>"Positioning and Geometry","order"=>"40","default"=>"No","label"=>"Always create square images","description"=>"","type"=>"array","subType"=>"radio","values"=>["Yes","No"],"scope"=>"module"],
            "zoomDistance"=>["id"=>"zoomDistance","group"=>"Positioning and Geometry","order"=>"50","default"=>"15","label"=>"Zoom distance","description"=>"Distance between small image and zoom window (in pixels).","type"=>"num","scope"=>"magiczoom"],
            "selectorTrigger"=>["id"=>"selectorTrigger","advanced"=>"1","group"=>"Multiple images","order"=>"10","default"=>"click","label"=>"Swap trigger","description"=>"Mouse event used to switch between multiple images.","type"=>"array","subType"=>"radio","values"=>["click","hover"],"scope"=>"magiczoom","desktop-only"=>""],
            "selector-max-width"=>["id"=>"selector-max-width","group"=>"Multiple images","order"=>"10","default"=>"100","label"=>"Maximum width of additional thumbnails (in pixels)","type"=>"num","scope"=>"module"],
            "selector-max-height"=>["id"=>"selector-max-height","group"=>"Multiple images","order"=>"11","default"=>"100","label"=>"Maximum height of additional thumbnails (in pixels)","type"=>"num","scope"=>"module"],
            "transitionEffect"=>["id"=>"transitionEffect","advanced"=>"1","group"=>"Multiple images","order"=>"20","default"=>"Yes","label"=>"Transition effect on swap","description"=>"Whether to enable dissolve effect when switching between images.","type"=>"array","subType"=>"radio","values"=>["Yes","No"],"scope"=>"magiczoom"],
            "lazyZoom"=>["id"=>"lazyZoom","group"=>"Miscellaneous","order"=>"10","default"=>"No","label"=>"Lazy load of zoom image","description"=>"Whether to load large image on demand (on first activation).","type"=>"array","subType"=>"radio","values"=>["Yes","No"],"scope"=>"magiczoom"],
            "rightClick"=>["id"=>"rightClick","group"=>"Miscellaneous","order"=>"20","default"=>"No","label"=>"Right-click menu on image","type"=>"array","subType"=>"radio","values"=>["Yes","No"],"scope"=>"magiczoom","desktop-only"=>""],
            "cssClass"=>["id"=>"cssClass","advanced"=>"1","group"=>"Miscellaneous","order"=>"30","default"=>"","label"=>"Extra CSS","description"=>"Extra CSS class(es) to apply to zoom instance.","type"=>"text","scope"=>"magiczoom"],
            "link-to-product-page"=>["id"=>"link-to-product-page","group"=>"Miscellaneous","order"=>"30","default"=>"Yes","label"=>"Link image to the product page","type"=>"array","subType"=>"select","values"=>["Yes","No"],"scope"=>"module"],
            "youtube-nocookie"=>["id"=>"youtube-nocookie","group"=>"Miscellaneous","order"=>"200","default"=>"No","label"=>"Use youtube-nocookie.com","description"=>"Use youtube-nocookie.com instead of youtube.com for YouTube videos.","type"=>"array","subType"=>"radio","values"=>["Yes","No"],"scope"=>"module"],
            "show-message"=>["id"=>"show-message","group"=>"Miscellaneous","order"=>"370","default"=>"No","label"=>"Show message under images","type"=>"array","subType"=>"radio","values"=>["Yes","No"],"scope"=>"module"],
            "message"=>["id"=>"message","group"=>"Miscellaneous","order"=>"380","default"=>"Move your mouse over image","label"=>"Enter message to appear under images","type"=>"text","scope"=>"module"],
            "zoomMode"=>["id"=>"zoomMode","group"=>"Zoom mode","order"=>"10","default"=>"zoom","label"=>"Zoom mode","description"=>"How to zoom image. off - disable zoom.","type"=>"array","subType"=>"radio","values"=>["zoom","magnifier","preview","off"],"scope"=>"magiczoom","desktop-only"=>"preview"],
            "zoomOn"=>["id"=>"zoomOn","group"=>"Zoom mode","order"=>"20","default"=>"hover","label"=>"Zoom on","description"=>"When to activate zoom.","type"=>"array","subType"=>"radio","values"=>["hover","click"],"scope"=>"magiczoom","desktop-only"=>""],
            "upscale"=>["id"=>"upscale","advanced"=>"1","group"=>"Zoom mode","order"=>"30","default"=>"Yes","label"=>"Upscale image","description"=>"Whether to scale up the large image if its original size is not enough for a zoom effect.","type"=>"array","subType"=>"radio","values"=>["Yes","No"],"scope"=>"magiczoom"],
            "smoothing"=>["id"=>"smoothing","advanced"=>"1","group"=>"Zoom mode","order"=>"35","default"=>"Yes","label"=>"Smooth zoom movement","type"=>"array","subType"=>"radio","values"=>["Yes","No"],"scope"=>"magiczoom"],
            "variableZoom"=>["id"=>"variableZoom","advanced"=>"1","group"=>"Zoom mode","order"=>"40","default"=>"No","label"=>"Variable zoom","description"=>"Whether to allow changing zoom ratio with mouse wheel.","type"=>"array","subType"=>"radio","values"=>["Yes","No"],"scope"=>"magiczoom","desktop-only"=>""],
            "zoomCaption"=>["id"=>"zoomCaption","group"=>"Zoom mode","order"=>"50","default"=>"off","label"=>"Caption in zoom window","description"=>"Position of caption on zoomed image. off - disable caption on zoom window.","type"=>"array","subType"=>"radio","values"=>["top","bottom","off"],"scope"=>"magiczoom"],
            "hint"=>["id"=>"hint","group"=>"Hint","order"=>"10","default"=>"once","label"=>"Display hint to suggest image is zoomable","description"=>"How to show hint. off - disable hint.","type"=>"array","subType"=>"radio","values"=>["once","always","off"],"scope"=>"magiczoom"],
            "textHoverZoomHint"=>["id"=>"textHoverZoomHint","advanced"=>"1","group"=>"Hint","order"=>"20","default"=>"Hover to zoom","label"=>"Hint to suggest image is zoomable (on hover)","description"=>"Hint that shows when zoom mode is enabled, but inactive, and zoom activates on hover (Zoom on: hover).","type"=>"text","scope"=>"magiczoom"],
            "textClickZoomHint"=>["id"=>"textClickZoomHint","advanced"=>"1","group"=>"Hint","order"=>"21","default"=>"Click to zoom","label"=>"Hint to suggest image is zoomable (on click)","description"=>"Hint that shows when zoom mode is enabled, but inactive, and zoom activates on click (Zoom on: click).","type"=>"text","scope"=>"magiczoom"],
            "zoomModeForMobile"=>["id"=>"zoomModeForMobile","group"=>"Mobile","order"=>"10","default"=>"off","label"=>"Zoom mode","description"=>"How to zoom image. off - disable zoom.","type"=>"array","subType"=>"radio","values"=>["zoom","magnifier","off"],"scope"=>"magiczoom-mobile"],
            "textHoverZoomHintForMobile"=>["id"=>"textHoverZoomHintForMobile","advanced"=>"1","group"=>"Mobile","order"=>"20","default"=>"Touch to zoom","label"=>"Hint to suggest image is zoomable (on hover)","description"=>"Hint that shows when zoom mode is enabled, but inactive, and zoom activates on hover (Zoom on: hover).","type"=>"text","scope"=>"magiczoom-mobile"],
            "textClickZoomHintForMobile"=>["id"=>"textClickZoomHintForMobile","advanced"=>"1","group"=>"Mobile","order"=>"21","default"=>"Double tap or pinch to zoom","label"=>"Hint to suggest image is zoomable (on click)","description"=>"Hint that shows when zoom mode is enabled, but inactive, and zoom activates on click (Zoom on: click).","type"=>"text","scope"=>"magiczoom-mobile"],
            "width"=>["id"=>"width","group"=>"Scroll","order"=>"10","default"=>"auto","label"=>"Scroll width","description"=>"auto | pixels | percentage","type"=>"text","scope"=>"magicscroll"],
            "height"=>["id"=>"height","group"=>"Scroll","order"=>"20","default"=>"auto","label"=>"Scroll height","description"=>"auto | pixels | percentage","type"=>"text","scope"=>"magicscroll"],
            "orientation"=>["id"=>"orientation","group"=>"Scroll","order"=>"30","default"=>"horizontal","label"=>"Orientation of scroll","type"=>"array","subType"=>"radio","values"=>["horizontal","vertical"],"scope"=>"magicscroll"],
            "mode"=>["id"=>"mode","group"=>"Scroll","order"=>"40","default"=>"scroll","label"=>"Scroll mode","type"=>"array","subType"=>"radio","values"=>["scroll","animation","carousel","cover-flow"],"scope"=>"magicscroll"],
            "items"=>["id"=>"items","group"=>"Scroll","order"=>"50","default"=>"3","label"=>"Items to show","description"=>"auto | fit | integer | array","type"=>"text","scope"=>"magicscroll"],
            "speed"=>["id"=>"speed","group"=>"Scroll","order"=>"60","default"=>"600","label"=>"Scroll speed (in milliseconds)","description"=>"e.g. 5000 = 5 seconds","type"=>"num","scope"=>"magicscroll"],
            "autoplay"=>["id"=>"autoplay","group"=>"Scroll","order"=>"70","default"=>"0","label"=>"Autoplay speed (in milliseconds)","description"=>"e.g. 0 = disable autoplay; 600 = 0.6 seconds","type"=>"num","scope"=>"magicscroll"],
            "loop"=>["id"=>"loop","group"=>"Scroll","order"=>"80","advanced"=>"1","default"=>"infinite","label"=>"Continue scroll after the last(first) image","description"=>"infinite - scroll in loop; rewind - rewind to the first image; off - stop on the last image","type"=>"array","subType"=>"radio","values"=>["infinite","rewind","off"],"scope"=>"magicscroll"],
            "step"=>["id"=>"step","group"=>"Scroll","order"=>"90","default"=>"auto","label"=>"Number of items to scroll","description"=>"auto | integer","type"=>"text","scope"=>"magicscroll"],
            "arrows"=>["id"=>"arrows","group"=>"Scroll","order"=>"100","default"=>"inside","label"=>"Prev/Next arrows","type"=>"array","subType"=>"radio","values"=>["inside","outside","off"],"scope"=>"magicscroll"],
            "pagination"=>["id"=>"pagination","group"=>"Scroll","order"=>"110","advanced"=>"1","default"=>"No","label"=>"Show pagination (bullets)","type"=>"array","subType"=>"radio","values"=>["Yes","No"],"scope"=>"magicscroll"],
            "easing"=>["id"=>"easing","group"=>"Scroll","order"=>"120","advanced"=>"1","default"=>"cubic-bezier(.8, 0, .5, 1)","label"=>"CSS3 Animation Easing","description"=>"see cubic-bezier.com","type"=>"text","scope"=>"magicscroll"],
            "scrollOnWheel"=>["id"=>"scrollOnWheel","group"=>"Scroll","order"=>"130","advanced"=>"1","default"=>"auto","label"=>"Scroll On Wheel mode","description"=>"auto - automatically turn off scrolling on mouse wheel in the 'scroll' and 'animation' modes, and enable it in 'carousel' and 'cover-flow' modes","type"=>"array","subType"=>"radio","values"=>["auto","turn on","turn off"],"scope"=>"magicscroll"],
            "lazy-load"=>["id"=>"lazy-load","group"=>"Scroll","order"=>"140","advanced"=>"1","default"=>"No","label"=>"Lazy load","description"=>"Delay image loading. Images outside of view will be loaded on demand.","type"=>"array","subType"=>"radio","values"=>["Yes","No"],"scope"=>"magicscroll"],
            "scroll-extra-styles"=>["id"=>"scroll-extra-styles","group"=>"Scroll","order"=>"150","advanced"=>"1","default"=>"","label"=>"Scroll extra styles","description"=>"mcs-rounded | mcs-shadows | bg-arrows | mcs-border","type"=>"text","scope"=>"module"],
            "show-image-title"=>["id"=>"show-image-title","group"=>"Scroll","order"=>"160","default"=>"No","label"=>"Show image title","type"=>"array","subType"=>"radio","values"=>["Yes","No"],"scope"=>"module"]
        ];
        $this->params->appendParams($params);
    }
}
