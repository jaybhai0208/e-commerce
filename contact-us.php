<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Khaatu Foods</title>
    <?php include 'assets.php'; ?>
</head>
<body>
    <?php include 'header.php'; ?>
    <!-- Page Title -->
        <section class="section-page-title text-center flat-spacing-2 pb-0" style="background: url('assets/bread.jpg') center center cover / no-repeat;">
            <div class="container">
                <div class="main-page-title">
                    <div class="breadcrumbs">
                        <a href="index.php" class="text-caption-01 cl-text-3 link">Home</a>
                        <i class="icon icon-CaretRightThin cl-text-3"></i>
                        <P class="text-caption-01">
                            Contact Us
                        </P>
                    </div>
                    <h3>
                        Contact Us
                    </h3>
                    <p class="text-body-1 cl-text-2">
                        Get in touch with us for inquiries, support, or collaboration we’re here to help you.
                    </p>
                </div>
            </div>
        </section>
        <!-- /Page Title -->
        <!-- Map -->
        <div class="section-map flat-spacing-2 pb-0">
            <div class="container">
                <div class="wg-map">
                   <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d910642.8154339067!2d83.706926!3d26.928921!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3993fbd400106417%3A0x42752772ef8d995d!2sBaba%20sweet%20house%20and%20restaurant!5e0!3m2!1sen!2sin!4v1789496961147!5m2!1sen!2sin" width="100%" height="550" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="strict-origin-when-cross-origin"></iframe>
                </div>
            </div>
        </div>
        <!-- /Map -->
        <!-- Contact -->
        <section class="section-contact flat-spacing">
            <div class="container">
                <div class="row gy-5 flex-wrap-reverse">
                    <div class="col-md-6">
                        <div class="col-left">
                            <div class="heading d-grid gap-8">
                                <h4>Information</h4>
                                <h5 class="d-none">Perfect Heading SEO</h5>
                                <p class="cl-text-2">
                                    Have a question? Please contact us using the customer support channels below.
                                </p>
                            </div>
                            <div class="grid-info tf-grid-layout sm-col-2">
                                <div class="d-grid gap-8">
                                    <h6>Phone:</h6>
                                    <p>
                                        <a href="tel:+917398026045" class="cl-text-2 link">
                                            +91 7398026045
                                        </a>
                                    </p>
                                </div>
                                <div class="d-grid gap-8">
                                    <h6>Email:</h6>
                                    <p>
                                        <a href="mailto:khaatufood@gmail.com" class="cl-text-2 link">
                                            khaatufood@gmail.com
                                        </a>
                                    </p>
                                </div>
                                <div class="wd-full d-grid gap-8">
                                    <h6>Address:</h6>
                                    <p>
                                        <a href="https://maps.app.goo.gl/FG2LPWTF7EMT3A2X8"
                                            target="_blank" class="cl-text-2 link">
                                           Basahiya Urf Captainganj Babhnauli Near Baba Dhaba Ramkola Road Kushinagar, 274301, U.P. India.
                                        </a>
                                    </p>
                                </div>
                                <div class="wd-full d-grid gap-8">
                                    <h6>Open Time:</h6>
                                    <ul class="open-text">
                                        <li class="d-flex gap-4 mb-4">
                                            <span class="cl-text-2">Mon - Sat:</span>7:30am - 8:00pm PST
                                        </li>
                                        <li class="d-flex gap-4">
                                            <span class="cl-text-2">Sunday:</span>9:00am - 5:00pm PST
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h4 class="mb-8">Get In Touch</h4>
                        <p class="mb-24 cl-text-2">
                            Use the form below to get in touch with the sales team
                        </p>
                        <form class="form-get">
                            <div class="form-content">
                                <div class="tf-grid-layout sm-col-2">
                                    <fieldset class="tf-field">
                                        <label for="your-name" class="tf-lable fw-medium">
                                            Your Name
                                            <span class="text-primary">*</span>
                                        </label>
                                        <input type="text" id="your-name" placeholder="Your Name*" required>
                                    </fieldset>
                                    <fieldset class="tf-field">
                                        <label for="your-email" class="tf-lable fw-medium">
                                            Your Email
                                            <span class="text-primary">*</span>
                                        </label>
                                        <input type="email" id="your-email" placeholder="Your Email*" required>
                                    </fieldset>
                                </div>
                                <fieldset class="tf-field">
                                    <label for="your-email" class="tf-lable fw-medium">
                                        Your Message
                                        <span class="text-primary">*</span>
                                    </label>
                                    <textarea placeholder="Your Message*" required></textarea>
                                </fieldset>
                                <div class="checkbox-wrap">
                                    <input class="tf-check flex-shrink-0" type="checkbox" id="agree-term-2">
                                    <label for="agree-term-2">
                                        Save my name, email, and website in this browser for the next time I comment.
                                    </label>
                                </div>
                            </div>
                            <button type="submit" class="tf-btn animate-btn">
                                Send message
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </section>
        <!-- /Contact -->
    <?php include 'footer.php'; ?>
</body>
</html>