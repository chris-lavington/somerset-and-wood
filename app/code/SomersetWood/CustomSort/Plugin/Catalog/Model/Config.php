<?php

namespace SomersetWood\CustomSort\Plugin\Catalog\Model;

class Config
{
    public function afterGetAttributeUsedForSortByArray(
    \Magento\Catalog\Model\Config $catalogConfig,
    $options
    ) {

    	//Remove specific default sorting options
	    //unset($options['position']);
	    unset($options['name']);
	    unset($options['price']);

        $optionsnew['low_to_high'] = __('Price - Low to High');
        $optionsnew['high_to_low'] = __('Price - High to Low');
        $options = array_merge($options,$optionsnew);
        return $options;

    }

	/**     * This method is optional. Use it to set Most Viewed as the default

	* sorting option in the category view page

	*

	* @param \Magento\Catalog\Model\Config $catalogConfig

	* @return string

	* @SuppressWarnings(PHPMD.UnusedFormalParameter)

	*/

	public function afterGetProductListDefaultSortBy(\Magento\Catalog\Model\Config $catalogConfig)    {

		return 'high_to_low';

	}

}