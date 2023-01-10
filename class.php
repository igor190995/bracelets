<?


if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

class Bracelets extends CBitrixComponent{

    const arGroups = [
        'controllers' => 32,
    ];

    public function executeComponent(){

        $this->init();
        $usersControllersIds = $this->getUsersGroup(self::arGroups['controllers']);

        //получаем все передачи билетов
        $this->arResult['SENDS'] = $this->getSends();

        $this->arResult['CONTROLLERS'] = $usersControllersIds;
        $this->arResult['USERS_IDS'] = array_values(array_unique(array_merge($usersControllersIds, $this->arResult['USERS_IDS'])));
        if(!empty($usersIds = $this->arResult['USERS_IDS'])){
            $this->arResult['USERS_DATA'] = $this->getUsersData($usersIds);
        }

        if(!empty($usersControllersIds)){
            //получаем текущее количество билетов в у каждого котролера

            foreach ($usersControllersIds as $controllerId){

                $countBracelets = 0;

                foreach ($this->arResult['SENDS'] as $send){
                    if(
                        $send['USER_FROM'] == $controllerId
                        && !empty($send['DATETIME_RECEIVING'])
                    ){
                        $countBracelets -= $send['COUNT'];
                    }

                    if(
                        $send['USER_TO'] == $controllerId
                        && !empty($send['DATETIME_RECEIVING'])
                    ){
                        $countBracelets += $send['COUNT'];
                    }
                }

                $this->arResult['COUNT_BRACELETS'][$controllerId] = $countBracelets;
            }
        }
        else{
            $this->arResult['ERROR'] = "Контролёров не найден";
        }

        $this->includeComponentTemplate();
    }

    function getUsersData($usersIds){
        $result = [];

        if(!empty($usersIds)) {
            $arFilter = [
                '@ID' => $usersIds
            ];

            $res = \Bitrix\Main\UserTable::getList(array(
                "select" => ["ID", "NAME", "LAST_NAME"],
                "filter" => $arFilter,
            ));

            while ($arr = $res->Fetch()) {
                $result[$arr['ID']] = $arr;
            }
        }

        return $result;
    }

    function getUsersGroup($groupId){

        $arFilter = [
            'GROUP_ID' => $groupId
        ];

        $res = \Bitrix\Main\UserGroupTable::getList([
            "select" => ["USER_ID"],
            "filter" => $arFilter
        ]);

        $usersIds = [];
        while($arr = $res->fetch()){
            $usersIds[] = $arr['USER_ID'];
        }

        return $usersIds;
    }

    function init(){
        CModule::IncludeModule('srlab.workzone');
        CModule::IncludeModule('srlab.tickets');

        return;
    }

    function getSends(){
        $result = [];

        $res = \SRLab\Tickets\CBraceletsTable::getList();

        $usersIds = [];
        while($arr = $res->fetch()){
            $result[] = $arr;

            if(!empty($arr['USER_FROM'])){
                $usersIds[] = $arr['USER_FROM'];
            }

            if(!empty($arr['USER_TO'])){
                $usersIds[] = $arr['USER_TO'];
            }
        }

        if(!empty($usersIds)){
            $this->arResult['USERS_IDS'] = array_values(array_unique($usersIds));
        }

        return $result;
    }

}

