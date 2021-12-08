<?php
$url         	= "https://fcp.shaparak.ir/nvcservice/Api/v2/";
$MerchantID 	= "";
$username 	    = "";
$password 	    = "";
$CallbackURL 	= "http://example.com/verify.php";


function sendRequest(){
    global $MerchantID,$username,$password,$CallbackURL,$InvoiceID,$Amount;

    $postField = [
        "CARDACCEPTORCODE"=>$MerchantID,
        "USERNAME"=>$username,
        "USERPASSWORD"=>$password,
        "PAYMENTID"=>$InvoiceID,
        "AMOUNT"=>$Amount,
        "CALLBACKURL"=>$CallbackURL,
    ];

    $result = callCurl($postField,"PayRequest");

    if (isset($result->ActionCode) && (int)$result->ActionCode == 0)
    {
        header("Location: {$result->RedirectUrl}");
    } else {
        if (isset($result->ActionCode)) {
            return getMessage($result->ActionCode);
        }
    }
    return getMessage(900);
}

function verify(){
    global $MerchantID,$username,$password,$InvoiceID;

    if (isset($_POST["Data"])){
        $data = $_POST["Data"];
        $data = json_decode($data);
        
        $postField = [
            "CARDACCEPTORCODE"=>$MerchantID,
            "USERNAME"=>$username,
            "USERPASSWORD"=>$password,
            "PAYMENTID"=>$InvoiceID,
            "RRN"=>$data->RRN,
        ];
        $result = callCurl($postField,"Confirm");

        if (isset($result->ActionCode))
        {
            return getMessage($result->ActionCode);
        }
    }
    return getMessage(900);
}

function callCurl($postField,$action){
    global $url;
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url.$action);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type' => 'application/json'));
    curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($postField));
    curl_setopt($curl, CURLOPT_TIMEOUT, 30);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-Type: application/json", "Accept: application/json"));
    $curl_exec = curl_exec($curl);
    curl_close($curl);
    return json_decode($curl_exec);
}

