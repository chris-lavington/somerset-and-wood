<?php 
namespace SomersetWood\SidebarCategories\Block;

class SidebarCategories extends \Magento\Framework\View\Element\Template 
{
    protected $_categoryHelper;
        protected $categoryFlatConfig;
        protected $topMenu;
        protected $_categoryFactory;
        protected $_registry;
    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Catalog\Helper\Category $categoryHelper
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Catalog\Helper\Category $categoryHelper,
        \Magento\Catalog\Model\Indexer\Category\Flat\State $categoryFlatState,
        \Magento\Theme\Block\Html\Topmenu $topMenu,
        \Magento\Catalog\Model\CategoryFactory $categoryFactory,
        \Magento\Framework\Registry $registry,
        $data = []
    ) {
        $this->_categoryHelper = $categoryHelper;
        $this->categoryFlatConfig = $categoryFlatState;
        $this->topMenu = $topMenu;
        $this->_categoryFactory = $categoryFactory;
        $this->_registry = $registry;
        parent::__construct($context,$data);
    }

    /**
     * Return categories helper
     */
    public function getCategoryHelper()
    {
        return $this->_categoryHelper;
    }
    
     public function getCategorymodel($id)
    {
        $_category = $this->_categoryFactory->create();
        $_category->load($id);
        return $_category;
    }
    
    
    /**
     * Retrieve child store categories
     *
     */
    public function getChildCategories($catId)
    {
        $cat = $this->_categoryFactory->create();
        $cat = $this->_categoryFactory->load($catId);
        $subcats = $cat->getChildren();
        
            
            return $subcats;
    }
    
    /**
      * Retrieve category object
      *
      * @return \Magento\Catalog\Model\Category
      */
    public function getCurrentCategory()
    {
        return $this->_registry->registry('current_category');
    }


    /**
     * Retrieve current store level 2 category
     *
     * @param bool|string $sorted (if true display collection sorted as name otherwise sorted as based on id asc)
     * @param bool $asCollection (if true display all category otherwise display second level category menu visible category for current store)
     * @param bool $toLoad
     */

    public function getStoreCategories($sorted = false, $asCollection = false, $toLoad = true)
    {
        return $this->_categoryHelper->getStoreCategories($sorted , $asCollection, $toLoad);
    }
    

    public function getUriSegments() {
        return explode("/", parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
    }
     
    public function getUriSegment($n) {
        $segs = $this->getUriSegments();
        return count($segs)>0&&count($segs)>=($n-1)?$segs[$n]:'';
    }

}

