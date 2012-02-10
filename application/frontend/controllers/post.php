<?php
if (!defined('BASEPATH'))
  exit('No direct script access allowed');

/**
 * Post
 * 
 * @package tribble
 * @author 123456
 * @copyright 2012
 * @version $Id$
 * @access public
 */
class Post extends CI_Controller
{

  /**
   * Post::__construct()
   * 
   * @return
   */
  public function __construct()
  {
    parent::__construct();

    // Load the rest client spark
    $this->load->spark('restclient/2.0.0');
    // Run some setup
    $this->rest->initialize(array('server' => api_url()));
    // load the pagination library
    $this->load->library('pagination');

    // $this->output->enable_profiler(TRUE);
  }

  public function obras(){
    show_error('Closed for maintenance!',500,'Carmona says:');
  }

  /**
   * Post::dosearch()
   * 
   * @return
   */
  public function dosearch()
  {
    echo $this->input->post('search', true);
    redirect('search/' . $this->input->post('search', true));
  }

  /**
   * Post::tag()
   * 
   * @param string $tag
   * @param integer $page
   * @return
   */
  public function tag($tag, $dummy = null, $page = 1)
  {
    
    $session = $this->alternatesession->session_exists();

    if($session){
      $data['user'] = $session;
    }

    // set the defaults
    $display_per_page = 12;
    // number of rows per result set
    $api_dataset_rows = 600;

    // calculate wich result set page should we request from the api
    $api_page = (int)floor((($page * $display_per_page) - $display_per_page) / $api_dataset_rows) + 1;

    // try to get the data from the API and show error on failure
    if (!$REST_data = $this->rest->get('posts/tag/' . $tag . '/' . $api_page))
    {
      show_error(lang('F_API_CONNECT'), 404);
      log_message(1, 'API Failure. CALL: posts/tag/' . $tag . '/' . $api_page);
    }
    // check if the data is here
    if ($REST_data->request_status == false)
    {
      show_error($REST_data->message, 404);
    }

    $tag_data = $this->rest->get('meta/tags');
    $color_data = $this->rest->get('meta/colors');
    $users_data = $this->rest->get('meta/users');

    $data['tags'] = $tag_data->tags;
    $data['colors'] = $color_data->colors;
    $data['users'] = $users_data->users;

    // pagination
    $config['uri_segment'] = 4;
    $config['base_url'] = '/tag/' . $tag . '/page';
    $config['total_rows'] = $REST_data->post_count;
    $this->pagination->initialize($config);
    $data['paging'] = $this->pagination->create_links();

    // cast the uri segment as int
    $page = (int)$page;

    // calculate the offset to use below
    $offset = (($page - 1) * $display_per_page) - ($api_dataset_rows * ($api_page - 1));

    // chop the posts data object to the default per page length
    $data['posts'] = array_slice($REST_data->posts, $offset, $display_per_page, true);
    $data['tag'] = $REST_data->tag;
    $data['count'] = $REST_data->post_count;

    $data['title'] = $this->config->item('site_name') . ' - ' . $REST_data->tag;
    $data['meta_description'] = $this->config->item('site_description');
    $data['meta_keywords'] = $this->config->item('site_keywords');

    // load views and show the page
    $this->load->view('common/page_top.php', $data);
    $this->load->view('search/tags.php', $data);
    $this->load->view('widgets/widgets.php', $data);
    $this->load->view('common/page_end.php', $data);
  }

