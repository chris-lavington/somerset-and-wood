<?php

namespace SomersetWood\RemoveDiscountLabel\Plugin;

class RemoveDiscountLabelPlugin
{
       public function aroundAddDiscountDescription(
        \Magento\SalesRule\Model\RulesApplier $rulesApplier,
        callable $proceed,
        \Magento\Quote\Model\Quote\Address $address,
        \Magento\SalesRule\Model\Rule $rule
    ) {
        $description = $address->getDiscountDescriptionArray();
        $ruleLabel = $rule->getStoreLabel($address->getQuote()->getStore());
        $label = '';
        if ($ruleLabel) {
            $label = $ruleLabel;
        }
        //in original method, there was a piece of code here that added the coupon code to the discount

        if (strlen($label)) {
            $description[$rule->getId()] = $label;
        }
        $address->setDiscountDescriptionArray($description);
        return $rulesApplier;
    }
}