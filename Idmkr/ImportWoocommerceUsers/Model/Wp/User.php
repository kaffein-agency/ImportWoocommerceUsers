<?php


namespace Idmkr\ImportWoocommerceUsers\Model\Wp;

use Idmkr\ImportWoocommerceUsers\Helper\PDOFactory;
use mysql_xdevapi\Exception;

class User
{

    protected $data = [];
    protected $meta = [];


    const WP_MANDATORY_FIELD = [
        'shipping_last_name',
        'shipping_first_name',
        'shipping_country',
        'shipping_postcode',
        'shipping_city',
        'billing_phone',
        'shipping_address_1',
    ];

    public function __construct($data, $meta)
    {
        $this->data = $data;
        $this->meta = $meta;
    }

    public static function fetchAll() {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $db = $objectManager
            ->get('Idmkr\ImportWoocommerceUsers\Helper\PDOFactory')->getMysqlConnexion();
        $tp = PDOFactory::getTablePrefix();
        $sqlUsers =  'SELECT * FROM '.$tp.'_users';

        $users = [];

        foreach  ( $db->query($sqlUsers) as $user) {
            echo $user['ID']. ' ';
            $sqlMetas =  'SELECT * FROM '.$tp.'_usermeta WHERE user_id = "'.$user['ID'].'"';
            $metas = [];
            foreach( $db->query($sqlMetas) as $m){
                $metas[$m['meta_key']] = $m['meta_value'];
            }
            $users[] = new User($user, $metas);
        }

        return $users;
    }

    public function setData($data)
    {
        $this->data = $data;
        return $this;
    }

    public function getData()
    {
        return $this->data;
    }

    public function getMeta(): array
    {
        return $this->meta;
    }

    public function setMeta(array $meta)
    {
        $this->meta = $meta;
        return $this;
    }

    public function __get($key) {
        if(array_key_exists($key, $this->data))
            return $this->data[$key];
        elseif(array_key_exists($key, $this->meta))
            return $this->meta[$key];
        else
            return '';
    }

    public function hasAddress(){
        foreach (self::WP_MANDATORY_FIELD as $f){
            if($this->$f == ''){
                echo ' '.$f.' ';
                return false;
            }
        }
        return true;
    }

    public function hasValidEmail(){
        return (!preg_match(
            "^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$^", $this->user_email))
            ? FALSE : TRUE;
    }

}