  /**
   * Post::search()
   * 
   * @param mixed $searchString
   * @param mixed $page
   * @return
   */
  public function search($searchString, $page = null)
  {
    $data['title'] = $this->config->item('site_name');
    $data['meta_description'] = $this->config->item('site_description');
    $data['meta_keywords'] = $this->config->item('site_keywords');

    $session = $this->alternatesession->session_exists();

    if($session){
      $data['user'] = $session;
    }

    $search_results = $this->rest->get('posts/find/txt/' . $searchString . '/' . $page);

    if ($search_results->request_status == false)
      show_error($search_results->message, 404);

    $data['search_text'] = $search_results->search->string;
    $data['results'] = $search_results->search->count;

    $tag_data = $this->rest->get('meta/tags');
    $color_data = $this->rest->get('meta/colors');
    $users_data = $this->rest->get('meta/users');

    $data['tags'] = $tag_data->tags;
    $data['colors'] = $color_data->colors;
    $data['users'] = $users_data->users;

    if ($search_results->search->count == 0)
    {
      $this->load->view('common/page_top.php', $data);
      $this->load->view('search/empty_search.php', $data);
      $this->load->view('widgets/widgets.php', $data);
      $this->load->view('common/page_end.php', $data);
    } else
    {
      $data['posts'] = $search_results->search->posts;

      $config['base_url'] = site_url('search');
      $config['total_rows'] = $search_results->search->count;

      $this->pagination->initialize($config);
      $data['paging'] = $this->pagination->create_links();

      $this->load->view('common/page_top.php', $data);
      $this->load->view('search/search.php', $data);
      $this->load->view('widgets/widgets.php', $data);
      $this->load->view('common/page_end.php', $data);
    }
  }

  /**
   * Post::newer()
   * 
   * @param integer $page
   * @return
   */
  public function lists($type = 'new',$page = 1)
  {


    switch ($type) {
      case 'new':
        $api_call = 'posts/list/new/';
        $list_type = 'new';
        $title_append = ' - Most recent';
        break;
      case 'buzzing':
        $api_call = 'posts/list/buzzing/';
        $list_type = 'buzzing';
        $title_append = ' - Most commented';
        break;
      case 'loved':
        $api_call = 'posts/list/loved/';
        $list_type = 'loved';
        $title_append = ' - Most liked';
        break;
      default:
        $api_call = 'posts/list/new/';
        $list_type = 'new';
        $title_append = ' - Most recent';
        break;
    }

    $data['title'] = $this->config->item('site_name') . $title_append;
    $data['meta_description'] = $this->config->item('site_description');
    $data['meta_keywords'] = $this->config->item('site_keywords');    

    $session = $this->alternatesession->session_exists();

    if($session){
      $data['user'] = $session;
    }

    if (!$POST_Total = $this->rest->get('posts/total'))
    {
      show_error(lang('F_API_CONNECT'), 404);
    }

    // set the defaults
    $display_per_page = 12;
    // number of rows per result set
    $api_dataset_rows = 600;

    // calculate wich result set page should we request from the api
    $api_page = floor((($page * $display_per_page) - $display_per_page) / $api_dataset_rows) + 1;

    // try to get the data from the API and show error on failure
    if (!$REST_data = $this->rest->get($api_call . $api_page))
    {
      show_error(lang('F_API_CONNECT'), 404);
      log_message(1, 'API Failure. CALL: ' . $api_call . $api_page);
    }

    if ($REST_data->request_status == false)
    {
      show_error($REST_data->message, 404);
    }

    $page = (int)$page;
    $offset = (($page - 1) * $display_per_page) - ($api_dataset_rows * ($api_page - 1));

    $tag_data = $this->rest->get('meta/tags');
    $color_data = $this->rest->get('meta/colors');
    $users_data = $this->rest->get('meta/users');

    $data['tags'] = $tag_data->tags;
    $data['colors'] = $color_data->colors;
    $data['users'] = $users_data->users;


    $data['posts'] = array_slice($REST_data->posts, $offset, $display_per_page, true);

    $config['base_url'] = site_url($list_type.'/page');
    $config['total_rows'] = $POST_Total->post_count;

    $this->pagination->initialize($config);
    $data['paging'] = $this->pagination->create_links();

    $this->load->view('common/page_top.php', $data);
    $this->load->view('lists/main.php', $data);
    $this->load->view('widgets/widgets.php', $data);
    $this->load->view('common/page_end.php', $data);
  }

