<!DOCTYPE html>
  <head>
    <title>Tribble - Home</title>
    <meta charset="utf-8">
    <meta name="description" content="A design content sharing and discussion tool." />
    <meta name="keywords" content="Tribble" />
    <meta http-equiv="refresh" content="<?=$delay?>; URL=<?=site_url()?>">
    <link type="text/css" rel="stylesheet" href="/assets/css/addictive.css" />
    <link type="text/css" rel="stylesheet" href="/assets/css/tribble.css" />
    <link rel="stylesheet" type="text/css" href="/assets/css/tagsinput.css" />
    <script type="text/javascript" src="/assets/js/jquery.1.4.1-min.js"></script>
    <script type="text/javascript" src="/assets/js/jquery.tagsinput.min.js"></script>
    <script type="text/javascript" src="/assets/js/tribble.js"></script>
    <style type="text/css">
      html {
        background: url(/assets/images/success.jpg) no-repeat center center fixed;
        -webkit-background-size: cover;
        -moz-background-size: cover;
        -o-background-size: cover;
        background-size: cover;
        }
        
        body {background: none;}
        
        .wrapper {
          width: 500px;
          position: absolute;
          top: 50%;
          margin-top: -50px;
          left: 50%;
          margin-left: -250px;
        }
        
        .error {
          color: #333 !important;
          text-align: center;
          background: rgba(255,255,255,0.8);
          padding: 16px;
          -webkit-border-radius: 6px;
          -moz-border-radius: 6px;
          border-radius: 6px;
          -webkit-box-shadow: 0px 0px 1px 2px rgba(100, 100, 100, 0.2);
          -moz-box-shadow: 0px 0px 1px 2px rgba(100, 100, 100, 0.2);
          box-shadow: 0px 0px 1px 2px rgba(100, 100, 100, 0.2);
        }
        
        .error h4 { color: #1eb736; font-size: 2em; }
    </style>
  </head>
  <body>
    <div class="wrapper">
      <div class="error">
          <h4><?=$heading?></h4>
          <p><?=$message?></p>
      </div>
    </div>
  </body>
</html>