{{ Form::open(['route' => 'hotel-room-booking.store', 'method' => 'post', 'enctype' => 'multipart/form-data','class'=>'needs-validation','novalidate']) }}
<div class="modal-body">
    <div class="row">

        <div class="form-group col-md-12">
            {!! Form::label('apartment_type_id', 'Apartment Type', ['class' => 'form-label']) !!}<x-required></x-required>
            {!! Form::select('apartment_type_id', $apartmentTypes->pluck('name', 'id'), null, [
                'class' => 'form-control',
                'placeholder' => 'Select Type',
                'required' => true,
                'id' => 'apartment_type_id'
            ]) !!}
        </div>
        
        <div class="form-group col-md-12">
            {!! Form::label('', __('Room'), ['class' => 'form-label']) !!}<x-required></x-required>
            <select class="form-control" name="room_id" id="room_id" required>
                <option value="" data-rent-value="0">{{ __('Select Room') }}</option>
                @foreach ($rooms as $room)
                    <option value="{{ $room->id }}" data-apartment-type-id="{{ $room->apartment_type_id }}" data-rent-value="{{ $room->final_price }}">
                        {{ $room->room_type }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group col-md-6">
            {!! Form::label('', __('Check In'), ['class' => 'form-label']) !!}<x-required></x-required>
            {!! Form::date('check_in', null, ['class' => 'form-control check_in check_date', 'data-url'=>route('add.dayprice'), 'required' => true, 'min' => date('Y-m-d')]) !!}
        </div>

        <div class="form-group col-md-6">
            {!! Form::label('', __('Check Out'), ['class' => 'form-label']) !!}<x-required></x-required>
            {!! Form::date('check_out', null, ['class' => 'form-control check_out check_date', 'data-url'=>route('add.dayprice'), 'required' => true, 'min' => date('Y-m-d')]) !!}
        </div>

        <div class="form-group col-md-6">
            {!! Form::label('', __('Total Room'), ['class' => 'form-label']) !!}<x-required></x-required>
            {!! Form::number('room', null, [
                'class' => 'form-control room', 'data-url'=>route('add.dayprice'),
                'placeholder' => 'Enter Total Room',
                'required' => true,
            ]) !!}
        </div>

        <div class="form-group col-md-6">
            {!! Form::label('', __('Adults'), ['class' => 'form-label']) !!}<x-required></x-required>
            {!! Form::number('adults', null, [
                'class' => 'form-control',
                'placeholder' => 'Enter Adults',
                'required' => true,
            ]) !!}
        </div>

        <div class="form-group col-md-6">
            {!! Form::label('', __('Children'), ['class' => 'form-label']) !!}<x-required></x-required>
            {!! Form::number('children', null, [
                'class' => 'form-control',
                'placeholder' => 'Enter Children',
                'required' => true,
            ]) !!}
        </div>

        <div class="form-group col-md-6">
            {!! Form::label('', __('Total Rent'), ['class' => 'form-label']) !!}
            {!! Form::number('total_rent', null, ['class' => 'form-control total', 'required' => true, 'readonly' => true]) !!}
        </div>

        <div class="form-group col-md-6">
            {!! Form::label('discount_amount', __('Discount Amount'), ['class' => 'form-label']) !!}
            <div class="input-group">
                {!! Form::number('discount_amount', null, ['class' => 'form-control', 'id' => 'discount_amount']) !!}
                <button type="button" class="btn btn-primary" id="applyDiscountButton">Apply</button>
            </div>
            <span id="discountErrorMessage" class="text-danger" style="display: none;"></span>
        </div>
        
        <div class="form-group col-md-6">
            {!! Form::label('', __('Amount to Pay'), ['class' => 'form-label']) !!}
            {!! Form::number('amount_to_pay', null, ['class' => 'form-control amount-to-pay', 'required' => true, 'readonly' => true]) !!}
            <small id="discountMessage" class="text-success" style="display:none;"></small>
        </div>

        @php
            $paymentStatus = ['0' => 'UnPaid', '1' => 'Paid'];
        @endphp

        <style>
            .pull-right{
                float: right !important;
            }
        </style>
        
        <div class="form-group col-12 switch-width">
            {{ Form::label('user_id', __('Customer'), ['class' => 'form-label']) }}<x-required></x-required>
            <label class="form-label pull-right"><a href="javascript:void(0);" onclick="ShowPopup();">New Customer</a></label>                            
            <select name="user_id" class="form-control" id="user_id" required>
                <option value="">{{ __('Select Customer') }}</option>
                @foreach ($customers as $customer)
                    <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                @endforeach
            </select>
        </div> 
                
        <script>
            function ShowPopup(){
            $("#commonModalOver").modal('show');
            var title ='Customer Booking';
            var size ='xl'
            var url ='{{ route('hotel-customer.create') }}';


            $("#commonModalOver .modal-title").html(title);
            $("#commonModalOver .modal-dialog").addClass('modal-' + size);

            $.ajax({
                url: url ,
                beforeSend: function () {
                    $(".loader-wrapper").removeClass('d-none');
                },
                success: function (data) {
                    $(".loader-wrapper").addClass('d-none');
                    $('#commonModalOver .body').html(data);
                    $("#commonModalOver").modal('show');
                    summernote();
                    taskCheckbox();
                },
                error: function (xhr) {
                    $(".loader-wrapper").addClass('d-none');
                    toastrs('Error', xhr.responseJSON.error, 'error')
                }
            });
        }
        </script>


        @php
            $payment = \Workdo\Holidayz\Entities\Utility::ActivePaymentGateway();
        @endphp

        <div class="form-group col-md-6">
            {!! Form::label('', __('Payment Method'), ['class' => 'form-label']) !!}<x-required></x-required>
            {!! Form::select('payment_method', $payment, null, ['class' => 'form-control']) !!}
        </div>

        <div class="form-group col-md-6">
            {!! Form::label('', __('Payment Status'), ['class' => 'form-label']) !!}<x-required></x-required>
            {!! Form::select('payment_status', $paymentStatus, null, ['class' => 'form-control']) !!}
        </div>
        <div class="modal-footer pb-0">
            <input type="button" value="Cancel" class="btn btn-light" data-bs-dismiss="modal">
            <input type="submit" value="Create" class="btn btn-primary bg-primary">
        </div>
    </div>
</div>
{!! Form::close() !!}

{{-- <script>
    $(document).ready(function() {
        // Store the original options in an array
        const originalRoomOptions = [];

        $('#room_id option').each(function() {
            originalRoomOptions.push($(this).clone()); // Clone the option element
        });

        $('#apartment_type_id').change(function() {
            const selectedApartmentTypeId = $(this).val();
            $('#room_id').empty().append('<option value="" data-rent-value="0">{{ __('Select Room') }}</option>');

            // Loop through original room options
            originalRoomOptions.forEach(function(option) {
                // Check if the apartment type matches
                if (option.data('apartment-type-id') == selectedApartmentTypeId) {
                    $('#room_id').append(option);
                }
            });
        });
    });
    $(document).ready(function () {

        // When room is selected, update total rent and amount to pay
        $('#room_id').change(function () {
            const selectedOption = this.options[this.selectedIndex];
            const totalRent = parseFloat(selectedOption.getAttribute('data-rent-value'));

            $('.total').val(totalRent.toFixed(2));
            updateAmountToPay(totalRent);  // Update amount to pay
        });

        // When discount is toggled, update amount to pay accordingly
        $('#discountCheckbox').change(function () {
            const totalRent = parseFloat($('.total').val()) || 0;
            updateAmountToPay(totalRent);  // Recalculate amount to pay
        });

        // Function to update amount to pay based on total rent and discount
        function updateAmountToPay(totalRent) {
            let amountToPay = totalRent;
            let discountMessage = $('#discountMessage');

            if ($('#discountCheckbox').is(':checked')) {
                const discount = totalRent * 0.05;
                amountToPay = totalRent - discount; 
                discountMessage.text(`$${discount.toFixed(2)} saved`).show();  // Show discount message
            } else {
                discountMessage.hide();  // Hide discount message if discount is not applied
            }

            $('.amount-to-pay').val(amountToPay.toFixed(2));  // Update amount to pay field
        }

        // When check-in/check-out dates or room number changes, recalculate the rent
        $('.check_date, .room').change(function() {
            const date1 = $('.check_in').val();
            const date2 = $('.check_out').val();
            const totalRooms = $('.room').val();
            const roomId = $('#room_id').val();

            if (date1 && date2 && totalRooms && roomId) {
                const data = {
                    room: roomId,
                    total_room: totalRooms,
                    date1: date1,
                    date2: date2
                };

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    type: "get",
                    url: $('.check_date').attr('data-url'),
                    data: data,
                    cache: false,
                    beforeSend: function () {
                        $('.booking-btn').attr('disabled', 'disabled');
                    },
                    success: function (data) {
                        $('.total').val(data.total_price);  // Update total rent with AJAX response
                        updateAmountToPay(data.total_price);  // Ensure amount to pay is updated
                    },
                    complete: function () {
                        $('.booking-btn').removeAttr('disabled');
                    },
                });
            }
        });

        // Initialize select2 if applicable
        select2();

        function select2() {
            if ($(".select2").length > 0) {
                $($(".select2")).each(function (index, element) {
                    var id = $(element).attr('id');
                    new Choices('#' + id, {
                        removeItemButton: true,
                    });
                });
            }
        }
    });
</script> --}}

