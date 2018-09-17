<button type="button" class="btn btn-success btn-block mb-3" data-toggle="modal" data-target="#overallBalanceReport">
  Overall Balance Report
</button>
<div class="modal fade" id="overallBalanceReport" tabindex="-1" role="dialog" aria-labelledby="overallBalanceReport" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">{{ $overall['title'] }}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-sm-3">
            <div class="card p-3">
              <div class="d-flex align-items-center">
                <span class="stamp stamp-md bg-blue mr-3">
                  <i class="fe fe-dollar-sign"></i>
                </span> 
                <div>
                  <h4 class="m-0">
                    <a href="#">
                      {{ number_format($overall['opening']) }}/=
                    </a>
                  </h4> 
                  <small class="text-muted">Opening Balance</small>
                </div>
              </div>
            </div>
          </div>
          <div class="col-sm-3">
            <div class="card p-3">
              <div class="d-flex align-items-center">
                <span class="stamp stamp-md bg-blue mr-3">
                  <i class="fe fe-dollar-sign"></i>
                </span> 
                <div>
                  <h4 class="m-0">
                    <a href="#">
                      {{ number_format($overall['toPay']) }}/=
                    </a>
                  </h4> 
                  <small class="text-muted">Total Amount</small>
                </div>
              </div>
            </div>
          </div>
          <div class="col-sm-3">
            <div class="card p-3">
              <div class="d-flex align-items-center">
                <span class="stamp stamp-md bg-blue mr-3">
                  <i class="fe fe-dollar-sign"></i>
                </span> 
                <div>
                  <h4 class="m-0">
                    <a href="#">
                      {{ number_format($overall['paid']) }}/=
                    </a>
                  </h4> 
                  <small class="text-muted">Paid Amount</small>
                </div>
              </div>
            </div>
          </div>
          <div class="col-sm-3">
            <div class="card p-3">
              <div class="d-flex align-items-center">
                <span class="stamp stamp-md bg-blue mr-3">
                  <i class="fe fe-dollar-sign"></i>
                </span> 
                <div>
                  <h4 class="m-0">
                    <a href="#">
                      {{ number_format($overall['opening'] + $overall['toPay'] - $overall['paid']) }}/=
                    </a>
                  </h4> 
                  <small class="text-muted">Due Amount</small>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>