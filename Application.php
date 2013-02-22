<?php

class EtuDev_Zend_Application extends Zend_Application {

	/**
	 *
	 * @var Zend_Cache_Core|null
	 */
	protected $_configCache;

	public function __construct($environment, $options = null, Zend_Cache_Core $configCache = null) {
		$this->_configCache = $configCache;
		parent::__construct($environment, $options);
	}

	protected function _cacheId($file) {
		return 'appconfig_' . $this->getEnvironment() . '_' . sha1($file);
	}

	protected function _loadConfigCache() {
		$configCache = new Zend_Cache_Core(array('automatic_serialization' => true));

		if (extension_loaded('xcache')) {
			$backend_type = 'Xcache';
		} elseif (extension_loaded('apc')) {
			$backend_type = 'Apc';
		} elseif (extension_loaded('memcache')) {
			$backend_type = 'Memcached';
		} else {
			$backend_type = 'File';
		}

		$backend = Zend_Cache::_makeBackend($backend_type, array());

		$configCache->setBackend($backend);
		$this->_configCache = $configCache;
	}

	//Override
	protected function _loadConfig($file) {
		if (!$this->_configCache) {
			$this->_loadConfigCache();
		}
		$suffix = strtolower(pathinfo($file, PATHINFO_EXTENSION));
		if ($this->_configCache === null || $suffix == 'php' || $suffix == 'inc') { //No need for caching those
			return parent::_loadConfig($file);
		}

		$configMTime = filemtime($file);

		$cacheId = $this->_cacheId($file);

		//podemos quitar esto para acelerar, pero habría que borrar la cache manualmente en deploy entonces
		$cacheLastMTime = $this->_configCache->test($cacheId);
		if ($cacheLastMTime !== false && $configMTime < $cacheLastMTime) { //Valid cache?
			return $this->_configCache->load($cacheId, true);
		} else {
			$config = parent::_loadConfig($file);
			$this->_configCache->save($config, $cacheId);

			return $config;
		}
	}
}