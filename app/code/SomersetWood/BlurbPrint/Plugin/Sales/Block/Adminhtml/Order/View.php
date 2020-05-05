<?php

namespace SomersetWood\BlurbPrint\Plugin\Sales\Block\Adminhtml\Order;

use Magento\Sales\Block\Adminhtml\Order\View as OrderView;
class View
{
    public function beforeSetLayout(OrderView $subject)
    {
        $subject->addButton(
            'order_custom_blurb_button',
            [
                'label' => __('Print Blurb'),
                'class' => __('print-blurb'),
                'id' => 'order-view-blurb-button',
                'onclick' => 'setLocation(\'' . $subject->getUrl('blurbprint/blurb/index') . '\')'
            ]
        );
    }
}