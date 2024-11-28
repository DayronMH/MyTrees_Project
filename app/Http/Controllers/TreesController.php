<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TreesModel;
use Illuminate\Support\Facades\Auth;
Class TreesController {

    
     public function getSoldTrees()
     {
         $soldTreesCount = TreesModel::countSoldTrees();
     
        
     
         return response()->json([
             'soldTrees' => $soldTreesCount
         ]);
     }

}