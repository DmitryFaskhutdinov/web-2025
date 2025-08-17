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
});

// Задизейбливание кнопки и вывод данных поста в консоль
document.addEventListener("DOMContentLoaded", () => {
  const submitButton = document.querySelector(".frame__submit");
  const textArea = document.querySelector("textarea[name='content']");
  const oneFileInput = document.getElementById("oneFileInput");
  const multiFileInput = document.getElementById("multFileInput");

  function updateButtonState() {
    const hasText = textArea.value.trim() !== "";
    const hasOneFile = oneFileInput.files.length > 0;
    const hasMultiFile = multiFileInput.files.length > 0;

    submitButton.disabled = !(hasText && (hasOneFile || hasMultiFile));
  }

  textArea.addEventListener("input", updateButtonState);
  oneFileInput.addEventListener("change", updateButtonState);
  multiFileInput.addEventListener("change", updateButtonState);

  updateButtonState();

  submitButton.addEventListener("click", (event) => {
    event.preventDefault(); // чтобы форма не отправлялась сразу

    console.log("=== Информация о посте ===");
    console.log("Текст:", textArea.value.trim());
    console.log("Файлы (одиночная загрузка):", oneFileInput.files);
    console.log("Файлы (множественная загрузка):", multiFileInput.files);
    console.log("============================");
  });
});