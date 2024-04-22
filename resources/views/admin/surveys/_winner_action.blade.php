<div class="d-flex gap-2">
    <div  class="d-flex gap-1">
        <a onclick="openSweetAlert('warning', '{{ route('admin.surveys.winners_delete', $winner->id) }}', 'Etes vous sûr de vouloir supprimer cette personne ?', 'winner-table')"
           class="btn btn-sm btn-danger mb-2" title="Delete" data-bs-toggle="tooltip">
            <i class="fas fa-trash"></i>
        </a>
    </div>
</div>

