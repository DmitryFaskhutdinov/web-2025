aaaa0.
//галерея сохранения поста
document.addEventListener("DOMContentLoaded", function () {
  const gallery = document.querySelector('.form__gallery');
  if (!gallery) return;

  const track = gallery.querySelector('.frame__track');

  const nextButton = gallery.querySelector('.form__next-image');
  const lastButton = gallery.querySelector('.form__last-image');
  const frameButton = gallery.querySelector('.frame__button'); 
  const framePlaceholder = gallery.querySelector('.frame__placeholder');

  const fileInput = document.getElementById('oneFileInput');
  const multFileInput = document.getElementById('multFileInput');
  
  let currentIndex = 0;

  function updateGallery() {
    const images = track.querySelectorAll('img');
    if (images.length === 0) {
      nextButton.style.display = 'none';
      lastButton.style.display = 'none';
      frameButton.style.display = '';
      framePlaceholder.style.display = '';
      return;
    }

    const imageWidth = images[0].clientWidth;
    track.style.transform = `translateX(${-currentIndex * imageWidth}px)`;

    if (images.length <= 1) {
      nextButton.style.display = 'none';
      lastButton.style.display = 'none';
    } else {
      nextButton.style.display = '';
      lastButton.style.display = '';
    }

    if (images.length >= 1) {
      frameButton.style.display = 'none';
      framePlaceholder.style.display = 'none';
    } else {
      frameButton.style.display = '';
      framePlaceholder.style.display = '';
    }
  }

  function addImages(files) {
    Array.from(files).forEach(file => {
        const img = document.createElement('img');
        img.src = URL.createObjectURL(file); // создаём временный URL для файла
        img.alt = 'Выбранное фото';
        track.appendChild(img);
    });

    const images = track.querySelectorAll('img');
    currentIndex = images.length - 1;
    updateGallery();
  }

  fileInput.addEventListener('change', (e) => addImages(e.target.files));
  multFileInput.addEventListener('change', (e) => addImages(e.target.files));

  if (nextButton) {
      nextButton.addEventListener('click', () => {
          const images = track.querySelectorAll('img');
          currentIndex = (currentIndex + 1) % images.length;
          updateGallery();
      });
  }
  if (lastButton) {
      lastButton.addEventListener('click', () => {
          const images = track.querySelectorAll('img');
          currentIndex = (currentIndex - 1 + images.length) % images.length;
          updateGallery();
      });
  }
  updateGallery();

// Задизейбливание кнопки
  const submitButton = document.querySelector(".frame__submit");
  const textArea = document.querySelector("textarea[name='content']");
  const oneFileInput = document.getElementById("oneFileInput");
  const multiFileInput = document.getElementById("multFileInput");

  const errorBox = document.querySelector(".frame__error-box");
  const succesBox = document.querySelector(".post-saved");
  const formBox = document.querySelector(".form");

  function updateButtonState() {
    const hasText = textArea.value.trim() !== "";
    const hasOneFile = oneFileInput.files.length > 0;
    const hasMultiFile = multiFileInput.files.length > 0;
    const hasExistingImages = track.querySelectorAll('img').length > 0;

    submitButton.disabled = !(hasText && (hasOneFile || hasMultiFile || hasExistingImages));
  }

  textArea.addEventListener("input", updateButtonState);
  oneFileInput.addEventListener("change", updateButtonState);
  multiFileInput.addEventListener("change", updateButtonState);

  updateButtonState();

  submitButton.addEventListener("click", async (event) => {
    event.preventDefault();

    const formData = new FormData();
    const postId = formBox.dataset.postId;
    const postData = {
      content: textArea.value.trim(),
    };

    if (postId) {
      postData.post_id = postId;
    }
    
    formData.append("json", JSON.stringify(postData));

    Array.from(oneFileInput.files).forEach(file => formData.append("image[]", file));
    Array.from(multiFileInput.files).forEach(file => formData.append("image[]", file));

    const act = formBox.action.split("act=")[1];

    try {

      console.log("FormData содержимое:");
      for (let [key, value] of formData.entries()) {
          console.log(key, value);
      }
      
      const response = await fetch(`api.php?act=${act}`, {
        method: "POST",
        body: formData
      });

      if (!response.ok) {
        const text = await response.text();
        console.error("Ошибка от сервера:", text);
        throw new Error("Сервер вернул ошибку " + response.status);
      }

      const result = await response.json();

      if (result.status === "ok") {
        formBox.style.display = "none";
        succesBox.style.display = "flex";
        errorBox.style.display = "none"; 
        textArea.value = "";
        oneFileInput.value = "";
        multiFileInput.value = "";
        updateButtonState();
      } else {
        errorBox.textContent = ("Ошибка: " + result.message);
        errorBox.style.display = "block";
      }
    } catch (error) {
      errorBox.textContent = "Произошла ошибка при отправке поста.";
      errorBox.style.display = "block";
    }
  });
});

