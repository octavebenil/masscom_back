@if(!$survey->is_closed)
<div class="d-flex gap-2">

        @if($survey->is_active)
            <a href="javascript:void(0)"

               onclick="openSweetAlert('warning', '{{ route('admin.surveys.status', $survey->id) }}', 'Vous souhaitez désactiver cette question', 'banner-table')"
               class="btn btn-sm btn-warning mb-2" data-bs-title="Disable" data-bs-toggle="tooltip">
                <i class="fas fa-ban"></i>
            </a>
        @else


        <a href="javascript:void(0)"

           onclick="openSweetAlert('warning', '{{ route('admin.surveys.status', $survey->id) }}', 'You want to active this question', 'banner-table')"
           class="btn btn-sm btn-success mb-2" data-bs-title="Activate" data-bs-toggle="tooltip">
            <i class="fas fa-check"></i>
        </a>
    @endif
    @endif
    <div  class="d-flex gap-1">
        @if(!$survey->is_closed)
        <a href="{{ route('admin.surveys.edit', $survey->id) }}"
           class="btn btn-sm btn-primary mb-2" title="Edit" data-bs-toggle="tooltip">
            <i class="fas fa-pen"></i>
        </a>
        @endif

        <a href="{{ route('admin.surveys.view', $survey->id) }}"
           class="btn btn-sm btn-primary mb-2" title="View" data-bs-toggle="tooltip">
            <i class="fas fa-eye"></i>
        </a>

        <a onclick="openSweetAlert('warning', '{{ route('admin.surveys.delete', $survey->id) }}', 'You want to delete this question', 'question-table')"
           class="btn btn-sm btn-danger mb-2" title="Delete" data-bs-toggle="tooltip">
            <i class="fas fa-trash"></i>
        </a>
    </div>

</div>

