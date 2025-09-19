/** 
 * @param {{
 *  title: string,
 *  body: string,
 *  likeCount: number,
 * }} post
*/

function renderPost(post) {
    const posts = document.querySelector('#posts');

    const postBlock = document.createElement('div');
    postBlock.classList.add('post');

    const postTitle = document.createElement('h2');
    postTitle.textContent = post.title;
    postBlock.appendChild(postTitle);

    const postDescription = document.createElement('p');
    postDescription.textContent = post.body;
    postBlock.appendChild(postDescription);

    const postLikeButton = document.createElement('button')
    postLikeButton.textContent = post.LikeCount;
    postLikeButton.addEventListener('click', event => {
        const likes = +event.target.innerText;
        const newLikesCount = likes + 1;
        event.target.innerText = newLikesCount;
    });
    postBlock.appendChild(postLikeButton);

    posts.appendChild(postBlock);
}

function getPosts() {
    fetch('api.php?act=login')
        .then(res => res.json())
        .then(res => {
            res.posts.forEach(post => {
                renderPost({
                    title: post.title,
                    body: post.body,
                    likeCount: post.reactions.likes,
                });
            })
        });
}

/**
 * @param {string} title
 * @param {string} description
 */
async function addPost(title, description) {
    const res = await fetch('https://dummyjson.com/posts/add', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            title,
            body: description,
            userId: 1,
        }),
    }); 
    const post = await res.json();

    renderPost({
        title: post.title,
        body: post.body,
        likeCount: 0,
    });
}

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

function initAddPost() {
    const addPostButton = document.querySelector('#addPostButton');
    addPostButton.addEventListener('click', () => {
        const title = document.querySelector('#postTitle');
        const description = document.querySelector('#postDescription');
        addPost(title.value, description.value);
    });
}

getPosts();
initAddPost();

//////////////////////