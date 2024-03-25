<?php

namespace Magentogreeks\Customwork\Model;

class Cart
	{
		private function aroundAddProduct(
			\Magento\Checkout\Model\Cart $subject,
			\Closure $proceed,
			$productInfo,
			$requestInfo = null
		) {
			$requestInfo['qty'] = 10; // setting quantity to 10
			$result = $proceed($productInfo, $requestInfo);
			// change result here
			return $result;
		}
	}


