@php

    $logo = get_file('uploads/hotel_logo');
    $defaultlogoPath = get_file('uploads/logo');
    if (auth()->guard('holiday')->user()){
        $hotel = Workdo\Holidayz\Entities\Hotels::where('workspace', auth()->guard('holiday')->user()->workspace)->get()->first();
    }else{
        $hotel = Workdo\Holidayz\Entities\Hotels::where('created_by', creatorId())->where('workspace', getActiveWorkSpace())->get()->first();
    }
@endphp
<div class="modal-body">
    <div class="row">
        <!-- [ Invoice ] start -->
        <div class="container">
            <div>
                <div class="card shadow-none bg-transparent border mb-3" id="printTable" style="border: 1px solid #dddbe2;border-radius: 10px;">
                    <div class="card-body">
                        <div class="row ">
                            <div class="col-md-8 invoice-contact pt-0">
                                <div class="invoice-box row">
                                    <div class="col-sm-12">
                                        <table class="table mt-0 table-responsive invoice-table table-borderless">
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        <a href="#">
                                                            @if(isset($hotel->invoice_logo) && !empty($hotel->invoice_logo))
                                                                <img src="{{$logo . '/' . $hotel->invoice_logo}}" class="invoice-logo" style="border:none;display:block;outline:none;text-decoration:none;width:40%" />
                                                            @else
                                                                <img src="{{ isset($hotel->logo) ?  $logo .'/'. $hotel->logo : $defaultlogoPath .'/'.'logo_dark.png' }}" class="invoice-logo" style="border:none;display:block;outline:none;text-decoration:none;width:40%" />
                                                            @endif
                                                        </a>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4"></div>
                        </div>
                        <div class="row invoive-info d-print-inline-flex">
                            <div class="col-sm-4 invoice-client-info">
                                <h6>{{ __('Booking To') }}:</h6>
                                <h6 class="m-0">
                                    @if ($booking->user_id == 0 || $booking->user_id == null)
                                        {{ $booking->first_name }}
                                    @else
                                        {{ $booking->getCustomerDetails->name }}
                                    @endif
                                </h6>
                                <p class="m-0">
                                    @if ($booking->user_id == 0 || $booking->user_id == null)
                                        {{ $booking->phone ? $booking->phone : '-' }}
                                    @else
                                        {{ $booking->getCustomerDetails->mobile_phone ? $booking->getCustomerDetails->mobile_phone : '-' }}
                                    @endif
                                </p>
                                <p><a class="text-secondary" href="#" target="_top"><span class="__cf_email__"
                                            data-cfemail="6a0e0f07052a0d070b030644090507">{{ $booking->user_id != 0 ? $booking->getCustomerDetails->email : $booking->email }}</span></a>
                                </p>
                            </div>
                            <div class="col-sm-4">
                                <h6 class="m-b-20">{{ __('Payment Details') }}:</h6>
                                <table class="table table-responsive mt-0 invoice-table invoice-order table-borderless">
                                    <tbody>
                                        <tr>
                                            <th>{{ __('Paid Via') }} :</th>
                                            <td>{{ $booking->payment_method }}</td>
                                        </tr>
                                        <tr>
                                            <th>{{ __('Status') }}:</th>
                                            <td>
                                                @if ($booking->payment_status == 1)
                                                    <span
                                                        class="badge fix_badge bg-primary p-2 px-3 rounded">{{ __('Paid') }}</span>
                                                @else
                                                    <span
                                                        class="badge fix_badge bg-danger p-2 px-3 rounded">{{ __('Unpaid') }}</span>
                                                @endif
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-sm-4">
                                <h6 class="m-b-20">{{ __('Booking No.') }}</h6>
                                <h6 class="text-uppercase text-primary">
                                    <td>{{ Workdo\Holidayz\Entities\RoomBooking::bookingNumberFormat($booking->booking_number,$hotel->created_by,$hotel->workspace) }}
                                    </td>
                                </h6>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="table-responsive mb-4">
                                    <table class="table invoice-detail-table">
                                        <thead>
                                            <tr class="thead-default">
                                                <th>{{ __('Room') }}</th>
                                                <th>{{ __('Check In') }}</th>
                                                <th>{{ __('Check Out') }}</th>
                                                <th>{{ __('Rent') }}</th>
                                                <th>{{ __('Total Room') }}</th>
                                                <th>{{ __('Service Charge') }}</th>
                                                <th>{{ __('Total') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($booking->GetBookingOrderDetails as $item)
                                                <tr>

                                                    <td>{{ $item->getRoomDetails->room_type }}</td>
                                                    <td>{{ company_date_formate($item->check_in,$hotel->created_by,$hotel->workspace) }}</td>
                                                    <td>{{ company_date_formate($item->check_out,$hotel->created_by,$hotel->workspace) }}</td>
                                                    <td>{{ currency_format_with_sym($item->getRoomDetails->final_price,$hotel->created_by,$hotel->workspace) }}</td>
                                                    <td>{{ $item->room }}</td>
                                                    <td>{{ currency_format_with_sym($item->service_charge ? $item->service_charge : 0,$hotel->created_by,$hotel->workspace) }}
                                                    </td>
                                                    <td>{{ currency_format_with_sym($item->price + $item->service_charge,$hotel->created_by,$hotel->workspace) }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="invoice-total">
                                    <table class="table invoice-table ">
                                        <tbody>
                                            @php
                                                $total = $booking->GetBookingOrderDetails->sum('price') + $booking->GetBookingOrderDetails->sum('service_charge');
                                            @endphp
                                            <tr>
                                                <th>{{ __('Sub Total') }}:</th>
                                                <td>{{ currency_format_with_sym($total,$hotel->created_by,$hotel->workspace) }}</td>
                                            </tr>
                                            <tr>
                                                @if ($booking->coupon_id == 0)
                                                    @php
                                                        $discoundPrice = 0;
                                                    @endphp
                                                    <th>{{ __('Discount (0%)') }}:</th>
                                                    <td>{{ currency_format_with_sym(0,$hotel->created_by,$hotel->workspace) }}</td>
                                                @else
                                                    <th>{{ __('Discount (' . $booking->getCouponDetails->discount . '%) ') }}:
                                                    </th>
                                                    @php
                                                        $discoundPrice = ($total * $booking->getCouponDetails->discount) / 100;
                                                    @endphp
                                                    <td>{{ currency_format_with_sym($discoundPrice,$hotel->created_by,$hotel->workspace) }}</td>
                                                @endif
                                            </tr>
                                            <tr>
                                                <td>
                                                    <hr />
                                                    <h5 class="text-primary m-r-10">{{ __('Total') }} :</h5>
                                                </td>
                                                <td>
                                                    <hr />
                                                    <h5 class="text-primary">
                                                        {{ currency_format_with_sym($total - $discoundPrice,$hotel->created_by,$hotel->workspace) }}</h5>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- [ Invoice ] end -->
    </div>
</div>
