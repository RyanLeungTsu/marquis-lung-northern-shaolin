(function () {
  const sections = ["home", "history", "team"];
  let currentIndex = 0;

  const container = document.getElementById("scroll-arrow-container");
  if (!container) return;

  const scrollButton = document.createElement("button");
  scrollButton.id = "scroll-arrow-button"; 
  scrollButton.className = "scroll-arrow-button"; 
  scrollButton.textContent = "↓";
  container.appendChild(scrollButton);

  function updateButtonIcon() {
    scrollButton.textContent = currentIndex === sections.length - 1 ? "↑" : "↓";
  }

  function updateSectionIndex() {
    const scrollY = window.scrollY;
    const atBottom = window.innerHeight + scrollY >= document.body.scrollHeight - 10;

    if (atBottom) {
      currentIndex = sections.length - 1;
      updateButtonIcon();
      return;
    }

    for (let i = 0; i < sections.length; i++) {
      const current = document.getElementById(sections[i]);
      const next = document.getElementById(sections[i + 1]);
      if (!current) continue;

      const top = current.offsetTop;
      const bottom = next ? next.offsetTop : Infinity;

      if (scrollY >= top - 50 && scrollY < bottom - 50) {
        currentIndex = i;
        updateButtonIcon();
        break;
      }
    }
  }

  function scrollToSection() {
    if (currentIndex === sections.length - 1) {
      window.scrollTo({ top: 0, behavior: "smooth" });
    } else {
      const next = document.getElementById(sections[currentIndex + 1]);
      if (next) next.scrollIntoView({ behavior: "smooth" });
    }
  }

  scrollButton.addEventListener("click", scrollToSection);
  window.addEventListener("scroll", updateSectionIndex);
  updateSectionIndex();
})();