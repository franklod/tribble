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
}
