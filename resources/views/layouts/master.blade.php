<!DOCTYPE html>
<html>
    <head>
        @include('layouts.includes.head')
    </head>
    <body>
      <span class="group-id-display text-secondary ml-2">
        @if(Auth::user() && Auth::user()->group_id)
          {{ \Teamwork\Group::where('id', Auth::user()->group_id)->pluck('group_number')->first() }}
        @endif
      </span>
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
