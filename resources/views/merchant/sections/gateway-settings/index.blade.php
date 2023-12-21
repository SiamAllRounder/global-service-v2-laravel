@extends('merchant.layouts.master')

@push('css')
    <style>
        .copy-button {
            cursor: pointer;
        }
    </style>
@endpush

@section('breadcrumb')
    @include('merchant.components.breadcrumb',['breadcrumbs' => [
        [
            'name'  => __("Dashboard"),
            'url'   => setRoute("merchant.dashboard"),
        ]
    ], 'active' => __($page_title)])
@endsection

@section('content')
<div class="body-wrapper">
    <div class="row mb-20-none">
        <div class="col-xl-12 col-lg-12 mb-20">
            <div class="custom-card mt-10">
                <div class="dashboard-header-wrapper">
                    <h5 class="title">{{ __($page_title) }}</h5>
                </div>
                <div class="row mb-30-none">
                    <div class="col-xxl-4 col-xl-6 col-md-6 mb-20">
                        <div class="gateway-item">
                            <div class="gateway-item-wrapper">
                                <div class="content">
                                    <h4 class="title">{{ __("Wallet Balance") }}</h4>
                                    <span><i class="las la-exclamation-circle"></i>{{ __("enable or disable this feature") }}</span>
                                </div>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" name="wallet_status" type="checkbox" id="walletStatusCheckbox" {{ @$setting->wallet_status == 1 ?'checked' :'' }} >
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xxl-4 col-xl-6 col-md-6 mb-20">
                        <div class="gateway-item">
                            <div class="gateway-item-wrapper">
                                <div class="content">
                                    <h4 class="title">{{ __("Virtual Card") }}</h4>
                                    <span><i class="las la-exclamation-circle"></i>{{ __("enable or disable this feature") }}</span>
                                </div>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="virtualStatusCheckbox" {{ @$setting->virtual_card_status == 1 ?'checked' :'' }}>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xxl-4 col-xl-6 col-md-6 mb-20">
                        <div class="gateway-item">
                            <div class="gateway-item-wrapper">
                                <div class="content">
                                    <h4 class="title">{{ __("Master / Visa Card") }} <small class="text--base">({{ __("Stripe") }})</small></h4>
                                    <span><i class="las la-exclamation-circle"></i>{{ __("enable or disable this feature") }}</span>
                                </div>
                                <div class="form-check form-switch">
                                    <input class="form-check-input master-visa-switch" type="checkbox" id="masterVisaStatusCheckBox" {{ @$setting->master_visa_status == 1 ?'checked' :'' }}>
                                </div>
                            </div>
                            <form class="master-visa-api-form mt-20 {{ @$setting->master_visa_status == 1 ?'active' :'' }}" id="master" action="{{ setRoute('merchant.gateway.setting.update.master.card.credentials') }}" method="POST">
                                @csrf
                                <div class="api-input-wrapper mb-20-none">
                                    <div class="form-group">
                                        <label>{{ __("Primary Key") }} <span class="text--base">*</span></label>
                                        <input type="text" name="primary_key" placeholder="Primary Key" class="form--control" value="{{ @$setting->credentials->primary_key }}" required>
                                    </div>
                                    <div class="form-group">
                                        <label>{{ __("Secret Key") }} <span class="text--base">*</span></label>
                                        <input type="text" name="secret_key" placeholder="Secret Key" class="form--control" value="{{ @$setting->credentials->secret_key }}" required>
                                    </div>
                                    <div class="form-group">
                                        <button type="submit" class="btn--base">{{ __("Save") }} <i class="fas fa-check-circle ms-1"></i></button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('script')
<script>
    var app_mode = "{{ env('APP_MODE') }}"
    $(document).ready(function() {
        //wallet system
        $('#walletStatusCheckbox').on('change', function() {
            var status = this.checked ? 1 : 0;
            if(app_mode == "demo"){
                throwMessage('error',["Can't change anything for demo application."]);
                setTimeout(function wait(){
                    location.reload();
                },2000);
                return false;
            }

            $.ajax({
                url: '{{ setRoute("merchant.gateway.setting.update.wallet.status") }}',
                method: 'POST',
                data: {
                    status: status,
                    _token: '{{csrf_token()}}'
                 },
                success: function(response) {
                    console.log(response);
                    var updated_status = response.status;
                    if(updated_status == 1){
                        throwMessage('success',["Wallet Balance System Enabled Successfully"]);
                    }else{
                        throwMessage('success',["Wallet Balance System Disabled  Successfully"]);
                    }
                },
                error: function(xhr, status, error) {

                    throwMessage('error',["Something is wrong!, Please try again later"]);
                }
            });
        });
        //virtual card
        $('#virtualStatusCheckbox').on('change', function() {
            var status = this.checked ? 1 : 0;
            if(app_mode == "demo"){
                throwMessage('error',["Can't change anything for demo application."]);
                setTimeout(function wait(){
                    location.reload();
                },2000);
                return false;
            }

            $.ajax({
                url: '{{ setRoute("merchant.gateway.setting.update.virtual.status") }}',
                method: 'POST',
                data: {
                    status: status,
                    _token: '{{csrf_token()}}'
                 },
                success: function(response) {
                    var updated_status = response.status;
                    if(updated_status == 1){
                        throwMessage('success',["Virtual Card System Enabled Successfully"]);
                    }else{
                        throwMessage('success',["Virtual Card System Disabled  Successfully"]);
                    }
                },
                error: function(xhr, status, error) {
                    throwMessage('error',["Something is wrong!, Please try again later"]);
                }
            });
        });
        //Master/Visa card
        $('#masterVisaStatusCheckBox').on('change', function() {
            var status = this.checked ? 1 : 0;
            if(app_mode == "demo"){
                throwMessage('error',["Can't change anything for demo application."]);
                setTimeout(function wait(){
                    location.reload();
                },2000);
                return false;
            }
            if(status == 1){
                $('#master').addClass('active');
            }else{
                $('#master').removeClass('active');
                $.ajax({
                url: '{{ setRoute("merchant.gateway.setting.update.master.status") }}',
                method: 'POST',
                data: {
                    status: status,
                    _token: '{{csrf_token()}}'
                 },
                success: function(response) {
                   var updated_status = response.status;
                   if(updated_status == 1){
                    throwMessage('success',["Master/Visa Card System Enabled Successfully"]);
                   }else{
                    throwMessage('success',["Master/Visa Card System Disabled  Successfully"]);
                   }

                },
                error: function(xhr, status, error) {
                    throwMessage('error',["Something is wrong!, Please try again later"]);
                }
            });
            }

        });
    });



</script>
@endpush
