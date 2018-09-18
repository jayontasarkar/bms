<button type="button" class="btn btn-primary btn-block mb-3" data-toggle="modal" data-target="#openingBalanceReport">
  Opening Balance Report
</button>
<div class="modal fade" id="openingBalanceReport" tabindex="-1" role="dialog" aria-labelledby="openingBalanceReport" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Opening Balance Report</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-12">
            <div class="table-responsive">
              <table class="table card-table table-bordered table-vcenter text-nowrap">
                <thead>
                  <tr class="bg-gray-dark">
                    <th>Vendor Name</th>
                    <th>Closing Date</th>
                    <th>Opening Balance</th>
                    <th>&nbsp;</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($overall['openingResults'] as $trans)
                    <tr>
                      <td>{{ $trans->vendor->name }}</td>
                      <td>{{ $trans->transaction_date->format('M d, Y') }}</td>
                      <td>{{ number_format($trans->amount) }}/=</td>
                      <td>
                        <remove-btn :url="'{{ route('transactions.destroy', [$trans]) }}'"
                                    :redirect-path="'{{ route('outlets.show', [$outlet]) }}'"
                        ></remove-btn>
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
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