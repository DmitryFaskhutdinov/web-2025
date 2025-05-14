

function initPosts() {
    const posts = document.querySelector('#posts');
    fetch('https://dummyjson.com/posts?limit=5')
        .then(res => res.json())
        .then(posts => {
            posts.posts.forEach(post => {
                console.log(post.title);
                const postBlock = document.createElement('div');
                postBlock.classList.add('post');

                const postTitle = document.createElement('h2');
                postTitle.textContent = post.title;
                postBlock.appendChild(postTitle);

                const postDescription = document.createElement('p');
                postDescription.textContent = post.body;
                postBlock.appendChild(postDescription);

                posts.appendChild(postBlock);
            })
        });
}

async function initAddPost() {
    const res = await fetch('https://dummyjson.com/posts/add', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            title: '',
            userId: 1,
        }),
    });
    const post = await res.json();

    renderPost({
        title: post.title,
        body: post.body,
    });
}


function renderPost() {
    const posts = document.querySelector('#posts');
    const postBlock = document.createElement('div');
    postBlock.classList.add('post');

    const postTitle = document.createElement('h2');
    postTitle.textContent = post.title;
    postBlock.appendChild(postTitle);

    const postDescription = document.createElement('p');
    postDescription.textContent = post.body;
    postBlock.appendChild(postDescription);

    const postLikeButton = document.ctreateElement('button')
    postLikeButton.textContent = postLikeCount;
    postLikeButton.addEventListener('click', event => {
        const likes = +event.target.innerText;
        const newLikesCount = likes + 1;
        event.target.innerText = newLikesCount;
    });
    postBlock.appendChild(postLikeButton);

    posts.appendChild(postBlock);
}

function initAddPost() {
    const addPostButton = document.querySelector('#addPostButton');
    addPostButton.addEventListener('click', () => {
        const title = document.querySelector('#postTitle');
        const description = document.querySelector('#postDescription');
        console.log(title.value);
        console.log(description.value)


        addPost(title.value);
    });
}

getPosts();
initAddPost();
//initGetOists();
//initAddPost();

