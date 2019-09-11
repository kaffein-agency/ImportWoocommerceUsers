<?php
namespace Idmkr\ImportWoocommerceUsers\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Customer\Model\ResourceModel\CustomerRepository;
use Magento\Customer\Model\CustomerRegistry;
use Idmkr\ImportWoocommerceUsers\Helper\PasswordHash;
use Magento\Framework\Encryption\EncryptorInterface;


class UpgradeCustomerPasswordObserver implements ObserverInterface
{
    /**
     * Encryption model
     *
     * @var EncryptorInterface
     */
    protected $encryptor;

    /** @var \Idmkr\ImportWoocommerceUsers\Helper\PasswordHash */
    protected $WpPasswordHasher;
    /**
     * @var CustomerRegistry
     */
    protected $customerRegistry;
    /**
     * @var CustomerRepository
     */
    protected $customerRepository;
    public function __construct(
        EncryptorInterface $encryptor,
        CustomerRegistry $customerRegistry,
        CustomerRepository $customerRepository,
        PasswordHash $WpPasswordHasher
    ) {
        $this->encryptor = $encryptor;
        $this->WpPasswordHasher = $WpPasswordHasher;
        $this->customerRegistry = $customerRegistry;
        $this->customerRepository = $customerRepository;
    }
    /**
     * Upgrade customer password hash when customer has logged in
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @return void
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $requestParams = $observer->getEvent()->getData('request')->getParams();
        $username = $requestParams['login']['username'];
        $password = $requestParams['login']['password'];
        try {
            /** @var \Magento\Customer\Api\Data\CustomerInterface */
            $customer = $this->customerRepository->get($username);
            $customerSecure = $this->customerRegistry->retrieveSecureData($customer->getId());
            $hash = $customerSecure->getPasswordHash();
            if ($this->WpPasswordHasher->CheckPassword($password, $hash)) {
                $customerSecure->setPasswordHash($this->encryptor->getHash($password, true));
                $this->customerRepository->save($customer);
            }
        } catch (\Exception $e) {
        }
    }
}