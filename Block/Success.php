<?php

namespace ThoughtMetric\OrderTracking\Block;

require_once __DIR__ . '/../lib/TmOrderObject.php';

class Success extends \Magento\Framework\View\Element\Template
{
    /**
     * @var \Magento\Csp\Api\InlineUtilInterface
     */

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Checkout\Model\Session $checkoutSession,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->checkoutSession = $checkoutSession;
    }

    public function getLastOrder(){
      $lastOrder = $this->checkoutSession->getLastRealOrder();
      return json_encode($lastOrder->getData());
    }

    public function getLastOrderItems(){
      $lastOrder = $this->checkoutSession->getLastRealOrder();

      $itemsData = array();
      foreach($lastOrder->getAllVisibleItems() as $item) {
        if ($item->getData()){
          $itemsData[] = $item->getData();
        }
      }

      return json_encode($itemsData);
    }

    public function getOrderJsObject(){
      $lastOrder = $this->checkoutSession->getLastRealOrder();
      return setup_thoughtmetric_order($lastOrder);
    }

    public function getCustomerJsObject(){
      $lastOrder = $this->checkoutSession->getLastRealOrder();
      return setup_thoughtmetric_customer($lastOrder);
    }

    public function getCustomerIDJsObject(){
      $lastOrder = $this->checkoutSession->getLastRealOrder();
      return setup_thoughtmetric_customer_ID($lastOrder);
    }

    public function isDuplicate(){
      $lastOrder = $this->checkoutSession->getLastRealOrder();
      return setup_thoughtmetric_is_duplicate($lastOrder);
    }

    public function getPropertyID(){
        return $this->_scopeConfig->getValue("thoughtmetric/credentials/property_id", \Magento\Store\Model\ScopeInterface::SCOPE_WEBSITE);
    }

    public function getSstDomain(){
        return $this->_scopeConfig->getValue("thoughtmetric/credentials/sst_domain", \Magento\Store\Model\ScopeInterface::SCOPE_WEBSITE);
    }

}
