<!-- modal.php -->
<!-- ✅ Load reCAPTCHA script once in your layout -->
<script src="https://www.google.com/recaptcha/api.js" async defer></script>

<!-- ✅ Bootstrap Modal Form -->
<form id="customerForm" method="POST">
  <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">

        <div class="modal-header">
          <h1 class="modal-title fs-5" id="ModalLabel">Request Quote</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">
          <!-- ✅ Message box -->
          <div id="formAlert" class="alert d-none" role="alert"></div>

          <div class="mb-3">
            <label for="name" class="form-label">Name:</label>
            <input type="text" class="form-control" id="name" name="name" placeholder="Enter your name" required>
          </div>

          <div class="mb-3">
            <label for="location" class="form-label">Location:</label>
            <input type="text" class="form-control" id="location" name="location" placeholder="Enter your location" required>
          </div>

          <div class="mb-3">
            <label for="email" class="form-label">Email:</label>
            <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" required>
          </div>

          <div class="mb-3">
            <label for="phoneNumber" class="form-label">Phone Number:</label>
            <input type="tel" class="form-control" id="phoneNumber" name="phoneNumber" placeholder="Enter your phone number" required>
          </div>

          <div class="mb-3">
            <label for="serviceOption" class="form-label">Service Option:</label>
            <select class="form-select" id="serviceOption" name="serviceOption" required>
              <option value="" selected disabled>Select a service option</option>
              <option value="Web Design, Hosting & Digital Marketing">Web Design, Hosting & Digital Marketing</option>
              <option value="Networking & Digital Security">Networking & Digital Security</option>
              <option value="Graphic Design & Publish Printing">Graphic Design & Publish Printing</option>
              <option value="ICT Hardware Supply, Installation & Maintenance">ICT Hardware Supply, Installation & Maintenance</option>
              <option value="Other">Other</option>
            </select>
          </div>

          <div class="mb-3">
            <label for="message" class="form-label">Additional Message:</label>
            <textarea class="form-control" id="message" name="message" placeholder="Enter your message"></textarea>
          </div>

          <!-- ✅ Google reCAPTCHA -->
          <div class="mb-3 text-center">
            <div class="g-recaptcha" data-sitekey="6LctQoErAAAAADObMskiBehEEH0JuWs72jrIf8df"></div>
          </div>
        </div>

        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Submit</button>
        </div>

      </div>
    </div>
  </div>
</form>

<!-- ✅ AJAX Submission Script -->
<script>
document.addEventListener('DOMContentLoaded', function () {
  const form = document.getElementById('customerForm');
  const alertBox = document.getElementById('formAlert');

  form.addEventListener('submit', function (e) {
    e.preventDefault();

    const formData = new FormData(form);

    fetch('quote_handler.php', {
      method: 'POST',
      body: formData
    })
    .then(res => res.text())
    .then(response => {
      alertBox.classList.remove('d-none', 'alert-success', 'alert-danger');

      if (response.toLowerCase().includes("thank you")) {
        alertBox.classList.add('alert-success');
        alertBox.textContent = response;

        // ✅ Close modal after 2 seconds
        setTimeout(() => {
          const modal = bootstrap.Modal.getInstance(document.getElementById('exampleModal'));
          modal.hide();
          form.reset();
          grecaptcha.reset();
          alertBox.classList.add('d-none');
        }, 2000);

      } else {
        alertBox.classList.add('alert-danger');
        alertBox.textContent = response;
        grecaptcha.reset(); // allow retry
      }
    })
    .catch(err => {
      alertBox.classList.remove('d-none');
      alertBox.classList.add('alert-danger');
      alertBox.textContent = "Something went wrong. Please try again.";
      grecaptcha.reset();
    });
  });
});
</script>