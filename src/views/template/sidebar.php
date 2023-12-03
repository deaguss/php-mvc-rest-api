<nav class="main-menu">
    <ul>
        <li>
            <div class="search-box">
                <button class="btn-search"><i class="fa fa-search" onclick="toggleSearch()"></i></button>
                <input type="text" class="input-search" id="searchInput" placeholder="Type to Search..." onkeyup="performSearch()" placeholder="Type to Search...">
            </div>
        </li>
        <li>
            <a href="<?= BASE_URL ?>">
                <i class="fa fa-home fa-2x"></i>
                <span class="nav-text">
                    Home
                </span>
            </a>

        </li>
        <li class="has-subnav">
            <a href="<?= BASE_URL . '/barang' ?>">
                <i class="fa fa-globe fa-2x"></i>
                <span class="nav-text">
                    Barang
                </span>
            </a>

        </li>
        <li class="has-subnav">
            <a href="<?= BASE_URL . '/kategori' ?>">
                <i class="fa fa-comments fa-2x"></i>
                <span class="nav-text">
                    Kategori
                </span>
            </a>

        </li>

    </ul>
</nav>