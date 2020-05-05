<?php
namespace SomersetWood\PaymentCcImages\Plugin;
 
class CcConfigProviderPlugin
{
    /**
     * @var \Magento\Framework\View\Asset\Repository
     */
    private $assetRepo;
 
    /**
     * CcConfigProviderPlugin constructor.
     *
     * @param \Magento\Framework\View\Asset\Repository $assetRepo
     */
    public function __construct(
        \Magento\Framework\View\Asset\Repository $assetRepo
    )
    {
        $this->assetRepo = $assetRepo;
    }
 
    /**
     * @param \Magento\Payment\Model\CcConfigProvider $subject
     * @param $result
     * @return mixed
     */
    public function afterGetIcons(\Magento\Payment\Model\CcConfigProvider $subject, $result)
    {
        if (isset($result['AE'])) {
            $result['AE']['url'] = $this->assetRepo->getUrl('SomersetWood_PaymentCcImages::images/amex.svg');
            $result['AE']['width'] = 46;
            $result['AE']['height'] = 30;
        }
        if (isset($result['VI'])) {
            $result['VI']['url'] = $this->assetRepo->getUrl('SomersetWood_PaymentCcImages::images/visa.svg');
            $result['VI']['width'] = 46;
            $result['VI']['height'] = 30;
        }
        if (isset($result['MC'])) {
            $result['MC']['url'] = $this->assetRepo->getUrl('SomersetWood_PaymentCcImages::images/mastercard.svg');
            $result['MC']['width'] = 46;
            $result['MC']['height'] = 30;
        }
        if (isset($result['DI'])) {
            $result['DI']['url'] = $this->assetRepo->getUrl('SomersetWood_PaymentCcImages::images/discover.svg');
            $result['DI']['width'] = 46;
            $result['DI']['height'] = 30;
        }
        if (isset($result['JCB'])) {
            $result['JCB']['url'] = $this->assetRepo->getUrl('SomersetWood_PaymentCcImages::images/jcb.svg');
            $result['JCB']['width'] = 46;
            $result['JCB']['height'] = 30;
        }
        if (isset($result['DN'])) {
            $result['DN']['url'] = $this->assetRepo->getUrl('SomersetWood_PaymentCcImages::images/diners.svg');
            $result['DN']['width'] = 46;
            $result['DN']['height'] = 30;
        }
        if (isset($result['MI'])) {
            $result['MI']['url'] = $this->assetRepo->getUrl('SomersetWood_PaymentCcImages::images/maestro.svg');
            $result['MI']['width'] = 46;
            $result['MI']['height'] = 30;
        }
	if (isset($result['PP'])) {
            $result['PP']['url'] = $this->assetRepo->getUrl('SomersetWood_PaymentCcImages::images/paypal.svg');
            $result['PP']['width'] = 46;
            $result['PP']['height'] = 30;
        }
        return $result;
    }
}
