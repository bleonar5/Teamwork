function instructionPaginator(callback) {
  page_count =  1;
  $(".inst").hide();
  $("#instr_nav #back").hide();
  $("#inst_"+page_count).show();

  if(page_count > 1) $("#instr_nav #back").show();

  function goToPage(page) {
    page_count = page;
    console.log('at page ' + page_count);
  }

  /*
    Handles click events for instruction navigation buttons
   */


  $('.instr_nav').click(function(event) {
    console.log('lfg');

    $.post('/crypto-proceed', {id: user_id,_token: $('meta[name="csrf-token"]').attr('content')})
      .done(function(data) {
        console.log(data);
        if(data == 'WAIT'){
          $('#next').val('Waiting for teammates...');
        }
        else{
          if(data == 'GO')
            return false;
          console.log('went');
          $("#inst_" + page_count).hide();

          var dir = $(event.target).attr('id'); // Direction the user is moving
          console.log(dir);
          console.log(event.target);

          // Increment or decrement the page count, based on nav button clicked
          page_count = (dir == 'next') ? page_count += 1 : page_count -= 1;
          console.log(page_count);
          // If we've reached the end of instructions, go to redirect url or callback
          if(page_count > $(".inst").length){
            console.log('longer');

            $("#pagination-display").hide();
            $('.instr_nav').hide();
            $("#waiting").show();

            if($(this).attr('type') == 'submit') {
              $('form').submit();
            }
            else if(typeof(callback) === 'function') {
              callback();
            }
          }

          // Show the new instruction
          $("#inst_"+page_count).show();

          // Hide back button if we're at the start
          if(page_count <= 1){
            $("#instr_nav #back").hide();
          }

          else {
            $("#instr_nav #back").show();
          }

          // If there is a page # display, update it
          if($("#pagination-display").length) {

            $("#curr-page").html(page_count);
          }

          event.preventDefault();
          return false;

        }
      });
    // Hide the previous instruction
    

  });

}
