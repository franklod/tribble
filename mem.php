<?php

if (@ini_get('zlib.output_compression') === FALSE) {
    echo "népia";
    // ob_gzhandler depends on zlib
    if (extension_loaded('zlib')) {
        // if the client supports GZIP compression
        if (isset($_SERVER['HTTP_ACCEPT_ENCODING']) AND strpos($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip') !== FALSE) {
            ob_start('ob_gzhandler');
        }
    }
}

?>