<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;


class MerqueoGeneralService {

    public function __construct(EntityManagerInterface $entityManager) {                
        $this->entityManager = $entityManager;      
    }
       
    public function validateMethod($request, $method){
        $code = Response::HTTP_OK;
        $message = 'success';
        $result = 'Metodo correcto';

        if (!$request->isMethod($method)) {
            $code = Response::HTTP_METHOD_NOT_ALLOWED;//405
            $message = 'error';
            $result = 'Metodo no soportado.';
        }

        $result = [
            "message" => $message,
            "code" => $code,
            "result" => $result
        ];

        return $result;
    }

    public function generateResponse($code, $message, $result){
        return json_encode([
            "message" => $message,
            "code" => $code,            
            "result" => $result
        ]);
    }

    public function validateRequest($parameters, $parametersMandatory){
        $result = [];             
        foreach($parametersMandatory as $parameter){           
            if(!array_key_exists($parameter, $parameters)){                
                $result['error'] = 'Error en el campo '.$parameter;
            }
        }
        
        return $result;

    }
    

    public function inventoryProducts(){
        $inventory = '{"inventory":[{"quantity":3,"date":"2019-03-01","id":1},{"quantity":3,"date":"2019-03-01","id":2},{"quantity":7,"date":"2019-03-01","id":3},{"quantity":8,"date":"2019-03-01","id":4},{"quantity":10,"date":"2019-03-01","id":5},{"quantity":15,"date":"2019-03-01","id":6},{"quantity":26,"date":"2019-03-01","id":7},{"quantity":11,"date":"2019-03-01","id":8},{"quantity":1,"date":"2019-03-01","id":9},{"quantity":8,"date":"2019-03-01","id":10},{"quantity":7,"date":"2019-03-01","id":11},{"quantity":8,"date":"2019-03-01","id":12},{"quantity":2,"date":"2019-03-01","id":13},{"quantity":1,"date":"2019-03-01","id":14},{"quantity":1,"date":"2019-03-01","id":15},{"quantity":9,"date":"2019-03-01","id":16},{"quantity":17,"date":"2019-03-01","id":17},{"quantity":8,"date":"2019-03-01","id":18},{"quantity":9,"date":"2019-03-01","id":19},{"quantity":9,"date":"2019-03-01","id":20},{"quantity":3,"date":"2019-03-01","id":21},{"quantity":6,"date":"2019-03-01","id":22},{"quantity":9,"date":"2019-03-01","id":23},{"quantity":9,"date":"2019-03-01","id":24},{"quantity":10,"date":"2019-03-01","id":25},{"quantity":40,"date":"2019-03-01","id":26},{"quantity":2,"date":"2019-03-01","id":27},{"quantity":3,"date":"2019-03-01","id":28},{"quantity":2,"date":"2019-03-01","id":29},{"quantity":1,"date":"2019-03-01","id":30},{"quantity":9,"date":"2019-03-01","id":31},{"quantity":10,"date":"2019-03-01","id":32},{"quantity":2,"date":"2019-03-01","id":33},{"quantity":3,"date":"2019-03-01","id":34},{"quantity":3,"date":"2019-03-01","id":35},{"quantity":6,"date":"2019-03-01","id":36}]}';
        return $inventory;
    }

    public function providersProducts(){
        $providers = '{"providers":[{"id":1,"name":"Ruby","products":[{"productId":1},{"productId":2},{"productId":45},{"productId":3},{"productId":4},{"productId":5},{"productId":46},{"productId":24},{"productId":25},{"productId":26},{"productId":27}]},{"id":1,"name":"Raul","products":[{"productId":28},{"productId":47},{"productId":29},{"productId":30},{"productId":31},{"productId":32},{"productId":33},{"productId":34},{"productId":35},{"productId":36},{"productId":16},{"productId":17}]},{"id":1,"name":"Angelica","products":[{"productId":6},{"productId":7},{"productId":8},{"productId":9},{"productId":10},{"productId":11},{"productId":12},{"productId":13},{"productId":14},{"productId":15},{"productId":18},{"productId":19},{"productId":20},{"productId":21},{"productId":22},{"productId":23}]}]}';
        return $providers;
    }


