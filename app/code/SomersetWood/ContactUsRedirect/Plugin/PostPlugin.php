<?php

namespace SomersetWood\ContactUsRedirect\Plugin;

class PostPlugin
{
    /**
     * @var \Magento\Framework\App\Request\DataPersistorInterface
     */
    private $_dataPersistor;

    /**
     * @var \Magento\Framework\App\Response\RedirectInterface
     */
    private $_redirect;

    /**
     * PostPlugin constructor.
     * @param \Magento\Framework\App\Request\DataPersistorInterface $dataPersistor
     * @param \Magento\Framework\App\Response\RedirectInterface $redirect
     */
    public function __construct(
        \Magento\Framework\App\Request\DataPersistorInterface $dataPersistor,
        \Magento\Framework\App\Response\RedirectInterface $redirect
    ) {
        $this->_dataPersistor = $dataPersistor;
        $this->_redirect = $redirect;
    }

    public function afterExecute(\Magento\Contact\Controller\Index\Post $subject, $result)
    {
        $post = $subject->getRequest()->getPostValue();

        if (!$post) {
            return;
        }

        if (!$this->_dataPersistor->get('contact_us')) {
            $this->_redirect->redirect($subject->getResponse(), 'contact-us/success');
        }
    }
}