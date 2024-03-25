<?php

namespace Magentogreeks\Customwork\Controller\Index;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\ResponseInterface;

class ExportCsv extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \Magento\Framework\App\Response\Http\FileFactory
     */
    protected $fileFactory;

    /**
     * @var \Magento\Catalog\Model\ProductFactory
     */
    protected $productFactory;

    /**
     * @var \Magento\Framework\View\Result\LayoutFactory
     */
    protected $resultLayoutFactory;

    /**
     * @var \Magento\Framework\File\Csv
     */
    protected $csvProcessor;

    /**
     * @var \Magento\Framework\App\Filesystem\DirectoryList
     */
    protected $directoryList;

    /**
     * @param \Magento\Framework\App\Action\Context            $context
     * @param \Magento\Framework\App\Response\Http\FileFactory $fileFactory
     * @param \Magento\Catalog\Model\ProductFactory            $productFactory
     * @param \Magento\Framework\View\Result\LayoutFactory     $resultLayoutFactory
     * @param \Magento\Framework\File\Csv                      $csvProcessor
     * @param \Magento\Framework\App\Filesystem\DirectoryList  $directoryList
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\App\Response\Http\FileFactory $fileFactory,
        \Magento\Catalog\Model\ProductFactory $productFactory,
        \Magento\Framework\View\Result\LayoutFactory $resultLayoutFactory,
        \Magento\Framework\File\Csv $csvProcessor,
        \Magento\Framework\App\Filesystem\DirectoryList $directoryList
    ) {
        $this->fileFactory = $fileFactory;
        $this->productFactory = $productFactory;
        $this->resultLayoutFactory = $resultLayoutFactory;
        $this->csvProcessor = $csvProcessor;
        $this->directoryList = $directoryList;
        parent::__construct($context);
    }

    /**
     * CSV Create and Download
     *
     * @return ResponseInterface
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    public function execute()
    {
        /** Add yout header name here */
        $content[] = [
            'entity_id' => __('product_id'),
            'name' => __('product_title'),
            'product_url' => __('product_url'),
            'date' => __('date'),
            'review_content' => __('review_content'),
            'review_score' => __('review_score'),
            'review_title' => __('review_title'),
            'display_name' => __('display_name'),
            'email' => __('email'),
            'md_customer_country' => __('md_customer_country'),
            'published' => __('published'),
            'product_image_url' => __('product_image_url'),
            'product_description' => __('product_description'),
            'comment_content' => __('comment_content'),
            'comment_public' => __('comment_public'),
            'comment_created_at' => __('comment_created_at'),
            'published_image_url' => __('published_image_url'),
            'unpublished_image_url' => __('unpublished_image_url'),
            'published_video_url' => __('published_video_url'),
            'cf_Y__X'             => __('cf_Y__X')

        ];
      

            $md_customer_country = '';
            $published = 'true';
            $comment_public = 'true';

            $unpublished_image_url = '';
            $published_video_url = '';
            $cf_Y__X = 'jj';









         /*************************************************Start*******************************************************/
       
       $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
       $customerFactory = $objectManager->get('\Magento\Customer\Model\CustomerFactory')->create();


                    //Get current store id
                    $storeManager = $objectManager->get('\Magento\Store\Model\StoreManagerInterface');
                    $currentStoreId = $storeManager->getStore()->getId();

                    $reviewCollectionFactory = $objectManager->create('Magento\Review\Model\ResourceModel\Review\CollectionFactory')->create();

                    // Get reviews collection
                    $reviewsCollection = $reviewCollectionFactory->addFieldToSelect('*')
                            ->addStoreFilter($currentStoreId)
                           // ->addStatusFilter(\Magento\Review\Model\Review::STATUS_APPROVED)
                            ->setDateOrder()
                            ->addRateVotes();

                    // echo "<pre>";
                    //  print_r($reviewsCollection->getData());
                    // echo "</pre>";


                   
                   
                 
                    //$dateFormat = $block->getDateFormat() ? : \IntlDateFormatter::SHORT;

                    if ($reviewsCollection && count($reviewsCollection) > 0) {
                        foreach ($reviewsCollection AS $review) {
                            // echo $review->getTitle() . "<br/>";
                            // echo $review->getDetail() . "<br/>";
                            // echo $review->getNickname() . "<br/>";
                            $customerId = $review->getCustomerId();
                            $entity_pk_value = $review->getEntityPkValue();
                            $customer = $customerFactory->load($customerId);
                            $customer = $customer->getData();
                          
                            if(!empty($customer['email'])){
                                $customerEmail = $customer['email'];
                            }
                            else
                            {
                               $customerEmail = 'Guest';
                            }
                            
                           $customerObj = $objectManager->create('Magento\Customer\Model\Customer')->load($customerId);
                            $customerAddress = array();

                           foreach ($customerObj->getAddresses() as $address)
                            {
                                $customerAddress[] = $address->toArray();
                            }

                           foreach ($customerAddress as $customerAddres) {

                            $md_customer_country= $customerAddres['country_id'];
                              
                            }
						              
                           // echo '<pre>';print_r($customerEmail);
                            $productData = $objectManager->create("Magento\Catalog\Model\Product")->load($entity_pk_value); 
                            $countRatings = count($review->getRatingVotes());
                             $allRatings = 0;
                                    foreach ($review->getRatingVotes() as $vote) {
                                        $allRatings = $allRatings + $vote->getPercent();
                                    }
                                     if ($countRatings == 0 ){ 
                                        continue;
                                    }
                                    $allRatingsAvg = $allRatings / $countRatings;
                                    
                                    $calculatedReviewScore = 0;
                                    if($allRatingsAvg=='20')
                                    {
                                        $calculatedReviewScore = 1;
                                    }
                                    elseif ($allRatingsAvg=='40') {
                                         $calculatedReviewScore = 2;
                                    }
                                    elseif ($allRatingsAvg=='60') {
                                         $calculatedReviewScore = 3;
                                    }
                                     elseif ($allRatingsAvg=='80') {
                                         $calculatedReviewScore = 4;
                                    }
                                    elseif ($allRatingsAvg=='100') {
                                         $calculatedReviewScore = 5;
                                    }
                          // echo 'Review Score--'. $calculatedReviewScore;

                            $prdoduct = $objectManager->get('Magento\Catalog\Api\ProductRepositoryInterface')->getById($productData->getId());
                            $store = $objectManager->get('Magento\Store\Model\StoreManagerInterface')->getStore();
 
                            $productImageUrl = $store->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) . 'catalog/product' . $prdoduct->getImage();
                             $content[] = [
                                            $productData->getId(),
                                            $productData->getName(),
                                            $productData->getProductUrl(),
                                            $review->getCreatedAt(),
                                            $review->getDetail(),
                                            $calculatedReviewScore,
                                            $review->getTitle(),
                                            $review->getNickname(),
                                            $customerEmail,
                                            $md_customer_country,
                                            $published,
                                            $productImageUrl,
                                            $productData->getDescription(),
                                            $review->getDetail(),
                                            $comment_public,
                                            $review->getCreatedAt(),
                                            $productImageUrl,
                                            $unpublished_image_url,
                                            $published_video_url,
                                            $cf_Y__X


                ];

                           


                            //echo $block->formatDate($review->getCreatedAt(), $dateFormat) . "<br/>";

                        }
                    }

 
     /*************************************************End**********************************************************/




        $fileName = 'magentogreeks_export.csv'; // Add Your CSV File name

        $filePath =  $this->directoryList->getPath(DirectoryList::MEDIA) . "/" . $fileName;


        $this->csvProcessor->setEnclosure('"')->setDelimiter(',')->saveData($filePath, $content);
        return $this->fileFactory->create(
            $fileName,
            [
                'type'  => "filename",
                'value' => $fileName,
                'rm'    => false, // True => File will be remove from directory after download.
            ],
            DirectoryList::MEDIA,
            'text/csv',
            null
        );
    }
}