    public function ordersProducts(){
        $orders = '{"orders":[{"id":1,"priority":1,"address":"KR 14 # 87 - 20 ","user":"Sofia","products":[{"id":1,"name":"Leche","quantity":1},{"id":2,"name":"Huevos","quantity":21},{"id":37,"name":"Pan Bimbo","quantity":7},{"id":3,"name":"Manzana Verde","quantity":10},{"id":4,"name":"Pepino Cohombro","quantity":5}],"deliveryDate":"2019-03-01"},{"id":2,"priority":1,"address":"KR 20 # 164A - 5 ","user":"Angel","products":[{"id":5,"name":"Pimentón Rojo","quantity":100},{"id":6,"name":"Kiwi","quantity":60}],"deliveryDate":"2019-03-01"},{"id":3,"priority":3,"address":"KR 13 # 74 - 38 ","user":"Hocks","products":[{"id":7,"name":"Cebolla Cabezona Blanca Limpia","quantity":4},{"id":8,"name":"Habichuela","quantity":3},{"id":9,"name":"Mango Tommy Maduro","quantity":4},{"id":10,"name":"Tomate Chonto Pintón","quantity":8},{"id":11,"name":"Zanahoria Grande","quantity":5}],"deliveryDate":"2019-03-01"},{"id":4,"priority":1,"address":"CL 93 # 12 - 9 ","user":"Michael","products":[{"id":12,"name":"Aguacate Maduro","quantity":3},{"id":13,"name":"Kale o Col Rizada","quantity":2},{"id":14,"name":"Cebolla Cabezona Roja Limpia","quantity":4},{"id":4,"name":"Pepino Cohombro","quantity":2},{"id":15,"name":"Tomate Chonto Maduro","quantity":3}],"deliveryDate":"2019-03-01"},{"id":5,"priority":1,"address":"CL 71 # 3 - 74 ","user":"Bar de Alex","products":[{"id":16,"name":"Acelga","quantity":1500}],"deliveryDate":"2019-03-01"},{"id":6,"priority":2,"address":"KR 20 # 134A - 5 ","user":"Sabor Criollo","products":[{"id":17,"name":"Espinaca Bogotana x 500grs","quantity":2},{"id":18,"name":"Ahuyama","quantity":3},{"id":15,"name":"Tomate Chonto Maduro","quantity":2},{"id":19,"name":"Cebolla Cabezona Blanca Sin Pelar","quantity":2},{"id":20,"name":"Melón","quantity":3}],"deliveryDate":"2019-03-01"},{"id":7,"priority":2,"address":"CL 80 # 14 - 38 ","user":"El Pollo Rojo","products":[{"id":21,"name":"Cebolla Cabezona Roja Sin Pelar","quantity":3},{"id":22,"name":"Cebolla Larga Junca x 500grs","quantity":2},{"id":23,"name":"Hierbabuena x 500grs","quantity":2},{"id":39,"name":"Lechuga Crespa Morada","quantity":4},{"id":24,"name":"Lechuga Crespa Verde","quantity":15}],"deliveryDate":"2019-03-01"},{"id":8,"priority":7,"address":"KR 14 # 98 - 74 ","user":"All Salad","products":[{"id":25,"name":"Limón Tahití","quantity":3},{"id":26,"name":"Mora de Castilla","quantity":2},{"id":22,"name":"Cebolla Larga Junca x 500grs","quantity":4},{"id":27,"name":"Pimentón Verde","quantity":1},{"id":5,"name":"Pimentón Rojo","quantity":1}],"deliveryDate":"2019-03-01"},{"id":9,"priority":1,"address":"KR 58 # 93 - 1 ","user":"Parrilla y sabor","products":[{"id":22,"name":"Cebolla Larga Junca x 500grs","quantity":1}],"deliveryDate":"2019-03-01"},{"id":15,"address":"KR 14 # 87 - 20 ","priority":9,"user":"Sofia","products":[{"id":28,"name":"Tomate Larga Vida Maduro","quantity":1}],"deliveryDate":"2019-03-01"},{"id":10,"priority":1,"address":"CL 93B # 17 - 12 ","user":"restaurante yerbabuena ","products":[{"id":7,"name":"Cebolla Cabezona Blanca Limpia","quantity":1}],"deliveryDate":"2019-03-01"},{"id":11,"priority":10,"address":"KR 68D # 98A - 11 ","user":"Luis David","products":[{"id":41,"name":"Banano","quantity":1},{"id":19,"name":"Cebolla Cabezona Blanca Sin Pelar","quantity":6},{"id":29,"name":"Cilantro x 500grs","quantity":1},{"id":17,"name":"Espinaca Bogotana x 500grs","quantity":1},{"id":30,"name":"Fresa Jugo","quantity":1}],"deliveryDate":"2019-03-01"},{"id":12,"priority":2,"address":"AC 72 # 20 - 45 ","user":"David Carruyo","products":[{"id":7,"name":"Cebolla Cabezona Blanca Limpia","quantity":1},{"id":25,"name":"Limón Tahití","quantity":2},{"id":5,"name":"Pimentón Rojo","quantity":1},{"id":31,"name":"Papa R-12 Mediana","quantity":25}],"deliveryDate":"2019-03-01"},{"id":13,"priority":3,"address":"KR 22 # 122 - 57 ","user":"MARIO","products":[{"id":43,"name":"Banano","quantity":1},{"id":30,"name":"Fresa Jugo","quantity":1},{"id":32,"name":"Curuba ","quantity":1},{"id":33,"name":"Brócoli","quantity":1},{"id":28,"name":"Tomate Larga Vida Maduro","quantity":2}],"deliveryDate":"2019-03-01"},{"id":14,"priority":8,"address":"KR 88 # 72A - 26 ","user":"Harold","products":[{"id":16,"name":"Acelga","quantity":3},{"id":34,"name":"Aguacate Hass Pintón","quantity":3},{"id":35,"name":"Aguacate Hass Maduro ","quantity":3},{"id":12,"name":"Aguacate Maduro","quantity":1},{"id":36,"name":"Aguacate Pintón","quantity":1}],"deliveryDate":"2019-03-01"}]}';
        return $orders;
    }
    
}