  public function tags()
  {
    $data['title'] = $this->config->item('site_name') . ' - Tags';
    $data['meta_description'] = $this->config->item('site_description');
    $data['meta_keywords'] = $this->config->item('site_keywords');

    $session = $this->alternatesession->session_exists();

    if($session){
      $data['user'] = $session;
    }

    if (!$TAGS_get = $this->rest->get('meta/tags/0'))
      show_error('The API could not be reached!', 404, 'Carmona says:');

    if (!$TAGS_get->request_status)
      show_error('The API didn\'t get any tags!', 503, '503 Carmona is unavailable');


    $top_tag = max(get_object_vars($TAGS_get->tags));    

    // foreach ($TAGS_get->tags as $tag => $count){
    //   $initial = mb_substr($tag,0,1);
    //   $alphabetized[$initial][] = array('tag'=>$tag,'count'=>$count,'percent'=>floor((($count/$top_tag)*100)));
    // }  

    $data['tag_list'] = $this->_alphabetize($TAGS_get->tags,$top_tag);

    $tag_data = $this->rest->get('meta/tags');
    $color_data = $this->rest->get('meta/colors');
    $users_data = $this->rest->get('meta/users');

    $data['tags'] = $tag_data->tags;
    $data['colors'] = $color_data->colors;
    $data['users'] = $users_data->users;

    $this->load->view('common/page_top.php', $data);
    $this->load->view('lists/tags.php', $data);
    $this->load->view('widgets/widgets.php', $data);
    $this->load->view('common/page_end.php', $data);
  }

  public function user($user, $dummy = null, $page = 1)
  {    
    if(!strpos($user, '-'))
    {
      $slug = strlen($user);
    } else {
      $slug = strpos($user, '-');
    }
      
    $user_id = substr($user, 0, $slug);

    $session = $this->alternatesession->session_exists();

    if($session){
      $data['user'] = $session;
    }
    
    // set the defaults
    $display_per_page = 12;
    // number of rows per result set
    $api_dataset_rows = 600;

    // calculate wich result set page should we request from the api
    $api_page = (int)floor((($page * $display_per_page) - $display_per_page) / $api_dataset_rows) + 1;

    // try to get the data from the API and show error on failure
    if (!$user_request = $this->rest->get('posts/user/id/' . $user_id . '/' . $api_page))
    {
      show_error(lang('F_API_CONNECT'), 404);
      log_message(1, 'API Failure. CALL: ' . $api_call . $api_page);
    }

    if ($user_request->request_status == false)
    {
      show_error($user_request->message, 404);
    }

    $page = (int)$page;
    $offset = (($page - 1) * $display_per_page) - ($api_dataset_rows * ($api_page - 1));

    $tag_data = $this->rest->get('meta/tags');
    $color_data = $this->rest->get('meta/colors');
    $users_data = $this->rest->get('meta/users');

    $data['tags'] = $tag_data->tags;
    $data['colors'] = $color_data->colors;
    $data['users'] = $users_data->users;

    $data['posts'] = array_slice($user_request->posts, $offset, $display_per_page, true);
    $data['name'] = $user_request->user_name;
    $data['email'] = $user_request->user_email;
    $data['count'] = $user_request->post_count;
    $data['bio'] = $user_request->user_bio;

    $data['title'] = $this->config->item('site_name') . ' - ' . $user_request->user_name;
    $data['meta_description'] = $this->config->item('site_description');
    $data['meta_keywords'] = $this->config->item('site_keywords');

    // pagination
    $config['uri_segment'] = 4;
    $config['base_url'] = site_url('user/'.$user.'/page');
    $config['total_rows'] = $user_request->post_count;

    $this->pagination->initialize($config);
    $data['paging'] = $this->pagination->create_links();

    $this->load->view('common/page_top.php', $data);
    $this->load->view('search/user.php', $data);
    $this->load->view('widgets/widgets.php', $data);
    $this->load->view('common/page_end.php', $data);


  }

