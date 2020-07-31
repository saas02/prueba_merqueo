<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use App\Entity\Productos;
//services
use App\Service\MerqueoGeneralService;


class MerqueoController extends AbstractController
{
    
    public function __construct(MerqueoGeneralService $MerqueoGeneralService, SerializerInterface $serializer) {        
        $this->MerqueoGeneralService = $MerqueoGeneralService;
        $this->serializer = $serializer;
        $this->inventory = json_decode($this->MerqueoGeneralService->inventoryProducts(), true);
        $this->providers = json_decode($this->MerqueoGeneralService->providersProducts(), true);                        
        $this->orders = json_decode($this->MerqueoGeneralService->ordersProducts(), true);
    }
    
    /**
     * @Route("/v1/merqueo/consultas/productos", name="merqueo_productos", methods={"GET", "POST"} )
     */
    public function products()
    {
        $em = $this->getDoctrine()->getManager();    
        $request = Request::createFromGlobals();
        $validateMethod = $this->MerqueoGeneralService->validateMethod($request, "GET");   
        
        if ($validateMethod['message'] == 'error') {
            $response = $this->MerqueoGeneralService->generateResponse($validateMethod['code'], $validateMethod['message'], $validateMethod['result']);
            return new Response($response);
        }
        $parametersRequest = json_decode($request->getContent(), true);
                    
        if (empty($parametersRequest)) {  
            $message = "error";
            $code = Response::HTTP_BAD_REQUEST;//400
            $result = "1. Error con los parametros de entrada.";            
            $response = $this->MerqueoGeneralService->generateResponse($code, $message, $result);
            return new Response($response);
        }else { 
            $parametersMandatory = ["type", "estado"];
            $validateInfo = $this->MerqueoGeneralService->validateRequest($parametersRequest, $parametersMandatory);
            if(isset($validateInfo['error'])){
                $message = "error";
                $code = Response::HTTP_BAD_REQUEST;//400
                $response = "2. Error con los parametros de entrada.".$validateInfo['error'];
                $response = $this->MerqueoGeneralService->generateResponse($code, $message, $response);
            }else{ 
                $productos = $em->getRepository(Productos::class)->findBy(["estado" => $parametersRequest['estado']]);
                $result = [];
                $providers = $this->providers;
                $orders = $this->orders;       
                $inventory = $this->inventory; 
                switch($parametersRequest['type']){
                    case 'productos':                                                                                               
                        foreach($productos as $key => $producto){                
                            $result[$key]["id"] = $producto->getId();
                            $result[$key]["nombre"] = $producto->getNombre();
                            foreach($inventory['inventory'] as $dataInventory){
                                if($dataInventory['id'] === $producto->getId()){
                                    $result[$key]["inventory"] = $dataInventory['quantity'];
                                }
                            }               
                        }
                    break;
                    case 'transportadores':
                        foreach($providers as $provider){                            
                            foreach($provider as $datas){                                                                                        
                                foreach($datas['products'] as $productsProvider){                                    
                                    foreach($productos as $key => $producto){                                        
                                        if($productsProvider['productId'] === $producto->getId()){
                                            $result[$datas["name"]]["productos"][$producto->getId()]["id"] = $producto->getId();
                                            $result[$datas["name"]]["productos"][$producto->getId()]["nombre"] = $producto->getNombre();                                            
                                            foreach($orders['orders'] as $key => $order){                                                                           
                                                foreach($order['products'] as $productsOrder){                                                    
                                                    if($productsOrder['id'] === $producto->getId()){
                                                        $result[$datas["name"]]["productos"][$producto->getId()]["orders"][] = $order['id'];
                                                    }
                                                }                                            
                                            }
                                        }
                                        
                                    }

                                }
                            }
                        }                                                
                    break;
                    case 'menos_vendidos' || 'mas_vendidos':
                        foreach($productos as $key => $producto){
                            $result[$producto->getNombre()]['quantity'] = 0;
                            foreach($orders['orders'] as $key => $order){                                                                           
                                foreach($order['products'] as $productsOrder){
                                    if($productsOrder['id'] === $producto->getId()){
                                        $result[$producto->getNombre()]['quantity'] = $result[$producto->getNombre()]['quantity'] + $productsOrder['quantity'];
                                    }
                                }
                            }
                        }
                        ($parametersRequest['type'] == 'menos_vendidos') ? asort($result) : arsort($result);
                        
                    break;
                    default:
                        $result = [];
                    break;
                }                
                
                $response = $this->MerqueoGeneralService->generateResponse(Response::HTTP_OK, "success", $result);
            }
            
        }
                
        return new Response($response);
    }


