function instructionPaginator(callback) {
  var page_count =  1;
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

    // Hide the previous instruction
    $("#inst_" + page_count).hide();

    var dir = $(this).attr('id'); // Direction the user is moving

    // Increment or decrement the page count, based on nav button clicked
    page_count = (dir == 'next') ? page_count += 1 : page_count -= 1;

    // If we've reached the end of instructions, go to redirect url or callback
    if(page_count > $(".inst").length){

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

  });

}
