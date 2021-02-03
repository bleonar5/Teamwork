<!DOCTYPE html>
<html>
    <head>
        @include('layouts.includes.head')
    </head>

    <body>
        <!--Start of Tawk.to Script-->
<script type="text/javascript">
var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
(function(){
var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
s1.async=true;
s1.src='https://embed.tawk.to/60194dd2c31c9117cb74ffb1/1ethdfvhk';
s1.charset='UTF-8';
s1.setAttribute('crossorigin','*');
s0.parentNode.insertBefore(s1,s0);
})();
Tawk_API.onLoad = function() {
  Tawk_API.setAttributes({
        group : '<?php echo \Teamwork\User::find(\Auth::user()->id)->group_id; ?>',
        task : '<?php if(session('currentGroupTask')){echo session('currentGroupTask');} ?>',
        id  : '<?php echo \Teamwork\User::find(\Auth::user()->id)->participant_id; ?>',
    }, function(error){});
  Tawk_API.visitor = {
    prolific_id  : '<?php echo \Teamwork\User::find(\Auth::user()->id)->participant_id; ?>',
    email : '<?php echo \Teamwork\User::find(\Auth::user()->id)->participant_id."@gmail.com"; ?>',
    hash  : '<?php echo hash_hmac("sha256", \Teamwork\User::find(\Auth::user()->id)->email, "3dafa7d435ae5ca17f6ba50b01eb7fe036d5df9a"); ?>'
  };
}

</script>
<!--End of Tawk.to Script-->
        @yield('content')
        <!-- Include all compiled plugins (below), or include individual files as needed -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js"
                integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb"
                crossorigin="anonymous">
        </script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js"
                integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh"
                crossorigin="anonymous">
        </script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js"
                integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ"
                crossorigin="anonymous">
        </script>
        <script src="https://js.pusher.com/7.0/pusher.min.js"></script>
        <script>
          window.addEventListener('keydown', function(e) {
              if (e.keyIdentifier == 'U+000A' || e.keyIdentifier == 'Enter' || e.keyCode == 13) {
                      e.preventDefault();
                      return false;
              }
          }, true);
          // Disable auto-complete for all forms
          $(document).ready(function(){
            $('form,input,select,textarea').attr("autocomplete", "off");
          });

          // Disable back button OwO
          history.pushState(null, null, document.URL);
          window.addEventListener('popstate', function () {
            history.pushState(null, null, document.URL);
          });
        </script>
    </body>
</html>
