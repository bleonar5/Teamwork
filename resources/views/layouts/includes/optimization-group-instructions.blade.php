<div class="modal fade" id="review-instructions">
  <div class="modal-dialog modal-dialog-wide">
    <div class="modal-content">
      <div class="modal-body text-center">
        <h5>
          Your group’s goal is to find a number (between 0 and 300) that
          results in your computer returning the biggest possible value. You
          each have {{ floor($maxResponses / $groupSize) }} guesses, which you enter into your own laptop. A guess
          can be any number between 0 and 300.
        </h5>
        <h5>
          After you enter a guess, the computer will give you back a number.
          There is a relationship between the number you guess and the number
          the computer gives you. In the practice round, we tell you the
          relationship (you won’t know this in the actual task):
        </h5>
        <img src="/img/optimization-task/function-example.png" style="width:400px; height: auto;">
        <h5>
          After each person has had five guesses, the computer will ask the
          Reporter to enter the Group’s Best Guess. In this example, the best
          guess is 244. The worst guess is 140.
        </h5>
      </div>
      <div class="modal-body text-center">
        <button class="btn btn-lg btn-primary pull-right" data-dismiss="modal" type="button">Ok</button>
      </div>
    </div><!-- modal-content -->
  </div><!-- modal-dialog -->
</div><!-- modal -->