  public function users()
  {
    $data['title'] = $this->config->item('site_name') . ' - Designers';
    $data['meta_description'] = $this->config->item('site_description');
    $data['meta_keywords'] = $this->config->item('site_keywords');

    $session = $this->alternatesession->session_exists();

    if($session){
      $data['user'] = $session;
    }

    if (!$users_request = $this->rest->get('users/list'))
      show_error(lang('F_API_CONNECT'), 404);

    if (!$users_request->request_status)
      show_error(lang('F_USER_LIST'), 503);

    $alphabetized = array();

    $normalizeChars = array(
      'Š'=>'S', 'š'=>'s', 'Ð'=>'Dj','Ž'=>'Z', 'ž'=>'z', 'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 
      'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E', 'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 
      'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U', 'Ú'=>'U', 
      'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss','à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 
      'å'=>'a', 'æ'=>'a', 'ç'=>'c', 'è'=>'e', 'é'=>'e', 'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i', 
      'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o', 'ö'=>'o', 'ø'=>'o', 'ù'=>'u', 
      'ú'=>'u', 'û'=>'u', 'ý'=>'y', 'ý'=>'y', 'þ'=>'b', 'ÿ'=>'y', 'ƒ'=>'f'
    );

    foreach ($users_request->user_list as $user){
      $initial = strtr(mb_substr($user->user_name,0,1),$normalizeChars);
      $alphabetized[$initial][] = array('user'=>$user);
    }

    // $data['user_list'] = $users_request->user_list;
    $data['user_list'] = $alphabetized;

    $tag_data = $this->rest->get('meta/tags');
    $color_data = $this->rest->get('meta/colors');
    $users_data = $this->rest->get('meta/users');

    $data['tags'] = $tag_data->tags;
    $data['colors'] = $color_data->colors;
    $data['users'] = $users_data->users;

    $this->load->view('common/page_top.php', $data);
    $this->load->view('lists/users.php', $data);
    $this->load->view('widgets/widgets.php', $data);
    $this->load->view('common/page_end.php', $data);
  }

  /**
   * Post::view()
   * 
   * @param mixed $postId
   * @return
   */
  public function view($postId)
  {

    if(!strpos($postId, '-'))
    {
      $slug = strlen($postId);
    } else {
      $slug = strpos($postId, '-');
    }
      
    $post_id = substr($postId, 0, $slug);

    //Pull in an array of tweets
    $REST_Data = $this->rest->get('posts/single/' . $post_id);

    if($REST_Data->request_status == false)
      show_404('The post you requested does not exist!');
      

    $session = $this->alternatesession->session_exists();

    if($session){
      $data['user'] = $session;

      $LIKE_Data = $this->rest->get('likes/exists/post/' . $postId . '/user/' . $session->user_id);

      if ($LIKE_Data->request_status)
      {
        $data['like_status'] = $LIKE_Data->like;
      }
    }

    $tag_data = $this->rest->get('meta/tags');
    $color_data = $this->rest->get('meta/colors');
    $users_data = $this->rest->get('meta/users');

    $data['tags'] = $tag_data->tags;
    $data['colors'] = $color_data->colors;
    $data['users'] = $users_data->users;   

    $rgb = json_decode($REST_Data->post[0]->post_image_palette);

    $hex = array();

    foreach($rgb as $color){
      list($r,$g,$b) = explode(',',$color);
      $hex[] = RGBToHex($r,$g,$b);
    }

    $parent_id = $REST_Data->post[0]->post_parent_id;

    if($parent_id != 0){
      $parent_data = $this->rest->get('posts/single/' . $parent_id);
      $data['parent'] = $parent_data->post[0];
    }

    $REST_Data->post[0]->post_image_palette = $hex;

    $data['post'] = $REST_Data->post[0];    
    $data['replies'] = $REST_Data->post_replies->replies;
    $data['replies_count'] = $REST_Data->post_replies->count;

    $data['title'] = $this->config->item('site_name') . ' - ' . $data['post']->post_title;
    $data['meta_description'] = $this->config->item('site_description') . ' - ' .  $data['post']->post_text;
    $data['meta_keywords'] = $this->config->item('site_keywords') . $data['post']->post_tags;

    $this->load->view('common/page_top.php', $data);
    $this->load->view('post/view.php', $data);
    $this->load->view('widgets/widgets.php', $data);
    $this->load->view('common/page_end.php', $data);
  }

