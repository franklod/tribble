<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title><?=$api_site_name?></title>
  <link type="text/css" rel="stylesheet" href="/assets/css/addictive.css" />  
  <style type="text/css">
    body { background: #f2f2f2; }
    .main {
      width: 976px;
      margin: 32px auto;    
    }
    pre {
      background: #effaff;
      padding: 16px;
      -webkit-border-radius: 6px;
      -moz-border-radius: 6px;
      border-radius: 6px;
      -webkit-box-shadow: 0px 1px 1px 0px rgba(150, 150, 150, 0.3);
      -moz-box-shadow: 0px 1px 1px 0px rgba(150, 150, 150, 0.3);
      box-shadow: 0px 1px 1px 0px rgba(150, 150, 150, 0.3);
      margin: 16px 0;
    }
    section { margin: 16px 0; }
    section h3 { color: #00a1e5; }
    
    pre {
      background: #00354c;
      padding: 16px;
      -webkit-border-radius: 6px;
      -moz-border-radius: 6px;
      border-radius: 6px;
      -webkit-box-shadow: 0px 1px 1px 0px rgba(150, 150, 150, 0.3);
      -moz-box-shadow: 0px 1px 1px 0px rgba(150, 150, 150, 0.3);
      box-shadow: 0px 1px 1px 0px rgba(150, 150, 150, 0.3);
      margin: 16px 0;
      color: #eee;
    }
    pre strong { color: #2dc0ff; }
    ul {
      margin: 16px 0;
    }
  </style>
  </head>
<body>
<div class="main">
  
<h1>Welcome to the <?=$api_site_name?></h1>

<p>Access to the api is provided only over HTTP requests and by default results are returned as JSON.</p>
<p>You can get the results in other formats  by adding /format/desired_format at the end of the request url. The following formats are available:</p>
<ul>
  <li>JSON (format/json)</li>
  <li>XML (format/xml)</li>
  <li>Serialized PHP object (format/serialized)</li>
</ul>
<pre>
This request woud get you the second page of 600 results of the most recent posts:
  
<strong>$ curl http://api.tribble.local/posts/list/new/format/xml</strong>
  
  &lt;?xml version="1.0" encoding="utf-8"?&gt;
  &lt;xml&gt;
    &lt;request_status&gt;1&lt;/request_status&gt;
    &lt;result_page&gt;1&lt;/result_page&gt;
    &lt;post_count&gt;600&lt;/post_count&gt;
    &lt;posts&gt;
      &lt;post&gt;
        &lt;post_id&gt;1817&lt;/post_id&gt;
        &lt;post_title&gt;funny iPhone composition&lt;/post_title&gt;
        &lt;post_text&gt;funny iPhone composition&lt;/post_text&gt;
        &lt;post_date&gt;2012-01-13 17:08:19&lt;/post_date&gt;
        &lt;post_image_path&gt;/data/tests/15.png&lt;/post_image_path&gt;
        &lt;post_like_count&gt;1&lt;/post_like_count&gt;
        &lt;post_reply_count&gt;0&lt;/post_reply_count&gt;
        &lt;user_id&gt;907&lt;/user_id&gt;
        &lt;user_name&gt;Serafina Henriques&lt;/user_name&gt;
        &lt;user_avatar&gt;&lt;/user_avatar&gt;
      &lt;/post&gt;
      ...
    &lt;/posts&gt;
  &lt;/xml&gt;      
</pre>
<p class="note">Note: </p>
<p>By default when requesting lists, only the first 600 results are returned. If you wish to request more results you can use the 'page/n' uri parameter to get more pages:</p>
<pre>
This request woud get you the second page of 600 results of the most recent posts:

<strong>$ curl http://api.tribble.local/posts/list/new/2</strong>
</pre>


<p>The following methods are available:</p>

<article>
<section>
<h3><span class="label_new">GET</span> posts/list/new</h3>
<p>Gets a list of the most recent posts.</p>
<pre>
<strong>$ curl <?=site_url('/posts/list/new')?></strong>

  {
    "request_status":true,
    "result_page":1,
    "post_count":600,
    "posts":[
      {
        "post_id":"1817",
        "post_title":"funny iPhone composition",
        "post_text":"funny iPhone composition",
        "post_date":"2012-01-13 17:08:19",
        "post_image_path":"\/data\/tests\/15.png",
        "post_like_count":"1",
        "post_reply_count":"0",
        "user_id":"907",        
        "user_name":"Serafina Henriques",
        "user_avatar":null
      },
      ...
    ]
  }
</pre>
</section>
<section>
  <h3><span class="label_new">GET</span> posts/list/buzzing</h3>
<p class="note">Gets a list of posts sorted by number of replies and date.</p>
<pre>
<strong>$ curl <?=site_url('/posts/list/buzzing')?></strong>

  {
    "request_status":true,
    "result_page":1,
    "post_count":600,
    "posts":[
      {
        "post_id":"85",
        "post_title":"Election Survey Pie",
        "post_text":"A pie graph for an election survey site.\r\n\r\nThank you Vucek for inviting me!",
        "post_date":"2012-01-09 12:18:34",
        "post_image_path":"\/data\/b5e0eaebec229148d61d1881b27d1865e1bb5003\/18.jpg",
        "post_like_count":"1",
        "post_reply_count":"3",
        "user_id":"8",
        "user_name":"Pedro Correia",
        "user_avatar":null
      },
      ...
    ]
  }
</pre>
</section>
<section>
<h3><span class="label_new">GET</span> posts/list/loved</h3>
<p>Gets a list of the most liked posts.</p>
<pre>
<strong>$ curl <?=site_url('/posts/list/loved')?></strong>


  {
    "request_status":true,
    "result_page":1,
    "post_count":600,
    "posts":[
      {
        "post_id":"88",
        "post_title":"Stats (iPad UX\/UI)",
        "post_text":"Here is part of an iPad App UX project I'm working on...",
        "post_date":"2012-01-09 13:21:39",
        "post_image_path":"\/data\/b5e0eaebec229148d61d1881b27d1865e1bb5003\/21.png",
        "post_like_count":"3",
        "post_reply_count":"0",
        "user_id":"8",
        "user_name":"Pedro Correia",
        "user_avatar":null
      },
      ...
    ]
  }
</pre>
</section>
<section>
<h3><span class="label_new">GET</span> /posts/tag/:string</h3>
<p>Get a list of all posts tagged as (:string)</p>
<pre>
<strong>$ curl <?=site_url('/posts/tag/iPhone')?></strong>


  {
    "request_status":true,
    "result_page":1,
    "post_count":600,
    "posts":[
      {
        "post_id":"88",
        "post_title":"Stats (iPad UX\/UI)",
        "post_text":"Here is part of an iPad App UX project I'm working on...",
        "post_date":"2012-01-09 13:21:39",
        "post_image_path":"\/data\/b5e0eaebec229148d61d1881b27d1865e1bb5003\/21.png",
        "post_like_count":"3",
        "post_reply_count":"0",
        "user_id":"8",
        "user_name":"Pedro Correia",
        "user_avatar":null
      },
      ...
    ]
  }
</pre>
</section>
<section>
<h3><span class="label_new">GET</span> posts/single/:id</h3>
<p>Retrieve a single post</p>
<pre>
<strong>$ curl <?=site_url('posts/single/85')?></strong>


  {
    "request_status":true,
    "post":[
      {
        "post_id":"85",
        "post_title":"Election Survey Pie",
        "post_text":"A pie graph for an election survey site.\r\n\r\nThank you Vucek for inviting me!",
        "post_date":"2012-01-09 12:18:34",
        "post_num_likes":"1",
        "post_image_path":"\/data\/b5e0eaebec229148d61d1881b27d1865e1bb5003\/18.jpg",
        "post_image_palette":"[\"FFFFFF\",\"CCFFFF\",\"CCCCCC\",\"CCCCFF\",\"003399\",993366,\"FF6633\",\"CC0000\"]",
        "post_tags":"ui,election survey,chart,graph,pie",
        "user_id":"8",
        "user_name":"Pedro Correia",
        "user_avatar":null
      }
    ],
    "post_replies":
      {
        "count":3,
        "replies":[
          {
            "reply_post_id":null,
            "reply_post_title":null,
            "reply_post_text":null,
            "post_image_path":null,
            "reply_post_user_id":null,
            "reply_post_user_name":null,
            "reply_post_avatar":null,
            "reply_comment_id":"34",
            "reply_comment_text":"godon brwon still it ati see the media won t admit that iraq is raising petrol prices.",
            "reply_comment_user_id":"8",
            "reply_comment_user_name":"Pedro Correia",
            "reply_comment_user_avatar":null,
            "reply_date":"2012-01-12 16:30:19"
          },
          ...
        ]
      }
    }
</pre>
</section>





<section>
<h3><span class="label_new">GET</span> posts/count</h3>
<p>Retrieve a count of all posts</p>
<pre>
<strong>$ curl <?=site_url('posts/count')?></strong>


  {
    "request_status":true,
    "post_count":1745
  }
</pre>
</section>
</article>
<footer>
  
</footer>
</body>
</html>