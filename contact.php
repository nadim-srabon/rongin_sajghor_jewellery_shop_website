<?php include 'includes/header.php'; ?>

<!-- HERO SECTION -->
<section class="hero">
    <div class="hero-content">
        <h1>Contact Us</h1>
        <p>We Would Love To Hear From You</p>
        <a href="shop.php" class="btn-gold">Explore Collection</a>
    </div>
</section>


<!-- CONTACT SECTION -->
<section class="section">

    <h2 class="section-title">Get In Touch</h2>

    <div style="max-width:1200px; margin:auto; display:grid; grid-template-columns:1fr 1fr; gap:60px;">

        <!-- CONTACT INFO -->
        <div>

            <h3 style="margin-bottom:20px;">Contact Information</h3>

            <p style="margin-bottom:15px; color:#555;">
                📍 Address: Dhaka, Bangladesh
            </p>

            <p style="margin-bottom:15px; color:#555;">
                📞 Phone: +880 1405948946
            </p>

            <p style="margin-bottom:15px; color:#555;">
                ✉ Email: ronginsajghor12@gmail.com
            </p>

            <p style="margin-top:30px; color:#777; line-height:1.8;">
                Have questions about our products or your order?
                Fill out the form and our team will get back to you shortly.
            </p>

        </div>


        <!-- CONTACT FORM -->
        <div style="background:white; padding:40px; box-shadow:0 5px 20px rgba(0,0,0,0.05);">

            <form method="post" action="">

                <input type="text" name="name" placeholder="Your Name" required
                    style="width:100%; padding:12px; margin-bottom:15px; border:1px solid #ddd;">

                <input type="email" name="email" placeholder="Your Email" required
                    style="width:100%; padding:12px; margin-bottom:15px; border:1px solid #ddd;">

                <input type="text" name="subject" placeholder="Subject" required
                    style="width:100%; padding:12px; margin-bottom:15px; border:1px solid #ddd;">

                <textarea name="message" placeholder="Your Message" rows="5" required
                    style="width:100%; padding:12px; margin-bottom:20px; border:1px solid #ddd;"></textarea>

                <button class="btn-gold" style="color:black; cursor:pointer;">
                    Send Message
                </button>

            </form>

        </div>

    </div>

</section>


<?php include 'includes/footer.php'; ?>