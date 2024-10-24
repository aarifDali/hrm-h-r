@extends('layouts.main')
@section('page-title')
    {{ __('Notification Templates') }}
@endsection
@section('page-breadcrumb')
    {{ __('Notification Templates') }}
@endsection
@section('page-action')
@endsection

@php
$activeModule = '';
foreach ($notifications as $key => $value) {
    $txt = module_is_active($key);
    if ($txt == true) {
        $activeModule = $key;
        break;
    }
}

@endphp

@push('css')
    @include('layouts.includes.datatable-css')
@endpush
@section('content')
<div class="row">
    <div class="col-sm-12">
        <div class="col-md-12 d-flex align-items-center justify-content-between justify-content-md-end mb-4">
            <div class="col-md-6">
                <ul class="nav nav-pills nav-fill cust-nav information-tab" id="pills-tab" role="tablist">
                    @foreach ($notifications as $key => $value)
                        @if (module_is_active($key) && $key == 'Slack')
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" data-id="Slack" data-bs-toggle="pill"  data-bs-target="#slack-tab"
                                    type="button">{{ __('Slack') }}</button>
                            </li>
                        @endif
                        @if (module_is_active($key) && $key == 'Telegram')
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" data-id="Telegram" data-bs-toggle="pill"
                                    data-bs-target="#telegram-tab" type="button">{{ __('Telegram') }}</button>
                            </li>
                        @endif
                        @if (module_is_active($key) && $key == 'Twilio')
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" data-id="Twilio" data-bs-toggle="pill"
                                    data-bs-target="#twilio-tab" type="button">{{ __('Twilio') }}</button>
                            </li>
                        @endif
                        @if (module_is_active($key) && $key == 'Whatsapp')
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" data-id="Whatsapp" data-bs-toggle="pill" data-bs-target="#whatsapp-tab"
                                    type="button">{{ __('Whatsapp') }}</button>
                            </li>
                        @endif
                        @if (module_is_active($key) && $key == 'WhatsAppAPI')
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" data-id="WhatsAppAPI" data-bs-toggle="pill" data-bs-target="#whatsappapi-tab"
                                    type="button">{{ __('Whatsapp Api') }}</button>
                            </li>
                        @endif
                        @if (module_is_active($key) && $key == 'SMS')
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" data-id="SMS" data-bs-toggle="pill" data-bs-target="#sms-tab"
                                    type="button">{{ __('SMS') }}</button>
                            </li>
                        @endif
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body table-border-style">
                @if($activeModule == '')
                    <div class="text-center">
                        <h5 class="text-danger">{{ __('Make sure to activate at least one notification add-on. A notification template will be visible after that.') }}</h5>
                    </div>
                @else
                    <div  class="table-responsive">
                        {{ $dataTable->table(['width' => '100%']) }}
                    </div>
                @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
    $(document).ready(function() {
        $('.nav-link').first().addClass('active');
    });
</script>
@include('layouts.includes.datatable-js')
{{ $dataTable->scripts() }}
@endpush