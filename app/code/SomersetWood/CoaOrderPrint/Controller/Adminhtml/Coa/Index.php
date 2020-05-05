<?php
namespace SomersetWood\CoaOrderPrint\Controller\Adminhtml\Coa;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;

class Index extends Action
{
    /**
     * @var \Magento\Framework\App\Response\Http\FileFactory
     */
    protected $fileFactory;

    protected $orderRepository;

    protected $productFactory;

    private $eavConfig;

    protected $_directoryList;

    /**
     * @param Context                               $context
     */
    public function __construct(
        Context $context,
        \Magento\Framework\App\Response\Http\FileFactory $fileFactory,
        \Magento\Sales\Model\OrderRepository $orderRepository,
        \Magento\Catalog\Model\ProductFactory $productFactory,
        \Magento\Eav\Model\Config $eavConfig,
        \Magento\Framework\App\Filesystem\DirectoryList $directoryList
    ) {
        $this->fileFactory = $fileFactory;
        $this->orderRepository = $orderRepository;
        $this->productFactory = $productFactory;
        $this->eavConfig = $eavConfig;
        $this->_directoryList = $directoryList;
        parent::__construct($context);
    }

    public function getOrderById($id) {
        return $this->orderRepository->get($id);
    }

    public function getProductBySku($sku)
    {
        $product = $this->productFactory->create();
        return $product->loadByAttribute('sku', $sku);
    }

    /**
     * to generate pdf
     *
     * @return void
     */
    public function execute()
    {
        $orderId = basename(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

        /** @var $_order \Magento\Sales\Model\Order  */
        $order = $this->getOrderById($orderId);
        // Get all items from your order
        $items = $order->getAllItems();

        $pdf = new \Zend_Pdf();

        $artworks_title = '';
        $artworks_medium_options_value = '';
        $artworks_medium = '';
        $artist_concatenated_name = '';
        $created ='';
        $printed_date = date("d M Y");

        function pointsToMm( $points )
        {
            return $points / 72 * 25.4;
        }

        function mmToPoints( $mm )
        {
            return $mm / 25.4 * 72;
        }

        
        // Iterate through all of the items in the order
        foreach ($items as $item) {
            $page = $pdf->newPage('422:300:');
            $sku = $item->getSku();
            $name = $item->getName();
            $price = $item->getPrice();
            $product = $this->getProductBySku($sku);
            $artworks_title = $product->getCustomAttribute('artworks_title')->getValue();
            
            if($product->getCustomAttribute('artworks_medium')!==null){
                $artworks_medium_options_value = $product->getCustomAttribute('artworks_medium')->getValue();
                $attribute_medium_options = $this->eavConfig->getAttribute('catalog_product', 'artworks_medium');
                $mediums = $attribute_medium_options->getSource()->getAllOptions();
                array_shift($mediums);
                foreach ($mediums as $medium) {

                    if($artworks_medium_options_value === $medium['value']) {
                        $artworks_medium = $medium['label'];
                    }


                }
            }
            
            if($product->getCustomAttribute('artworks_dated')!==null){
                $created = $product->getCustomAttribute('artworks_dated')->getValue();
            } elseif($product->getCustomAttribute('artworks_age')!==null){
                
                $artworks_age_options_value = $product->getCustomAttribute('artworks_age')->getValue();
                $attribute_age_options = $this->eavConfig->getAttribute('catalog_product', 'artworks_age');
                $ages = $attribute_age_options->getSource()->getAllOptions();
                array_shift($ages);
                foreach ($ages as $age) {
                    if($artworks_age_options_value === $age['value']) {
                        if($age['value']==='177'||$age['value']==='178'||$age['value']==='179') {
                            $explode = explode("/",$age['label']);
                            $nineteenthCenturyStrip = $explode[1];
                            $created = $nineteenthCenturyStrip;
                        } else {
                            $created = $age['label'];
                        }
                    }
                }
            }

            if($product->getCustomAttribute('artist_concatenated_name')!==null){
                $artist_concatenated_name = $product->getCustomAttribute('artist_concatenated_name')->getValue();    
            }
            

            $style = new \Zend_Pdf_Style();
            $style->setLineColor(new \Zend_Pdf_Color_Rgb(0,0,0));
            //$font = \Zend_Pdf_Font::fontWithName(\Zend_Pdf_Font::FONT_TIMES);
            $font = \Zend_Pdf_Font::fontWithPath($this->_directoryList->getRoot().'/lib/customfonts/Lato-Regular.ttf');
            $style->setFont($font,8);
            $page->setStyle($style);
            $width = $page->getWidth();
            $height = $page->getHeight();

            $page->drawText($artist_concatenated_name, mmToPoints(46), ($height - mmToPoints(55)), 'UTF-8');
            $page->drawText($artworks_title, mmToPoints(46), ($height - mmToPoints(59.5)), 'UTF-8');
            $page->drawText($artworks_medium, mmToPoints(46), ($height - mmToPoints(64)), 'UTF-8');
            $page->drawText($created, mmToPoints(46), ($height - mmToPoints(68.5)), 'UTF-8');
            $page->drawText($printed_date, mmToPoints(85), ($height - mmToPoints(76)), 'UTF-8');
            $page->drawText($sku, mmToPoints(115), ($height - mmToPoints(76)), 'UTF-8');
            
            $pdf->pages[] = $page;

        } // ends $_items foreach

        
        $fileName = $orderId.'–COA.pdf';
        $this->fileFactory->create(
           $fileName,
           $pdf->render(),
           \Magento\Framework\App\Filesystem\DirectoryList::VAR_DIR, // this pdf will be saved in var directory with the name example.pdf
           'application/pdf'
        );       
        
    }
}