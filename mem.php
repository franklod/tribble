<?php

$output = array('id'=>89,'title'=>'Nucky\'s Speakeasy Lounge - Block Print '); 

// '{"id":"89","title":"Nucky\'s Speakeasy Lounge - Block Print ","text":"I can\'t say hot much I like the real craftmanship of your work. It makes me want to get wood and chisels tomorrow. Absolutely fantastic!","ts":"2012-01-09 15:05:28","username":"Pedro Correia","userid":"8","avatar":null,"image":"\/data\/b5e0eaebec229148d61d1881b27d1865e1bb5003\/23.jpg","likes":"1","replies":"0"}'; 

header('Content-type: application/json');
header('HTTP/1.1: ' . 200);
header('Status: ' . 200);
header('Content-Length: ' . strlen(json_encode($output)));

exit(json_encode($output));

?>