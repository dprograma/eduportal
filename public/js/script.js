const countElements = document.querySelectorAll('.count');

countElements.forEach((element) => {
    const target = +element.innerText;
    let count = 0;

    const updateCount = () => {
        const speed = 2000; 
        const increment = target / speed;

        if (count < target) {
            count += increment;
            element.innerText = Math.ceil(count);
            setTimeout(updateCount, 1);
        } else {
            element.innerText = target;
        }
    };

    updateCount();
});

document.addEventListener("DOMContentLoaded", function() {
  const contactForm = document.querySelector(".container-form");

  contactForm.addEventListener("submit", function(event) {
      const nameInput = document.getElementById("name");
      const emailInput = document.getElementById("email");
      const messageInput = document.getElementById("message");

      const nameError = document.querySelector(".name-error");
      const emailError = document.querySelector(".email-error");
      const messageError = document.querySelector(".message-error");

      nameError.textContent = "";
      emailError.textContent = "";
      messageError.textContent = "";

      if (nameInput.value.trim() === "") {
          nameError.textContent = "Name is required";
          nameError.classList.add("error"); // Apply error class to the error element
          nameInput.classList.add("error");
          contactForm.classList.add("error"); 
          event.preventDefault();
      }

      if (emailInput.value.trim() === "") {
          emailError.textContent = "Email is required";
          emailError.classList.add("error"); // Apply error class to the error element
          emailInput.classList.add("error");
          contactForm.classList.add("error");
          event.preventDefault();
      } else if (!isValidEmail(emailInput.value.trim())) {
          emailError.textContent = "Invalid email address";
          emailError.classList.add("error"); // Apply error class to the error element
          emailInput.classList.add("error");
          contactForm.classList.add("error"); 
          event.preventDefault();
      }

      if (messageInput.value.trim() === "") {
          messageError.textContent = "Message is required";
          messageError.classList.add("error"); // Apply error class to the error element
          messageInput.classList.add("error");
          contactForm.classList.add("error"); 
          event.preventDefault();
      }
  });

  function isValidEmail(email) {
      const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      return emailRegex.test(email);
  }

  const formInputs = document.querySelectorAll("input, textarea");
  formInputs.forEach(input => {
      input.addEventListener("focus", function() {
          this.classList.remove("error");
          // Also remove the error class from the corresponding error element
          if (this.id === "name") {
              document.querySelector(".name-error").classList.remove("error");
          } else if (this.id === "email") {
              document.querySelector(".email-error").classList.remove("error");
          } else if (this.id === "message") {
              document.querySelector(".message-error").classList.remove("error");
          }
          contactForm.classList.remove("error"); 
      });
  });
});


document.querySelectorAll('a[href^="#"]').forEach(anchor => {
  anchor.addEventListener('click', function(e) {
      e.preventDefault();

      const targetId = this.getAttribute('href').substring(1);
      const targetElement = document.getElementById(targetId);

      if (targetElement) {
          window.scrollTo({
              top: targetElement.offsetTop,
              behavior: 'smooth'
          });
      }
  });
});

