@extends('layouts.app')
@section('content')
<div class="container">
   @php
      $gameStart = App\Models\GameEntry::first();
      $GamePoints = App\Models\GamePoint::all();      
   @endphp
   @foreach (['success', 'danger', 'warning', 'info'] as $alert)
      @if ($message = Session::get($alert))
         <div class="alert alert-{{$alert}} text-center alert-block">
            <button type="button" class="close text-light" data-dismiss="alert">×</button>
            <strong lass="text-light">{{ $message }}</strong>
         </div>
      @endif
   @endforeach

   <style>
      .game tr td { border: 1px solid blue; }
      .box{ width: 40px; height: 40px; text-align: center; }
      .fa-2x{ padding-top: 5px; }

      @foreach($GamePoints as $disable)
         .{{$disable->areaId}}{pointer-events: none;}
      @endforeach

      /* Right side */
      .col-4 tr td:first-child, .col-4 tr th{background-color: #17a2b8!important; color: #fff !important; padding: 0px !important;}
      .col-4 tr td:nth-child(2), .col-4 tr td:nth-child(3){background-color: #1abc9c!important; color: #fff; padding: 0px !important;}
   </style>

   <div class="row justify-content-center">
      <div class="col-md-12">
         <div class="card">
            <div class="card-header">
               <span>Game design</span>
               @if(isset($gameStart))
                  <a href="{{ Route('gameDelete')}}" class="btn btn-sm btn-danger text-light float-right">Delete game</a>
               @else
                  <button class="btn btn-sm btn-success text-light float-right" data-toggle="modal" data-original-title="test" data-target="#gameEntry">Start new game</button>
               @endif
            </div>

            <div class="card-body row">
               @if(isset($gameStart) && $gameStart->count()==1)

                  <?php $rowCol = $gameStart->rowCol; ?>
                  <div class="col-8">                 
                     <table class="game">
                        @for($i = 1; $i <= $rowCol; $i++)
                           <tr>
                              @for($j = 1; $j <= $rowCol; $j++)                       
                                 <td>                                    
                                    @php $areaId = 'c'.$i.'-r'.$j; @endphp
                                    <a class="{{$areaId}}" href="{{ Route('gameAction', $areaId)}}">
                                       <div class="box">
                                          @foreach($GamePoints as $areaPrint)
                                             @if($areaPrint->firstPlayer==1 && $areaPrint->areaId==$areaId)                                               
                                                <i class="fa-solid fa-xmark fa-2x"></i>
                                             @elseif($areaPrint->secondPlayer==1 && $areaPrint->areaId==$areaId)
                                                <i class="fa-regular fa-circle fa-2x"></i>                                                
                                             @endif   
                                          @endforeach
                                       </div>    
                                    </a>
                                 </td>
                              @endfor
                           </tr>
                        @endfor
                     </table>
                  </div>                 

                  <div class="col-4">
                     <table class="table table-bordered text-center">
                        <thead class="thead-light">
                           <tr class="p-0">
                              <th>Step</th>
                              <th>{{$gameStart->firstPlayer}}</th>
                              <th>{{$gameStart->secondPlayer}}</th>
                           </tr>
                        </thead>
                        <tbody>
                           @foreach($GamePoints as $point)
                              <tr>
                                 <td>{{$loop->iteration}}</td>
                                 <td>{{$point->firstPlayer}}</td>
                                 <td>{{$point->secondPlayer}}</td>
                              </tr>
                           @endforeach
                           @php
                              $fP = $GamePoints->sum('firstPlayer');
                              $sP = $GamePoints->sum('secondPlayer');
                           @endphp
                           <tr>
                              <td><b>Sum :</b></td>
                              <td><b>{{$fP}}{{($fP>$sP)?'[win]':''}}</b></td>
                              <td><b>{{$sP}}{{($fP<$sP)?'[win]':''}}</b></td>
                           </tr>
                        </tbody>
                     </table>
                  </div>
               @endif
            </div>
         </div>
      </div>
   </div>
</div>
@endsection

<div class="modal fade" id="gameEntry" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
   <div class="modal-dialog" role="document">
      <div class="modal-content">
         <div class="modal-header">
            <h6 class="modal-title pl-2" id="exampleModalLabel">Add information</h6>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
         </div>
         <div class="modal-body py-0">
            <form action="{{ Route('gameEntry') }}" method="post" enctype="multipart/form-data" class="needs-validation">
               @csrf                   
               <div class="row">                  
                  <div class="form-group col-4">
                     <label>First player :</label>
                     <input class="form-control" name="firstPlayer" value="{{Auth::user()->name}}" type="text" placeholder="{{Auth::user()->name}}" readonly>
                  </div>
                  <div class="form-group col-4">
                     <label for="secondPlayer">Second player :</label>
                     <input name="secondPlayer" class="form-control" id="secondPlayer" type="text" placeholder="Enter name" required>
                  </div>
                  <div class="form-group col-4">
                     <label for="link">Select col-row :</label>
                     <select class="custom-select" name="rowCol">
                        <option selected>Choose...</option>
                        @for($i=3; $i<=10; $i++)
                           <option value="{{$i}}" {{ $i==3 ? 'selected':'' }} >{{$i}}*{{$i}}</option>
                        @endfor
                     </select>
                  </div>
               </div>
               <div class="modal-footer pt-1 pb-0">
                  <button class="btn btn-sm btn-primary">Save</button>
                  <button class="btn btn-sm btn-secondary" type="button" data-dismiss="modal">Close</button>         
               </div>
            </form>
         </div>
      </div>
   </div>
</div>
