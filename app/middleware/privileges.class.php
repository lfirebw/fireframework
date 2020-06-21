<?php
Import::Model('menu_rolModel','menuRol');
Import::Model('extra_accessModel','extraaccess');

class privileges{
    public static function validateAccess($session){
        try{
            $access = false;
            if(empty($session['idrol'])){
                throw new Exception("The User not have any rol");
            }
            $oMenuRol = new menuRol();
            $oExtraaccess = new extraaccess();
            //obtener privilegios segun el rol actual
            $rs = $oMenuRol->selectRel(
                array(array('innerjoin'=>'menu','on'=>array('id','idmenu'))),
                "a.*,b.page,b.name",array('estado'=>1,'idrol'=>$session['idrol'])
            );
            $rs_e = $oExtraaccess->selectRel(
                array(array('innerjoin'=>'menu','on'=>array('id','idmenu'))),
                "a.*,b.page,b.name",array('iduser'=>$session['id'])
            );
            if(!empty($rs)){
                if(!empty($rs_e)){
                    foreach($rs_e as $value){
                        if(empty($value['estado'])){ continue; }
                        $find = array_search($value['idmenu'],array_column($rs,'idmenu'));
                        if($find !== false && $value['estado'] == '2'){
                            unset($rs[$find]);
                        }else{
                            $rs[] = array('idmenu'=>$value['idmenu'],'page'=>$value['page'],'name'=>$value['name'],'estado'=>1,'perms'=>$value['perms']);
                        }
                    }
                }
                //verificar si el nombre de la funcion esta en la lista
                $access = (array_search(request::getQuery(),array_column($rs,'page')) !== false);
            }
            //decidir accesos
            if($access === false){
                printf("Usted no tiene accesos al modulo");
                exit(0);
                // route::redir();
            }
        }catch(Exception $e){
            printf("Error on validate %s",$e->getMessage());
            return false;
        }
    }
}

?>