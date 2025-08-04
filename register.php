<!DOCTYPE html>
<html lang='ru'>
    <head>
        <meta charset='UTF-8'>
        <title>Зарегистрироваться</title>
    </head>
    <body>
        <form action='api.php?act=register' enctype='multipart/form-data' method='POST'>
            <div>
                <label>Имя</label>
                <input type='text' name='name' required autocomplete='given-name'/>
            </div>
            <div>
                <label>Фамилия</label>
                <input type='text' name='surname' required autocomplete='family-name'/>
            </div>
            <div>
                <label>Почта</label>
                <input type='email' name='email' required autocomplete='email'/>
            </div>
            <div>
                <label>Пароль</label>
                <input type="password" name="password" required />
            </div>
            <div>
                <label>Повторите пароль</label>
                <input type="password" name="password_confirm" required />
            </div>
            <div>
                <label>Обо мне</label>
                <input type='text' name='about_me' />
            </div>
            <div>
                <label>Аватар</label>
                <input type='file' name='image' accept='.png' />
            </div>
            <div>
                <button type='submit'>Зарегистрироваться</button>
            </div>
        </form>
    </body>
</html>