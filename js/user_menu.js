document.addEventListener("DOMContentLoaded", () => {
  const avatar = document.getElementById("avatar");
  const menu = document.getElementById("menu");

  if (avatar && menu) {
    avatar.addEventListener("click", (event) => {
      event.stopPropagation();
      menu.classList.toggle("show");
    });

    // Fecha se clicar fora
    window.addEventListener("click", () => {
      if (menu.classList.contains("show")) {
        menu.classList.remove("show");
      }
    });
  }
});
