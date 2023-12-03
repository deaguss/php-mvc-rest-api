function toggleSearch() {
  var searchBox = document.querySelector(".search-box");
  searchBox.classList.toggle("active");
}

function performSearch() {
  var input = document.getElementById("searchInput");
  var filter = input.value.toUpperCase();
  var navItems = document.querySelectorAll(
    ".main-menu ul li:not(:first-child)"
  );

  navItems.forEach(function (item) {
    var text = item.textContent || item.innerText;
    var shouldShow = text.toUpperCase().indexOf(filter) > -1;

    if (shouldShow) {
      item.style.display = "";
    } else {
      item.style.display = "none";
    }
  });
}
