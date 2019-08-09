<?php

namespace app\commands;

use Yii;
use yii\console\Controller;

/**
 * Инициализатор RBAC выполняется в консоли php yii rbac/init
 */
class RbacController extends Controller
{

    public function actionInit()
    {
        $auth = Yii::$app->authManager;

        $auth->removeAll();
        /*
                if ($auth->getRole('admin') !== null) {
                    echo 'roles already exists ' . PHP_EOL;
                    return true;
                }
        */

        // Создадим роли админа и редактора новостей
        $admin = $auth->createRole('admin');
        $editor = $auth->createRole('editor');

        // запишем их в БД
        $auth->add($admin);
        $auth->add($editor);

        // Создаем наше правило, которое позволит проверить автора новости
        $authorRule = new \app\common\rbac\AuthorRule;
        // Запишем его в БД
        $auth->add($authorRule);

        // Создаем разрешения. Например, просмотр админки viewAdminPage и редактирование новости updateNews
        $viewAdminPage = $auth->createPermission('viewAdminPage');
        $viewAdminPage->description = 'Просмотр админки';

        $updateNews = $auth->createPermission('updateNews');
        $updateNews->description = 'Редактирование новости';

        // Создадим еще новое разрешение «Редактирование собственной новости» и ассоциируем его с правилом AuthorRule
        $updateOwnNews = $auth->createPermission('updateOwnNews');
        $updateOwnNews->description = 'Редактирование собственной новости';
        // Указываем правило AuthorRule для разрешения updateOwnNews.
        $updateOwnNews->ruleName = $authorRule->name;

        // Запишем эти разрешения в БД
        $auth->add($viewAdminPage);
        $auth->add($updateNews);
        $auth->add($updateOwnNews);


        // Теперь добавим наследования. Для роли editor мы добавим разрешение updateNews,
        // а для админа добавим наследование от роли editor и еще добавим собственное разрешение viewAdminPage
        // Роли «Редактор новостей» присваиваем разрешение «Редактирование новости»
        $auth->addChild($editor, $updateNews);

        // админ наследует роль редактора новостей. Он же админ, должен уметь всё! :D
        $auth->addChild($admin, $editor);

        // Еще админ имеет собственное разрешение - «Просмотр админки»
        $auth->addChild($admin, $viewAdminPage);

        // Назначаем роль admin пользователю с ID 1
        $auth->assign($admin, 1);

        // Назначаем роль editor пользователю с ID 2
        $auth->assign($editor, 2);
    }
}