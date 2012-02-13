<?php if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Posts
 * 
 * @package tribble
 * @author xxx xxx xxx
 * @copyright 2011
 * @version $Id$
 * @access public
 */

require APPPATH . '/libraries/REST_Controller.php';

class Cache extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->driver('cache');
    }

    public function flush()
    {        
        $this->cache->memcached->clean();
    }
    
    public function info()
    {
      var_dump(@$this->cache->memcached->cache_info());
    }

    public function wipe()
    {
        $cachekey = sha1(str_replace('-', '/', $this->uri->segment(3)));

        echo "<h3>API Method:</h3>";
        var_dump(str_replace('-', '/', $this->uri->segment(3)));
        echo "<h3>Key:</h3>";
        var_dump($cachekey);
        echo "<h3>Cache meta data:</h3>";
        var_dump($this->cache->memcached->get_metadata($cachekey));

        if($this->cache->memcached->get_metadata($cachekey)){
            if($this->cache->memcached->delete($cachekey)){
                echo "<h3>Cache key ".$cachekey." was successfully deleted.</h3>";
            } else {
                echo "<h3>Cache key ".$cachekey." could not be deleted.</h3>";
            }
        }
    }
}
