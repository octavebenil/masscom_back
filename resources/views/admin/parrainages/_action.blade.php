<div class="d-flex gap-2">
    <div class="d-flex gap-1">
        <a href="{{ route('admin.parrainages.edit', $user->id) }}"
           class="btn btn-sm btn-primary mb-2" title="Edit" data-bs-toggle="tooltip">
            <i class="fas fa-pen"></i>
        </a>

        <a onclick="openSweetAlert('warning', '{{ route('admin.parrainages.reset', $user->id) }}', 'Etes vous sûr de vouloir remettre à zero son nombre de filleuls?', 'parrainages-table')"
           class="btn btn-sm btn-warning mb-2" title="Reset" data-bs-toggle="tooltip">
            <i class="fas fa-check"></i>
        </a>

        <a onclick="openSweetAlert('warning', '{{ route('admin.parrainages.delete', $user->id) }}', 'Etes vous sûr de vouloir supprimer cet élément?', 'parrainages-table')"
           class="btn btn-sm btn-danger mb-2" title="Delete" data-bs-toggle="tooltip">
            <i class="fas fa-trash"></i>
        </a>
    </div>

</div>

