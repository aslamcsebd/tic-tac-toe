<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use Redirect;
use DB;
use Carbon\Carbon;

use App\Models\GameEntry;
use App\Models\GameStep;
use App\Models\GamePoint;

class GameController extends Controller {
    
    public function gameEntry(Request $request){

        $validator = Validator::make($request->all(),[
           'firstPlayer'=>'required',
           'secondPlayer'=>'required',
           'rowCol'=>'required'
        ]);
        
        if($validator->fails()){
           $messages = $validator->messages(); 
           return Redirect::back()->withErrors($validator);
        }
  
        GameEntry::create([
           'firstPlayer'=>$request->firstPlayer,
           'secondPlayer'=>$request->secondPlayer,
           'rowCol'=>$request->rowCol
        ]);

        GameStep::create([
            'playerName'=>$request->firstPlayer,
            'action'=>true,
        ]);

        GameStep::create([
            'playerName'=>$request->secondPlayer,
            'action'=>false,
        ]);        
  
        return back()->with('success', 'Game info add successfully');
    }

    public function gameDelete(){
        GameEntry::truncate();
        GameStep::truncate();
        GamePoint::truncate();
                
        return back()->with('danger', 'Game info delete successfully');
    }

    public function gameAction($id){
   
        $pointTo = GameStep::where('action', 1)->first();
        if($pointTo->id==1){
            GamePoint::create([
                'firstPlayer'=>true
            ]);  
        }else{
            GamePoint::create([
                'secondPlayer'=>true
            ]);  
        } 

        $steps = GameStep::all();
        foreach($steps as $step){
            if($step->action==1){
                GameStep::where('id', $step->id)->update([
                    'action' => false
                ]);
            }else{
                GameStep::where('id', $step->id)->update([
                    'action' => true
                ]);
            }
        }

        return back()->with('danger', 'Action update successfully');
    }
}
