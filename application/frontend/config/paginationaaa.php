<?
$config['uri_segment'] = 3;
// The pagination function automatically determines which segment of your URI contains the page number. If you need something different you can specify it.

$config['num_links'] = 2;
// The number of "digit" links you would like before and after the selected page number. For example, the number 2 will place two digits on either side, as in the example links at the very top of this page.

$config['use_page_numbers'] = TRUE;
// By default, the URI segment will use the starting index for the items you are paginating. If you prefer to show the the actual page number, set this to TRUE.

$config['page_query_string'] = TRUE;
// By default, the pagination library assume you are using URI Segments, and constructs your links something like

$config['full_tag_open'] = '<p>';
//The opening tag placed on the left side of the entire result.

$config['full_tag_close'] = '</p>';
//The closing tag placed on the right side of the entire result.

$config['first_link'] = 'First';
//The text you would like shown in the "first" link on the left. If you do not want this link rendered, you can set its value to FALSE.

$config['first_tag_open'] = '<div>';
//The opening tag for the "first" link.

$config['first_tag_close'] = '</div>';
//The closing tag for the "first" link.

$config['last_link'] = 'Last';
//The text you would like shown in the "last" link on the right. If you do not want this link rendered, you can set its value to FALSE.

$config['last_tag_open'] = '<div>';
//The opening tag for the "last" link.

$config['last_tag_close'] = '</div>';
//The closing tag for the "last" link.

$config['next_link'] = '&gt;';
//The text you would like shown in the "next" page link. If you do not want this link rendered, you can set its value to FALSE.

$config['next_tag_open'] = '<div>';
//The opening tag for the "next" link.

$config['next_tag_close'] = '</div>';
//The closing tag for the "next" link.

$config['prev_link'] = '&lt;';
//The text you would like shown in the "previous" page link. If you do not want this link rendered, you can set its value to FALSE.

$config['prev_tag_open'] = '<div>';
//The opening tag for the "previous" link.

$config['prev_tag_close'] = '</div>';
//The closing tag for the "previous" link.

$config['cur_tag_open'] = '<b>';
//The opening tag for the "current" link.

$config['cur_tag_close'] = '</b>';
//The closing tag for the "current" link.

$config['num_tag_open'] = '<div>';
//The opening tag for the "digit" link.

$config['num_tag_close'] = '</div>';
//The closing tag for the "digit" link.

$config['display_pages'] = FALSE;
//If you wanted to not list the specific pages (for example, you only want "next" and "previous" links), you can suppress their rendering by adding:

?>