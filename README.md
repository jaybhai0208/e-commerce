# Khaatu Foods — Dynamic E-commerce Backend

This package converts the existing PHP storefront into a database-backed flow while retaining the existing frontend CSS/classes and visual direction. No CSS files were redesigned.

## Stack
- PHP 8.1+
- MySQL 8+/MariaDB 10.5+
- Existing Bootstrap/Swiper/jQuery assets
- PHP sessions + `password_hash/password_verify`
- Native `mail()` for order enquiry email

## Setup

1. Create/import the database:
   - Open phpMyAdmin/MySQL.
   - Import `database.sql`.
2. Configure database credentials. Defaults are:
   - host: `localhost`
   - user: `root`
   - password: empty
   - database: `jay_ecom`
   You can override with `DB_HOST`, `DB_USER`, `DB_PASS`, `DB_NAME`.
3. Put the project under Apache/Nginx PHP document root.
4. Configure `ORDER_TO_EMAIL` to the business inbox. If omitted it defaults to `khaatufood@gmail.com`.
5. Ensure PHP has `mysqli` enabled.
6. Ensure `admin/uploads-img/` is writable by PHP for product images.
7. Create an admin account using the existing admin registration flow, or insert a user with `role_as=1` and a password hash generated with `password_hash()`.

## Main dynamic flow

- `index.php`: products/categories come from MySQL.
- `product.php?id=...`: dynamic product detail.
- `cart-action.php`: guest + authenticated cart add/update/remove. Guest cart is kept in the session and is merged into the customer account after login/registration.
- `view-cart.php`: database cart and totals.
- `checkout.php`: guest checkout address form; if the customer is not logged in, the entered address is saved in the session and the login page opens. After login/registration, the customer returns to checkout with the address restored.
- `place-order.php`: validates checkout; guests are sent to login after their address is saved; logged-in customers can place the order without logging in again. Orders are created in a transaction, stock is decremented, address can be saved, and the cart is cleared.
- `order.php?id=...`: order success/summary.
- `return-request.php`: customer return request.
- `account-page.php`: logged-in customer orders.
- `register.php` + `auth.php`: email OTP verification before a new customer account is inserted into `users`. OTP expires after 10 minutes and is limited to 5 verification attempts.
- `track-order.php`: guest order tracking.
- `admin/`: existing admin panel connected to the same database; product changes are immediately reflected on the storefront.

## Email

The registration OTP uses PHP `mail()`. On XAMPP/local Windows, PHP mail is normally not configured by default, so configure PHP's mail transport or replace `send_otp_email()` in `includes/config.php` with your SMTP/PHPMailer setup. The order email still uses `ORDER_TO_EMAIL`.

## Security included

- Prepared statements in new customer/cart/order code
- Password hashing
- Session ID regeneration after login
- HttpOnly/SameSite session cookie settings
- CSRF token on state-changing customer forms
- Server-side validation
- Transactional order placement and stock locking
- HTML escaping for rendered database content

## Important existing data

If you already have a `jay_ecom` database, back it up before importing. The `CREATE TABLE IF NOT EXISTS` statements are safe for existing tables, but the new flow expects the columns shown in `database.sql` (especially `users.status`, product fields, and order tables).


## Razorpay + Checkout + Returns Update

1. Import/run `database/migration.sql` in the same MySQL database used by Khaatu Foods.
2. In the project root `.env`, set:
   - `RAZORPAY_KEY_ID=rzp_test_...`
   - `RAZORPAY_KEY_SECRET=...`
3. Start with Razorpay Test Mode keys. After successful testing, replace them with Live keys.
4. Razorpay Checkout amount is generated server-side from the checkout snapshot. The frontend amount is never trusted for payment.
5. Online payment is marked `paid` only after Razorpay signature verification and a backend payment-amount/status check. Successful online orders become `confirmed`, not `placed`.
6. Customer confirmation email and admin order email are sent after successful payment verification. COD orders continue to use `placed` + `pending` payment.
7. Return requests are stored in `return_requests` and are visible from Admin → Returns and inside each order. The request stores customer details through the linked order plus product, unit price, quantity and reason.
8. The existing `.env` file is kept in this ZIP. Review its SMTP/DB values before deployment and do not commit production secrets to a public repository.
