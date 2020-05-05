<?php
namespace SomersetWood\BlurbPrint\Controller\Adminhtml\Blurb;

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

        $artworks_blurb_collection_title = '';
        $logo = '';
        $artworks_blurb_code = '';
        $artworks_blurb = '';
        $artworks_blurb2 = '';

        function pointsToMm( $points )
        {
            return $points / 72 * 25.4;
        }

        function mmToPoints( $mm )
        {
            return $mm / 25.4 * 72;
        }


        function getTextWidth($string, $font, $fontSize) {
            $drawingString = iconv('UTF-8', 'UTF-16BE//IGNORE', $string);
            //$drawingString = mb_convert_encoding($string, "UTF-16BE", "UTF-8");
            // $drawingString = $string;
            $characters = array();
            for ($i = 0; $i < strlen($drawingString); $i++) {
                $characters[] = (ord($drawingString[$i++]) << 8) |
                ord($drawingString[$i]);
            }
            $glyphs = $font->glyphNumbersForCharacters($characters);
            $widths = $font->widthsForGlyphs($glyphs);
            $stringWidth = (array_sum($widths) / $font->getUnitsPerEm()) * $fontSize;      
            return $stringWidth;
        }

        
        // Iterate through all of the items in the order
        foreach ($items as $item) {
            $page1 = $pdf->newPage(\Zend_Pdf_Page::SIZE_A4);
            $page2 = $pdf->newPage(\Zend_Pdf_Page::SIZE_A4);
            
            $sku = $item->getSku();
            $product = $this->getProductBySku($sku);
            
            if($product->getCustomAttribute('artworks_blurb_collection_title')!='') {
                $artworks_blurb_collection_title = $product->getCustomAttribute('artworks_blurb_collection_title')->getValue();
            }

            $logo = $this->_directoryList->getPath('media').'/logo/default/somersetandwood-logo-3x.png';

            if($product->getCustomAttribute('artworks_blurb')!==null){
                $artworks_blurb = $product->getCustomAttribute('artworks_blurb')->getValue();
            }

            if($product->getCustomAttribute('artworks_blurb2')!==null){
                $artworks_blurb2 = $product->getCustomAttribute('artworks_blurb2')->getValue();
            }

            if($product->getCustomAttribute('artworks_blurb_code')!==null){
                $artworks_blurb_code = $product->getCustomAttribute('artworks_blurb_code')->getValue();    
            }

            $style = new \Zend_Pdf_Style();
            $style->setLineColor(new \Zend_Pdf_Color_Html('#000000'));

            // $bodyFont = \Zend_Pdf_Font::fontWithPath($this->_directoryList->getRoot().'/lib/customfonts/CrimsonPro-Regular.ttf');
            // $headingFont  = \Zend_Pdf_Font::fontWithPath($this->_directoryList->getRoot().'/lib/customfonts/CrimsonPro-Bold.ttf');
            // $lightFont = \Zend_Pdf_Font::fontWithPath($this->_directoryList->getRoot().'/lib/customfonts/CrimsonPro-Light.ttf');
            
            $bodyFont = \Zend_Pdf_Font::fontWithName(\Zend_Pdf_Font::FONT_TIMES);
            $headingFont  = \Zend_Pdf_Font::fontWithName(\Zend_Pdf_Font::FONT_TIMES_BOLD);
            $lightFont  = \Zend_Pdf_Font::fontWithName(\Zend_Pdf_Font::FONT_TIMES);


            $width = $page1->getWidth();
            $height = $page1->getHeight();

            $paperHeight = pointsToMm($height);

            $logoSize = getimagesize( $logo );
            $logoWidth = $logoSize[0];
            $logoHeight = $logoSize[1];

            $logoWidth = 225;
            $logoHeight = 40;

            $x_mm = 65;
            $y_mm = 15;
            $w_mm = pointsToMm( $logoWidth );
            $h_mm = $logoHeight / $logoWidth * $w_mm;

            $x1 = mmToPoints( $x_mm );
            $x2 = mmToPoints( $x_mm + $w_mm );
            $y1 = mmToPoints( $paperHeight - $y_mm - $h_mm );
            $y2 = mmToPoints( $paperHeight - $y_mm );

            $page1->drawImage(\Zend_Pdf_Image::imageWithPath($logo), $x1, $y1, $x2, $y2); 

            $style->setFont($headingFont,13);
            $page1->setStyle($style);
            
            if($product->getCustomAttribute('artworks_blurb_collection_title')!='') {
                $heading_width = getTextWidth($artworks_blurb_collection_title,$headingFont,13);
                $page1->drawText($artworks_blurb_collection_title, ($width - $heading_width)/2, ($height - mmToPoints(45)), 'UTF-8');       
            }

            $i='';
            $line='';
            // $yPos = $height - 220;

            $style->setFont($bodyFont,11);
            $page1->setStyle($style);
            $page2->setStyle($style);
            
            $description_page1 = wordwrap($artworks_blurb, 100, "<br>",false);
            $description_page2 = wordwrap($artworks_blurb2, 100, "<br>",false);

            foreach (explode("<br>", $description_page1 ) as $i => $line) {
                $desWidth = getTextWidth($line,$bodyFont,11);
                $desArrary[] = $desWidth;

            }
            $maxDesWidth = max($desArrary); 
            foreach (explode("<br>", $description_page1 ) as $i => $line) {
                 $page1->drawText(strip_tags($line), ($width - $maxDesWidth)/2, ($height-mmToPoints(60)) - $i * 16, 'UTF-8');
            }

            foreach (explode("<br>", $description_page2 ) as $i2 => $line2) {
                $desWidth2 = getTextWidth($line2,$bodyFont,11);
                $desArrary2[] = $desWidth2;            
            }
            $maxDesWidth2 = max($desArrary2);             
            foreach (explode("<br>", $description_page2 ) as $i2 => $line2) {
                $page2->drawText(strip_tags($line2), ($width - $maxDesWidth2)/2, ($height-mmToPoints(20)) - $i2 * 16, 'UTF-8');
            }

            // footer text
            $footerText = "Text copyright © Somerset & Wood Fine Art Ltd. All rights reserved | somersetandwood.com";
            // $style->setLineColor(new \Zend_Pdf_Color_Rgb(211,211,211));
            $style->setFont($lightFont,9);
            $page1->setStyle($style);
            $page2->setStyle($style);
            $footerWidth = getTextWidth($footerText,$lightFont,9);
            $page1->setFillColor(new \Zend_Pdf_Color_Html('#000000'))
                    ->drawText($footerText, ($width / 2) - ($footerWidth / 2), mmToPoints(13), 'UTF-8');
            $page2->setFillColor(new \Zend_Pdf_Color_Html('#000000'))
                    ->drawText($footerText, ($width / 2) - ($footerWidth / 2), mmToPoints(13), 'UTF-8');

            // blurb code
            $artworks_blurb_code_width = getTextWidth($artworks_blurb_code,$lightFont,10);
            if($product->getCustomAttribute('artworks_blurb_code')!==null){
                $page1->setFillColor(new \Zend_Pdf_Color_Html('#d2d2d2'))
                    ->drawText($artworks_blurb_code, $width - $artworks_blurb_code_width -mmToPoints(20), $height - mmToPoints(7), 'UTF-8');
            }
            
            $pdf->pages[] = $page1;
            if($description_page2 !='') {
                $pdf->pages[] = $page2;
            } 

        } // ends $_items foreach
   
        $fileName = $orderId.'–blurb.pdf';

        $this->fileFactory->create(
           $fileName,
           $pdf->render(),
           \Magento\Framework\App\Filesystem\DirectoryList::VAR_DIR, // this pdf will be saved in var directory with the name example.pdf
           'application/pdf'
        );       
        
    }
}