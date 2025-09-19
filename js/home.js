function initGallery(postElement) {
    const gallery = postElement.querySelector('.post__gallery');
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
    updateGallery();
}

function initMoreBtn(postElement) {
    const content = postElement.querySelector('.post__content');
    const moreBtn = postElement.querySelector('.post__more-button');

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
}

function initLikes(postElement) {
    const button = postElement.querySelector('.post__likes');

    button.addEventListener('click', async () => {
        const postId = button.dataset.postId;
        const likesScore = button.querySelector('.likes__score');
        const errorSpan = postElement.querySelector('.likes__errors');
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
            console.error('Ошибка при попытке поставить лайк', err);
            errorSpan.textContent = 'Сервер недоступен. Попробуйте позже';
            errorSpan.style.display = '';
        }
    });
}

function initModalWindow(postElement) {
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

    postElement.querySelectorAll('.gallery__image').forEach(img => {
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
    
    if (!modal.dataset.initialized) {
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
        modal.dataset.initialized = "true"; // чтобы не дублировать слушатели
    } 
}

async function loadPosts() {
    try {
        const response = await fetch('api.php?act=render');
        const data = await response.json();

        if (!data.posts) return;

        data.posts.forEach(post => {
            renderPost(post);
        });

    } catch (error) {
        console.error('Ошибка загрузки постов:', error);
    }
}

function showData(date) {
    const timestamp = new Date(date).getTime(); // миллисекунды
    const currTime = Date.now();
    const diff = (currTime - timestamp) / 1000;

    if (diff < 60) {
        return 'только что';
    } else if (diff < 3600) {
        const minutes = Math.floor(diff / 60);
        return `${minutes} минут назад`;
    } else if (diff < 86400) {
        const hours = Math.floor(diff / 3600);
        return `${hours} часов назад`;
    } else {
        const currDate = new Date(timestamp);
        return currDate.toLocaleString('ru-RU', {
            day: '2-digit',
            month: '2-digit',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        }).replace(',', ' ');
    }
}

function renderPost(post) {
    const postsScrollContainer = document.querySelector('.scroll');

    const postContainer = document.createElement('div');
    postContainer.className = 'post';

        // Заголовок поста
        const postHead = document.createElement('div');
        postHead.className = 'head post__head';

            const headLink  = document.createElement('a');
            headLink.className = 'head__avatar-link';
            headLink.href = `profile.php?id=${post.userId}`;

            //Аватар и имя автора
            if (post.userId) {
                const userAvatar = document.createElement('div');
                userAvatar.className = 'head__avatar';

                    const userAvImg = document.createElement('img');
                    userAvImg.className = 'head__avatar-image';
                    userAvImg.src = `images/${post.avatar}`;
                    userAvImg.alt = 'Аватар';
                    userAvImg.title = 'Аватар';
                    userAvatar.appendChild(userAvImg);

                headLink.appendChild(userAvatar);

                const userName = document.createElement('div');
                userName.className = 'head__name';
                userName.textContent = `${post.name} ${post.surname}`;
                headLink.appendChild(userName);
            } else {
                const defAvatar = document.createElement('div');
                defAvatar.className = 'head__avatar';

                    const avImg = document.createElement('img');
                    avImg.src = 'images/default-avatar.png';
                    avImg.alt = 'Аватар';
                    avImg.title = 'Аватар';
                    defAvatar.appendChild(avImg);

                headLink.appendChild(defAvatar);

                const defName = document.createElement('div');
                defName.className = 'head__name';
                defName.textContent = 'Удаленный пользователь';
                headLink.appendChild(defName);
            }

            postHead.appendChild(headLink);

        if (currentUserId && currentUserId === post.userId) {
            // Кнопка редактировать пост
            const editBtn = document.createElement('a');
            editBtn.className = 'head__edit-button';
            editBtn.href = `create_post.php?id=${post.post_id}`;

                const editBtnImg = document.createElement('img');
                editBtnImg.src = 'images/write.svg';
                editBtnImg.alt = 'Редактировать';
                editBtnImg.title = 'Редактировать';
                editBtn.appendChild(editBtnImg);

            postHead.appendChild(editBtn);

            // и кнопка удалить пост
            const deleteBtn = document.createElement('button');
            deleteBtn.className = 'head__delete-button';

                const deleteBtnImg = document.createElement('img');
                deleteBtnImg.src = 'images/delete-grey.svg';
                deleteBtnImg.alt = 'Удалить';
                deleteBtnImg.title = 'Удалить';
                deleteBtn.appendChild(deleteBtnImg);

            postHead.appendChild(deleteBtn);
        }
        
        postContainer.appendChild(postHead);

        // Галерея
        if (post.images && post.images.length) {
            const postGallery = document.createElement('div');
            postGallery.className = 'post__gallery modal__gallery';

                // вывод картинки
                const galleryTrack = document.createElement('div');
                galleryTrack.className = 'gallery__track';

                post.images.forEach(image => {
                    const galleryImg = document.createElement('img');
                    galleryImg.className = 'gallery__image';
                    galleryImg.src = `images/${image}`;
                    galleryImg.alt = 'Пост-картинка';
                    galleryImg.title = 'Пост-картинка';
                    galleryTrack.appendChild(galleryImg);
                });    
                
                postGallery.appendChild(galleryTrack);

            // индикатор количества изображений и кнопки их переключения
            if (post.images.length > 1) {
                const galleryIndicator = document.createElement('div');
                galleryIndicator.className = 'gallery__indicator';
                galleryIndicator.textContent = `1/${post.images.length}`;
                postGallery.appendChild(galleryIndicator);

                const nextBtn = document.createElement('button');
                nextBtn.className = 'gallery__next-image';
                
                    const nextBtnImg = document.createElement('img');
                    nextBtnImg.src = 'images/Arrow-right 10.svg';
                    nextBtnImg.title = 'Листать';
                    nextBtnImg.alt = 'Листать';
                    nextBtn.appendChild(nextBtnImg);

                postGallery.appendChild(nextBtn);

                const lastBtn = document.createElement('button');
                lastBtn.className = 'gallery__last-image';
                
                    const lastBtnImg = document.createElement('img');
                    lastBtnImg.src = 'images/Arrow-left 10.svg';
                    lastBtnImg.title = 'Листать';
                    lastBtnImg.alt = 'Листать';
                    lastBtn.appendChild(lastBtnImg);

                postGallery.appendChild(lastBtn);
            }

            postContainer.appendChild(postGallery);
        }

        // Лайки
        const postLikes = document.createElement('div');
        postLikes.className = 'post__like-container';

            const postLikeButton = document.createElement('button'); 
            postLikeButton.className = 'post__likes';
            if (post.liked) {
                postLikeButton.classList.add('liked');
            }
            postLikeButton.type = 'button';
            postLikeButton.setAttribute('data-post-id', post.post_id);
            
                const postLikeImage = document.createElement('img');
                postLikeImage.className = 'likes__heart-image';
                postLikeImage.src = 'images/heart.png';
                postLikeImage.title = 'Реакции';
                postLikeImage.alt = 'Реакции';
                postLikeButton.appendChild(postLikeImage);

                const postLikesScore = document.createElement('span');
                postLikesScore.className = 'likes__score';
                postLikesScore.textContent = post.likes;
                postLikeButton.appendChild(postLikesScore);

            postLikes.appendChild(postLikeButton);

            const postLikeError = document.createElement('span');
            postLikeError.className = 'likes__errors';
            postLikeError.textContent = 'Ошибка!';
            postLikes.appendChild(postLikeError);

        postContainer.appendChild(postLikes);

        // Контент
        const postContent = document.createElement('div');
        postContent.className = 'post__text';

            const postContentText = document.createElement('p');
            postContentText.className = 'post__content';
            postContentText.textContent = post.content;
            postContent.appendChild(postContentText);

            const postContentButton = document.createElement('button');
            postContentButton.className = 'post__more-button';
            postContentButton.textContent = 'еще';
            postContent.appendChild(postContentButton);

        postContainer.appendChild(postContent);

        // Дата поста
        const postDate = document.createElement('span');
        postDate.className = 'post__date';
        postDate.textContent = showData(post.created_at);
        postContainer.appendChild(postDate);
    
    postsScrollContainer.appendChild(postContainer); 

    initGallery(postContainer);
    initLikes(postContainer);
    initMoreBtn(postContainer);
    initModalWindow(postContainer);
}  

loadPosts();