  function upload()
  {
    $data['title'] = $this->config->item('site_name') . ' - Upload';
    $data['meta_description'] = $this->config->item('site_description');
    $data['meta_keywords'] = $this->config->item('site_keywords');

    $session = $this->alternatesession->session_exists();

    if($session){
        $data['user'] = $session;

        $this->form_validation->set_error_delimiters('<p class="help">', '</p>');

        // check form submission and validate
        if ($this->form_validation->run('upload_image') == false)
        {

          //form has errors : show page and errors
          $this->load->view('common/page_top.php', $data);
          $this->load->view('post/upload.php', $data);
          $this->load->view('common/page_end.php', $data);

        } else
        {

          // get the uid from the session data and hash it to be used as the user upload folder name
          $user_hash = do_hash($session->user_email);

          // set the upload configuration
          $ulConfig['upload_path'] = './data/' . $user_hash . '/';
          $ulConfig['allowed_types'] = 'jpg|png';
          $ulConfig['max_width'] = '400';
          $ulConfig['max_height'] = '300';
          // $ulConfig['min_width'] = '400';
          // $ulConfig['min_height'] = '300';

          // load the file uploading lib and initialize
          $this->load->library('upload', $ulConfig);
          $this->upload->initialize($ulConfig);

          // check if upload was successful and react
          if (! $this->upload->do_upload('image_file'))
          {
            
            $data['errors'] = array('message' => $this->upload->display_errors());
            //form has errors : show page and errors
            $this->load->view('common/page_top.php', $data);
            $this->load->view('post/upload.php', $data);
            $this->load->view('common/page_end.php', $data);

          } else
          {
            $data = array('upload_data' => $this->upload->data());
            // set the data to write in db;

            $image_color_data = ImageProcessing::GetImageInfo($data['upload_data']['full_path']);

            $imgdata = array(
              'path' => substr($ulConfig['upload_path'] . $data['upload_data']['file_name'], 1), 
              'palette' => json_encode($image_color_data->relevantColors),
              'ranges' => json_encode($image_color_data->relevantColorRanges)
            );

            $config['image_library'] = 'gd2';
            $config['source_image'] = $ulConfig['upload_path'] . $data['upload_data']['file_name'];
            $config['create_thumb'] = true;
            $config['maintain_ratio'] = true;
            $config['width'] = 200;
            $config['height'] = 150;

            $this->load->library('image_lib', $config);
            $this->image_lib->resize();

            $post_put_data = array(
            'image_data' => $imgdata,
            'post_title' => $this->input->post('post_title'),
            'post_text' => $this->input->post('post_text'),
            'post_tags' => $this->input->post('post_tags'),
            'user_id' => $session->user_id
            );
            
            $post_put = $this->rest->put('posts/upload', $post_put_data);

            if ($post_put->request_status)
            {
              redirect('/view/' . $post_put->post_id);
            } else
            {

              $this->rest->put('trash/throw',array('trash_path'=>$imgdata['image_path']));

              $data['title'] = $this->config->item('site_name');
              $data['meta_description'] = $this->config->item('site_description');
              $data['meta_keywords'] = $this->config->item('site_keywords');

              $data['errors'] = array('message' => 'Something is broken. Let\'s all pray to the Mighty Carmona!');

              //form has errors : show page and errors
              $this->load->view('common/page_top.php', $data);
              $this->load->view('post/upload.php', $data);
              $this->load->view('common/page_end.php', $data);
            }

          }          

        }

      } else {
        redirect('/auth/login/upload');
      }

   
  }

  public function delete($postId)
  {

    if(!strpos($postId, '-'))
    {
      $slug = strlen($postId);
    } else {
      $slug = strpos($postId, '-');
    }
      
    $post_id = substr($postId, 0, $slug);

    $session = $this->alternatesession->session_exists();

    if($session){
      $data['user'] = $session;

      $delete_request = $this->rest->delete('posts/delete',array('post_id'=>$post_id,'user_id'=>$session->user_id));

      if(!$delete_request)
        show_error(lang('F_API_CONNECT'));
        
      if(!$delete_request->request_status)
        show_error($delete_request->message);
            
      $data['message'] = $delete_request->message;
      $data['heading'] = 'It\'s gone!';
      $data['delay'] = 5;

      $this->load->view('common/success.php',$data);
    } else {
      redirect(site_url());
    }
    
  }
  
