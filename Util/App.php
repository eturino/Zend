<?php


class EtuDev_Zend_Util_App
{

    /**
     * @var Zend_Application_Bootstrap_BootstrapAbstract
     */
    static protected $boostrap;

    static public function setBootstrap(Zend_Application_Bootstrap_BootstrapAbstract $bootstrap)
    {
        static::$boostrap = $bootstrap;
    }

    /**
     * @static
     * @return null|Zend_Application_Bootstrap_BootstrapAbstract
     */
    static public function getBootstrap()
    {
        if (static::$boostrap) {
            return static::$boostrap;
        }

        if (Zend_Controller_Front::getInstance()) {
            return Zend_Controller_Front::getInstance()->getParam('bootstrap');
        }

        return null;
    }

    /**
     * @static
     *
     * @param string $discriminante ie "mi.path.de.options"
     *
     * @return array
     * @uses Zend_Controller_Front
     */
    static public function getZFConfig($discriminante = null)
    {
        if (!$discriminante) {
            $discArray = array();
        } elseif (is_array($discriminante)) {
            $discArray = $discriminante;
        } else {
            $discriminante = trim((string) $discriminante);
            $discArray     = explode('.', $discriminante);
        }


        $config = static::getConfig();

        if (!$discArray) {
            return $config;
        }

        $ops = $config;
        foreach ($discArray as $dn) {
            if (array_key_exists($dn, $ops)) {
                $ops = $ops[$dn];
            } else {
                $dnlower = EtuDev_Util_String::strtolower($dn);
                if (array_key_exists($dnlower, $ops)) {
                    $ops = $ops[$dnlower];
                } else {
                    //si no existe, devolvemos vacio.
                    return array();
                }
            }
        }

        return $ops;
    }

    /**
     * @var array
     */
    static protected $config;

    /**
     * @param array|Zend_Config $config
     */
    static public function setConfig($config)
    {
        if ($config instanceof Zend_Config) {
            static::$config = $config->toArray();
        } else {
            static::$config = $config;
        }
    }

    /**
     * @return array
     */
    static protected function getConfig()
    {
        $bt = static::getBootstrap();

        if ($bt) {
            return $bt->getOptions();
        }

        //no bootstrap available, let's hope we do have config stored!
        return static::$config;
    }

}