function animateCount(id, target, duration) {
  const element = document.getElementById(id);
  const start = 0;
  const increment = target / (duration / 50);

  let current = start;
  const interval = setInterval(() => {
    current += increment;
    if (current >= target) {
      current = target;
      clearInterval(interval);
    }
    element.textContent = Math.floor(current);
  }, 10);
}

document.addEventListener("DOMContentLoaded", () => {
  animateCount(
    "registrants-count",
    parseInt(document.getElementById("registrants-count").dataset.target),
    2000
  );
  animateCount(
    "bases-count",
    parseInt(document.getElementById("bases-count").dataset.target),
    2000
  );
});
