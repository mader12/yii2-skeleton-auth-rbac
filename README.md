<p align="center">
    <h1 align="center">Yii2 skeleton basic app with auth DB and RBAC</h1>
</p>
<p align="center">
Standart yii2 skeleton basic app with auth, signup, reset pass, RBAC.

</p>

DIRECTORY STRUCTURE
-------------------

      assets/             contains assets definition
      commands/           contains console commands (controllers)
      config/             contains application configurations
      controllers/        contains Web controller classes
      mail/               contains view files for e-mails
      models/             contains model classes
      runtime/            contains files generated during runtime
      tests/              contains various tests for the basic application
      vendor/             contains dependent 3rd-party packages
      views/              contains view files for the Web application
      web/                contains the entry script and Web resources



REQUIREMENTS
------------

The minimum requirement by this project template that your Web server supports PHP 5.4.0.


INSTALLATION
------------

### Install via Composer

~~~
git clone https://github.com/mader12/yii2-skeleton-auth-rbac.git
~~~

~~~
composer install
~~~

NEXT STEP CONFIGURATION
-------------

### Database

Edit the file `config/db.php` with real data, for example:

```php
return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=localhost;dbname=yii2basic',
    'username' => 'root',
    'password' => '1234',
    'charset' => 'utf8',
];
```

### NEXT 
~~~
./install.sh
~~~


### settings RBAC


~~~

<?php
...

// Назначаем роль в методе afterSave модели User
$auth = Yii::$app->authManager;
$editor = $auth->getRole('editor'); // Получаем роль editor
$auth->assign($editor, $this->id); // Назначаем пользователю, которому принадлежит модель User

...    

~~~

### Правила / RBAC Rules

RBAC дает возможность очень гибко работать с разрешениями и ролями с помощью правил. Правила добавляют ролям и разрешениям дополнительные ограничения. В RBAC Yii1 эти правила хранились непосредственно в разрешениях и назывались Business rules.

В Yii2 правила являются классами (php файлами), которые наследуются от yii\rbac\Rule и должны содержать в себе единственный метод execute().

Создадим правило, которое позволяет проверять, является ли пользователь автором новости. Файл common\rbac\AuthorRule.php. Путь может быть любой. Мне удобно хранить правила в common\rbac.

~~~
<?php
namespace common\rbac;

class AuthorRule extends \yii\rbac\Rule
{
    public $name = 'isAuthor';

    /**
     * @param string|integer $user ID пользователя.
     * @param Item $item роль или разрешение с которым это правило ассоциировано
     * @param array $params параметры, переданные в ManagerInterface::checkAccess(), например при вызове проверки
     * @return boolean a value indicating whether the rule permits the role or permission it is associated with.
     */
    public function execute($user, $item, $params)
    {
        return isset($params['news']) ? $params['news']->createdBy == $user : false;
    }
}
~~~

Таким образом, мы проверяем, что поле createdBy у новости совпадает или нет с user id. Файл правила мы создали. Теперь его нужно добавть в RBAC. Для этого мы модернизируем наш инициализатор RbacController и выполним его еще раз. Старые данные сотрем:

~~~
<?php

namespace app\commands;

use Yii;
use yii\console\Controller;
/**
 * Инициализатор RBAC выполняется в консоли php yii rbac/init
 */
class RbacController extends Controller {

    public function actionInit() {
        $auth = Yii::$app->authManager;
        
        $auth->removeAll(); //На всякий случай удаляем старые данные из БД...
        
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
        
        // Запишем все разрешения в БД
        $auth->add($viewAdminPage);
        $auth->add($updateNews);
        $auth->add($updateOwnNews);
        
        // Теперь добавим наследования. Для роли editor мы добавим разрешение updateOwnNews (редактировать собственную новость),
        // а для админа добавим собственные разрешения viewAdminPage и updateNews (может смотреть админку и редактировать любую новость)
        
        // Роли «Редактор новостей» присваиваем разрешение «Редактирование собственной новости»
        $auth->addChild($editor,$updateOwnNews);

        // админ имеет собственное разрешение - «Редактирование новости»
        $auth->addChild($admin, $updateNews);
        
        // Еще админ имеет собственное разрешение - «Просмотр админки»
        $auth->addChild($admin, $viewAdminPage);

        // Назначаем роль admin пользователю с ID 1
        $auth->assign($admin, 1); 
        
        // Назначаем роль editor пользователю с ID 2
        $auth->assign($editor, 2);
    }
}
~~~

Теперь, что бы вызвать проверку прав на редактирование собственной новости, в экшене редактирования производим проверку:

~~~
<?php
...
if (!\Yii::$app->user->can('updateOwnNews', ['news' => $newsModel])) {
    throw new ForbiddenHttpException('Access denied');
}
...
~~~

Здесь мы вызываем проверку updateOwnNews и передаем в правило этого разрешения параметр news (модель новости) в виде ассоциативного массива.

### Использование проверки в фильтре доступа AccessControl
Использовать проверку в фильтре доступа AccessControl выгодно по причине того, что, если пользователь не авторизован и не имеет разрешения, то yii перекинет его на страницу авторизации. А если пользователь был авторизован и не имеет разрешения, то получает страницу ошибки. Пример ниже проверяет разрешение viewAdminModule для всех экшенов контроллера:

~~~
<?php
public function behaviors() {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['viewAdminModule']
                    ],
                ],
            ],
        ];
    }
~~~

Здесь параметру roles передается массив ролей или разрешений, что в свою очередь в недрах системы вызывает проверку Yii::$app->user->can(‘viewAdminModule’)).

Кстати, с помощью Yii::$app->user->can() мы можем проверять не только наличие разрешения у роли, но и наличие роли. Yii::$app->user->can('editor')) вернет true, если текущему пользователю назначена роль editor. Получить массив ролей мы можем так: Yii::$app->authManager->getRolesByUser(Yii::$app->user->getId()).

Имейте в виду, что если роль админа наследует роль редактора новостей, то для админа Yii::$app->user->can('editor')) вернет true.