  /**
   * Post::add_comment()
   * 
   * @return
   */
  public function add_comment()
  {

    $session = $this->alternatesession->session_exists();

    if(!$session){
      redirect(site_url());
    }

    $user_id = $this->input->post('user_id');
    $post_id = $this->input->post('post_id');
    $comment_text = $this->input->post('comment_text');
    $comment_response = $this->rest->put('/reply/comment', array(
      'post_id' => $post_id,
      'user_id' => $user_id,
      'comment_text' => $comment_text));
    if ($comment_response->request_status)
    {
      redirect('/view/' . $post_id);
    } else
    {      
      redirect(site_url('/view/' . $post_id), 404);
    }

  }

  /**
   * Post::delete_comment()
   * 
   * @param mixed $comment_id
   * @param mixed $post_id
   * @param mixed $user_id
   * @return
   */
  public function delete_comment($comment_id, $post_id, $user_id)
  {

    $session = $this->alternatesession->session_exists();

    if(!$session){
      redirect(site_url());
    }

    $comment_response = $this->rest->delete('/reply/comment', array(
      'post_id' => $post_id,
      'comment_id' => $comment_id,
      'user_id' => $user_id));
    if ($comment_response->request_status)
    {
      redirect(site_url('/view/' . $post_id));
    } else
    {
      redirect(site_url('/view/' . $post_id), 404);
    }

  }

  /**
   * Post::add_like()
   * 
   * @param mixed $post_id
   * @return
   */
  public function add_like($postId)
  {

    if(!strpos($postId, '-'))
    {
      $slug = strlen($postId);
    } else {
      $slug = strpos($postId, '-');
    }
      
    $post_id = substr($postId, 0, $slug);

    $session = $this->alternatesession->session_exists();

    if($session){
      $data['user'] = $session;
    } else {
      redirect(site_url());
    }    

    $like_add = $this->rest->put('/likes/like', array('post_id' => $post_id, 'user_id' => $session->user_id));
    if ($like_add->status)
    {
      //var_($like_add);
      redirect('/view/' . $post_id);
    } else
    {
      //echo($like_add);
      redirect('/view/' . $post_id);
    }
  }

  /**
   * Post::remove_like()
   * 
   * @param mixed $post_id
   * @return
   */
  public function remove_like($postId)
  {

    if(!strpos($postId, '-'))
    {
      $slug = strlen($postId);
    } else {
      $slug = strpos($postId, '-');
    }
      
    $post_id = substr($postId, 0, $slug);

    $session = $this->alternatesession->session_exists();

    if($session){
      $data['user'] = $session;
    } else {
      redirect(site_url());
    }

    $like_remove = $this->rest->delete('/likes/like', array('post_id' => $post_id, 'user_id' => $session->user_id));
    if ($like_remove->status)
    {
      redirect('/view/' . $post_id);
    } else
    {
      redirect('/view/' . $post_id);
    }
  }

  private function _alphabetize($data,$top){

    $normalizeChars = array(
      'Š'=>'S', 
      'š'=>'s', 
      'Ð'=>'Dj',
      'Ž'=>'Z', 
      'ž'=>'z', 
      'À'=>'A', 
      'Á'=>'A', 
      'Â'=>'A', 
      'Ã'=>'A', 
      'Ä'=>'A', 
      'Å'=>'A', 
      'Æ'=>'A', 
      'Ç'=>'C', 
      'È'=>'E', 
      'É'=>'E', 
      'Ê'=>'E', 
      'Ë'=>'E', 
      'Ì'=>'I', 
      'Í'=>'I', 
      'Î'=>'I', 
      'Ï'=>'I', 
      'Ñ'=>'N', 
      'Ò'=>'O', 
      'Ó'=>'O', 
      'Ô'=>'O', 
      'Õ'=>'O', 
      'Ö'=>'O', 
      'Ø'=>'O', 
      'Ù'=>'U', 
      'Ú'=>'U', 
      'Û'=>'U', 
      'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss','à'=>'A', 'á'=>'A', 'â'=>'A', 'ã'=>'a', 'ä'=>'a', 
      'å'=>'a', 'æ'=>'a', 'ç'=>'c', 'è'=>'A', 'é'=>'e', 'ê'=>'E', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i', 
      'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o', 'ö'=>'o', 'ø'=>'o', 'ù'=>'u', 
      'ú'=>'u', 'û'=>'u', 'ý'=>'y', 'ý'=>'y', 'þ'=>'b', 'ÿ'=>'y', 'ƒ'=>'f'
    );

    $alpha = array();

    foreach ($data as $item => $count){
      $initial = strtr(strtolower(mb_substr($item,0,1)),$normalizeChars);
      $alpha[$initial][] = array('item'=>$item,'count'=>$count,'percent'=>floor((($count/$top)*100)));
    }

    return $alpha;
  }

