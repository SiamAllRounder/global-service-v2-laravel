<?php

namespace App\Http\Controllers\Api\User;

use App\Models\Transaction;
use App\Http\Controllers\Controller;
use App\Constants\PaymentGatewayConst;
use App\Http\Helpers\Api\Helpers;
use App\Http\Resources\User\AddMoneyLogs;
use App\Http\Resources\User\AddSubBalanceLogs;
use App\Http\Resources\User\BillPayLogs;
use App\Http\Resources\User\MakePaymentLogs;
use App\Http\Resources\User\MerchantPaymentLogs;
use App\Http\Resources\User\MobileTopupLogs;
use App\Http\Resources\User\MoneyOutLogs;
use App\Http\Resources\User\RemittanceLogs;
use App\Http\Resources\User\TransferMoneyLogs;
use App\Http\Resources\User\VirtualCardLogs;
use Exception;

class TransactionController extends Controller
{
    public function slugValue($slug) {
        $values =  [
            'add-money'             => PaymentGatewayConst::TYPEADDMONEY,
            'money-out'             => PaymentGatewayConst::TYPEMONEYOUT,
            'transfer-money'        => PaymentGatewayConst::TYPETRANSFERMONEY,
            'bill-pay'              => PaymentGatewayConst::BILLPAY,
            'mobile-top-up'          => PaymentGatewayConst::MOBILETOPUP,
            'virtual-card'          => PaymentGatewayConst::VIRTUALCARD,
            'remittance'            => PaymentGatewayConst::SENDREMITTANCE,
            'merchant-payment'      => PaymentGatewayConst::MERCHANTPAYMENT,
            'make-payment'          => PaymentGatewayConst::TYPEMAKEPAYMENT,
            'add-sub-balance'       => PaymentGatewayConst::TYPEADDSUBTRACTBALANCE,

        ];

        if(!array_key_exists($slug,$values)) return abort(404);
        return $values[$slug];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($slug = null) {

        // start transaction now
        $bill_pay           = Transaction::auth()->billPay()->orderByDesc("id")->get();
        $mobileTopUp        = Transaction::auth()->mobileTopup()->orderByDesc("id")->get();
        $addMoney           = Transaction::auth()->addMoney()->orderByDesc("id")->latest()->get();
        $moneyOut           = Transaction::auth()->moneyOut()->orderByDesc("id")->get();
        $sendMoney          = Transaction::auth()->senMoney()->orderByDesc("id")->get();
        $virtualCard        = Transaction::auth()->virtualCard()->orderByDesc("id")->get();
        $remittance         = Transaction::auth()->remitance()->orderByDesc("id")->get();
        $merchant_payment   = Transaction::auth()->merchantPayment()->orderByDesc("id")->get();
        $make_payment       = Transaction::auth()->makePayment()->orderByDesc("id")->get();
        $addSubBalance      = Transaction::auth()->addSubBalance()->orderByDesc("id")->get();


        $transactions = [
            'bill_pay'          => BillPayLogs::collection($bill_pay),
            'mobile_top_up'     => MobileTopupLogs::collection($mobileTopUp),
            'add_money'         => AddMoneyLogs::collection($addMoney),
            'money_out'         => MoneyOutLogs::collection($moneyOut),
            'send_money'        => TransferMoneyLogs::collection($sendMoney),
            'virtual_card'      => VirtualCardLogs::collection($virtualCard),
            'remittance'        => RemittanceLogs::collection($remittance),
            'merchant_payment'  => MerchantPaymentLogs::collection($merchant_payment),
            'make_payment'      => MakePaymentLogs::collection($make_payment),
            'add_sub_balance'   => AddSubBalanceLogs::collection($addSubBalance),
        ];
        $transactions = (object)$transactions;

        $transaction_types = [
            'add_money'         => PaymentGatewayConst::TYPEADDMONEY,
            'money_out'         => PaymentGatewayConst::TYPEMONEYOUT,
            'transfer_money'    => PaymentGatewayConst::TYPETRANSFERMONEY,
            'bill_pay'          => PaymentGatewayConst::BILLPAY,
            'mobile_top_up'      => PaymentGatewayConst::MOBILETOPUP,
            'virtual_card'      => PaymentGatewayConst::VIRTUALCARD,
            'remittance'        => PaymentGatewayConst::SENDREMITTANCE,
            'merchant-payment'  => PaymentGatewayConst::MERCHANTPAYMENT,
            'make_payment'      => PaymentGatewayConst::TYPEMAKEPAYMENT,
            'add_sub_balance'       => PaymentGatewayConst::TYPEADDSUBTRACTBALANCE,

        ];
        $transaction_types = (object)$transaction_types;
        $data =[
            'transaction_types' => $transaction_types,
            'transactions'=> $transactions,
        ];
        $message =  ['success'=>['All Transactions']];
        return Helpers::success($data,$message);
    }

}
