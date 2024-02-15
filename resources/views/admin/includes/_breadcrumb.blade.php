<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            @if(isset($title))
                <h4 class="mb-sm-0 font-size-18">{{ $title }}</h4>
            @endif

            <div class="{{ isset($title) ? 'page-title-right' : '' }}">
                <ol class="breadcrumb m-0">
                    @forelse($items as $key => $value)
                        <li class="breadcrumb-item {{ empty($value) ? 'active' : '' }}">
                            @if($value)
                                <a href="{{ $value }}">{{ $key }}</a>
                            @else
                                {{ $key }}
                            @endif
                        </li>
                    @empty
                        <li class="breadcrumb-item active">Dashboard</li>
                    @endforelse
                </ol>
            </div>
        </div>
    </div>
</div>
