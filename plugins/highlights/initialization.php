<?php
namespace Pure\Plugins\HighLights{
    class Configuration extends \Pure\Plugins\Configuration{
        public $version     = '0.01';
        public $name        = '[PT]HighLights';
        public $id          = 'PureTheme_HighLights';
        public $description = 'Pure theme plugin';
    }
    class Initialization extends \Pure\Plugins\Initialization{
        static private $self;
        static function instance(){
            if (!self::$self){
                $namespace  = preg_split('/(\\\)/', __NAMESPACE__);
                $namespace  = array_splice($namespace, 2);
                self::$self = new self(new Configuration($namespace));
            }
            return self::$self;
        }
    }
    Initialization::instance()->init();
}
?>