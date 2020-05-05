<?php

namespace SomersetWood\CoaOrderPrint\Plugin\Sales\Block\Adminhtml\Order;

use Magento\Sales\Block\Adminhtml\Order\View as OrderView;
class View
{
    public function beforeSetLayout(OrderView $subject)
    {
        $subject->addButton(
            'order_custom_button',
            [
                'label' => __('Print COA'),
                'class' => __('print-coa'),
                'id' => 'order-view-coa-button',
                'onclick' => 'setLocation(\'' . $subject->getUrl('coaprint/coa/index') . '\')'
            ]
        );
    }
}