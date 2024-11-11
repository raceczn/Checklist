document.addEventListener('DOMContentLoaded', () => {
  let intro = document.querySelector('.intro');
  let logo = document.querySelector('.logo-header');
  let logoSpan = document.querySelectorAll('.logo');

  console.log("DOM fully loaded and parsed");

  setTimeout(() => {
      logoSpan.forEach((span, idx) => {
          setTimeout(() => {
              console.log(`Activating span ${idx}`);
              span.classList.add('active');
          }, (idx + 1) * 400);
      });

      setTimeout(() => {
          logoSpan.forEach((span, idx) => {
              setTimeout(() => {
                  console.log(`Fading span ${idx}`);
                  span.classList.remove('active');
                  span.classList.add('fade');
              }, (idx + 1) * 50);
          });
      }, 2000);

  }, 2000);

  setTimeout(() => {
      console.log("Moving intro up and redirecting to profile page");
      intro.style.top = '-100vh';
      setTimeout(() => {
          window.location.href = 'http://localhost/studentchecklist/profile.php';
      }, 1000); // Adjust this timing to match the duration of the intro transition
  }, 4000); // Adjust the timing if needed

});
