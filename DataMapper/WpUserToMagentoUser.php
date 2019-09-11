<?php
namespace Idmkr\ImportWoocommerceUsers\DataMapper;


class WpUserToMagentoUser
{
    protected $storeManager;
    protected $customerFactory;

    public function __construct(
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Customer\Model\CustomerFactory $customerFactory
    )
    {
        $this->storeManager = $storeManager;
        $this->customerFactory = $customerFactory;
    }

    public function transform($user){
        $websiteId = $this->storeManager->getWebsite()->getWebsiteId();
        $storeId = $this->storeManager->getStore()->getId();

        $customer = $this->customerFactory->create();
        $customer->setWebsiteId($websiteId);
        $customer->setEmail($user->user_email);
        $customer->setFirstname($this->getUserName($user));
        $customer->setLastname($this->getLastName($user));
        $customer->setPassword($user->user_pass);

        return $customer;
    }

    protected function getUserName($user){
        if($user->first_name != ''){
            return $user->first_name;
        }elseif($user->last_name != ''){
            return $user->last_name;
        }elseif($user->user_login != ''){
            return $user->user_login;
        }else{
            return uniqid();
        }
    }

    protected function getLastName($user){
        if($user->last_name != ''){
            return $user->last_name;
        }elseif($user->first_name != ''){
            return $user->first_name;
        }elseif($user->user_login != ''){
            return $user->user_login;
        }else{
            return uniqid();
        }
    }
}