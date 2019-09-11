<?php
namespace Idmkr\ImportWoocommerceUsers\Manager;

use Idmkr\ImportWoocommerceUsers\DataMapper\WpUserToMagentoAddress;
use Idmkr\ImportWoocommerceUsers\DataMapper\WpUserToMagentoUser;
use Idmkr\ImportWoocommerceUsers\Model\Wp\User;
use Magento\Customer\Model\Customer;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\App\ResourceConnection;

class ImportOrderManager
{
    protected $wpUserToMagentoUser;
    protected $wpUserToMagentoAddress;
    protected $customer;
    protected $storeManager;
    protected $resourceConnection;


    public function __construct(
        WpUserToMagentoAddress $wpUserToMagentoAddress,
        WpUserToMagentoUser $wpUserToMagentoUser,
        Customer $customer,
        StoreManagerInterface $storeManager,
        ResourceConnection $resourceConnection
    )
    {
        $this->wpUserToMagentoAddress = $wpUserToMagentoAddress;
        $this->wpUserToMagentoUser = $wpUserToMagentoUser;
        $this->customer = $customer;
        $this->storeManager = $storeManager;
        $this->resourceConnection = $resourceConnection;
    }

    public function process(){
        $users = User::fetchAll();
        foreach ($users as $user){
            if(!$this->customerExists($user->user_email) && $user->hasValidEmail()){
                $this->createUser($user);
            }
        }
    }

    protected function createUser($user){
        echo $user->ID.' ';
        $magentoUser = $this->wpUserToMagentoUser->transform($user);
        $magentoUser->save();
        $this->updatePassword($user->user_pass, $magentoUser->getId());
        $this->createAddress($user, $magentoUser->getId());
    }

    protected function createAddress($user, $customerId){
        if($user->hasAddress()){
            echo ' ha ';
            $magentoAddress = $this->wpUserToMagentoAddress->transform($user, $customerId);
            $magentoAddress->save();
        }
    }

    protected function updatePassword($hash, $customerId){
        $connection = $this->resourceConnection->getConnection();
        $sql = "UPDATE `customer_entity` SET `password_hash` = '".$hash."' WHERE `customer_entity`.`entity_id` = '".$customerId."'; ";
        $connection->query($sql);
    }

    public function customerExists($email)
    {
        $customer = $this->customer;
        $websiteId = $this->storeManager->getWebsite()->getWebsiteId();

        $customer->setWebsiteId($websiteId);

        $customer->loadByEmail($email);
        if ($customer->getId()) {
            return $customer;
        }

        return false;
    }
}