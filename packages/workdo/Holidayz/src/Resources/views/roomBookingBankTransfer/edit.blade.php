{{ Form::model($bank_transfer_payment, ['route' => ['room-booking-bank-transfer.update', $bank_transfer_payment->id], 'method' => 'PUT']) }}
<div class="modal-body">
    <div class="table-responsive">
        <table class="table table-bordered ">
            <tr>
                <th>{{__('Booking Number')}}</th>
                <td>{{$invoice_id}}</td>
            </tr>
            <tr role="row">
                <th>{{ __('Transaction ID') }}</th>
                <td>{{ $bank_transfer_payment->order_id }}</td>
            </tr>
            <tr>
                @php
                    $hotelCustomer = \Workdo\Holidayz\Entities\HotelCustomer::where('id',$bank_transfer_payment->user_id)->pluck('name')->first();
                @endphp
                <th>{{ __('Name') }}</th>
                <td>{{ !empty($hotelCustomer)?$hotelCustomer:'Guest' }}</td>
            </tr>
            <tr>
                <th>{{__('status')}}</th>
                <td>
                    @if($bank_transfer_payment->status == 'Approved')
                        <span class="bg-success p-1 px-3 rounded text-white">{{ucfirst($bank_transfer_payment->status)}}</span>
                    @elseif($bank_transfer_payment->status == 'Pending')
                        <span class="bg-warning p-1 px-3 rounded text-white">{{ucfirst($bank_transfer_payment->status)}}</span>
                    @else
                        <span class="bg-danger p-1 px-3 rounded text-white">{{ucfirst($bank_transfer_payment->status)}}</span>
                    @endif
                </td>
            </tr>
            <tr>
                <th>{{__('Price')}}</th>
                <td>{{$bank_transfer_payment->price.' '.$bank_transfer_payment->price_currency}}</td>
            </tr>
            <tr>
                <th>{{__('Payment Type')}}</th>
                <td>{{('Bank transfer')}}</td>
            </tr>
            <tr>
                <th>{{ __('Payment Date') }}</th>
                <td>{{ company_datetime_formate($bank_transfer_payment->created_at)}}</td>
            </tr>
            <tr>
                <th>{{__('Attachment')}}</th>
                <td>
                    @if (!empty($bank_transfer_payment->attachment) && (check_file($bank_transfer_payment->attachment)))
                        <div class="action-btn bg-primary ms-2">
                            <a class="mx-3 btn btn-sm align-items-center" href="{{ get_file($bank_transfer_payment->attachment) }}" download>
                                <i class="ti ti-download text-white"></i>
                            </a>
                        </div>
                        <div class="action-btn bg-secondary ms-2">
                            <a class="mx-3 btn btn-sm align-items-center" href="{{ get_file($bank_transfer_payment->attachment) }}" target="_blank"  >
                                <i class="ti ti-crosshair text-white" data-bs-toggle="tooltip" data-bs-original-title="{{ __('Preview') }}"></i>
                            </a>
                        </div>
                    @else
                        {{ __('Not Found')}}
                    @endif
                </td>
            </tr>
        </table>
    </div>
</div>
@if ($bank_transfer_payment->status == 'Pending')
    <div class="modal-footer">
        <input type="submit" value="{{ __('Approved') }}" class="btn btn-success rounded" name="status">
        <input type="submit" value="{{ __('Reject') }}" class="btn btn-danger rounded" name="status">
    </div>
@endif
{{ Form::close() }}
