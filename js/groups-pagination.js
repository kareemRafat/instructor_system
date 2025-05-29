// Function to update query strings and reload the page
    function updateQueryString(key, value) {
      const url = new URL(window.location);
      if (value) {
        url.searchParams.set(key, value); // Set or update the query param
      } else {
        url.searchParams.delete(key); // Remove param if value is empty
      }
      // Reload the page with the updated URL
      window.location = url;
    }

    // Function to handle pagination
    function goToPage(page) {
      updateQueryString('page', page);
    }

    // Event listeners for inputs
    branchSelect.addEventListener('change', (e) => {
      updateQueryString('branch', e.target.value);
    });

    const searchInput = document.getElementById("table-search");
    searchInput.addEventListener('input', (e) => {
      // Optional: Debounce to avoid reloading on every keystroke
      clearTimeout(window.searchTimeout); // Clear previous timeout
      window.searchTimeout = setTimeout(() => {
        // updateQueryString('search', e.target.value);
      }, 1000); // 500ms delay to allow typing
    });

    const pageNum = document.querySelectorAll(".page-num");
    pageNum.forEach((page) => {
        page.addEventListener('click', (e) => {
            e.preventDefault();            
            const page = e.target.dataset.page;          
            if (page) {
                goToPage(page);
            }
        })
    })

    // Initialize: Load query params on page load
    window.addEventListener('load', () => {
      const params = new URLSearchParams(window.location.search);
      const branch = params.get('branch') || '';
    //   const search = params.get('search') || '';
      const page = params.get('page') || '1';

      // Set input values based on query params
      branchSelect.value = branch;
    //   searchInput.value = search;
      console.log(`Loaded with: branch=${branch}, page=${page}`);
    });