{{-- <script>
    $(document).ready(function() {
        $('#apartment_type_id').change(function() {
            const selectedApartmentTypeId = $(this).val();
            $('#room_id').empty().append('<option value="" data-rent-value="0">{{ __('Select Room') }}</option>'); // Clear existing rooms

            if (selectedApartmentTypeId) {
                $.ajax({
                    url: '{{ route("fetch.rooms") }}',
                    type: 'GET',
                    data: { apartment_type_id: selectedApartmentTypeId },
                    success: function(response) {
                        if (response.rooms && response.rooms.length > 0) {
                            response.rooms.forEach(function(room) {
                                $('#room_id').append(`
                                    <option value="${room.id}" data-apartment-type-id="${room.apartment_type_id}" data-rent-value="${room.final_price}">
                                        ${room.room_type}
                                    </option>
                                `);
                            });
                        }
                    },
                    error: function() {
                        console.log('Error fetching rooms.');
                    }
                });
            }
        });

        $('#room_id').change(function () {
            const selectedOption = this.options[this.selectedIndex];
            const totalRent = parseFloat(selectedOption.getAttribute('data-rent-value'));

            $('.total').val(totalRent.toFixed(2));
            updateAmountToPay(totalRent);  
        });

        $('#discountCheckbox').change(function () {
            const totalRent = parseFloat($('.total').val()) || 0;
            updateAmountToPay(totalRent);  
        });

        function updateAmountToPay(totalRent) {
            let amountToPay = totalRent;
            let discountMessage = $('#discountMessage');

            if ($('#discountCheckbox').is(':checked')) {
                const discount = totalRent * 0.05;
                amountToPay = totalRent - discount; 
                discountMessage.text(`$${discount.toFixed(2)} saved`).show();  
            } else {
                discountMessage.hide();  
            }

            $('.amount-to-pay').val(amountToPay.toFixed(2)); 
        }

        $('.check_date, .room').change(function() {
            const date1 = $('.check_in').val();
            const date2 = $('.check_out').val();
            const totalRooms = $('.room').val();
            const roomId = $('#room_id').val();

            if (date1 && date2 && totalRooms && roomId) {
                const data = {
                    room: roomId,
                    total_room: totalRooms,
                    date1: date1,
                    date2: date2
                };

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    type: "get",
                    url: $('.check_date').attr('data-url'),
                    data: data,
                    cache: false,
                    beforeSend: function () {
                        $('.booking-btn').attr('disabled', 'disabled');
                    },
                    success: function (data) {
                        $('.total').val(data.total_price);  
                        updateAmountToPay(data.total_price);  
                    },
                    complete: function () {
                        $('.booking-btn').removeAttr('disabled');
                    },
                });
            }
        });

        select2();

        function select2() {
            if ($(".select2").length > 0) {
                $($(".select2")).each(function (index, element) {
                    var id = $(element).attr('id');
                    new Choices('#' + id, {
                        removeItemButton: true,
                    });
                });
            }
        }
    });
