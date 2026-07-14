@extends('admin.admin_master')
@section('admin')

<div class="content">
    <div class="container-xxl">
        <div class="row justify-content-center mt-5">
            <div class="col-md-6">
                <div class="card text-center">
                    <div class="card-body py-5">
                        <div class="mb-3">
                            <i class="bi bi-x-circle-fill text-danger" style="font-size: 60px;"></i>
                        </div>
                        <h4 class="fw-semibold">Hindi Natuloy ang Bayad</h4>
                        <p class="text-muted">{{ $message ?? 'May problema sa pagproseso ng bayad mo. Subukan ulit.' }}</p>
                        <a href="{{ route('due.sale') }}" class="btn btn-sm text-white mt-2" style="background-color:#dc3545;">
                            Bumalik sa Due Sales
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
