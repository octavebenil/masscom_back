<div class="d-flex gap-2">
    <div class="d-flex gap-1">
        <a href="{{ route('admin.advertisement.edit', $advertisement->id) }}"
           class="btn btn-sm btn-primary mb-2" title="Edit" data-bs-toggle="tooltip">
            <i class="fas fa-pen"></i>
        </a>

        <a onclick="openSweetAlert('warning', '{{ route('admin.advertisement.delete', $advertisement->id) }}', 'You want to delete this advertisement', 'question-table')"
           class="btn btn-sm btn-danger mb-2" title="Delete" data-bs-toggle="tooltip">
            <i class="fas fa-trash"></i>
        </a>
    </div>
</div>

