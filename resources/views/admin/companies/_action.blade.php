<div class="d-flex gap-2">
    <div class="d-flex gap-1">
        <a href="{{ route('admin.companies.edit', $company->id) }}"
           class="btn btn-sm btn-primary mb-2" title="Edit" data-bs-toggle="tooltip">
            <i class="fas fa-pen"></i>
        </a>
        <a href="{{ route('public.company.statistics', $company->id) }}"
           class="btn btn-sm btn-primary mb-2" title="Edit" data-bs-toggle="statistics">
            <i class="fas fa-arrow-trend-up"></i>
        </a>
        <a onclick="openSweetAlert('warning', '{{ route('admin.companies.delete', $company->id) }}', 'You want to delete this company', 'question-table')"
           class="btn btn-sm btn-danger mb-2" title="Delete" data-bs-toggle="tooltip">
            <i class="fas fa-trash"></i>
        </a>
    </div>

</div>

