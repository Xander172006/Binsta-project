const searchForm = document.getElementById('SearchForm');
const input = document.getElementById('Searchbar');
const output = document.getElementById('output');

input.addEventListener("input", () => {
    output.textContent = input.value;
});

const expand = document.getElementById('expand');

expand.addEventListener('click', function() {
    if (searchForm.style.display === 'none') {
      searchForm.style.display = 'block';
    } else {
      searchForm.style.display = 'none';
    }
});