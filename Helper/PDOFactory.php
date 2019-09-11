<?php
namespace Idmkr\ImportWoocommerceUsers\Helper;

use Magento\Framework\App\Helper\Context;
use Magento\Framework\Encryption\EncryptorInterface;

class PDOFactory extends \Magento\Framework\App\Helper\AbstractHelper
{
    protected $encryptor;


    public function __construct(
        Context $context,
        EncryptorInterface $encryptor)
    {
        parent::__construct($context);
        $this->encryptor = $encryptor;
    }

    public function getMysqlConnexion()
    {
        $host = $this->scopeConfig->getValue('woocommerce_import/database/host');
        $dbname = $this->scopeConfig->getValue('woocommerce_import/database/dbname');
        $username = $this->scopeConfig->getValue('woocommerce_import/database/username');
        $password = $this->encryptor->decrypt($this->scopeConfig->getValue('woocommerce_import/database/password'));
        $port = $this->scopeConfig->getValue('woocommerce_import/database/port');

        $db = new \PDO('mysql:host='.$host.';dbname='.$dbname.';port='.$port.'',$username,$password);
        return $db;
    }

    public static function getTablePrefix(){
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        return $conf = $objectManager
            ->get('Magento\Framework\App\Config\ScopeConfigInterface')
            ->getValue('woocommerce_import/database/table_prefix');
    }

}