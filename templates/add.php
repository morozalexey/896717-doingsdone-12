<div class="content">
    <section class="content__side">
    <h2 class="content__side-heading">Категории</h2>
    <nav class="main-navigation">
        <ul class="main-navigation__list">
            <?php foreach($categories as $category) : ?>
            <li class="main-navigation__list-item <?= ($_GET['cat_id'] === $category['id']) ? 'main-navigation__list-item--active' : '' ; ?>
            ">
                <a class="main-navigation__list-item-link" href="/add.php?cat_id=<?= $category['id'] ; ?>"><?= $category['name'] ; ?></a>
                <span class="main-navigation__list-item-count">
                <?= (!empty($_GET['cat_id'])) ? task_сount($all_tasks, $category['id']) : task_сount($tasks, $category['id']); ?></span>
            </li>
            <?php endforeach ; ?>
        </ul>
    </nav>
    <a class="button button--transparent button--plus content__side-button" href="add_category.php">Добавить категорию</a>
    </section>
    <main class="content__main">
    <h2 class="content__main-heading">Добавление задачи</h2>
    <form class="form"  action="add.php" method="post" autocomplete="off" enctype="multipart/form-data">
        <div class="form__row">
            <?php
            $error_classname = !empty($errors['name']) ? "form__input--error" : "";
            $error_text = !empty($errors['name']) ? $errors['name'] : "";
            ?>
            <label class="form__label" for="name">Название <sup>*</sup></label>
            <input class="form__input <?= $error_classname; ?>" type="text" name="name" id="name" value="<?= getPostVal('name'); ?>" placeholder="Введите название">
            <p class="form__message"><?= $error_text; ?></p>
        </div>
        <div class="form__row">
            <label class="form__label" for="project">Проект <sup>*</sup></label>
            <select class="form__input form__input--select" name="project" id="project">
            <option value="">Выберете категорию</option>
            <?php foreach($categories as $category) : ?>
                <option value="<?= $category['name'] ; ?>"><?= $category['name'] ; ?></option>
            <?php endforeach ; ?>
            </select>
        </div>
        <div class="form__row">
            <?php $error_classname = !empty($errors['date']) ? "form__input--error" : ""; ?>
            <?php $error_text = !empty($errors['date']) ? $errors['date'] : ""; ?>
            <label class="form__label" for="date">Дата выполнения</label>
            <input class="form__input form__input--date <?= $error_classname; ?>" type="text" name="date" id="date" value="<?= getPostVal('date'); ?>" placeholder="Введите дату в формате ГГГГ-ММ-ДД">
            <p class="form__message"><?= $error_text; ?></p>
        </div>
        <div class="form__row">
            <?php $error_classname = !empty($errors['file']) ? "form__input--error" : ""; ?>
            <?php $error_text = !empty($errors['file']) ? $errors['file'] : ""; ?>
            <label class="form__label" for="file">Файл</label>
            <div class="form__input-file">
                <input class="visually-hidden" type="file" name="file" id="file" value="<?= getPostVal('file'); ?>">
                <label class="button button--transparent" for="file">
                <span>Выберите файл</span>
                </label>
            </div>
            <p class="form__message"><?= $error_text; ?></p>
        </div>
        <div class="form__row form__row--controls">
            <input class="button" type="submit" name="" value="Добавить">
        </div>
    </form>
    </main>
</div>