    /**
     * @Route("/v1/merqueo/consultas/pedidos", name="merqueo_pedidos", methods={"GET", "POST"} )
     */
    public function pedidos()
    {
        $em = $this->getDoctrine()->getManager();    
        $request = Request::createFromGlobals();
        $validateMethod = $this->MerqueoGeneralService->validateMethod($request, "GET");   
        
        if ($validateMethod['message'] == 'error') {
            $response = $this->MerqueoGeneralService->generateResponse($validateMethod['code'], $validateMethod['message'], $validateMethod['result']);
            return new Response($response);
        }
        $parametersRequest = json_decode($request->getContent(), true);
                    
        if (empty($parametersRequest)) {  
            $message = "error";
            $code = Response::HTTP_BAD_REQUEST;//400
            $result = "1. Error con los parametros de entrada.";            
            $response = $this->MerqueoGeneralService->generateResponse($code, $message, $result);
            return new Response($response);
        }else { 
            $parametersMandatory = ["id_pedido"];
            $validateInfo = $this->MerqueoGeneralService->validateRequest($parametersRequest, $parametersMandatory);
            if(isset($validateInfo['error'])){
                $message = "error";
                $code = Response::HTTP_BAD_REQUEST;//400
                $response = "2. Error con los parametros de entrada.".$validateInfo['error'];
                $response = $this->MerqueoGeneralService->generateResponse($code, $message, $response);
            }else{ 
                
                $result = [];
                $providers = $this->providers;
                $orders = $this->orders;       
                $inventorys = $this->inventory; 

                
                foreach($orders['orders'] as $order){
                    if($order['id'] === $parametersRequest['id_pedido']){
                        foreach($order['products'] as $orderProducts){                            
                            foreach($inventorys['inventory'] as $inventory){                                
                                if($orderProducts['id'] == $inventory['id']){                                    
                                    $result[$orderProducts['name']]['quantity']['inventory'] = $inventory['quantity'];
                                    if($inventory['quantity'] < $orderProducts['quantity']){
                                        foreach($providers['providers'] as $provider){
                                            foreach($provider['products'] as $providerProduct){                                                
                                                if($orderProducts['id'] == $providerProduct['productId']){                                                    
                                                    $result[$orderProducts['name']]['provider']['name'] = $provider['name'];
                                                    $result[$orderProducts['name']]['provider']['quantity'] = ($orderProducts['quantity'] - $inventory['quantity']);
                                                }                                                
                                            }                                            
                                        }
                                    }
                                }

                            }                                                        
                        }                        
                    }                    
                }
                                
                
                $response = $this->MerqueoGeneralService->generateResponse(Response::HTTP_OK, "success", $result);
            }
            
        }
                
        return new Response($response);
    }


     /**
     * @Route("/v1/merqueo/consultas/inventarios", name="merqueo_inventarios", methods={"GET", "POST"} )
     */
    public function inventarios()
    {
        $em = $this->getDoctrine()->getManager();    
        $request = Request::createFromGlobals();
        $validateMethod = $this->MerqueoGeneralService->validateMethod($request, "GET");   
        
        if ($validateMethod['message'] == 'error') {
            $response = $this->MerqueoGeneralService->generateResponse($validateMethod['code'], $validateMethod['message'], $validateMethod['result']);
            return new Response($response);
        }
        $parametersRequest = json_decode($request->getContent(), true);
                    
        if (empty($parametersRequest)) {  
            $message = "error";
            $code = Response::HTTP_BAD_REQUEST;//400
            $result = "1. Error con los parametros de entrada.";            
            $response = $this->MerqueoGeneralService->generateResponse($code, $message, $result);
            return new Response($response);
        }else { 
            $parametersMandatory = ["fecha"];
            $validateInfo = $this->MerqueoGeneralService->validateRequest($parametersRequest, $parametersMandatory);
            if(isset($validateInfo['error'])){
                $message = "error";
                $code = Response::HTTP_BAD_REQUEST;//400
                $response = "2. Error con los parametros de entrada.".$validateInfo['error'];
                $response = $this->MerqueoGeneralService->generateResponse($code, $message, $response);
            }else{ 
                
                $result = [];
                $providers = $this->providers;
                $orders = $this->orders;       
                $inventorys = $this->inventory; 


                foreach($inventorys['inventory'] as $inventory){                      
                    $result[$inventory['id']]['quantity'] = $inventory['quantity'];
                    foreach($orders['orders'] as $order){
                        foreach($order['products'] as $orderProducts){   
                            if($inventory['id'] == $orderProducts['id']){
                                $result[$inventory['id']]['quantity'] = $result[$inventory['id']]['quantity'] - $orderProducts['quantity'];
                                $result[$inventory['id']]['nombre'] = $orderProducts['name'];                                
                            }                              
                        }                        
                    }
                }                
                                
                
                $response = $this->MerqueoGeneralService->generateResponse(Response::HTTP_OK, "success", $result);
            }
            
        }
                
        return new Response($response);
    }
}