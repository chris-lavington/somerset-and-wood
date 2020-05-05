<?php
namespace SomersetWood\SocialLinksWidget\Model\Config\Source;
 
class Show implements \Magento\Framework\Option\ArrayInterface
{
    public function toOptionArray()
    {
        return [
            ['value' => 'yes', 'label' => __('Yes')],
            ['value' => 'no', 'label' => __('No')]];
    }
}

?>