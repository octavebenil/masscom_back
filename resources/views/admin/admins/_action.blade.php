
<div class="d-flex gap-2">
    <div  class="d-flex gap-1">
        <a href="{{ route('admin.admins.edit', $admin->id) }}"
           class="btn btn-sm btn-primary mb-2" title="Edit" data-bs-toggle="tooltip">
            <i class="fas fa-pen"></i>
        </a>

        <a onclick="openSweetAlert('warning', '{{ route('admin.admins.delete', $admin->id) }}', 'You want to delete this admin user', 'question-table')"
           class="btn btn-sm btn-danger mb-2" title="Delete" data-bs-toggle="tooltip">
            <i class="fas fa-trash"></i>
        </a>
    </div>

</div>

