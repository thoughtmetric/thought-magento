<?php

namespace ThoughtMetric\OrderTracking\Block;


class Pixel extends \Magento\Framework\View\Element\Template
{
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->scopeConfig = $scopeConfig;
    }

    public function getPropertyID()
    {
        return $this->_scopeConfig->getValue("thoughtmetric/credentials/property_id", \Magento\Store\Model\ScopeInterface::SCOPE_WEBSITE);
    }

    public function getSstDomain(){
        return $this->_scopeConfig->getValue("thoughtmetric/credentials/sst_domain", \Magento\Store\Model\ScopeInterface::SCOPE_WEBSITE);
    }
}
