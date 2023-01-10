<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php"); ?>

<?
global $APPLICATION;
$APPLICATION->SetTitle("Браслеты");
$APPLICATION->AddChainItem("Билеты", $_SERVER['HTTP_REFERER']);
$APPLICATION->AddChainItem("Браслеты", "");
?>
    <label>Контролёры</label>
    <div id="controllers" class="col-md-12">
        <table class="table table-striped table-bordered">
            <thead>
            <tr>
                <th>#</th>
                <th>Контролёр</th>
                <th>Браслетов</th>
            </tr>
            </thead>
            <tbody>
                <?
                $n = 1;
                foreach ($arResult['CONTROLLERS'] as $userId){
                    $user = $arResult['USERS_DATA'][$userId];?>
                    <tr>
                        <td><?=$n++?></td>
                        <td><?=$user['NAME'] . " " . $user['LAST_NAME'] . " [" . $user['ID'] . "]"?></td>
                        <td><?=$arResult['COUNT_BRACELETS'][$userId]?></td>
                    </tr>
                <? } ?>
            </tbody>
        </table>
    </div>

    <br>
    <br>
<? if (!empty($arResult['SENDS'])) { ?>
    <label>Передачи браслетов</label>
    <div class="col-md-12">
        <table class="table table-striped table-bordered">
            <thead>
            <tr>
                <th>#</th>
                <th>Кто передал</th>
                <th>Кому передал</th>
                <th>Время передачи</th>
                <th>Количество</th>
                <th>Приём</th>
            </tr>
            </thead>
            <tbody>
            <?
            $n = 0;
            foreach ($arResult['SENDS'] as $send) {
                $userFrom = $arResult['USERS_DATA'][$send['USER_FROM']];
                $userTo = $arResult['USERS_DATA'][$send['USER_TO']];
                ?>
                <tr>
                    <td><?= ++$n ?></td>
                    <td><?= $userFrom['NAME'] . " " . $userFrom['LAST_NAME'] . " [" . $userFrom['ID'] . "]"?></td>
                    <td><?= $userTo['NAME'] . " " . $userTo['LAST_NAME'] . " [" . $userTo['ID'] . "]"?></td>
                    <td><?= $send['DATETIME']->format("d.m.Y H:i:s")?></td>
                    <td><?= $send['COUNT']?></td>
                    <td>
                        <? if(!empty($send['DATETIME_RECEIVING'])){
                            echo "Выполнен<br>Время: " . $send['DATETIME_RECEIVING']->format("d.m.Y H:i:s");
                        }
                        else{
                            echo "Не выполнен";
                        }?>
                    </td>
                </tr>
            <? } ?>
            </tbody>
        </table>
    </div>
<? } else{
    echo "Истории передачи браслетов нет";
} ?>

    <script>

        var urlAjax = '<?=$componentPath?>/ajax.php';


    </script>

<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>