function getMessage($msgId) {
    switch((int)$msgId)
    {
        case	-1: $out = 'کلید نامعتبر است'; break;
        case	0: $out = 'تراکنش با موفقیت انجام شد.'; break;
        case	1: $out = 'صادرکننده ی کارت از انجام تراکنش صرف نظر کرد.'; break;
        case	2: $out = 'عملیات تاییدیه این تراکنش قبلا با موفقیت صورت پذیرفته است.'; break;
        case	3: $out = 'پذیرنده ی فروشگاهی نامعتبر است.'; break;
        case	5: $out = 'از انجام تراکنش صرف نظر شد.'; break;
        case	6: $out = 'بروز خطا'; break;
        case	7: $out = 'به دلیل شرایط خاص کارت توسط دستگاه ضبط شود.'; break;
        case	8: $out = 'باتشخیص هویت دارنده ی کارت، تراکنش موفق می باشد.'; break;
        case	9: $out = 'در حال حاضر امکان پاسخ دهی وجود ندارد'; break;
        case	12: $out = 'تراکنش نامعتبر است.'; break;
        case	13: $out = 'مبلغ تراکنش اصلاحیه نادرست است.'; break;
        case	14: $out = 'شماره کارت ارسالی نامعتبر است. (وجود ندارد)'; break;
        case	15: $out = 'صادرکننده ی کارت نامعتبراست.(وجود ندارد)'; break;
        case	16: $out = 'تراکنش مورد تایید است و اطلاعات شیار سوم کارت به روز رسانی شود.'; break;
        case	19: $out = 'تراکنش مجدداً ارسال شود.'; break;
        case	20: $out = 'خطای ناشناخته از سامانه مقصد'; break;
        case	23: $out = 'کارمزد ارسالی پذیرنده غیر قابل قبول است.'; break;
        case	25: $out = 'شماره شناسایی صادرکننده غیر معتبر'; break;
        case	30: $out = 'قالب پیام دارای اشکال است.'; break;
        case	31: $out = 'پذیرنده توسط سوئیچ پشتیبانی نمی شود.'; break;
        case	33: $out = 'تاریخ انقضای کارت سپری شده است'; break;
        case	34: $out = 'دارنده کارت مظنون به تقلب است.'; break;
        case	36: $out = 'کارت محدود شده است.کارت توسط دستگاه ضبط شود.'; break;
        case	38: $out = 'تعداد دفعات ورود رمز غلط بیش از حدمجاز است.'; break;
        case	39: $out = 'کارت حساب اعتباری ندارد.'; break;
        case	40: $out = 'عملیات درخواستی پشتیبانی نمی گردد.'; break;
        case	41: $out = 'کارت مفقودی می باشد.'; break;
        case	42: $out = 'کارت حساب عمومی ندارد.'; break;
        case	43: $out = 'کارت مسروقه می باشد.'; break;
        case	44: $out = 'کارت حساب سرمایه گذاری ندارد.'; break;
        case	48: $out = 'تراکنش پرداخت قبض قبلا انجام پذیرفته'; break;
        case	51: $out = 'موجودی کافی نیست.'; break;
        case	52: $out = 'کارت حساب جاری ندارد.'; break;
        case	53: $out = 'کارت حساب قرض الحسنه ندارد.'; break;
        case	54: $out = 'تاریخ انقضای کارت سپری شده است.'; break;
        case	55: $out = 'Pin-Error'; break;
        case	56: $out = 'کارت نا معتبر است.'; break;
        case	57: $out = 'انجام تراکنش مربوطه توسط دارنده ی کارت مجاز نمی باشد.'; break;
        case	58: $out = 'انجام تراکنش مربوطه توسط پایانه ی انجام دهنده مجاز نمی باشد.'; break;
        case	59: $out = 'کارت مظنون به تقلب است.'; break;
        case	61: $out = 'مبلغ تراکنش بیش از حد مجاز است.'; break;
        case	62: $out = 'کارت محدود شده است.'; break;
        case	63: $out = 'تمهیدات امنیتی نقض گردیده است.'; break;
        case	64: $out = 'مبلغ تراکنش اصلی نامعتبر است.(تراکنش مالی اصلی با این مبلغ نمی باشد)'; break;
        case	65: $out = 'تعداد درخواست تراکنش بیش از حد مجاز است.'; break;
        case	67: $out = 'کارت توسط دستگاه ضبط شود.'; break;
        case	75: $out = 'تعداد دفعات ورود رمزغلط بیش از حد مجاز است.'; break;
        case	77: $out = 'روز مالی تراکنش نا معتبر است.'; break;
        case	78: $out = 'کارت فعال نیست.'; break;
        case	79: $out = 'حساب متصل به کارت نامعتبر است یا دارای اشکال است.'; break;
        case	80: $out = 'خطای داخلی سوییچ رخ داده است'; break;
        case	81: $out = 'خطای پردازش سوییچ'; break;
        case	83: $out = 'ارائه دهنده خدمات پرداخت یا سامانه شاپرک اعلام Sign Off نموده است.'; break;
        case	84: $out = 'Host-Down'; break;
        case	86: $out = 'موسسه ارسال کننده، شاپرک یا مقصد تراکنش در حالت Sign off است.'; break;
        case	90: $out = 'سامانه مقصد تراکنش درحال انجام عملیات پایان روز می باشد.'; break;
        case	91: $out = 'پاسخی از سامانه مقصد دریافت نشد'; break;
        case	92: $out = 'مسیری برای ارسال تراکنش به مقصد یافت نشد. (موسسه های اعلامی معتبر نیستند)'; break;
        case	93: $out = 'پیام دوباره ارسال گردد. (درپیام های تاییدیه)'; break;
        case	94: $out = 'پیام تکراری است'; break;
        case	96: $out = 'بروز خطای سیستمی در انجام تراکنش'; break;
        case	97: $out = 'مبلغ تراکنش غیر معتبر است'; break;
        case	98: $out = 'شارژ وجود ندارد.'; break;
        case	99: $out = 'تراکنش غیر معتبر است یا کلید ها هماهنگ نیستند'; break;
        case	100: $out = 'خطای نامشخص'; break;
        case	500: $out = 'کدپذیرندگی معتبر نمی باشد'; break;
        case	501: $out = 'مبلغ بیشتر از حد مجاز است'; break;
        case	502: $out = 'نام کاربری و یا رمز ورود اشتباه است'; break;
        case	503: $out = 'آی پی دامنه کار بر نا معتبر است'; break;
        case	504: $out = 'آدرس صفحه برگشت نا معتبر است'; break;
        case	505: $out = 'ناشناخته'; break;
        case	506: $out = 'شماره سفارش تکراری است -  و یا مشکلی دیگر در درج اطلاعات'; break;
        case	507: $out = 'خطای اعتبارسنجی مقادیر'; break;
        case	508: $out = 'فرمت درخواست ارسالی نا معتبر است'; break;
        case	509: $out = 'قطع سرویس های شاپرک'; break;
        case	510: $out = 'لغو درخواست توسط خود کاربر'; break;
        case	511: $out = 'طولانی شدن زمان تراکنش و عدم انجام در زمان مقرر توسط کاربر'; break;
        case	512: $out = 'خطا اطلاعات Cvv2 کارت'; break;
        case	513: $out = 'خطای اطلاعات تاریخ انقضاء کارت'; break;
        case	514: $out = 'خطا در رایانامه درج شده'; break;
        case	515: $out = 'خطا در کاراکترهای کپچا'; break;
        case	516: $out = 'اطلاعات درخواست نامعتبر میباشد'; break;
        case	517: $out = 'خطا در شماره کارت'; break;
        case	518: $out = 'تراکنش مورد نظر وجود ندارد.'; break;
        case	519: $out = 'مشتری از پرداخت منصرف شده است'; break;
        case	520: $out = 'مشتری در زمان مقرر پرداخت را انجام نداده است'; break;
        case	521: $out = 'قبلا درخواست تائید با موفقیت ثبت شده است'; break;
        case	522: $out = 'قبلا درخواست اصلاح تراکنش با موفقیت ثبت شده است'; break;
        case	600: $out = 'لغو تراکنش'; break;
        case    403:$out = 'سفارش پیدا نشد'; break;
        default: $out ='خطا غیر منتظره رخ داده است';break;
    }

    return $out;
}