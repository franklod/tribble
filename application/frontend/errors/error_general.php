<!DOCTYPE html>
  <head>
    <title>Tribble - Home</title>
    <meta charset="utf-8">
    <meta name="description" content="A design content sharing and discussion tool." />
    <meta name="keywords" content="Tribble" />
    <link type="text/css" rel="stylesheet" href="/assets/css/addictive.css" />
    <link type="text/css" rel="stylesheet" href="/assets/css/tribble.css" />
    <link rel="stylesheet" type="text/css" href="/assets/css/tagsinput.css" />
    <script type="text/javascript" src="/assets/js/jquery.1.4.1-min.js"></script>
    <script type="text/javascript" src="/assets/js/jquery.tagsinput.min.js"></script>
    <script type="text/javascript" src="/assets/js/tribble.js"></script>
  </head>
  <body>
    <div id="headerContainer">
      <div id="header">
        <h1><a href="http://tribble.local/index.php">Tribble</a></h1>
        <div id="login">
          <ul>
            <li><a href="http://tribble.local/index.php/user/signup">Sign Up</a></li>
            <li><a href="http://tribble.local/index.php/auth/login" class="defaultBtn btn_send">Log in</a></li>
          </ul>
        </div>
      </div>
    </div>
    <div id="topNavigation" class="blackMenu">
      <ul class="h_navigation">
        <li><a href="http://tribble.local/index.php/">New</a></li>
        <li><a href="http://tribble.local/index.php/buzzing">Buzzing</a></li>
        <li><a href="http://tribble.local/index.php/loved">Loved</a></li>
        <hr />
      </ul>
      <form action="http://tribble.local/index.php/dosearch" method="post" accept-charset="utf-8">
        <div style="display:none">
          <input type="hidden" name="tr_csrf_tk" value="0d0e0d1049bd8a57b49ac0e86f4c9605" />
        </div>  
        <input name="search" type="text" class="" id="search" placeholder="Pesquisar" />
      </form>    
    </div>
    <div id="main">
      <div class="g75">
        <div class="block-alert-msg error">
        <h4><?=$heading?></h4>
        <p><?=$message?></p>
        </div>  
      </div>
    </div>
    <div class="push"></div>  
    <div class="footer"></div>
  </body>
</html>                                                                                                