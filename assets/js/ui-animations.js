document.addEventListener("DOMContentLoaded", function () {
  const revealTargets = document.querySelectorAll(
    "section, .grid > div, .space-y-6 > div, .space-y-4 > div, table, form, header",
  );

  revealTargets.forEach((el, index) => {
    if (!el.hasAttribute("data-reveal")) {
      el.setAttribute("data-reveal", "up");
    }
    el.style.transitionDelay = `${Math.min(index * 35, 260)}ms`;
  });

  const observer = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          entry.target.classList.add("is-visible");
          observer.unobserve(entry.target);
        }
      });
    },
    { threshold: 0.14, rootMargin: "0px 0px -40px 0px" },
  );

  revealTargets.forEach((el) => observer.observe(el));
});