</script> --}}
<script>
    $(document).ready(function() {
    $('#apartment_type_id').change(function() {
        const selectedApartmentTypeId = $(this).val();
        $('#room_id').empty().append('<option value="" data-rent-value="0">{{ __('Select Room') }}</option>'); // Clear existing rooms

        if (selectedApartmentTypeId) {
            $.ajax({
                url: '{{ route("fetch.rooms") }}',
                type: 'GET',
                data: { apartment_type_id: selectedApartmentTypeId },
                success: function(response) {
                    if (response.rooms && response.rooms.length > 0) {
                        response.rooms.forEach(function(room) {
                            $('#room_id').append(`
                                <option value="${room.id}" data-apartment-type-id="${room.apartment_type_id}" data-rent-value="${room.final_price}">
                                    ${room.room_type}
                                </option>
                            `);
                        });
                    }
                },
                error: function() {
                    console.log('Error fetching rooms.');
                }
            });
        }
    });

    $('#room_id').change(function () {
        const selectedOption = this.options[this.selectedIndex];
        const totalRent = parseFloat(selectedOption.getAttribute('data-rent-value'));

        $('.total').val(totalRent.toFixed(2));
        updateAmountToPay(totalRent);  
    });

    $('#applyDiscountButton').click(function () {
        const totalRent = parseFloat($('.total').val()) || 0;
        const discountAmount = parseFloat($('#discount_amount').val()) || 0;
        const errorMessage = $('#discountErrorMessage');

        errorMessage.hide();

        if (discountAmount < 0) {
            errorMessage.text('Discount cannot be less than 0').show();
            $('#discount_amount').val(0);
        } else if (discountAmount > totalRent) {
            errorMessage.text('Discount cannot be greater than Total Rent').show();
            $('#discount_amount').val(totalRent.toFixed(2));
        } else {
            updateAmountToPay(totalRent);
        }

        updateAmountToPay(totalRent);  
    });

    function updateAmountToPay(totalRent) {
        let discountAmount = parseFloat($('#discount_amount').val()) || 0;
        let amountToPay = totalRent - discountAmount;

        if (amountToPay < 0) {
            amountToPay = 0;
        }

        $('.amount-to-pay').val(amountToPay.toFixed(2)); 
    }

    $('.check_date, .room').change(function() {
        const date1 = $('.check_in').val();
        const date2 = $('.check_out').val();
        const totalRooms = $('.room').val();
        const roomId = $('#room_id').val();

        if (date1 && date2 && totalRooms && roomId) {
            const data = {
                room: roomId,
                total_room: totalRooms,
                date1: date1,
                date2: date2
            };

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: "get",
                url: $('.check_date').attr('data-url'),
                data: data,
                cache: false,
                beforeSend: function () {
                    $('.booking-btn').attr('disabled', 'disabled');
                },
                success: function (data) {
                    $('.total').val(data.total_price);  
                    updateAmountToPay(data.total_price);  
                },
                complete: function () {
                    $('.booking-btn').removeAttr('disabled');
                },
            });
        }
    });

    select2();

    function select2() {
        if ($(".select2").length > 0) {
            $($(".select2")).each(function (index, element) {
                var id = $(element).attr('id');
                new Choices('#' + id, {
                    removeItemButton: true,
                });
            });
        }
    }
});

</script>
