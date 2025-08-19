INSERT INTO 
    user (
        name, 
        surname,
        email, 
        password_hash,
        about_me, 
        avatar
    )
VALUES
    ("Ваня", "Денисов", "user1@example.com", "$2y$10$Ko90MvoYCMTSJuTZg/um9.ibXEc73iaWyjUNuaYFAjm3zt4hJ3zU", "Привет! Я системный аналитик в ACME :) Тут моя жизнь только для самых классных!", "images/me.jpeg"),
    ("Лиза", "Демина", "user1@example.com", "$2y$10$QNFXrh4jjrqY4LpTtaqoQOg3tmwT57aVf5vp11gZxZqw01RDQ46bO", "Сообщение, которое написала бы Лиза Демина", "images/her.jpeg");

INSERT INTO 
    post (
        user_id, 
        content, 
        likes
    )
VALUES
    (1, "Так красиво сегодня на улице! Настоящая зима)) Вспоминается Бродский: «Поздно ночью, в уснувшей долине, на самом дне, в гор...»", 203),
    (2, "", 178);

INSERT INTO 
    post_image (
        post_id,
        image_path
    )
VALUES
    (1, "images/1.jpeg"),
    (1, "images/2.jpeg"),
    (2, "images/lisa.jpeg");

    --to do: есть отдельная таблица лайков, часть данных сдесь более не актуальна