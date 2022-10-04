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
      .fa-solid{ padding-top: 5px; }
      .id_2_2{ background-color:green !important; }
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
                                 @php
                                    echo $id = 'id_'.$i.'_'.$j;
                                 @endphp
                                 <a href="{{ Route('gameAction', $id)}}">
                                    <div class="box id_{{$i.'_'.$j}}">
                                       {{-- id_{{$i.'_'.$j}} --}}
                                    <i class="fa-solid fa-circle-xmark fa-2x"></i>
                                    </div>    
                                    {{-- <i class="fa-solid fa-check fa-2x"></i> --}}
                                 </a>
                              </td>
                              @endfor
                           </tr>
                        @endfor
                     </table>
                  </div>

                  <style>
                       .col-4 tr td:first-child, .col-4 tr th{background-color: #17a2b8!important; color: #fff !important; padding: 5px !important;}
                       .col-4 tr td:nth-child(2), .col-4 tr td:nth-child(3){background-color: #1abc9c!important; color: #fff; padding: 5px !important;}
                  </style>

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

                           <tr>
                              <td><b>Sum :</b></td>
                              <td><b>{{$GamePoints->sum('firstPlayer')}}</b></td>
                              <td><b>{{$GamePoints->sum('secondPlayer')}}</b></td>
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
