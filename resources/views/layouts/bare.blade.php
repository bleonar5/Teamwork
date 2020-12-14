<!DOCTYPE html>
<html>
    <head>
        @include('layouts.includes.althead')
    </head>
    <body>
        @yield('content')
        <script>
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