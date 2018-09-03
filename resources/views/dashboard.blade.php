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
              <h4 class="m-0"><a href="javascript:void(0)">132 <small>Sales</small></a></h4>
              <small class="text-muted">12 waiting payments</small>
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
              <h4 class="m-0"><a href="javascript:void(0)">78 <small>Orders</small></a></h4>
              <small class="text-muted">32 shipped</small>
            </div>
          </div>
        </div>
      </div>
      <div class="col-sm-6 col-lg-3">
        <div class="card p-3">
          <div class="d-flex align-items-center">
            <span class="stamp stamp-md bg-red mr-3">
              <i class="fe fe-users"></i>
            </span>
            <div>
              <h4 class="m-0"><a href="javascript:void(0)">1,352 <small>Members</small></a></h4>
              <small class="text-muted">163 registered today</small>
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
              <h4 class="m-0"><a href="javascript:void(0)">132 <small>Comments</small></a></h4>
              <small class="text-muted">16 waiting</small>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="row row-cards row-deck">
      
      <div class="col-sm-6 col-lg-6">
        <div class="card">
          <div class="card-header">
            <h4 class="card-title">Browser Stats</h4>
          </div>
          <table class="table card-table">
            <tr>
              <td width="1"><i class="fa fa-chrome text-muted"></i></td>
              <td>Google Chrome</td>
              <td class="text-right"><span class="text-muted">23%</span></td>
            </tr>
            <tr>
              <td><i class="fa fa-firefox text-muted"></i></td>
              <td>Mozila Firefox</td>
              <td class="text-right"><span class="text-muted">15%</span></td>
            </tr>
            <tr>
              <td><i class="fa fa-safari text-muted"></i></td>
              <td>Apple Safari</td>
              <td class="text-right"><span class="text-muted">7%</span></td>
            </tr>
            <tr>
              <td><i class="fa fa-internet-explorer text-muted"></i></td>
              <td>Internet Explorer</td>
              <td class="text-right"><span class="text-muted">9%</span></td>
            </tr>
            <tr>
              <td><i class="fa fa-opera text-muted"></i></td>
              <td>Opera mini</td>
              <td class="text-right"><span class="text-muted">23%</span></td>
            </tr>
            <tr>
              <td><i class="fa fa-edge text-muted"></i></td>
              <td>Microsoft edge</td>
              <td class="text-right"><span class="text-muted">9%</span></td>
            </tr>
          </table>
        </div>
      </div>

      <div class="col-sm-6 col-lg-6">
        <div class="card">
          <div class="card-header">
            <h2 class="card-title">Projects</h2>
          </div>
          <table class="table card-table">
            <tr>
              <td>Admin Template</td>
              <td class="text-right">
                <span class="badge badge-default">65%</span>
              </td>
            </tr>
            <tr>
              <td>Landing Page</td>
              <td class="text-right">
                <span class="badge badge-success">Finished</span>
              </td>
            </tr>
            <tr>
              <td>Backend UI</td>
              <td class="text-right">
                <span class="badge badge-danger">Rejected</span>
              </td>
            </tr>
            <tr>
              <td>Personal Blog</td>
              <td class="text-right">
                <span class="badge badge-default">40%</span>
              </td>
            </tr>
            <tr>
              <td>E-mail Templates</td>
              <td class="text-right">
                <span class="badge badge-default">13%</span>
              </td>
            </tr>
            <tr>
              <td>Corporate Website</td>
              <td class="text-right">
                <span class="badge badge-warning">Pending</span>
              </td>
            </tr>
          </table>
        </div>
      </div>

      {{-- Latest Pending Purchases --}}
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title" style="width: 100%;">
              Latest Purchases ({{ $purchases->count() }})
              <a href="" class="btn btn-link btn-xs ml-auto float-md-right">View All</a>
            </h3>
          </div>
          <div class="card-body">
            @if(count($purchases))
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
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($purchases as $purchase)
                      <tr>
                        <td><span class="text-muted">{{ $purchase->memo }}</span></td>
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
                          <payment :purchase="{{ json_encode($purchase->load('vendor')) }}"
                                   :url="'{{ route('purchases.transactions.store', [$purchase]) }}'" 
                          ></payment>
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
              Latest Sales ({{ $sales->count() }})
              <a href="" class="btn btn-link btn-xs ml-auto float-md-right">View All</a>
            </h3>
          </div>
          <div class="card-body">
            @if(count($sales))
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
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($sales as $sale)
                      <tr>
                        <td><span class="text-muted">{{ $sale->memo }}</span></td>
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
                          <collection :sales="{{ json_encode($sale->load('outlet')) }}"
                                 :url="'{{ route('sales.transactions.store', [$sale]) }}'" 
                          ></collection>
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