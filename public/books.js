document.addEventListener("DOMContentLoaded", () => {
  const booksGrid = document.getElementById("booksGrid");
  const pagination = document.getElementById("pagination");
  const searchBox = document.getElementById("searchBox");
  const searchBtn = document.getElementById("searchBtn");

  let currentPage = 1;
  let currentQuery = "";

  // Fetch books from API
  async function fetchBooks(page = 1, q = "") {
    booksGrid.innerHTML = "Loading...";
    pagination.innerHTML = "";

    try {
      const res = await fetch(`/api/books?page=${page}&q=${encodeURIComponent(q)}`);
      const data = await res.json();

      if (!data.data || data.data.length === 0) {
        booksGrid.innerHTML = `<p>No books found.</p>`;
        return;
      }

      // Render books
      booksGrid.innerHTML = data.data
        .map(
          (book) => `
          <a href="/books/${book.id}" class="bg-white rounded-lg shadow p-3 block">
            <img src="${book.cover_url}" class="w-full h-48 object-cover rounded" alt="cover">
            <div class="mt-2 font-semibold">${book.title}</div>
            <div class="text-sm text-gray-600">${book.author?.name ?? "Unknown"}</div>
          </a>
        `
        )
        .join("");

      // Render pagination
      pagination.innerHTML = "";
      if (data.prev_page_url) {
        let prev = document.createElement("button");
        prev.textContent = "Prev";
        prev.className = "px-3 py-1 bg-gray-200 rounded";
        prev.onclick = () => fetchBooks(page - 1, currentQuery);
        pagination.appendChild(prev);
      }
      if (data.next_page_url) {
        let next = document.createElement("button");
        next.textContent = "Next";
        next.className = "px-3 py-1 bg-gray-200 rounded";
        next.onclick = () => fetchBooks(page + 1, currentQuery);
        pagination.appendChild(next);
      }
    } catch (err) {
      booksGrid.innerHTML = `<p class="text-red-600">Error loading books.</p>`;
      console.error(err);
    }
  }

  // Search handler
  searchBtn.addEventListener("click", () => {
    currentQuery = searchBox.value;
    fetchBooks(1, currentQuery);
  });

  // Initial load
  fetchBooks();
});
