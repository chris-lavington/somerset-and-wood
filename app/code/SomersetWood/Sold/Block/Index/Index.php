<?php
namespace SomersetWood\Sold\Block\Index;

use Magento\Catalog\Block\Product\ImageBuilder;
use Magento\Catalog\Model\Product\Attribute\Source\Status;
use Magento\Catalog\Model\Product\Visibility;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;


class Index extends Template 
{
    /**
     * @var CollectionFactory
     */
    protected $_productCollection;


    /**
     * @var Visibility
     */
    protected $_productVisibility;

    /**
     * @var Status
     */
    protected $_productStatus;

    /**
     * @var ImageBuilder
     */
    protected $_imageBuilder;

    /**
     * OoSProducts constructor.
     * @param Context $context
     * @param CollectionFactory $productCollection
     * @param Visibility $productVisibility
     * @param Status $productStatus
     */
    public function __construct(
        Context $context,
        CollectionFactory $productCollection,
        Visibility $productVisibility,
        Status $productStatus,
        ImageBuilder $imageBuilder

    )  {

        parent::__construct($context);
        $this->_productCollection = $productCollection;
        $this->_productVisibility = $productVisibility;
        $this->_productStatus = $productStatus;
        $this->_imageBuilder = $imageBuilder;
    }

    protected function _getProductCollection()
    {
        if ($this->_productCollection === null) {
            $this->_productCollection = $this->initializeProductCollection();
        }

        return $this->_productCollection;
    }

     public function _prepareLayout()
    {
        $this->pageConfig->getTitle()->set(__('Sold Artwork'));
        
        if ($this->getOutofstockProductCollection()) {
            $pager = $this->getLayout()->createBlock(
                'Magento\Theme\Block\Html\Pager',
                'sold.artwork.pager'
            )->setAvailableLimit(array(96=>96,48=>48,24=>24,16=>16))->setShowPerPage(true)->setCollection(
                $this->getOutofstockProductCollection()
            );
            $this->setChild('pager', $pager);
            $this->getOutofstockProductCollection()->load();
        }
        return parent::_prepareLayout();
    }

    /**
     * Out of Stock Product Collection
     * @return $this|\Magento\Catalog\Model\ResourceModel\Product\Collection
     */
    public function getOutofstockProductCollection()
    {
        $page = ($this->getRequest()->getParam('p'))? $this->getRequest()->getParam('p') : 1;
        $pageSize = ($this->getRequest()->getParam('limit'))? $this->getRequest()->getParam('limit') : 96;

        $collection = $this->_productCollection->create();
        $collection->setFlag('has_stock_status_filter', true);
        $collection = $collection->addAttributeToSelect(array('name', 'sku', 'product_url', 'price','image'))
            ->addAttributeToSort('price', 'DESC')
            ->joinField('qty',
                'cataloginventory_stock_item',
                'qty',
                'product_id=entity_id',
                '{{table}}.stock_id=1',
                'left'
            )
            ->joinTable('cataloginventory_stock_item', 'product_id=entity_id', array('stock_status' => 'is_in_stock'))
            ->addAttributeToSelect('stock_status')
            ->addFieldToFilter('stock_status', ['eq' => 0])
            ->addAttributeToFilter('status', ['in' => $this->_productStatus->getVisibleStatusIds()])
            ->setVisibility($this->_productVisibility->getVisibleInSiteIds());
        $collection->setPageSize($pageSize);
        $collection->setCurPage($page);
        return $collection;
    }

    public function getImage($product, $imageId)
    {
        return $this->_imageBuilder->setProduct($product)
            ->setImageId($imageId)
            ->create();
    }

    public function getPagerHtml()
    {
        return $this->getChildHtml('pager');
    }

}
