<?php

namespace Magentoghreeks\Customwork\Block;

class Exportcsv extends \Magento\Framework\View\Element\Template{



protected $_productRepository;
		
	public function __construct(
		\Magento\Backend\Block\Template\Context $context,		
		\Magento\Catalog\Model\ProductRepository $productRepository,
		array $data = []
	)
	{
		$this->_productRepository = $productRepository;
		parent::__construct($context, $data);
	}
	
	public function getProductById($id)
	{
		return $this->_productRepository->getById($id);
	}
	
	public function getProductBySku($sku)
	{
		return $this->_productRepository->get($sku);
	}





	
}