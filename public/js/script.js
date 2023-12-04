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

function edit(mode) {
  if (mode == "update") {
    document.getElementById("mode").value = "update";
    document.getElementById("form").submit();
  } else {
    Swal.fire({
      icon: "warning",
      title: "Konfirmasi",
      text: "Yakin akan dihapus?",
      showCancelButton: true,
      confirmButtonText: "Ya",
      cancelButtonText: "Tidak",
      reverseButtons: true,
    }).then((result) => {
      if (result.isConfirmed) {
        document.getElementById("mode").value = "delete";
        document.getElementById("form").submit();
      } else if (result.dismiss === Swal.DismissReason.cancel) {
        return false;
      }
    });
  }
}
