const loginForm = document.getElementById('loginForm');

  loginForm.addEventListener('submit', function (e) {
    const email = document.getElementById('email');
    const password = document.getElementById('password');
    let valid = true;

    if (email.value.trim() === '') {
      email.classList.add('is-invalid');
      valid = false;
    } else {
      email.classList.remove('is-invalid');
    }

    if (password.value.trim() === '') {
      password.classList.add('is-invalid');
      valid = false;
    } else {
      password.classList.remove('is-invalid');
    }

    if (!valid) {
      form.submit(); // Stop form submission
    }
  });