<?php
namespace C5F\ProductImage\Plugin;

use \Magento\Catalog\Helper\Image as ImageHelper;

class DefaultRendererPlugin {

	/**
	 * @var \Magento\Catalog\Helper\Image $imageHelper
	*/
	protected $imageHelper;

	/**
	 * @param ImageHelper      $imageHelper
	 */
	public function __construct
	(
		ImageHelper $imageHelper
	)
	{
		$this->imageHelper      = $imageHelper;
	}

	public function aroundGetColumnHtml(\Magento\Sales\Block\Adminhtml\Order\View\Items\Renderer\DefaultRenderer $defaultRenderer, \Closure $proceed,\Magento\Framework\DataObject $item, $column, $field=null) {
		// $column will equal the name value from sales_order_view.xml that was added
                if($column === 'image') {
			$img = $this->imageHelper->init($item->getProduct(), 'small_image')->setImageFile($item->getProduct()->getImage())->resize(200)->getUrl();
			$result = '<img src="'.$img.'" alt="'.$item->getName().'" />';
		}
		else {
			if($field) {
				$result = $proceed($item,$column,$field);
			}
			else {
				$result = $proceed($item,$column);
			}
		}

		return $result;
	}
}  