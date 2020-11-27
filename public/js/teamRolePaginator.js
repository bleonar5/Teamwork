var page_count =  1;

function instructionPaginator(callback) {

  $(".inst").hide();
  $("#instr_nav #back").hide();
  $("#inst_"+page_count).show();

  if(page_count > 1) $("#back").show();

  /*
    Handles click events for instruction navigation buttons
   */
  $('.instr_nav').click(function(event) {

    // Hide the previous instruction
    $("#inst_" + page_count).hide();

    var dir = $(this).attr('id'); // Direction the user is moving

    // Increment or decrement the page count, based on nav button clicked
    page_count = (dir == 'next') ? page_count += 1 : page_count -= 1;

    // If we've reached the end of instructions, go to redirect url


    if(page_count > $(".inst").length){

      if($(this).attr('type') == 'submit') {
        $('.instr_nav').hide();
        $("#waiting").show();
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

    // If they are on page 3 (the start of the second scenario)
    // we don't want them to go back
    if(page_count == 3) $("#back").hide();

    // If there is a page # display, update it
    if($("#pagination-display").length) {

      $("#curr-page").html(page_count);
    }

    if(page_count % 2 == 0) {
      $("#back").attr('value', 'Back to scenario description');
    }

    else {
      $("#back").attr('value', 'Back');
    }

    event.preventDefault();
    return false;

  });

}

function goToPage(page) {
  page_count = page;
}
