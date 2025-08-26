document.addEventListener("DOMContentLoaded", function () {
  const parentLinks = document.querySelectorAll(
    ".wp-block-navigation__responsive-container .has-child > a"
  );

  parentLinks.forEach((link) => {
    link.addEventListener("click", function (e) {
      e.preventDefault();

      const parentItem = this.closest(".wp-block-navigation-item");

      parentItem.classList.toggle("open");
    });
  });
});