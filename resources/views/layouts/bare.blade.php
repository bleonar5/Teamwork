<!DOCTYPE html>
<html>
    <head>
        @include('layouts.includes.althead')
    </head>
    <body>
      <span class="group-id-display text-secondary ml-2">
        @if(Auth::user() && Auth::user()->group_id)
          {{ \Teamwork\Group::where('id', Auth::user()->group_id)->pluck('group_number')->first() }}
        @endif
      </span>
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