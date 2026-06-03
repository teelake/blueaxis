(function () {
  const PROGRESS_TARGET = 72;
  const yearEl = document.getElementById("year");
  const progressFill = document.getElementById("progress-fill");
  const progressValue = document.getElementById("progress-value");
  const progressBar = document.querySelector(".progress__track");
  const form = document.getElementById("notify-form");
  const feedback = document.getElementById("notify-feedback");
  const emailInput = document.getElementById("email");

  if (yearEl) {
    yearEl.textContent = String(new Date().getFullYear());
  }

  function animateProgress() {
    if (!progressFill || !progressValue || !progressBar) return;

    const reducedMotion = window.matchMedia("(prefers-reduced-motion: reduce)").matches;
    const value = PROGRESS_TARGET;

    progressFill.style.width = value + "%";
    progressValue.textContent = value + "%";
    progressBar.setAttribute("aria-valuenow", String(value));

    if (!reducedMotion) {
      requestAnimationFrame(function () {
        progressFill.style.width = "0%";
        requestAnimationFrame(function () {
          progressFill.style.width = value + "%";
        });
      });
    }
  }

  function showFeedback(message, state) {
    if (!feedback) return;
    feedback.textContent = message;
    feedback.hidden = false;
    feedback.dataset.state = state;
  }

  function hideFeedback() {
    if (!feedback) return;
    feedback.hidden = true;
    feedback.textContent = "";
    delete feedback.dataset.state;
  }

  if (form && emailInput) {
    form.addEventListener("submit", function (event) {
      event.preventDefault();
      hideFeedback();

      if (!emailInput.validity.valid) {
        emailInput.focus();
        showFeedback("Please enter a valid email address.", "error");
        return;
      }

      const btn = form.querySelector(".notify__btn");
      if (btn) {
        btn.disabled = true;
        btn.setAttribute("aria-busy", "true");
      }

      window.setTimeout(function () {
        showFeedback("You're on the list. We'll be in touch at launch.", "success");
        form.reset();
        if (btn) {
          btn.disabled = false;
          btn.removeAttribute("aria-busy");
        }
      }, 600);
    });

    emailInput.addEventListener("input", hideFeedback);
  }

  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", animateProgress);
  } else {
    animateProgress();
  }
})();
