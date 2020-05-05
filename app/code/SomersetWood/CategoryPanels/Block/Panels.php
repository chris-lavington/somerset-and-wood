<?php
namespace SomersetWood\CategoryPanels\Block;

class Panels extends \Magento\Framework\View\Element\Template
{
	protected $categoryHelper;
    protected $categoryFactory;
    protected $categoryRepository;  

	public function __construct(
	    \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Catalog\Helper\Category $categoryHelper,
        \Magento\Catalog\Model\CategoryFactory $categoryFactory,
        \Magento\Catalog\Model\CategoryRepository $categoryRepository,

        array $data = []
	) {
	    $this->categoryHelper = $categoryHelper;
        $this->categoryFactory = $categoryFactory;
        $this->categoryRepository = $categoryRepository;
        parent::__construct($context, $data);
	}
 
   public function getCategories($parent_category_id) 
    {
    	$categoryObj = $this->categoryRepository->get($parent_category_id);
    	$subcategories = $categoryObj->getChildrenCategories();
        $subcategories->addAttributeToSelect('include_in_menu', '1');
        $subcategories->addAttributeToSelect('meta_keywords');
        $subcategories->addAttributeToSelect('meta_description');
    	return $subcategories;
    }

    public function getCategory($categoryId) 
    {
        $category = $this->categoryFactory->create();
        $category->load($categoryId);
        return $category;
    }
    
    public function getCategoryProducts($categoryId) 
    {
        $products = $this->getCategory($categoryId)->getProductCollection();
        $products->addAttributeToSelect('*');
        return $products;
    }


}