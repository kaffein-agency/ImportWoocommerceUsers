<?php
namespace Idmkr\ImportWoocommerceUsers\DataMapper;


class WpUserToMagentoAddress
{
    protected $storeManager;
    protected $addressFactory;


    public function __construct(
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Customer\Model\AddressFactory $addressFactory
    )
    {
        $this->storeManager = $storeManager;
        $this->addressFactory = $addressFactory;
    }

    public function transform($user, $magentoCustomerId){
        $address = $this->addressFactory->create();
        $address->setCustomerId($magentoCustomerId)
            ->setFirstname($user->shipping_last_name)
            ->setLastname($user->shipping_first_name)
            ->setCountryId($user->shipping_country)
            ->setPostcode($user->shipping_postcode)
            ->setCity($user->shipping_city)
            ->setTelephone($user->billing_phone)
            ->setCompany($user->shipping_company)
            ->setStreet($user->shipping_address_1. ' '.$user->shipping_address_2)
            ->setIsDefaultBilling('1')
            ->setIsDefaultShipping('1')
            ->setSaveInAddressBook('1');
        return $address;
    }
}