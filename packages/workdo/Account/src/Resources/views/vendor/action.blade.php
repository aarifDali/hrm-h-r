@if($vendor->is_disable == 1)
<span>
    @if (!empty($vendor['vendor_id']))
        @permission('vendor show')
            <div class="action-btn bg-warning ms-2">
                <a href="{{ route('vendors.show', \Crypt::encrypt($vendor['id'])) }}"
                    class="mx-3 btn btn-sm align-items-center"
                    data-bs-toggle="tooltip" title="{{ __('View') }}">
                    <i class="ti ti-eye text-white text-white"></i>
                </a>
            </div>
        @endpermission
    @endif
    @permission('vendor edit')
        <div class="action-btn bg-info ms-2">
            <a  class="mx-3 btn btn-sm  align-items-center"
                data-url="{{ route('vendors.edit', $vendor['id']) }}"
                data-ajax-popup="true" data-size="lg" data-bs-toggle="tooltip"
                title="" data-title="{{ __('Edit Vendor') }}"
                data-bs-original-title="{{ __('Edit') }}">
                <i class="ti ti-pencil text-white"></i>
            </a>
        </div>
    @endpermission
    @if (!empty($vendor['vendor_id']))
        @permission('vendor delete')
            <div class="action-btn bg-danger ms-2">
                {{ Form::open(['route' => ['vendors.destroy', $vendor['id']], 'class' => 'm-0']) }}
                @method('DELETE')
                <a
                    class="mx-3 btn btn-sm  align-items-center bs-pass-para show_confirm"
                    data-bs-toggle="tooltip" title=""
                    data-bs-original-title="Delete" aria-label="Delete"
                    data-confirm="{{ __('Are You Sure?') }}"
                    data-text="{{ __('This action can not be undone. Do you want to continue?') }}"
                    data-confirm-yes="delete-form-{{ $vendor['id'] }}"><i
                        class="ti ti-trash text-white text-white"></i></a>
                {{ Form::close() }}
            </div>
        @endpermission
    @endif
</span>
@else
<div class="text-center">
    <i class="ti ti-lock"></i>
</div>
@endif
