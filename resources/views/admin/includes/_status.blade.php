@if($model->status === \App\Enums\Status::PENDING())
    <span class="badge badge-soft-warning font-size-11">{{ 'Pending' }}</span>
@elseif($model->status === \App\Enums\Status::ACTIVE())
    <span class="badge badge-soft-success font-size-11">{{ 'Active' }}</span>
@elseif($model->status ===\App\Enums\Status::DISABLED())
    <span class="badge badge-soft-danger font-size-11">{{ 'Disabled' }}</span>
@elseif($model->status ===\App\Enums\Status::DELETED())
    <span class="badge bg-danger font-size-11">{{ 'Deleted' }}</span>
@else
    <span class="badge badge-soft-info font-size-11">{{ ucfirst($model->status) }}</span>
@endif
