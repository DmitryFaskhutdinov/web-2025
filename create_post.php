<!DOCTYPE html>
<html lang='ru'>
    <head>
        <meta charset='UTF-8'>
        <title>Создать пост</title>
    </head>
    <body>
        <form action='api.php?act=uploader' enctype='multipart/form-data' method='POST'>
            <div>
                <label>Текст поста</label>
                <textarea name="content" rows="5" cols="40"></textarea>
            </div>
            <div>
                <label>Картинка (можно несколько)</label>
                <input type="file" name="image[]" accept=".png" multiple />
            </div>
            <div>
                <button type='submit'>Создать пост</button>
            </div>
        </form>
    </body>
</html>