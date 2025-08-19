// галерея поста
document.querySelectorAll('.post__gallery').forEach(gallery => {
  const track = gallery.querySelector('.gallery__track');
  const images = gallery.querySelectorAll('.gallery__image');
  if (images.length === 0) return;

  const nextButton = gallery.querySelector('.gallery__next-image');
  const lastButton = gallery.querySelector('.gallery__last-image');
  const indicator = gallery.querySelector('.gallery__indicator');
    
  const imageWidth = images[0].clientWidth; 

  let currentIndex = 0;

  function updateGallery() {
      track.style.transform = `translateX(${-currentIndex * imageWidth}px)`;
      if (indicator) indicator.textContent = `${currentIndex + 1}/${images.length}`;
  }

  if (nextButton) {
      nextButton.addEventListener('click', () => {
          currentIndex = (currentIndex + 1) % images.length;
          updateGallery();
      });
  }
  if (lastButton) {
      lastButton.addEventListener('click', () => {
          currentIndex = (currentIndex - 1 + images.length) % images.length;
          updateGallery();
      });
  }
});


// Модальное окно
document.addEventListener('DOMContentLoaded', () => {
  const modal = document.querySelector('.modal');
  const modalClose = modal.querySelector('.modal__close');
  const modalNextBtn = modal.querySelector('.modal__next-image');
  const modalPrevBtn = modal.querySelector('.modal__prev-image');
  const modalIndicator = modal.querySelector('.modal__indicator');
  const modalTrack = modal.querySelector('.modal__track');

  let currentIndex = 0;
  let modalImages = [];

  function updateModalGallery() {
    const viewportWidth = modal.querySelector('.modal__viewport').clientWidth;
    modalTrack.style.transform = `translateX(${-currentIndex * viewportWidth}px)`;
    modalIndicator.textContent = `${currentIndex + 1} из ${modalImages.length}`;

    if (modalImages.length <= 1) {
      modalNextBtn.style.display = 'none';
      modalPrevBtn.style.display = 'none';
      modalIndicator.style.display = 'none';
    } else {
      modalNextBtn.style.display = '';
      modalPrevBtn.style.display = '';
      modalIndicator.style.display = '';
    }
  }

  document.querySelectorAll('.gallery__image').forEach(img => {
    img.addEventListener('click', () => {
      const gallery = img.closest('.post__gallery');
      modalImages = Array.from(gallery.querySelectorAll('.gallery__image'));

      modalTrack.innerHTML = '';
      modalImages.forEach(gImg => {
        const clone = gImg.cloneNode();
        clone.classList.add('modal__image');
        modalTrack.appendChild(clone);
      });

      currentIndex = modalImages.indexOf(img);
      updateModalGallery();
      modal.classList.add('modal_open');
    });
  });

  modalNextBtn.addEventListener('click', () => {
    currentIndex = (currentIndex + 1) % modalImages.length;
    updateModalGallery()
  });

  modalPrevBtn.addEventListener('click', () => {
    currentIndex = (currentIndex - 1 + modalImages.length) % modalImages.length;
    updateModalGallery()
  });

  modalClose.addEventListener('click', () => {
    modal.classList.remove('modal_open');
  });

  document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape' && modal.classList.contains('modal_open')) {
      modal.classList.remove('modal_open');
    }
  });  
});

// кнопка еще
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.post').forEach(post => {
        const content = post.querySelector('.post__content');
        const moreBtn = post.querySelector('.post__more-button');

        if (!content || !moreBtn) return;

        const lineHeight = parseFloat(getComputedStyle(content).lineHeight);
        const maxHeight = lineHeight * 2;

        if (content.scrollHeight <= maxHeight) {
            moreBtn.style.display = 'none';
            return;
        }

        moreBtn.addEventListener('click', function () {
            if (content.classList.contains('expanded')) {
                content.classList.remove('expanded');
                moreBtn.textContent = 'ещё';
            } else {
                content.classList.add('expanded');
                moreBtn.textContent = 'свернуть';
            }
        });
    });
});

//обработка лайков
document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('.post__likes').forEach(button => {
    button.addEventListener('click', async () => {
      const postId = button.dataset.postId;
      const likesScore = button.querySelector('.likes__score');
      const errorSpan = document.querySelector('.likes__errors');
      if (!likesScore) return;

      errorSpan.textContent = '';
      errorSpan.style.display = 'none';

      try {
        const response = await fetch('api.php?act=like', {
          method: 'POST',
          headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
          body: `post_id=${encodeURIComponent(postId)}`
        });

        const result = await response.json();
        if (result.status === 'ok') {
          likesScore.textContent = result.likes;
          if (result.liked) {
            button.classList.add('liked');
          } else {
            button.classList.remove('liked');
          }
        } else {
          errorSpan.textContent = 'Не удалось поставить лайк. Попробуйте снова';
          errorSpan.style.display = '';
        }
      } catch (err) {
        errorSpan.textContent = 'Сервер недоступен. Попробуйте позже';
        errorSpan.style.display = '';
      }
    });
  });
});