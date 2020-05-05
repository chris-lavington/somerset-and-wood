<?php 
namespace SomersetWood\ProductGallery\Block\Product\View;

class Gallery extends \Magento\Catalog\Block\Product\View\Gallery
{

    public function getSomersetGalleryImagesJson()
       {
            $product = $this->getProduct();
            $imagesItems = [];
            foreach ($this->getGalleryImages() as $image) {
                $imagesItems[] = [
                    // 'thumb' => $image->getData('small_image_url'),
                    'thumb' => $this->_imageHelper->init($product, 'catalog_product_view_480')
                        ->constrainOnly(FALSE)->keepAspectRatio(TRUE)->keepFrame(FALSE)->resize(480)
                        ->setImageFile($image->getFile())
                        ->getUrl(),
                     'somerset_376' => $this->_imageHelper->init($product, 'catalog_product_view_376')
                        ->constrainOnly(FALSE)->keepAspectRatio(TRUE)->keepFrame(FALSE)->resize(376)
                        ->setImageFile($image->getFile())
                        ->getUrl(),
                    'somerset_480' => $this->_imageHelper->init($product, 'catalog_product_view_480')
                        ->constrainOnly(FALSE)->keepAspectRatio(TRUE)->keepFrame(FALSE)->resize(480)
                        ->setImageFile($image->getFile())
                        ->getUrl(),
                    'somerset_480_2x' => $this->_imageHelper->init($product, 'catalog_product_view_480_2x')
                        ->constrainOnly(FALSE)->keepAspectRatio(TRUE)->keepFrame(FALSE)->resize(960)
                        ->setImageFile($image->getFile())
                        ->getUrl(),
                    'somerset_480_3x' => $this->_imageHelper->init($product, 'catalog_product_view_480_3x')
                        ->constrainOnly(FALSE)->keepAspectRatio(TRUE)->keepFrame(FALSE)->resize(1440)
                        ->setImageFile($image->getFile())
                        ->getUrl(),
                    'somerset_726' => $this->_imageHelper->init($product, 'catalog_product_view_726')
                        ->constrainOnly(FALSE)->keepAspectRatio(TRUE)->keepFrame(FALSE)->resize(726)
                        ->setImageFile($image->getFile())
                        ->getUrl(),
                    'somerset_726_2x' => $this->_imageHelper->init($product, 'catalog_product_view_726_2x')
                        ->constrainOnly(FALSE)->keepAspectRatio(TRUE)->keepFrame(FALSE)->resize(1452)
                        ->setImageFile($image->getFile())
                        ->getUrl(),
                    'somerset_726_3x' => $this->_imageHelper->init($product, 'catalog_product_view_726_3x')
                        ->constrainOnly(FALSE)->keepAspectRatio(TRUE)->keepFrame(FALSE)->resize(2178)
                        ->setImageFile($image->getFile())
                        ->getUrl(),
                    'full' => $image->getData('large_image_url'),
                    'caption' => $image->getLabel(),
                    'position' => $image->getPosition(),
                    'isMain' => $this->isMainImage($image),
                ];
            }
            if (empty($imagesItems)) {
                $imagesItems[] = [
                    'thumb' => $this->_imageHelper->getDefaultPlaceholderUrl('thumbnail'),
                    'img' => $this->_imageHelper->getDefaultPlaceholderUrl('image'),
                    'full' => $this->_imageHelper->getDefaultPlaceholderUrl('image'),
                    'caption' => '',
                    'position' => '0',
                    'isMain' => true,
                ];
            }
         return json_encode($imagesItems);
        }
}

