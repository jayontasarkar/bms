@extends('layouts.backend.master')

@section('content')
    @component('layouts.backend.common.page-header') Dashboard @endcomponent
    <div class="row row-cards">
      <div class="col-sm-6 col-lg-3">
        <div class="card p-3">
          <div class="d-flex align-items-center">
            <span class="stamp stamp-md bg-blue mr-3">
              <i class="fe fe-dollar-sign"></i>
            </span>
            <div>
              <h4 class="m-0">
                <a href="{{ route('sales.index') }}">
                  {{ $pendingSales->count() }} <small>Sales</small>
                </a>
              </h4>
              <small class="text-muted">waiting for collection</small>
            </div>
          </div>
        </div>
      </div>
      <div class="col-sm-6 col-lg-3">
        <div class="card p-3">
          <div class="d-flex align-items-center">
            <span class="stamp stamp-md bg-green mr-3">
              <i class="fe fe-shopping-cart"></i>
            </span>
            <div>
              <h4 class="m-0"><a href="{{ route('purchases.index') }}">{{ $pendingPurchases->count() }} <small>Purchases</small></a></h4>
              <small class="text-muted">Waiting for payment</small>
            </div>
          </div>
        </div>
      </div>
      <div class="col-sm-6 col-lg-3">
        <div class="card p-3">
          <div class="d-flex align-items-center">
            <span class="stamp stamp-md bg-yellow mr-3">
              <i class="fe fe-message-square"></i>
            </span>
            <div>
              <h4 class="m-0"><a href="{{ route('stores.index') }}">{{ $products->count() }} <small>Product Category</small></a></h4>
              <small class="text-muted">waiting for sale</small>
            </div>
          </div>
        </div>
      </div>
      <div class="col-sm-6 col-lg-3">
        <div class="card p-3">
          <div class="d-flex align-items-center">
            <span class="stamp stamp-md bg-red mr-3">
              <i class="fa fa-bank"></i>
            </span>
            <div>
              @php
                $deposit = 0;
                $withdraw = 0;
                if(count($banks)) {
                  foreach($banks as $bank) {
                    $deposit += $bank->transactions->where('type', true)->sum('amount');
                    $withdraw += $bank->transactions->where('type', false)->sum('amount');
                  }
                }
              @endphp
              <h4 class="m-0">
                <a href="{{ route('bankings.index') }}">
                  {{ $banks->count() }} <small>{{ str_plural('Bank', $banks->count()) }}</small>
                </a>
              </h4>
              <small class="text-muted">
                {{ ($deposit - $withdraw) > 0 ? number_format($deposit - $withdraw) . '/=' : 'No' }} Available Balance
              </small>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="row row-cards row-deck">
      
      {{-- Latest Bank Statements --}}
      <div class="col-sm-8 col-lg-8">
        <div class="card">
          <div class="card-header">
            <h4 class="card-title">
              Bank Transactions ({{ $banks->count() }} {{ str_plural('bank', $banks->count()) }})
            </h4>
            <a href="{{ route('bankings.index') }}" class="ml-auto btn btn-link btn-xs">View All</a>
          </div>
          <div class="card-body pl-5 pr-5">
            @if(count($transactions))
              <table class="table card-table">
                <thead class="bg-gray-dark">
                  <tr>
                    <th>Type</th> 
                    <th>Bank Name</th> 
                    <th>Transaction Date</th> 
                    <th class="text-right">Amount</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($transactions as $transaction)
                    <tr>
                      <td>
                        @if($transaction->type)
                          <span class="badge badge-success">Deposit</span>
                        @else
                          <span class="badge badge-danger">Withdraw</span>
                        @endif
                      </td>
                      <td>{{ $transaction->transactionable->name }}</td>
                      <td>{{ $transaction->transaction_date->format('M d, Y') }}</td>
                      <td class="text-right"><span class="text-muted">{{ number_format($transaction->amount) }}/=</span></td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            @else
              <div class="alert alert-danger text-center">
                <strong>No bank transaction was found</strong>
              </div>
            @endif  
          </div>
        </div>
      </div>

      {{-- Quick Actions --}}
      <div class="col-sm-4 col-lg-4">
        <div class="card">
          <div class="card-header">
            <h2 class="card-title">Quick Actions/Links</h2>
          </div>
          <ul class="list-group">
              <li class="list-group-item">
                <purchase-product :vendors="{{ json_encode($vendors) }}" 
                          :products="{{ json_encode($products) }}"
                          :url="'{{ route('purchases.store') }}'"
                          :class-name="'d-block width--100'"
                          :btn-class="'btn-block'"
                ></purchase-product>
              </li>
              <li class="list-group-item">
                <sale-product :products="{{ json_encode($products) }}"
                        :districts="{{ json_encode($districts) }}"
                        :url="'{{ route('sales.store') }}'"
                        :class-name="'d-block width--100'"
                        :btn-class="'btn-block'"
                ></sale-product>
              </li>
              <li class="list-group-item">
                <manage-bank :url="'{{ route('bankings.store') }}'" :banks="{{ json_encode($banks) }}"></manage-bank>
              </li>
              <li class="list-inline-item pl-5 pr-5 pt-3 pb-3">
                <a href="{{ route('stores.index') }}" class="btn btn-block btn-success">Go to store</a>
              </li>
          </ul>
        </div>
      </div>

      {{-- Latest Pending Purchases --}}
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title" style="width: 100%;">
              Latest Purchases ({{ $pendingPurchases->count() }})
              <a href="{{ route('purchases.index') }}" class="btn btn-link btn-xs ml-auto float-md-right">View All</a>
            </h3>
          </div>
          <div class="card-body">
            @if(count($pendingPurchases))
              <div class="table-responsive">
                <table class="table card-table table-vcenter text-nowrap">
                  <thead class="bg-gray-dark">
                    <tr>
                      <th class="w-1">PO. No.</th>
                      <th>Vendor/Suppliers</th>
                      <th>Purchase Date</th>
                      <th>Total Amount</th>
                      <th>Total Paid</th>
                      <th>Discount</th>
                      <th>Due Amount</th>
                      <th></th>
                      <th></th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($purchases = $pendingPurchases->take(15) as $purchase)
                      <tr>
                        <td>
                          <a href="{{ route('purchases.show', [$purchase]) }}"> {{ $purchase->memo }}</a>
                        </td>
                        <td>
                          <a href="{{ route('vendors.show', [$purchase->vendor]) }}" class="text-inherit">
                            {{ $purchase->vendor->name }}
                          </a>
                        </td>
                        <td>
                          {{ $purchase->purchase_date->format('M d, Y') }}
                        </td>
                        <td>
                          {{ number_format($total = $purchase->total_balance) }}/=
                        </td>
                        <td>
                          {{ number_format($paid = $purchase->total_paid) }}/=
                        </td>
                        <td>
                          {{ number_format($discount = $purchase->total_discount) }}/=
                        </td>
                        <td>
                          {{ number_format($total - $paid - $discount) }}/=
                        </td>
                        <td>
                          <payment :purchase="{{ json_encode($purchase) }}"
                                   :url="'{{ route('purchases.transactions.store', [$purchase]) }}'" 
                          ></payment>
                        </td>
                        <td>
                          @if( !$purchase->type )
                            <vendor-memo 
                                :purchase="{{ json_encode($purchase) }}"
                                :records="{{ json_encode($purchase->records) }}"
                                :transactions="{{ json_encode($purchase->transactions) }}"
                                :title="'Show'"
                            ></vendor-memo>
                          @else
                            <span class="badge badge-danger"><strong>Opening Balance</strong></span>
                          @endif  
                        </td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            @else
              <div class="alert alert-warning text-center">
                <strong>No purchase was found with pending amount</strong>
              </div>
            @endif
          </div>
        </div>
      </div>

      {{-- Latest Pending sales --}}
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title" style="width: 100%;">
              Latest Sales ({{ $pendingSales->count() }})
              <a href="{{ route('sales.index') }}" class="btn btn-link btn-xs ml-auto float-md-right">View All</a>
            </h3>
          </div>
          <div class="card-body">
            @if(count($pendingSales))
              <div class="table-responsive">
                <table class="table card-table table-vcenter text-nowrap">
                  <thead class="bg-gray-dark">
                    <tr>
                      <th class="w-1">SO. No.</th>
                      <th>Outlet/Customers</th>
                      <th>Sales Date</th>
                      <th>Total Amount</th>
                      <th>Total Paid</th>
                      <th>Discount</th>
                      <th>Due Amount</th>
                      <th></th>
                      <th></th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($sales = $pendingSales->take(15) as $sale)
                      <tr>
                        <td>
                          <a href="{{ route('sales.show', [$sale]) }}">{{ $sale->memo }}</a>
                        </td>
                        <td>
                          <a href="{{ route('outlets.show', [$sale->outlet]) }}" class="text-inherit">
                            {{ $sale->outlet->name }}
                          </a>
                        </td>
                        <td>
                          {{ $sale->sales_date->format('M d, Y') }}
                        </td>
                        <td>
                          {{ number_format($total = $sale->total_balance) }}/=
                        </td>
                        <td>
                          {{ number_format($paid = $sale->total_paid) }}/=
                        </td>
                        <td>
                          {{ number_format($discount = $sale->total_discount) }}/=
                        </td>
                        <td>
                          {{ number_format($total - $paid - $discount) }}/=
                        </td>
                        <td>
                          <collection :sales="{{ json_encode($sale) }}"
                                 :url="'{{ route('sales.transactions.store', [$sale]) }}'" 
                          ></collection>
                        </td>
                        <td>
                          @if ( ! $sale->type )
                            <outlet-memo 
                                :sales="{{ json_encode($sale) }}"
                                :records="{{ json_encode($sale->records) }}"
                                :transactions="{{ json_encode($sale->transactions) }}"
                                :title="'Show'"
                            ></outlet-memo>
                          @else
                            <span class="badge badge-success"><strong>Opening Balance</strong></span>
                          @endif   
                        </td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            @else
              <div class="alert alert-warning text-center">
                <strong>No sale was found with pending amount</strong>
              </div>
            @endif
          </div>
        </div>
      </div>
    </div>
@stop            