<?php require_once __DIR__ . '/includes/config.php';
$u = current_user();
$uid = $u ? (int)$u['id'] : null;
[$items, $subtotal] = cart_totals($uid);
if (!$items) {
    redirect('view-cart.php');
}
$checkoutSnapshotItems=[];
foreach($items as $ci){
    $checkoutSnapshotItems[]=[
        'product_id'=>(int)$ci['product_id'],
        'name'=>(string)$ci['name'],
        'quantity'=>(int)$ci['quantity'],
        'unit_price'=>(float)$ci['unit_price'],
        'line_total'=>round((float)$ci['line_total'],2)
    ];
}
$checkoutTotal=round((float)$subtotal,2);
if($u){
    $_SESSION['checkout_snapshot']=[
        'items'=>$checkoutSnapshotItems,
        'total'=>$checkoutTotal,
        'created_at'=>time()
    ];
}else{
    unset($_SESSION['checkout_snapshot']);
}
$addr = null;
if ($u) {
    $s = $conn->prepare("SELECT * FROM customer_addresses WHERE user_id=? ORDER BY is_default DESC,id DESC LIMIT 1");
    $s->bind_param('i', $u['id']);
    $s->execute();
    $addr = $s->get_result()->fetch_assoc();
}
$pending = $_SESSION['pending_checkout'] ?? [];
$parts = preg_split('/\s+/', trim($u['name'] ?? ($pending['first_name'] ?? '')), 2);
$first = $parts[0] ?? '';
$last = $parts[1] ?? ($pending['last_name'] ?? '');
if (!$u && !empty($pending)) {
    $first = $pending['first_name'] ?? '';
    $last = $pending['last_name'] ?? '';
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - Khaatu Foods</title><?php include 'assets.php'; ?>
</head>

<body><?php include 'header.php'; ?>
    <div class="section-page-title-single flat-spacing-3">
        <div class="container">
            <div class="main-page-title">
                <div class="breadcrumbs"><a href="index.php" class="text-caption-01 cl-text-3 link">Home</a><i class="icon icon-CaretRightThin cl-text-3"></i><a href="view-cart.php" class="text-caption-01 cl-text-3 link">Shopping Cart</a><i class="icon icon-CaretRightThin cl-text-3"></i>
                    <p class="text-caption-01">Checkout</p>
                </div>
                <h2 class="title">Checkout</h2>
            </div>
        </div>
    </div>
    <section class="section-checkout flat-spacing-2">
        <div class="container">
            <div class="row">
                <div class="col-lg-7">
                    <div class="tf-page-checkout mb-lg-0">
                        <form method="post" action="place-order.php" class="tf-checkout-cart-main" id="checkout-form"><input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>"><input type="hidden" name="ajax" value="1">
                            <div class="box-ip-checkout estimate-shipping">
                                <div class="h5 title">Information</div>
                                <div class="form-content">
                                    <fieldset class="tf-field">
                                        <label class="tf-lable text-caption-01">First Name*</label>
                                        <input type="text" name="first_name" value="<?= e($first) ?>" required>
                                    </fieldset>

                                    <fieldset class="tf-field">
                                        <label class="tf-lable text-caption-01">Last Name*</label>
                                        <input type="text" name="last_name" value="<?= e($last) ?>" required>
                                    </fieldset>
                                    <fieldset class="tf-field">
                                        <label class="tf-lable text-caption-01">Email Address*</label>
                                        <input type="email" name="email" value="<?= e($u['email'] ?? ($pending['email'] ?? '')) ?>" required>
                                    </fieldset>
                                    <fieldset class="tf-field">
                                        <label class="tf-lable text-caption-01">Phone Number*</label>
                                        <input type="tel" name="phone" value="<?= e($u['phone'] ?? ($pending['phone'] ?? '')) ?>" required>
                                    </fieldset>
                                    <fieldset class="tf-field">
                                        <label class="tf-lable text-caption-01">Country*</label>
                                        <select class="w-100" name="country" required>
                                            <option value="India" <?= ($addr['country'] ?? 'India') === 'India' ? 'selected' : '' ?>>India</option>
                                        </select>
                                    </fieldset>
                                    <fieldset class="tf-field">
                                        <label class="tf-lable text-caption-01">Town/City*</label>
                                        <input type="text" name="city" value="<?= e($addr['city'] ?? ($pending['city'] ?? '')) ?>" required>
                                    </fieldset>
                                    <fieldset class="tf-field">
                                        <label class="tf-lable text-caption-01">Street*</label>
                                        <input type="text" name="address" value="<?= e($addr['address_line'] ?? ($pending['address'] ?? '')) ?>" required>
                                    </fieldset>
                                    <fieldset class="tf-field">
                                        <label class="tf-lable text-caption-01">State*</label>
                                        <input type="text" name="state" value="<?= e($addr['state'] ?? ($pending['state'] ?? '')) ?>" required>
                                    </fieldset>
                                    <fieldset class="tf-field">
                                        <label class="tf-lable text-caption-01">Postal Code*</label>
                                        <input type="text" name="pincode" value="<?= e($addr['pincode'] ?? ($pending['pincode'] ?? '')) ?>" required pattern="[0-9]{5,6}">
                                    </fieldset>
                                    <fieldset class="checkbox-wrap">
                                        <input type="checkbox" name="save_address" id="save-address" class="tf-check style-2" checked>
                                        <label for="save-address">Save this address for next time</label>
                                    </fieldset>
                                </div>
                            </div>
                            <div class="box-ip-checkout">
    <div class="h5 title">Payment</div>

    <div class="form-content">
        <fieldset class="tf-field">
            <label class="tf-lable text-caption-01">
                Payment Method*
            </label>

            <div class="payment-methods">
                <label>
                    <input type="radio" name="payment_method" value="cod" required>
                    <span>Cash on Delivery</span>
                </label>

                <label>
                    <input type="radio" name="payment_method" value="razorpay">
                    <span>Online Payment</span>
                </label>
            </div>
        </fieldset>
    </div>
</div>
                            <div id="checkout-message" class="mt-15" style="display:none;"></div><button type="submit" id="checkout-submit" class="tf-btn animate-btn w-100"><span class="fw-semibold">Place Order / Pay Now</span></button>
                        </form>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="fl-sidebar-cart type-2 mt-lg-0 sticky-top">
                        <div class="box-your-order">
                            <h5 class="title">Your Order</h5>
                            <ul class="list-order-product">
                                <?php foreach ($items as $i): ?><li class="order-item fw-medium">
                                    <a href="product.php?id=<?= $i['product_id'] ?>" class="img-prd">
                                    <img loading="lazy" width="100" height="133" src="admin/uploads-img/<?= e($i['image']) ?>" alt="
                                    <?= e($i['name']) ?>">
                                    </a>
                                        <div class="infor-prd"><a href="product.php?id=<?= $i['product_id'] ?>" class="prd_name fw-medium lh-24 link link-underline"><?= e($i['name']) ?></a>
                                            <div class="text-caption-01">Quantity: <?= e($i['quantity']) ?></div>
                                        </div>
                                        <div class="quantity-price text-primary"><?= money($i['line_total']) ?></div>
                                    </li><?php endforeach; ?></ul>
                            <ul class="list-total">
                                <li class="total-item lh-24 fw-medium"><span>Shipping</span><span>Free</span></li>
                                <li class="total-item lh-24 fw-medium"><span>Discounts</span><span>₹0.00</span></li>
                            </ul>
                            <div class="last-total h5 fw-medium"><span>Total</span><span id="checkout-total"><?= money($checkoutTotal) ?></span></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section><?php include 'footer.php'; ?>

<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
(function(){
    const form=document.getElementById('checkout-form');
    if(!form)return;
    const btn=document.getElementById('checkout-submit');
    const msg=document.getElementById('checkout-message');
    function showMsg(text,error){
        msg.style.display='block';
        msg.style.color=error?'#b91c1c':'#15803d';
        msg.style.background=error?'#fef2f2':'#f0fdf4';
        msg.style.border='1px solid '+(error?'#fecaca':'#bbf7d0');
        msg.style.padding='10px 12px';
        msg.style.borderRadius='8px';
        msg.textContent=text;
    }
    form.addEventListener('submit',async function(ev){
        ev.preventDefault();
        const selected=form.querySelector('input[name="payment_method"]:checked');
        if(!selected){showMsg('Please select a payment method.',true);return;}
        btn.disabled=true;
        btn.querySelector('span').textContent=selected.value==='razorpay'?'Creating secure payment...':'Placing order...';
        msg.style.display='none';
        try{
            const response=await fetch(form.action,{method:'POST',body:new FormData(form),headers:{'X-Requested-With':'XMLHttpRequest'}});
            const data=await response.json();
            if(!data.ok)throw new Error(data.message||'Unable to place order.');
            if(selected.value==='cod'){
                window.location.href=data.redirect;
                return;
            }
            const options={
                key:data.razorpay.key_id,
                amount:data.razorpay.amount,
                currency:data.razorpay.currency,
                name:'<?= e(APP_NAME) ?>',
                description:'Order '+data.order_number,
                order_id:data.razorpay.order_id,
                prefill:{
                    name:data.customer.name,
                    email:data.customer.email,
                    contact:data.customer.phone
                },
                theme:{color:'#111827'},
                handler:async function(paymentResponse){
                    try{
                        showMsg('Verifying payment securely...',false);
                        const fd=new FormData();
                        fd.append('csrf',form.querySelector('input[name="csrf"]').value);
                        fd.append('order_id',data.order_id);
                        fd.append('razorpay_order_id',paymentResponse.razorpay_order_id||'');
                        fd.append('razorpay_payment_id',paymentResponse.razorpay_payment_id||'');
                        fd.append('razorpay_signature',paymentResponse.razorpay_signature||'');
                        const vr=await fetch('razorpay-verify.php',{method:'POST',body:fd,headers:{'X-Requested-With':'XMLHttpRequest'}});
                        const vd=await vr.json();
                        if(!vd.ok)throw new Error(vd.message||'Payment verification failed.');
                        window.location.href=vd.redirect;
                    }catch(err){
                        showMsg(err.message,true);
                        btn.disabled=false;
                        btn.querySelector('span').textContent='Pay Again';
                    }
                },
                modal:{
                    ondismiss:function(){
                        showMsg('Payment was cancelled. Your order is still pending and you can try again.',true);
                        btn.disabled=false;
                        btn.querySelector('span').textContent='Pay Again';
                    }
                }
            };
            const rzp=new Razorpay(options);
            rzp.on('payment.failed',async function(response){
                try{
                    const fd=new FormData();
                    fd.append('csrf',form.querySelector('input[name="csrf"]').value);
                    fd.append('order_id',data.order_id);
                    await fetch('razorpay-failed.php',{method:'POST',body:fd});
                }catch(e){}
                showMsg((response.error&&response.error.description)||'Payment failed. Please try again.',true);
                btn.disabled=false;
                btn.querySelector('span').textContent='Pay Again';
            });
            rzp.open();
        }catch(err){
            showMsg(err.message,true);
            btn.disabled=false;
            btn.querySelector('span').textContent='Place Order / Pay Now';
        }
    });
})();
</script>
</body>

</html>