  public function reply($postId){

    $session = $this->alternatesession->session_exists();

    if($session){

      $data['user'] = $session;

      $data['title'] = $this->config->item('site_name') . ' - Upload';
        $data['meta_description'] = $this->config->item('site_description');
        $data['meta_keywords'] = $this->config->item('site_keywords');

        if(!strpos($postId, '-'))
        {
          $slug = strlen($postId);
        } else {
          $slug = strpos($postId, '-');
        }
          
        $post_id = substr($postId, 0, $slug);

        //Pull in an array of tweets
        $post_data = $this->rest->get('posts/single/' . $post_id);
        $data['post'] = $post_data->post[0];

        $this->form_validation->set_error_delimiters('<p class="help">', '</p>');

        // check form submission and validate
        if ($this->form_validation->run('upload_image') == false)
        {

          //form has errors : show page and errors
          $this->load->view('common/page_top.php', $data);
          $this->load->view('post/reply.php', $data);
          $this->load->view('common/page_end.php', $data); 

        } else
        {

          // get the uid from the session data and hash it to be used as the user upload folder name
          $user_hash = do_hash($session->user_email);

          // set the upload configuration
          $ulConfig['upload_path'] = './data/' . $user_hash . '/';
          $ulConfig['allowed_types'] = 'jpg|png';
          $ulConfig['max_width'] = '400';
          $ulConfig['max_height'] = '300';

          // load the file uploading lib and initialize
          $this->load->library('upload', $ulConfig);
          $this->upload->initialize($ulConfig);

          // check if upload was successful and react
          if (!$this->upload->do_upload('image_file'))
          {

            $data['errors'] = array('message' => $this->upload->display_errors());
            //form has errors : show page and errors
            //form has errors : show page and errors
            $this->load->view('common/page_top.php', $data);
            $this->load->view('post/reply.php', $data);
            $this->load->view('common/page_end.php', $data); 

          } else
          {
            $data = array('upload_data' => $this->upload->data());
            // set the data to write in db;

            $image_color_data = ImageProcessing::GetImageInfo($data['upload_data']['full_path']);

            $imgdata = array(
              'path' => substr($ulConfig['upload_path'] . $data['upload_data']['file_name'], 1), 
              'palette' => json_encode($image_color_data->relevantColors),
              'ranges' => json_encode($image_color_data->relevantColorRanges)
            );

            $config['image_library'] = 'gd2';
            $config['source_image'] = $ulConfig['upload_path'] . $data['upload_data']['file_name'];
            $config['create_thumb'] = true;
            $config['maintain_ratio'] = true;
            $config['width'] = 200;
            $config['height'] = 150;

            $this->load->library('image_lib', $config);
            $this->image_lib->resize();
          }

          $post_put_data = array(
            'image_data' => $imgdata,
            'post_title' => $this->input->post('post_title'),
            'post_text' => $this->input->post('post_text'),
            'post_tags' => $this->input->post('post_tags'),
            'post_parent_id' => $this->input->post('post_parent_id'),
            'user_id' => $session->user_id,
          );          
          
          $post_put = $this->rest->put('reply/post', $post_put_data);

          if ($post_put->request_status)
          {
            redirect('/view/' . $post_put->post_id);
          } else
          {

            $this->rest->put('trash/throw',array('trash_path'=>$imgdata['path']));

            $data['title'] = $this->config->item('site_name');
            $data['meta_description'] = $this->config->item('site_description');
            $data['meta_keywords'] = $this->config->item('site_keywords');

            $data['errors'] = array('message' => 'Something is broken. Let\'s all pray to the Mighty Carmona!');

            //form has errors : show page and errors
            $this->load->view('common/page_top.php', $data);
            $this->load->view('post/reply.php', $data);
            $this->load->view('common/page_end.php', $data); 
          }

        }

    } else {
      redirect(site_url());
    }      

  }

}



/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
