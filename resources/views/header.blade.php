<?php
use App\Helpers\DataStore;
  $app = DataStore::instanceInfo()['app'];

 ?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
  <meta name="description" content="DevLess is a backend as a service framework that provide developers an easier way to rollout their web and mobile platform ">
  <meta name="author" content="DevLess">
  <meta name="keyword" content="DevLess, opensource, BAAS, Backend as a service, robust, php, laravel, postgresql, mysql">


  <title>DevLess {{config('devless')['version']}}</title>

    @include('stylesheet')

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="js/html5shiv.js"></script>
    <script src="js/respond.min.js"></script>
    <![endif]-->
  </head>

  <style>
    .btn-circle {
      width: 30px;
      height: 30px;
      text-align: center;
      padding: 6px 0;
      font-size: 12px;
      line-height: 1.428571429;
      border-radius: 15px;
      color: #32323a;
      margin-right: 10px;
      background-color: rgba(0, 0, 0, 0.1);
    }
  </style>

  <!-- start Mixpanel --><script type="text/javascript">(function(e,a){if(!a.__SV){var b=window;try{var c,l,i,j=b.location,g=j.hash;c=function(a,b){return(l=a.match(RegExp(b+"=([^&]*)")))?l[1]:null};g&&c(g,"state")&&(i=JSON.parse(decodeURIComponent(c(g,"state"))),"mpeditor"===i.action&&(b.sessionStorage.setItem("_mpcehash",g),history.replaceState(i.desiredHash||"",e.title,j.pathname+j.search)))}catch(m){}var k,h;window.mixpanel=a;a._i=[];a.init=function(b,c,f){function e(b,a){var c=a.split(".");2==c.length&&(b=b[c[0]],a=c[1]);b[a]=function(){b.push([a].concat(Array.prototype.slice.call(arguments,
0)))}}var d=a;"undefined"!==typeof f?d=a[f]=[]:f="mixpanel";d.people=d.people||[];d.toString=function(b){var a="mixpanel";"mixpanel"!==f&&(a+="."+f);b||(a+=" (stub)");return a};d.people.toString=function(){return d.toString(1)+".people (stub)"};k="disable time_event track track_pageview track_links track_forms register register_once alias unregister identify name_tag set_config reset people.set people.set_once people.increment people.append people.union people.track_charge people.clear_charges people.delete_user".split(" ");
for(h=0;h<k.length;h++)e(d,k[h]);a._i.push([b,c,f])};a.__SV=1.2;b=e.createElement("script");b.type="text/javascript";b.async=!0;b.src="undefined"!==typeof MIXPANEL_CUSTOM_LIB_URL?MIXPANEL_CUSTOM_LIB_URL:"file:"===e.location.protocol&&"//cdn.mxpnl.com/libs/mixpanel-2-latest.min.js".match(/^\/\//)?"https://cdn.mxpnl.com/libs/mixpanel-2-latest.min.js":"//cdn.mxpnl.com/libs/mixpanel-2-latest.min.js";c=e.getElementsByTagName("script")[0];c.parentNode.insertBefore(b,c)}})(document,window.mixpanel||[]);
mixpanel.init("ce7dd2fc4b5246ae1fa7c9d00cec362a");</script><!-- end Mixpanel -->
<script type="text/javascript">
    mixpanel.track("visit");
   function registerUser(){
     email =  document.getElementsByName('email')[0].value;
     userObj = {}
     userObj['email'] = email;
     mixpanel.track(JSON.stringify(userObj));
   }
</script>
  <body onload="init()" class="sticky-header">
    <section>
      @if(\Request::path() != '/' && \Request::path() != 'setup' && \Request::path() != 'recover_password')
      <div class="header-section">
        <!--toggle button start-->
        <a class="toggle-btn"><i class="fa fa-outdent"></i></a>
        <div class="notification-wrap">
          <div class="right-notification" style="position: relative; top: 1em; right: 1em;">
            <ul class="notification-menu">
              <li class="d-none">
                  <button type="button" class="btn btn-sm btn-primary btn-circle" data-toggle="modal" data-target="#quick-demo" data-tooltip="tooltip" data-placement="bottom" title="Getting Started">
                      <i class="fa fa-play" style="font-weight: 800;"></i>
                  </button>
              </li>
              <li class="d-none">
                <button data-toggle="modal" data-tooltip="tooltip" data-placement="bottom" title="Connect to App" data-target="#sdk-connect"  class="btn btn-sm btn-success btn-circle" >
                  <i class="fa fa-plug" style="font-weight: 800;"></i>
                </button>
              </li>
              <li class="d-none">
                <button class="btn btn-sm btn-warning btn-circle" data-toggle="tooltip" data-placement="bottom" title="Go to Documentation" href="https://devless.gitbooks.io/devless-docs-1-3-0/html_sdk.html" target="blank"><i class="fa fa-book" style="font-weight: 800;"></i></button>
              </li>
              <li class="d-none">
                  <button class="btn btn-sm btn-info btn-circle" data-toggle="tooltip" data-placement="bottom" title="Show Notifications" id="beamer-notification"> <i class="fa fa-bullhorn" style="font-weight: 800;"></i> </button>
              </li>
            </ul>
          </div>
        </div>
        
        {{-- <button class="btn btn-sm btn-info pull-right" id="beamer-notification" > <i class="fa fa-bullhorn"></i> Notifications</button> --}}
        
        {{-- <a  style="margin-right: 2%" data-toggle="modal" data-target="#sdk-connect"  class="btn btn-sm btn-primary pull-right" >
              <i class="fa fa-plug"></i>  Connect to App 
        </a> --}}
          
        {{-- <a class="btn btn-sm btn-warning pull-right" data-toggle="modal" href="https://devless.gitbooks.io/devless-docs-1-3-0/html_sdk.html" target="blank" style="margin-right: 2%">&nbsp;&nbsp;&nbsp; <i class="fa fa-book"></i> Docs &nbsp;&nbsp;&nbsp;</a> --}}
        
        <!--toggle button end-->
        <!-- Button trigger modal -->
        
        {{-- <button type="button"  style="margin-right: 2%" class="btn btn-sm btn-primary pull-right" data-toggle="modal" data-target="#quick-demo">
            <i class="fa fa-play"></i>  Getting Started
        </button> --}}
        
       
      </div>
      <!-- header section end-->


    <div class="modal fade" id="sdk-connect" tabindex="-1" role="dialog" aria-labelledby="quickGuideLabel">
     <div class="modal-dialog" role="document">
       <div class="modal-content">
         <div class="modal-header">
           <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
           <h4 class="modal-title" id="myModalLabel"><i class="fa fa-plug"></i> SDK Options</h4>
         </div>
         <div class="modal-body">

          <ul class="nav nav-tabs" id="options-tab">
            <li></li>
            <li><a data-target="#web" data-toggle="tab">Web</a></li>
            <li><a data-target="#android" data-toggle="tab">Android</a></li>
            <li><a data-target="#raw" data-toggle="tab">Raw</a></li>
          </ul>

          <div class="tab-content">
            <div class="tab-pane active" id="web">
<pre><code class="language-markup"><xmp style="display: inline;">
<script src="{{URL::to('/')}}/js/devless-sdk.js" class="devless-connection" devless-con-token="{{$app->token}}"  ></script></xmp></code>
</pre>

        </div>
        <div class="tab-pane" id="android"><center>NA</center></div>
        <div class="tab-pane" id="raw"><center></center>nter>
          Domain URL: {{URL::to('/')}}<br>
          Token: {{$app->token}}
        </center></div>
      </div>

    </div>
  </div>
</div>
</div>
@endif

<!-- Modal -->
<div class="modal fade" id="quick-demo" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Getting Started</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body text-center">
          <div class="row">
             <div class="col-lg-4"><a href="https://www.youtube.com/watch?v=sEFu4_124vQ" target="blank" class="btn btn-primary"><i class="fa fa-fast-forward"></i> Quick Video</a></div>
             <div class="col-lg-4"><a href="http://devless.io/tutorials.html" target="blank" class="btn btn-success"><i class="fa fa-youtube"></i> Tutorial Collection</a></div>
             <div class="col-lg-4"><a href="https://devless.gitbooks.io/devless-docs-1-3-0/html_sdk.html" target="blank" class="btn btn-warning">&nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-book"></i> Docs &nbsp;&nbsp;&nbsp;</a></div>
          </div>
      </div>
      
    </div>
  </div>
</div>
<?php $menuName = (isset($menuName))? $menuName : ''; ?>
<?php $services = (isset($services))? $services : []; ?>
@if($menuName == 'all_services' && ! count($services) >= 2)
<script>
   $(function () {

    $('#quick-demo').modal({
      keyboard: true
   });
   $('.modal-backdrop').removeClass("modal-backdrop");
});
</script>
@endif
