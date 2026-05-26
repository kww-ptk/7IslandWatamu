  <footer class="footer" id="contact">
    <div class="container footer__main">
      <div class="footer__brand">
        <img class="footer__logo-img" src="assets/img/logo-white.png" alt="Seven Islands Watamu">
        <p class="footer__tagline">Rated #1 all-inclusive beachfront resort in Watamu, Kenya.</p>
        <ul class="footer__social">
          <li><a href="#">Facebook</a></li>
          <li><a href="#">Instagram</a></li>
          <li><a href="#">Tripadvisor</a></li>
          <li><a href="#">Tiktok</a></li>
        </ul>
      </div>
      <nav class="footer__col" aria-label="Footer">
        <h4 class="footer__h">Explore</h4>
        <ul class="footer__links">
          <li><a href="index.php#top">Home</a></li>
          <li><a href="about.php">The Resort</a></li>
          <li><a href="rooms.php">Rooms</a></li>
          <li><a href="dining.php">Dining</a></li>
          <li><a href="spa.php">SPA &amp; Wellness</a></li>
          <li><a href="tours.php">Safari &amp; Excursion</a></li>
        </ul>
      </nav>
      <div class="footer__col">
        <h4 class="footer__h">Get in Touch</h4>
        <ul class="footer__contact">
          <li>
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 10c0 7-9 12-9 12s-9-5-9-12a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
            <span>P.O. Box 424, Jacaranda Road<br>80202 Watamu, Kenya</span>
          </li>
          <li>
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M22 16.9v3a2 2 0 0 1-2.2 2 19.8 19.8 0 0 1-8.6-3 19.5 19.5 0 0 1-6-6 19.8 19.8 0 0 1-3-8.6A2 2 0 0 1 4.1 2h3a2 2 0 0 1 2 1.7c.1 1 .3 1.9.6 2.8a2 2 0 0 1-.5 2.1L8 9.9a16 16 0 0 0 6 6l1.3-1.3a2 2 0 0 1 2.1-.4c.9.3 1.8.5 2.8.6a2 2 0 0 1 1.8 2.1z"/></svg>
            <a href="tel:+2540713326336">+254 0713 326 336</a>
          </li>
          <li>
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 4h16v16H4z"/><path d="M22 6l-10 7L2 6"/></svg>
            <a href="mailto:reservation@sevenislandswatamu.com">reservation@sevenislandswatamu.com</a>
          </li>
        </ul>
      </div>
      <div class="footer__col">
        <h4 class="footer__h">Newsletter</h4>
        <p class="footer__sub">Stay updated with resort news, seasonal offers and stories from the coast.</p>
        <form class="footer__form" onsubmit="return false">
          <input type="email" placeholder="Your Email" aria-label="Email address" required>
          <button type="submit">Subscribe</button>
        </form>
        <div class="footer-activity" id="activityToast" aria-live="polite">
          <div class="footer-activity__inner">
            <span class="footer-activity__icon" data-toast-icon aria-hidden="true"></span>
            <span class="footer-activity__text">
              <strong data-toast-title></strong>
              <span data-toast-meta></span>
            </span>
          </div>
        </div>
      </div>
    </div>
    <div class="container footer__bottom">
      <p>Copyright &copy; 2026 Seven Islands Resort. All rights reserved.</p>
      <ul class="footer__legal">
        <li><a href="#">Privacy Policy</a></li>
        <li><a href="#">Terms &amp; Conditions</a></li>
      </ul>
    </div>
  </footer>
  <script src="https://cdn.jsdelivr.net/npm/flatpickr@4.6.13/dist/flatpickr.min.js" defer></script>
  <script src="script.js" defer></script>
<?php if (!empty($extraScripts)) foreach ($extraScripts as $extraScript): ?>
  <script src="<?= $extraScript ?>" defer></script>
<?php endforeach; ?>
</body>
</html>
