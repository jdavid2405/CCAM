document.getElementById('signupForm').addEventListener('submit', function (e) {
  const firstName = document.getElementById('firstName');
  const lastName = document.getElementById('lastName');
  const email = document.getElementById('email');
  const contact = document.getElementById('contact');
  const password = document.getElementById('password');
  const confirmPassword = document.getElementById('confirmPassword');

  let isValid = true;

  [firstName, lastName, email, contact, password, confirmPassword].forEach(input => {
    if (input.value.trim() === '') {
      input.classList.add('is-invalid');
      isValid = false;
    } else {
      input.classList.remove('is-invalid');
    }
  });

  if (password.value !== confirmPassword.value) {
    confirmPassword.classList.add('is-invalid');
    isValid = false;
  } else {
    confirmPassword.classList.remove('is-invalid');
  }

  if (!isValid) {
    e.preventDefault(); // Prevent form submission only if invalid
  }
});
