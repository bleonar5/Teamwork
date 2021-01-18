var Memory = class Memory {

  constructor(tests, isReporter, callback) {
    this.tests = tests;
    this.blockIndex = localStorage.getItem('blockIndex') ? localStorage.getItem('blockIndex') : 0 ;
    this.testIndex = localStorage.getItem('testIndex') ? localStorage.getItem('testIndex') : 0;
    this.callback = callback;
    this.step = localStorage.getItem('step') ? localStorage.getItem('step') : 1;
    this.isReporter = isReporter;

    this.navTargetPosition = localStorage.getItem('navTargetPosition') ? localStorage.getItem('navTargetPosition') : 0;
    this.autoNavInterval = localStorage.getItem('autoNavInterval') ? localStorage.getItem('autoNavInterval') : null;
    this.popupTimeout; // Holds the timeout for any current instruction popups
    this.popupSeen = []; // Holds popups that have already been seen
    this.groupTestReviewChoice = localStorage.getItem('groupTestReviewChoice') ?  localStorage.getItem('groupTestReviewChoice') : null;
    this.navImgTargetPosition = localStorage.getItem('navImgTargetPosition') ? localStorage.getItem('navImgTargetPosition') : null;
    this.navWordTargetPosition = localStorage.getItem('navWordTargetPosition') ? localStorage.getItem('navImgTargetPosition') : null;


  }

  begin() {
    console.log(this.testIndex);
    $(".memory").hide();
    $(`#memory_${this.testIndex}_${this.blockIndex}`).show();
    this.initializeBlock();
  }

  advance() {
    $(`#memory_${this.testIndex}_${this.blockIndex}`).hide();
    this.blockIndex++;
    localStorage.setItem('blockIndex',this.blockIndex);
    this.checkPosition();
    $(`#memory_${this.testIndex}_${this.blockIndex}`).show();
  }


  advanceImageTest(val) {
    $(`#response_${this.testIndex}_${this.blockIndex}`).val(val);
    localStorage.setItem($(`#response_${this.testIndex}_${this.blockIndex}`).attr('name'),val);
    $(`#memory_${this.testIndex}_${this.blockIndex}`).hide();
    this.blockIndex++;
    localStorage.setItem('blockIndex',this.blockIndex);
    localStorage.setItem($(`#response_${this.testIndex}_${this.blockIndex}`).attr('name'),val);
    this.checkPosition();
    $(`#memory_${this.testIndex}_${this.blockIndex}`).show();

  }

  navTarget(dir) {

    // Get the number of target images to cycle through
    var items = $('.memory-review:visible .target-img').length;

    // Hide the current one
    $('.target-' + this.navTargetPosition).hide();

    this.navTargetPosition = (this.navTargetPosition < items - 1) ? this.navTargetPosition += 1 : 0;

    localStorage.setItem('navTargetPosition',this.navTargetPosition);

    $('.target-' + this.navTargetPosition).show();

  }

  navImgTarget(dir) {

    // Get the number of target images to cycle through
    var items = $('.memory-review:visible .target-img').length;

    // Hide the current one
    $('.img-target-' + this.navImgTargetPosition).hide();

    this.navImgTargetPosition = (this.navImgTargetPosition < items - 1) ? this.navImgTargetPosition += 1 : 0;

    localStorage.setItem('navImgTargetPosition',this.navImgTargetPosition);

    $('.img-target-' + this.navImgTargetPosition).show();

  }

  navWordTarget(dir) {

    // Get the number of target images to cycle through
    var items = $('.memory-review:visible .target-word').length;

    // Hide the current one
    $('.word-target-' + this.navWordTargetPosition).hide();

    if(dir == 'next'){
      this.navWordTargetPosition += 1;
      localStorage.setItem('navWordTargetPosition',this.navWordTargetPosition);
      $('.memory-review:visible .back').prop('disabled', false);
      if(this.navWordTargetPosition == items - 1) $('.memory-review:visible .next').prop('disabled', true);
    }

    if(dir == 'back'){
      this.navWordTargetPosition -= 1;
      localStorage.setItem('navWordTargetPosition',this.navWordTargetPosition);
      $('.memory-review:visible .next').prop('disabled', false);
      if(this.navWordTargetPosition == 0) $('.memory-review:visible .back').prop("disabled",true);
    }

    $('.word-target-' + this.navWordTargetPosition).show();

  }

  autoNavTarget() {

    $('.target-' + this.navTargetPosition).hide();
    this.navTargetPosition++;
    localStorage.setItem('navTargetPosition',this.navTargetPosition);

    //  If there are no other targets
    if(this.navTargetPosition == this.tests[this.testIndex].blocks[this.blockIndex].targets.length) {
      clearInterval(this.autoNavInterval);
      this.advance();
    }
    else {
      $('.target-' + this.navTargetPosition).show();
    }
  }

  checkPosition() {
    if(this.advanceToEnd) {
      this.callback();
      return;
    }
    if(this.blockIndex > this.tests[this.testIndex].blocks.length - 1) {
      if(this.testIndex + 1 > this.tests.length - 1) {
        // Do callback to redirect?
        this.callback();
        return;
      }
      else {
        this.testIndex++;
        this.blockIndex = 0;
        localStorage.setItem('testIndex',this.testIndex);
        localStorage.setItem('blockIndex',this.blockIndex);
      }
    }
    this.initializeBlock();
  }

  initializeBlock() {

    if(this.tests[this.testIndex].blocks[this.blockIndex].popup_text) {
      $("#popup-text").html(this.tests[this.testIndex].blocks[this.blockIndex].popup_text);
      this.popupTimeout = setTimeout(function(){
        $("#popup").modal();
      }, this.tests[this.testIndex].blocks[this.blockIndex].popup_display_time * 1000);
    }

    if(this.hasEndIndividualSection() && !this.isReporter) {
      //setTimeout(function() {
        //$("#waiting-for-reporter").modal();
      //}, 5000);
      //setTimeout(this.callback.bind(this), 10000);
    }

    if(this.tests[this.testIndex].blocks[this.blockIndex].type == 'review') {
      this.navTargetPosition = 0;
      localStorage.setItem('navTargetPosition',this.navTargetPosition);
      //$('.target-nav-back').hide();
      $('.target').hide();
      $('.target-' + this.navTargetPosition).show();

      // If there is a review time per target, advance the target after that time?
      if(this.tests[this.testIndex].blocks[this.blockIndex].review_time_each) {
        this.autoNavInterval = setInterval(this.autoNavTarget.bind(this), tests[this.testIndex].blocks[this.blockIndex].review_time_each * 1000);
      }

      // If there is a review time set, advance after that time
      if(this.tests[this.testIndex].blocks[this.blockIndex].review_time) {
        this.setTimer();
      }
    }

    if(this.tests[this.testIndex].blocks[this.blockIndex].type == 'mixed_review') {
      this.navImgTargetPosition = 0;
      this.navWordTargetPosition = 0;
      localStorage.setItem('navImgTargetPosition',this.navImgTargetPosition);
      localStorage.setItem('navWordTargetPosition',this.navWordTargetPosition);
      $('.mixed-mem-targets').hide();
      $('#'+this.groupTestReviewChoice+'_'+this.testIndex+'_'+this.blockIndex).show();

      $('.target').hide();
      $('.img-target-' + this.navImgTargetPosition).show();
      $('.word-target-' + this.navWordTargetPosition).show();

      // If there is a review time set, advance after that time
      if(this.tests[this.testIndex].blocks[this.blockIndex].review_time) {
        this.setTimer();
      }
    }
  }

  getTaskType() {
    return this.tests[this.testIndex].task_type;
  }

  hasPopup() {
    if(this.tests[this.testIndex].blocks[this.blockIndex].popup_text) {
      // If we've seen this popup before, don't show it again
      if(this.popupSeen.indexOf(this.popupTimeout) >= 0) return false;
      this.popupSeen.push(this.popupTimeout);
      clearTimeout(this.popupTimeout);
      $("#popup").modal();
      return true;
    }
    else return false;
  }

  hasWait() {
    if(this.tests[this.testIndex].blocks[this.blockIndex].wait_for_all &&
      this.tests[this.testIndex].blocks[this.blockIndex].wait_for_all == 'true') {
      return true;
    }
    else return false;
  }

  hasLeaderWait() {
    if(this.tests[this.testIndex].blocks[this.blockIndex].wait_for_leader &&
      this.tests[this.testIndex].blocks[this.blockIndex].wait_for_leader == 'true') {
      return true;
    }
    else return false;
  }

  hasEndIndividualSection() {
    if(this.tests[this.testIndex].blocks[this.blockIndex].end_individual_section &&
      this.tests[this.testIndex].blocks[this.blockIndex].end_individual_section == 'true') {
      console.log('end section');
      return true;
    }
    else {
      console.log('dont end section');
      return false;
    }
  }

  hasEndReporterOnlySection() {
    if(this.tests[this.testIndex].blocks[this.blockIndex].end_reporter_only &&
      this.tests[this.testIndex].blocks[this.blockIndex].end_reporter_only == 'true') {
      return true;
    }
    else return false;
  }

  setGroupTestReviewChoice(choice) {
    this.groupTestReviewChoice = choice;
    localStorage.setItem('groupTestReviewChoice',this.groupTestReviewChoice);
  }

  switchMemReviewType(type) {
    $('.mixed-mem-targets').hide();
    this.groupTestReviewChoice = type;
    localStorage.setItem('groupTestReviewChoice',this.groupTestReviewChoice);
    $('#'+this.groupTestReviewChoice+'_'+this.testIndex+'_'+this.blockIndex).show();
  }

  setTimer() {
    var timer = $("#timer_"+this.testIndex+"_"+this.blockIndex);
    if (localStorage.getItem('time')){
      if(parseInt(localStorage.getItem('time')) ){
        if(parseInt(localStorage.getItem('time')) > 0)
          tests[this.testIndex].blocks[this.blockIndex].review_time = localStorage.getItem('time');
      }

    }
    timer.html(tests[this.testIndex].blocks[this.blockIndex].review_time);
    itv = setInterval(function(){
      var time = parseInt(timer.html()) - 1;
      localStorage.setItem('time',time);
      timer.html(time);
    }, 1000);
    setTimeout(function() {
      $.event.trigger({
	       type: "timerComplete",
	         time: new Date()
      });
    }, tests[this.testIndex].blocks[this.blockIndex].review_time * 1000);
    setTimeout(this.advance.bind(this), tests[this.testIndex].blocks[this.blockIndex].review_time * 1000);
  }

  markMemoryChoice(userId, groupId, groupTasksId, token, modal) {
    $.post( "/mark-individual-ready", { user_id: userId, group_id: groupId, group_tasks_id: groupTasksId, step: this.step, _token: token } );
    this.waitForGroup(userId, groupId, groupTasksId, modal);
  }

  waitForGroup(userId, groupId, groupTasksId, modal) {
    console.log(this.step);
    self = this;
    $.get( "/check-group-ready", { user_id: userId, group_id: groupId, group_tasks_id: groupTasksId, step: this.step,} )
      .done(function( response ) {
        if(response == '1') {
          // Increment the step counter
          try{
            $(modal).modal('hide');
          }
          catch(e){
            location.reload();
          }
          
          self.step++;
          localStorage.setItem('step',self.step);
          $(modal).modal('hide');
          self.advance();
        }
        else {
          $(modal).modal('show');
          setTimeout(function(){
           $(modal).modal('show');
           console.log('waiting...');
           self.waitForGroup(userId, groupId, groupTasksId, modal);
         }, 1000);
        }
    });
  }

  waitForLeader(userId, groupId, groupTasksId, modal) {
    console.log(this.step);
    self = this;
    $.get( "/check-leader-ready", { user_id: userId, group_id: groupId, group_tasks_id: groupTasksId, step: this.step,} )
      .done(function( response ) {
        if(response == '1') {
          // Increment the step counter
          self.step++;
          $(modal).modal('hide');
          self.advance();
        }
        else {
          $(modal).modal('show');
          setTimeout(function(){
           $(modal).modal('show');
           console.log('waiting...');
           self.waitForGroup(userId, groupId, groupTasksId, modal);
         }, 1000);
        }
    });
  }

}
