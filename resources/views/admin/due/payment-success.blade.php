@extends('admin.admin_master')
@section('admin')

<style>
    .payment-success-card{
        border: none;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 15px 40px rgba(0,0,0,.12);
        animation: fadeUp .6s ease;
    }

    .success-header{
        background: linear-gradient(135deg,#8256D2,#6f42c1);
        padding: 45px 20px;
    }

    .success-icon{
        width: 100px;
        height: 100px;
        background: #fff;
        border-radius: 50%;
        display: inline-flex;
        justify-content: center;
        align-items: center;
        box-shadow: 0 10px 30px rgba(0,0,0,.18);
    }

    .success-icon i{
        font-size: 60px;
        color: #28a745;
    }

    .status-box{
        background: #f8f9fc;
        border: 1px solid #ebeef5;
        border-radius: 12px;
        padding: 15px;
    }

    .btn-purple{
        background: #8256D2;
        color: #fff;
        border-radius: 50px;
        padding: 12px 30px;
        font-weight: 600;
        transition: .3s;
    }

    .btn-purple:hover{
        background: #6f42c1;
        color: #fff;
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(130,86,210,.35);
    }

    @keyframes fadeUp{
        from{
            opacity:0;
            transform:translateY(30px);
        }
        to{
            opacity:1;
            transform:translateY(0);
        }
    }
</style>

<div class="content">
    <div class="container-xxl">

        <div class="row justify-content-center align-items-center" style="min-height:75vh;">
            <div class="col-lg-5 col-md-7">

                <div class="card payment-success-card">

                    <div class="success-header text-center">
                        <div class="success-icon">
                            <i class="bi bi-check-circle-fill"></i>
                        </div>
                    </div>

                    <div class="card-body text-center p-5">

                        <h2 class="fw-bold mb-3">
                            Payment Submitted Successfully!
                        </h2>

                        <p class="text-muted fs-6 mb-4">
                            {{ $message ?? 'Natanggap na namin ang iyong payment. Kasalukuyan itong bine-verify ng aming system. Mangyaring maghintay lamang ng ilang sandali habang kinukumpirma ang iyong transaksyon.' }}
                        </p>

                        <div class="status-box mb-4">
                            <i class="bi bi-hourglass-split text-warning me-2"></i>
                            <span class="text-muted">
                                Kapag nakumpirma na ang iyong bayad, awtomatikong maa-update ang status ng iyong transaction.
                            </span>
                        </div>

                        <a href="{{ route('due.sale') }}" class="btn btn-purple">
                            <i class="bi bi-arrow-left-circle me-2"></i>
                            Return to Due Sales
                        </a>

                    </div>

                </div>

            </div>
        </div>

    </div>
</div>

@endsection
