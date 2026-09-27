
<?php
require_once __DIR__ . '/includes/config.php';
if (current_user()) redirect('index.php');
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Create Account - Khaatu Foods</title>
    <?php include 'assets.php'; ?>
</head>

<body>
    <?php include 'header.php'; ?>
    <section class="flat-spacing">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6">
                    <div class="tf-page-login">
                        <div class="box-login">
                            <h3 class="title text-center mb-20">Create Account</h3>
                            <div id="regMsg"></div>
                            <form id="registerForm" class="form-log" autocomplete="off">
                                <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
                                <div class="form-content">
                                    <fieldset class="tf-field">
                                        <label class="tf-lable fw-medium">Name <span class="text-primary">*</span></label>
                                        <input id="regName" type="text" name="name" required autocomplete="name">
                                    </fieldset>
                                    <fieldset class="tf-field">
                                        <label class="tf-lable fw-medium">Phone <span class="text-primary">*</span></label>
                                        <input id="regPhone" name="phone" type="tel" required autocomplete="tel">
                                    </fieldset>
                                    <fieldset class="tf-field">
                                        <label class="tf-lable fw-medium">Email <span class="text-primary">*</span></label>
                                        <input id="regEmail" name="email" type="email" required autocomplete="email">

                                        <button id="sendOtp" type="button" class="tf-btn btn-stroke w-100 mt-12 mt-5">Send OTP</button>
                                    </fieldset>
                                    <fieldset class="tf-field" id="otpWrap" style="display:none">
                                        <label class="tf-lable fw-medium">Email OTP <span class="text-primary">*</span></label>
                                        <input id="regOtp" name="otp" type="text" inputmode="numeric" maxlength="6" pattern="[0-9]{6}" autocomplete="one-time-code">
                                        <button id="verifyOtp" type="button" class="tf-btn btn-stroke w-100 mt-12">Verify OTP</button>
                                        <button id="resendOtp" type="button" class="tf-btn btn-stroke w-100 mt-12">Resend OTP</button>
                                    </fieldset>
                                    <div id="passwordWrap" style="display:none">
                                        <fieldset class="tf-field">
                                            <label class="tf-lable fw-medium">Password <span class="text-primary">*</span></label>
                                            <input id="regPass" type="password" name="password" minlength="8" autocomplete="new-password">
                                        </fieldset>
                                        <fieldset class="tf-field">
                                            <label class="tf-lable fw-medium">Confirm Password <span class="text-primary">*</span></label>
                                            <input id="regPass2" type="password" name="password_confirmation" minlength="8" autocomplete="new-password">
                                        </fieldset>
                                        <button id="completeBtn" class="tf-btn animate-btn w-100" type="submit">Create Account</button>
                                    </div>
                                </div>
                            </form>
                            <p class="text-center mt-20">Already registered? <a class="link text-decoration-underline" href="login.php">Login</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script>
        const f = document.getElementById('registerForm');
        const msg = document.getElementById('regMsg');
        const sendOtpBtn = document.getElementById('sendOtp');
        const resendOtpBtn = document.getElementById('resendOtp');
        const verifyOtpBtn = document.getElementById('verifyOtp');
        const completeBtn = document.getElementById('completeBtn');
        const otpWrap = document.getElementById('otpWrap');
        const passwordWrap = document.getElementById('passwordWrap');
        const regName = document.getElementById('regName');
        const regPhone = document.getElementById('regPhone');
        const regEmail = document.getElementById('regEmail');
        const regOtp = document.getElementById('regOtp');
        const regPass = document.getElementById('regPass');
        const regPass2 = document.getElementById('regPass2');

        function showMsg(text, ok = false) {
            msg.innerHTML = '<div class="alert ' + (ok ? 'alert-success' : 'alert-danger') + '">' + escapeHtml(text) + '</div>';
        }

        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text ?? '';
            return div.innerHTML;
        }

        async function post(action, data = {}) {
            try {
                const fd = new FormData(f);
                fd.set('action', action);
                Object.entries(data).forEach(([key, value]) => fd.set(key, value));
                const response = await fetch('auth.php', {
                    method: 'POST',
                    body: fd,
                    credentials: 'same-origin',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                const text = await response.text();
                let result;
                try {
                    result = JSON.parse(text);
                } catch (e) {
                    console.error('Server response:', text);
                    return {
                        ok: false,
                        message: 'Server returned an invalid response. Please check auth.php.'
                    };
                }
                return result;
            } catch (error) {
                console.error(error);
                return {
                    ok: false,
                    message: 'Network error. Please try again.'
                };
            }
        }

        function setButtonLoading(button, loading, text) {
            if (loading) {
                button.dataset.oldText = button.innerHTML;
                button.disabled = true;
                button.innerHTML = text;
            } else {
                button.disabled = false;
                button.innerHTML = button.dataset.oldText || button.innerHTML;
            }
        }

        function validateBasicFields() {
            if (!regName.reportValidity()) return false;
            if (!regPhone.reportValidity()) return false;
            if (!regEmail.reportValidity()) return false;
            return true;
        }

        sendOtpBtn.addEventListener('click', async function() {
            if (!validateBasicFields()) return;
            setButtonLoading(sendOtpBtn, true, 'Sending OTP...');
            const r = await post('send_register_otp');
            setButtonLoading(sendOtpBtn, false);
            showMsg(r.message, r.ok);
            if (r.ok) {
                otpWrap.style.display = 'block';
                regOtp.focus();
                sendOtpBtn.style.display = 'none';
            }
        });

        resendOtpBtn.addEventListener('click', async function() {
            if (!validateBasicFields()) return;
            setButtonLoading(resendOtpBtn, true, 'Sending OTP...');
            const r = await post('send_register_otp');
            setButtonLoading(resendOtpBtn, false);
            showMsg(r.message, r.ok);
            if (r.ok) {
                regOtp.value = '';
                regOtp.readOnly = false;
                verifyOtpBtn.disabled = false;
                regOtp.focus();
            }
        });

        verifyOtpBtn.addEventListener('click', async function() {
            const otp = regOtp.value.trim();
            if (!/^[0-9]{6}$/.test(otp)) {
                showMsg('Enter the 6-digit OTP.');
                regOtp.focus();
                return;
            }
            setButtonLoading(verifyOtpBtn, true, 'Verifying...');
            const r = await post('verify_register_otp', {
                otp: otp
            });
            setButtonLoading(verifyOtpBtn, false);
            showMsg(r.message, r.ok);
            if (r.ok) {
                passwordWrap.style.display = 'block';
                regOtp.readOnly = true;
                verifyOtpBtn.disabled = true;
                resendOtpBtn.disabled = true;
                regPass.required = true;
                regPass2.required = true;
                regPass.focus();
            }
        });

        f.addEventListener('submit', async function(e) {
            e.preventDefault();
            if (passwordWrap.style.display === 'none') {
                showMsg('Please verify your email OTP first.');
                return;
            }
            if (!regPass.reportValidity()) return;
            if (!regPass2.reportValidity()) return;
            if (regPass.value.length < 8) {
                showMsg('Password must be at least 8 characters.');
                regPass.focus();
                return;
            }
            if (regPass.value !== regPass2.value) {
                showMsg('Both passwords must match.');
                regPass2.focus();
                return;
            }
            setButtonLoading(completeBtn, true, 'Creating Account...');
            const r = await post('complete_register');
            if (r.ok) {
                showMsg(r.message, true);
                setTimeout(function() {
                    location.href = r.redirect || 'login.php?registered=1';
                }, 900);
            } else {
                setButtonLoading(completeBtn, false);
                showMsg(r.message);
            }
        });

        regOtp.addEventListener('input', function() {
            this.value = this.value.replace(/\D/g, '').slice(0, 6);
        });

        regPhone.addEventListener('input', function() {
            this.value = this.value.replace(/[^\d+\-\s()]/g, '');
        });
    </script>
    <?php include 'footer.php'; ?>
</body>

</html>
