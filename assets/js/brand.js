(function () {
  var header = document.querySelector('.site-header');
  var menu = document.querySelector('.menu-button');
  var nav = document.querySelector('.nav-links');

  function updateHeader() {
    if (header) header.classList.toggle('scrolled', window.scrollY > 18);
  }
  updateHeader();
  window.addEventListener('scroll', updateHeader, { passive: true });

  if (menu && nav) {
    menu.addEventListener('click', function () {
      var open = nav.classList.toggle('open');
      menu.setAttribute('aria-expanded', String(open));
    });
  }

  document.querySelectorAll('[data-password-toggle]').forEach(function (button) {
    button.addEventListener('click', function () {
      var input = document.getElementById(button.getAttribute('data-password-toggle'));
      if (!input) return;
      var show = input.type === 'password';
      input.type = show ? 'text' : 'password';
      button.setAttribute('aria-label', show ? 'Hide password' : 'Show password');
      button.innerHTML = '<i class="bx ' + (show ? 'bx-hide' : 'bx-show') + '"></i>';
    });
  });
})();
