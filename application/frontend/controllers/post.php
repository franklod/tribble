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
    $data['title'] = 'Tribble - Home';
    $data['meta_description'] = 'A design content sharing and discussion tool.';
    $data['meta_keywords'] = 'Tribble';

    if ($session = $this->rest->get('auth/session/', array('id' => $this->session->userdata('sid'))))
      ;
    {
      if ($session->request_status == true)
      {
        $data['user'] = $session->user;
      } else
      {
        $this->session->sess_destroy();
      }
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

    // get the data for the sidebar widgets
    $tag_data = $this->rest->get('meta/tags');
    $color_data = $this->rest->get('meta/colors');
    // set the data fro the sidebar widgets
    $data['tags'] = $tag_data->tags;
    $data['colors'] = $color_data->colors;

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

    // load views and show the page
    $this->load->view('common/page_top.php', $data);
    $this->load->view('lists/tags.php', $data);
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
    $data['title'] = 'Tribble - Home';
    $data['meta_description'] = 'A design content sharing and discussion tool.';
    $data['meta_keywords'] = 'Tribble';

    if ($session = $this->rest->get('auth/session/', array('id' => $this->session->userdata('sid'))))
      ;
    {
      if ($session->request_status == true)
      {
        $data['user'] = $session->user;
      } else
      {
        $this->session->sess_destroy();
      }
    }

    $search_results = $this->rest->get('posts/find/txt/' . $searchString . '/' . $page);

    if ($search_results->request_status == false)
      show_error($search_results->message, 404);

    $data['search_text'] = $search_results->search->string;
    $data['results'] = $search_results->search->count;

    $tag_data = $this->rest->get('meta/tags');
    $color_data = $this->rest->get('meta/colors');
    $data['tags'] = $tag_data->tags;
    $data['colors'] = $color_data->colors;

    if ($search_results->search->count == 0)
    {
      $this->load->view('common/page_top.php', $data);
      $this->load->view('lists/empty_search.php', $data);
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
      $this->load->view('lists/search.php', $data);
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
        break;
      case 'buzzing':
        $api_call = 'posts/list/buzzing/';
        $list_type = 'buzzing';
        break;
      case 'loved':
        $api_call = 'posts/list/loved/';
        $list_type = 'loved';
        break;
      default:
        $api_call = 'posts/list/new/';
        $list_type = 'new';
        break;
    }

    $data['title'] = 'Tribble - Home';
    $data['meta_description'] = 'A design content sharing and discussion tool.';
    $data['meta_keywords'] = 'Tribble';

    if ($session = $this->rest->get('auth/session/', array('id' => $this->session->userdata('sid'))))
      ;
    {
      if ($session->request_status == true)
      {
        $data['user'] = $session->user;
      } else
      {
        $this->session->sess_destroy();
      }
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
    $data['tags'] = $tag_data->tags;
    $data['colors'] = $color_data->colors;

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

  // /**
  //  * Post::buzzing()
  //  * 
  //  * @param integer $page
  //  * @return
  //  */
  // public function buzzing($page = 1)
  // {
  //   $data['title'] = 'Tribble - Home';
  //   $data['meta_description'] = 'A design content sharing and discussion tool.';
  //   $data['meta_keywords'] = 'Tribble';

  //   if ($session = $this->rest->get('auth/session/', array('id' => $this->session->userdata('sid'))))
  //     ;
  //   {
  //     if ($session->request_status == true)
  //     {
  //       $data['user'] = $session->user;
  //     } else
  //     {
  //       $this->session->sess_destroy();
  //     }
  //   }

  //   if (!$POST_Total = $this->rest->get('posts/total'))
  //   {
  //     show_error(lang('F_API_CONNECT'), 404);
  //   }

  //   $display_per_page = 12;
  //   $api_dataset_rows = 600;

  //   $api_page = floor((($page * $display_per_page) - $display_per_page) / $api_dataset_rows) + 1;

  //   if (!$REST_data = $this->rest->get('posts/list/buzzing/' . $api_page))
  //   {
  //     show_error(lang('F_API_CONNECT'), 404);
  //     log_message(1, 'API Failure. CALL: posts/list/buzzing/' . $api_page);
  //   }

  //   if ($REST_data->request_status == false)
  //   {
  //     show_error($REST_data->message, 404);
  //   }

  //   $page = (int)$page;

  //   $offset = (($page - 1) * $display_per_page) - ($api_dataset_rows * ($api_page - 1));

  //   $tag_data = $this->rest->get('meta/tags');
  //   $color_data = $this->rest->get('meta/colors');
  //   $data['tags'] = $tag_data->tags;
  //   $data['colors'] = $color_data->colors;

  //   $data['posts'] = array_slice($REST_data->posts, $offset, $display_per_page, true);

  //   $config['base_url'] = site_url('buzzing/page');
  //   $config['total_rows'] = $POST_Total->post_count;

  //   $this->pagination->initialize($config);
  //   $data['paging'] = $this->pagination->create_links();

  //   $this->load->view('common/page_top.php', $data);
  //   $this->load->view('lists/main.php', $data);
  //   $this->load->view('widgets/widgets.php', $data);
  //   $this->load->view('common/page_end.php', $data);
  // }

  // /**
  //  * Post::loved()
  //  * 
  //  * @param integer $page
  //  * @return
  //  */
  // public function loved($page = 1)
  // {
  //   $data['title'] = 'Tribble - Home';
  //   $data['meta_description'] = 'A design content sharing and discussion tool.';
  //   $data['meta_keywords'] = 'Tribble';

  //   if ($session = $this->rest->get('auth/session/', array('id' => $this->session->userdata('sid'))))
  //     ;
  //   {
  //     if ($session->request_status == true)
  //     {
  //       $data['user'] = $session->user;
  //     } else
  //     {
  //       $this->session->sess_destroy();
  //     }
  //   }

  //   if (!$POST_Total = $this->rest->get('posts/total'))
  //   {
  //     show_error(lang('F_API_CONNECT'), 404);
  //   }

  //   $display_per_page = 12;
  //   $api_dataset_rows = 600;

  //   $api_page = floor((($page * $display_per_page) - $display_per_page) / $api_dataset_rows) + 1;

  //   if (!$REST_data = $this->rest->get('posts/list/loved/' . $api_page))
  //   {
  //     show_error(lang('F_API_CONNECT'), 404);
  //     log_message(1, 'API Failure. CALL: posts/list/loved/' . $api_page);
  //   }

  //   if ($REST_data->request_status == false)
  //   {
  //     show_error($REST_data->message, 404);
  //   }

  //   $page = (int)$page;

  //   $offset = (($page - 1) * $display_per_page) - ($api_dataset_rows * ($api_page - 1));

  //   $tag_data = $this->rest->get('meta/tags');
  //   $color_data = $this->rest->get('meta/colors');
  //   $data['tags'] = $tag_data->tags;
  //   $data['colors'] = $color_data->colors;

  //   $data['posts'] = array_slice($REST_data->posts, $offset, $display_per_page, true);

  //   $config['base_url'] = site_url('loved/page');
  //   $config['total_rows'] = $POST_Total->post_count;

  //   $this->pagination->initialize($config);
  //   $data['paging'] = $this->pagination->create_links();

  //   $this->load->view('common/page_top.php', $data);
  //   $this->load->view('lists/main.php', $data);
  //   $this->load->view('widgets/widgets.php', $data);
  //   $this->load->view('common/page_end.php', $data);
  // }

  public function tags()
  {
    $data['title'] = 'Tribble - Home';
    $data['meta_description'] = 'A design content sharing and discussion tool.';
    $data['meta_keywords'] = 'Tribble';

    if ($session = $this->rest->get('auth/session/', array('id' => $this->session->userdata('sid'))))
      ;
    {
      if ($session->request_status == true)
      {
        $data['user'] = $session->user;
      } else
      {
        $this->session->sess_destroy();
      }
    }

    if (!$TAGS_get = $this->rest->get('meta/tags/0'))
      show_error('The API could not be reached!', 404, 'Carmona says:');

    if (!$TAGS_get->request_status)
      show_error('The API didn\'t get any tags!', 503, '503 Carmona is unavailable');

    $data['tag_list'] = $TAGS_get->tags;

    $this->load->view('common/page_top.php', $data);
    $this->load->view('lists/tags.php', $data);
    $this->load->view('widgets/widgets.php', $data);
    $this->load->view('common/page_end.php', $data);
  }

  public function users()
  {
    $data['title'] = 'Tribble - Home';
    $data['meta_description'] = 'A design content sharing and discussion tool.';
    $data['meta_keywords'] = 'Tribble';

    if ($session = $this->rest->get('auth/session/', array('id' => $this->session->userdata('sid'))))
      ;
    {
      if ($session->request_status == true)
      {
        $data['user'] = $session->user;
      } else
      {
        $this->session->sess_destroy();
      }
    }

    if (!$users_request = $this->rest->get('users/list'))
      show_error(lang('F_API_CONNECT'), 404);

    if (!$users_request->request_status)
      show_error(lang('F_USER_LIST'), 503);

    $data['user_list'] = $users_request->user_list;

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

    $post_id = substr($postId, 0, strpos($postId, '-'));

    //Pull in an array of tweets
    $REST_Data = $this->rest->get('posts/single/' . $post_id);

    if ($session = $this->rest->get('auth/session/', array('id' => $this->session->userdata('sid'))));
    {
      if ($session->request_status == true)
      {
        $data['user'] = $session->user;

        $LIKE_Data = $this->rest->get('likes/exists/post/' . $postId . '/user/' . $session->user->user_id);

        if ($LIKE_Data->request_status)
        {
          $data['like_status'] = $LIKE_Data->like;
        }

      } else
      {
        $this->session->sess_destroy();
      }
    }

    $tag_data = $this->rest->get('meta/tags');
    $color_data = $this->rest->get('meta/colors');
    $data['tags'] = $tag_data->tags;
    $data['colors'] = $color_data->colors;

    $data['post'] = $REST_Data->post[0];
    $data['replies'] = $REST_Data->post_replies->replies;
    $data['replies_count'] = $REST_Data->post_replies->count;

    $data['title'] = 'Tribble - ' . $data['post']->post_title;
    $data['meta_description'] = $data['post']->post_title;
    $data['meta_keywords'] = $data['post']->post_tags;

    $this->load->view('common/page_top.php', $data);
    $this->load->view('post/view.php', $data);
    $this->load->view('widgets/widgets.php', $data);
    $this->load->view('common/page_end.php', $data);
  }

  function upload()
  {
    $data['title'] = 'Tribble - Upload';
    $data['meta_description'] = 'A design content sharing and discussion tool.';
    $data['meta_keywords'] = 'Tribble';

    if ($session = $this->rest->get('auth/session/', array('id' => $this->session->userdata('sid'))))
      ;
    {
      if ($session->request_status == true)
      {
        $data['user'] = $session->user;

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
          $user_hash = do_hash($session->user->user_email);

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
          if (!$this->upload->do_upload('image_file'))
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
            $imgdata = array('image_path' => substr($ulConfig['upload_path'] . $data['upload_data']['file_name'], 1), 'image_palette' => json_encode(getImageColorPalette($data['upload_data']['full_path'])));

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
            'user_id' => $session->user->user_id
            );

          $post_put = $this->rest->put('posts/upload', $post_put_data);

          if ($post_put->request_status)
          {
            redirect('/view/' . $post_put->post_id);
          } else
          {

            $this->rest->put('trash/throw',array('trash_path'=>$imgdata['image_path']));

            $data['title'] = 'Tribble - Upload';
            $data['meta_description'] = 'A design content sharing and discussion tool.';
            $data['meta_keywords'] = 'Tribble';

            $data['errors'] = array('message' => 'Something is broken. Let\'s all pray to the Mighty Carmona!');

            //form has errors : show page and errors
            $this->load->view('common/page_top.php', $data);
            $this->load->view('post/upload.php', $data);
            $this->load->view('common/page_end.php', $data);
          }

        }

      } else
      {
        // user is not logged in: redirect to login form
        redirect('/auth/login/upload');
      }

    }
  }

  public function delete($post_id){

    if ($session = $this->rest->get('auth/session/', array('id' => $this->session->userdata('sid'))));
    {
      if ($session->request_status == true)
      {
        $data['user'] = $session->user;

        $delete_request = $this->rest->delete('posts/delete',array('post_id'=>$post_id,'user_id'=>$session->user->user_id));

        if(!$delete_request)
          show_error(lang('F_API_CONNECT'));
        if(!$delete_request->request_status)
          show_error($delete_request->message);
              
        $data['message'] = $delete_request->message;
        $data['heading'] = 'It\'s gone!';
        $data['delay'] = 5;

        $this->load->view('common/success.php',$data);      
      } else
      {
        $this->session->sess_destroy();
        redirect(site_url());
      }
    }
    
  }
  
  /**
   * Post::add_comment()
   * 
   * @return
   */
  public function add_comment()
  {

    if (!$this->session->userdata('uid'))
    {
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

    if (!$this->session->userdata('uid'))
    {
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
  public function add_like($post_id)
  {
    if ($session = $this->rest->get('auth/session/', array('id' => $this->session->userdata('sid'))))
      ;
    {
      if ($session->request_status == true)
      {
        $data['user'] = $session->user;
      } else
      {
        $this->session->sess_destroy();
        redirect(site_url());
      }
    }

    $like_add = $this->rest->put('/likes/like', array('post_id' => $post_id, 'user_id' => $data['user']->id));
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
  public function remove_like($post_id)
  {

    if ($session = $this->rest->get('auth/session/', array('id' => $this->session->userdata('sid'))))
      ;
    {
      if ($session->request_status == true)
      {
        $data['user'] = $session->user;
      } else
      {
        $this->session->sess_destroy();
        redirect(site_url());
      }
    }

    $like_remove = $this->rest->delete('/likes/like', array('post_id' => $post_id, 'user_id' => $data['user']->id));
    if ($like_remove->status)
    {
      redirect('/view/' . $post_id);
    } else
    {
      redirect('/view/' . $post_id);
    }
  }

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
