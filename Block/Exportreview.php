<?php
/**
 * Created By : Ajay Singh
 */
namespace Magentogreeks\Customwork\Controller\Index;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\ResponseInterface;

class Exportreview extends \Magento\Framework\App\Action\Action
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
            'entity_id' => __('Entity ID'),
            'name' => __('product_title'),
            'product_url' => __('product_url'),
            'attribute_set_id' => __('Attribute Set ID'),
            'type_id' => __('Type ID'),
            'sku' => __('Sku'),
            'required_options' => __('Required Options'),
            'created_at' => __('Created At'),
            'updated_at' => __('Updated At'),
        ];
       // set_time_limit(0);
       // ini_set('memory_limit', '200000000000M');
        $resultLayout = $this->resultLayoutFactory->create();
        $product = $this->productFactory->create()->getCollection();
        $collection = $this->productFactory->create()->getCollection();
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
/** @var \Magento\Catalog\Model\ResourceModel\Product\Collection $productCollection */
         $productCollection = $objectManager->create('Magento\Catalog\Model\ResourceModel\Product\Collection');
/** Apply filters here */
         $collection = $productCollection->addAttributeToSelect('*')
            ->load();

        $reviewFactory = $objectManager->create('Magento\Review\Model\Review');

         
     /*************************************************Start*******************************************************/
       
       $objectManager = \Magento\Framework\App\ObjectManager::getInstance();

					//Get current store id
					$storeManager = $objectManager->get('\Magento\Store\Model\StoreManagerInterface');
					$currentStoreId = $storeManager->getStore()->getId();

					$reviewCollectionFactory = $objectManager->create('Magento\Review\Model\ResourceModel\Review\CollectionFactory')->create();

					// Get reviews collection
					$reviewsCollection = $reviewCollectionFactory->addFieldToSelect('*')
					        ->addStoreFilter($currentStoreId)
					        ->addStatusFilter(\Magento\Review\Model\Review::STATUS_APPROVED)
					        ->setDateOrder()
					        ->addRateVotes();

					echo "<pre>";
					print_r($reviewsCollection->getData());
					echo "</pre>";

					$dateFormat = $block->getDateFormat() ? : \IntlDateFormatter::SHORT;

					if ($reviewsCollection && count($reviewsCollection) > 0) {
					    foreach ($reviewsCollection AS $review) {
					        echo $review->getTitle() . "<br/>";
					        echo $review->getDetail() . "<br/>";
					        echo $review->getNickname() . "<br/>";
					        echo $block->formatDate($review->getCreatedAt(), $dateFormat) . "<br/>";

					        // Display Average Rating of Review
					        $countRatings = count($review->getRatingVotes());
					        if ($countRatings > 0) {
					            ?>
					            <div class="review-ratings">
					                <?php
					                $allRatings = 0;
					                foreach ($review->getRatingVotes() as $vote) {
					                    $allRatings = $allRatings + $vote->getPercent();
					                }
					                $allRatingsAvg = $allRatings / $countRatings;
					                ?>
					                <div class="rating-summary">
					                    <div class="rating-result" title="<?php echo $allRatingsAvg; ?>%">
					                        <meta itemprop="worstRating" content = "1"/>
					                        <meta itemprop="bestRating" content = "100"/>
					                        <span style="width:<?php echo $allRatingsAvg; ?>%">
					                            <span itemprop="ratingValue"><?php echo $allRatingsAvg; ?>%</span>
					                        </span>
					                    </div>
					                </div>
					            </div>
					            <?php
					        }
					    }
					}





     /*************************************************End**********************************************************/




die('yahi ruko bhai');





     foreach ($collection as $product){

     
        $productrating = $objectManager->create('Magento\Catalog\Model\Product')->load($product->getID());
        $storeManager  = $objectManager->create('\Magento\Store\Model\StoreManagerInterface');
        $storeId = $storeManager->getStore()->getStoreId();
        $reviewFactory->getEntitySummary($productrating, $storeId);

        $ratingSummary = $productrating->getRatingSummary()->getRatingSummary();
        $reviewCount = $productrating->getRatingSummary()->getReviewsCount();


          $rating = $objectManager->get("Magento\Review\Model\ResourceModel\Review\CollectionFactory");

          $reviewcollection = $rating->create()->addStoreFilter($storeId)->addStatusFilter(\Magento\Review\Model\Review::STATUS_APPROVED)->addEntityFilter(
            'product',$product->getId())->setDateOrder();
            
           $reviewdata = $reviewcollection->getData();

           foreach($reviewdata as $reviewrating){

              
              echo '<pre>';print_r($reviewrating);


           }

         
       
       echo 'Product namr'.'--'.$product->getName().'<br>';
       echo 'reviewCount'.'--'.$reviewCount.'<br>';
       echo 'reviewCount'.'--'.$ratingSummary.'<br>';

       //echo '<pre>';print_r($productrating); 
    // // echo '<pre>';print_r($product);
    //  echo  $product->getName().'<br>';

    //  echo  $product->getSku();

       $content[] = [
                $product->getEntityId(),
                $product->getName(),
                $product->getProductUrl(),
                $product->getAttributeSetId(),
                $product->getTypeId(),
                $product->getSku(),
                $product->getRequiredOptions(),
                $product->getCreatedAt(),
                $product->getUpdatedAt()
            ];




}  


 die('stop here');



        $fileName = 'magentogreeks_export.csv'; // Add Your CSV File name

        $filePath =  $this->directoryList->getPath(DirectoryList::MEDIA) . "/" . $fileName;

    

        $this->csvProcessor->setEnclosure('"')->setDelimiter(',')->saveData($filePath, $content);
        return $this->fileFactory->create(
            $fileName,
            [
                'type'  => "filename",
                'value' => $fileName,
                'rm'    => true, // True => File will be remove from directory after download.
            ],
            DirectoryList::MEDIA,
            'text/csv',
            null
        );
    }
}