<?php
use yii\helpers\Html;
?>
<div id="lang" style="width:150px;">

    <span id="current-lang">
        <?= $current->name;?> <span class="show-more-lang">▼</span>
    </span>

    <ul id="langs" style="display:none;
    list-style: none;
    background-color:#eee;
    padding:20px;width:100px;">

        <?php foreach ($langs as $lang):?>
            <li class="item-lang">
                <?= Html::a($lang->name, '/'.$lang->url.Yii::$app->getRequest()->getLangUrl()) ?>
            </li>
        <?php endforeach;?>
    </ul>
</div>

<script>
    document.getElementById('current-lang').onclick = function (){
        var el = document.getElementById('langs');
        if (document.getElementById('langs').style.display == 'none') {
            document.getElementById('langs').style.display = 'block';
        } else {
            document.getElementById('langs').style.display = 'none';
        }
    }
</script>