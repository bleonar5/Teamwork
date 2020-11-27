var InstructionPaginator = class InstructionPaginator {
  constructor(page, waitingPages, userId, groupId, groupTaskId, token, modal, callback) {
    this.page = page;
    this.step = 1;
    this.waitingPages = waitingPages;
    this.userId = userId;
    this.groupId = groupId;
    this.groupTasksId = groupTaskId;
    this.token = token;
    this.modal = modal;
    this.callback = callback;
    this.dir;
    this.init();
  }


  init() {
    $(".inst").hide();
    $("#instr_nav #back").hide();
    $("#inst_"+this.page).show();
    console.log($.inArray(this.page, this.waitingPages));

  }

  nav(dir) {
    console.log(dir);
    this.dir = dir;
    if(this.hasWait() && dir == 'next'){
      console.log('you should be waiting');
      this.markIndividualReady();
      this.waitForGroup();
    }
    else {
      this.advance();
    }
  }

  advance() {

    if(this.page > 1) $("#instr_nav #back").show();

    // Hide the previous instruction
    $("#inst_" + this.page).hide();

    // Increment or decrement the page count, based on nav button clicked
    this.page = (this.dir == 'next') ? this.page += 1 : this.page -= 1;

    // If we've reached the end of instructions, go to redirect url or callback
    if(this.page > $(".inst").length){
      $("#pagination-display").hide();
      $('.instr_nav').hide();
      $("#waiting").show();

      if(typeof(this.callback) === 'function') {
        this.callback();
      }
    }

    // Show the new instruction
    $("#inst_"+this.page).show();

    // Hide back button if we're at the start
    if(this.page <= 1){
      $("#instr_nav #back").hide();
    }

    else {
      $("#instr_nav #back").show();
    }

    // If there is a page # display, update it
    if($("#pagination-display").length) {

      $("#curr-page").html(this.page);
    }

    event.preventDefault();
    return false;
  }

  getCurrentPage() {
    return this.page;
  }

  hasWait() {
    return ($.inArray(this.page, this.waitingPages) > -1);
  }

  markIndividualReady() {
    $.post( "/mark-individual-ready", { user_id: this.userId, group_id: this.groupId,
                                        group_tasks_id: this.groupTasksId,
                                        step: this.page, _token: this.token } );
  }

  waitForGroup() {
    console.log(this.step);
    self = this;
    $.get( "/check-group-ready",
            { user_id: this.userId, group_id: this.groupId,
              group_tasks_id: this.groupTasksId, step: this.page,} )
      .done(function( response ) {
        if(response == '1') {
          $(self.modal).modal('hide');
          self.advance();
        }
        else {
          $(self.modal).modal('show');
          setTimeout(function(){
           $(self.modal).modal('show');
           console.log('waiting...');
           self.waitForGroup();
         }, 1000);
        }
    });
  }
}
