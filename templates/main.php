<div class="content">
    <section class="content__side">
        <h2 class="content__side-heading">Категории</h2>
        <nav class="main-navigation">
            <ul class="main-navigation__list">
                <?php foreach($categories as $category) : ?>
                <li class="main-navigation__list-item <?= (intval($cat_id) === $category['id']) ? 'main-navigation__list-item--active' : '' ; ?>
                ">
                    <a class="main-navigation__list-item-link" href="/index.php?cat_id=<?= $category['id'] ; ?>"><?= $category['name'] ; ?></a>
                    <span class="main-navigation__list-item-count">
                    <?= (!empty($_GET['cat_id'])) ? task_сount($all_tasks, $category['id']) : task_сount($tasks, $category['id']) ;?></span>
                </li>
                <?php endforeach ; ?>
            </ul>
        </nav>
        <a class="button button--transparent button--plus content__side-button" href="add_category.php" target="project_add">Добавить категорию</a>
    </section>
    <main class="content__main">
        <h2 class="content__main-heading">Список задач</h2>
        <form class="search-form" action="index.php" method="GET" autocomplete="off">
            <input class="search-form__input" type="text" name="search" value="" placeholder="Поиск по задачам">
            <input class="search-form__submit" type="submit" name="" value="Искать">
        </form>
        <div class="tasks-controls">
            <nav class="tasks-switch">
                <a href="/index.php?tasks-controls=all" class="tasks-switch__item
                <?= ($_GET['tasks-controls'] === 'all') ? 'tasks-switch__item--active':'' ?>
                ">Все задачи</a>
                <a href="/index.php?tasks-controls=today" class="tasks-switch__item
                <?= ($_GET['tasks-controls'] === 'today') ? 'tasks-switch__item--active':'' ?>">Повестка дня</a>
                <a href="/index.php?tasks-controls=tommorow" class="tasks-switch__item
                <?= ($_GET['tasks-controls'] === 'tommorow') ? 'tasks-switch__item--active':'' ?>">Завтра</a>
                <a href="/index.php?tasks-controls=overdue" class="tasks-switch__item
                <?= ($_GET['tasks-controls'] === 'overdue') ? 'tasks-switch__item--active':'' ?>">Просроченные</a>
            </nav>
            <label class="checkbox">
                <!--добавить сюда атрибут "checked", если переменная $show_complete_tasks равна единице-->
                <input class="checkbox__input visually-hidden show_completed" type="checkbox" name="done"
                <?= ($show_complete_tasks) ? 'checked' : '' ;?>
                >
                <span class="checkbox__text">Показывать выполненные</span>
            </label>
        </div>
        <table class="tasks">
            <?php foreach($tasks as $task) : ?>
            <?php if ( !($show_complete_tasks) && ($task['done']) ) { continue ; } ?>
            <tr class="tasks__item task
                <?= ($task['done']) ? 'task--completed' : '' ; ?>
                <?= (is_deadline($task['date'])) ? 'task--important' : '' ; ?>
            ">
                <td class="task__select">
                    <label class="checkbox task__checkbox">
                        <input class="checkbox__input visually-hidden task__checkbox" type="checkbox" value="<?= ($task['id']) ; ?>">
                        <span class="checkbox__text"><?= htmlspecialchars($task['name']) ; ?></span>
                    </label>
                </td>

                <td class="task__file">
                    <?php if (!empty($task['file'])) : ?>
                    <a class="download-link" href="<?= $task['file']; ?>">Файл</a>
                    <?endif;?>
                </td>
                <td class="task__date"><?= $task['date'] ; ?></td>
            </tr>
            <?php endforeach ; ?>
        </table>
    </main>
</div>

