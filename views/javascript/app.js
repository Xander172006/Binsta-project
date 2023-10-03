function previewImage() {
  const beforeUpload = document.getElementById('beforeUpload');
  const preview = document.getElementById('preview');
  const fileInput = document.getElementById('fileImg');
  const icon = document.querySelector('.create_icon');
  const container = document.querySelector('.snippet_element');
  const file = fileInput.files[0];
  const reader = new FileReader();

  reader.onloadend = function() {
    container.style.width = '50%';
    beforeUpload.style.display = 'block';
    preview.src = reader.result;
    icon.style.display = 'none';
    preview.style.display = 'block';
    preview.style.height = '24rem';
    preview.style.backgroundColor = 'white';
    preview.style.width = '30rem';
    fileInput.style.display = 'none';
  }

  if (file) {
    reader.readAsDataURL(file);
  } else {
    preview.src = "";
    preview.style.display = 'none';
  }
}

const likebutton = document.querySelector('.LikeButton');
const likeIcon = document.getElementById('Heart');

likebutton.addEventListener('click', () => {
  likebutton.style.color = 'red';
  likeIcon.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-heart-fill" viewBox="0 0 16 16">
  <path fill-rule="evenodd" d="M8 1.314C12.438-3.248 23.534 4.735 8 15-7.534 4.736 3.562-3.248 8 1.314z"/>
  </svg>`
})
