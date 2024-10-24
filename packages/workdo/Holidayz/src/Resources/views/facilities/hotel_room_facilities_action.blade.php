@permission('facilities edit')
    <div class="action-btn bg-info ms-2">
        <a href="#" class="mx-3 btn btn-sm d-inline-flex align-items-center"
            data-url="{{ route('hotel-room-facilities.edit', $facility->id) }}"
            class="dropdown-item" data-ajax-popup="true" data-bs-toggle="tooltip"
            data-size="lg" data-bs-original-title="{{ __('Edit facility') }}" data-title="{{ __('Edit Facility') }}">
            <span class="text-white">
                <i class="ti ti-pencil"></i></span></a>
    </div>
@endpermission
@permission('facilities delete')
    <div class="action-btn bg-danger ms-2">
        {{ Form::open(['route' => ['hotel-room-facilities.destroy', $facility->id], 'id' => 'delete-form-' . $facility->id]) }}
        @method('DELETE')
        <a href="#"
            class="mx-3 btn btn-sm  align-items-center bs-pass-para show_confirm"
            data-bs-toggle="tooltip" title="" data-bs-original-title="Delete"
            aria-label="Delete"
            data-confirm-yes="delete-form-{{ $facility->id }}"><i
                class="ti ti-trash text-white text-white"></i></a>
        {{ Form::close() }}
    </div>
@endpermission
