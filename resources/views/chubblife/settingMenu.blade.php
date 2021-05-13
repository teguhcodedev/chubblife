@extends('templates/main')
@section('css')
<link rel="stylesheet" href="{{ asset('css/dashboard/style.css') }}">
@endsection
@section('content')

<div class="card shadow">
    <div class="card-header">
        Setting Menu
    </div>
   <div class="card-body">
    <div class="row">
        <div class="col-md-12 col-lg-12 table-responsive">
            <table class="table table-hover">
                <thead>
                  <tr>
                    <th scope="col">#</th>
                    <th scope="col"  class="text-center">MENU NAME</th>
                    <th scope="col"  class="text-center">LEVEL TMR</th>
                    <th scope="col"  class="text-center">LEVEL SPV</th>
                    <th scope="col"  class="text-center">LEVEL ATM</th>
                    <th scope="col"  class="text-center">LEVEL ADMIN</th>
                    <th scope="col"  class="text-center">LEVEL QA</th>
                    <th scope="col"  class="text-center">LEVEL SPVQA</th>
                  </tr>
                </thead>
                <tbody>
                    @foreach ($MENUS as $MENU)
                    <tr>
                        <th scope="row">{{$MENU->id}}</th>
                        <td class="ml-2">
                            {{$MENU->MENU_NAME}}
                          
                        </td>
                        <td class="text-center">   
                         @if ($MENU->LEVEL_TMR==1)
                            <span class="btn btn-success" id="btn-menu">YES</span>
                        @else
                              <span class="btn btn-danger" id="btn-menu">NO</span>
                        @endif
                        </td>
                        <td class="text-center"> 
                        @if ($MENU->LEVEL_SPV==1)
                            <span class="btn btn-success " id="btn-menu">YES</span>
                        @else
                              <span class="btn btn-danger" id="btn-menu">NO</span>
                        @endif
                        </td>
                        <td class="text-center">
                         @if ($MENU->LEVEL_ATM==1)
                            <span class="btn btn-success " id="btn-menu">YES</span>
                        @else
                              <span class="btn btn-danger" id="btn-menu">NO</span>
                        @endif
                        </td>
                        <td class="text-center">
                        @if ($MENU->LEVEL_ADMIN==1)
                            <span class="btn btn-success " id="btn-menu">YES</span>
                        @else
                              <span class="btn btn-danger" id="btn-menu">NO</span>
                        @endif
                        </td>
                        <td class="text-center">
                        @if ($MENU->LEVEL_QA==1)
                            <span class="btn btn-success " id="btn-menu">YES</span>
                        @else
                              <span class="btn btn-danger" id="btn-menu">NO</span>
                        @endif
                        </td>
                        <td class="text-center">
                        @if ($MENU->LEVEL_SPVQA==1)
                            <span class="btn btn-success " id="btn-menu">YES</span>
                        @else
                              <span class="btn btn-danger" id="btn-menu">NO</span>
                        @endif
                        </td>
                      </tr>
                    @endforeach
                    <tr>
                        <th colspan="2">
                           <h3> Total Menu</h3>
                        </th>
                        <th class="text-center"><h4>{{$TOTAL_MENU_TMR}}</h4></th>
                        <th class="text-center"><h4>{{$TOTAL_MENU_SPV}}</h4></th>
                        <th class="text-center"><h4>{{$TOTAL_MENU_ATM}}</h4></th>
                        <th class="text-center"><h4>{{$TOTAL_MENU_ADMIN}}</h4></th>
                        <th class="text-center"><h4>{{$TOTAL_MENU_QA}}</h4></th>
                        <th class="text-center"><h4>{{$TOTAL_MENU_SPVQA}}</h4></th>
                    </tr>
                    <thead>
                        <tr>
                          <th scope="col"></th>
                          <th scope="col"  class="text-center"></th>
                          <th scope="col"  class="text-center">LEVEL TMR</th>
                          <th scope="col"  class="text-center">LEVEL SPV</th>
                          <th scope="col"  class="text-center">LEVEL ATM</th>
                          <th scope="col"  class="text-center">LEVEL ADMIN</th>
                          <th scope="col"  class="text-center">LEVEL QA</th>
                          <th scope="col"  class="text-center">LEVEL SPVQA</th>
                        </tr>
                      </thead>
                </tbody>
              </table>
        </div>
    </div>
   </div>
</div>

@endsection

@section('script')
<script src="{{ asset('js/manage_account/access/script.js') }}"></script>
<script type="text/javascript">
  function refreshNav(){
    $.ajax({
      url: "{{ url('/access/sidebar') }}",
      method: "GET",
      success:function(data){
        $('#sidebar').html(data);
      }
    });
  }

  $(document).on('click', '#btn-menu', function(){
    var data_access = $(this).parent().attr('data-access');
    var data_user = $(this).parent().attr('data-user');
    var data_role = $(this).parent().attr('data-role');
    if(data_role == 'admin'){
      swal({
        title: "Apa anda yakin?",
        text: "Program menyarankan untuk tidak mengubah hak akses admin",
        icon: "warning",
        buttons: true,
        dangerMode: true,
      })
      .then((willDelete) => {
        if (willDelete) {
          swal("Sedang diproses....", {
            buttons: false,
            timer: 1000,
          });
          $.ajax({
            url: "{{ url('/access/change') }}/" + data_user + '/' + data_access,
            method: "GET",
            success:function(data_1){
              var my_account = "{{ auth()->user()->id }}";
              $.ajax({
                url: "{{ url('/access/check') }}/" + my_account,
                method: "GET",
                success:function(data_2){
                  if(data_2 == 'benar'){
                    $('tbody').html(data_1);
                    refreshNav();
                  }else{
                    window.open("{{ url('/dashboard') }}", "_self");
                  }
                }
              });
            }
          }); 
        }
      });
    }else{
    //   swal("Sedang diproses....", {
    //     buttons: false,
    //     timer: 1000,
    //   });
    swal( {
        title: "Apa anda yakin?",
        text: "Program menyarankan untuk tidak mengubah hak akses admin",
        icon: "warning",
        buttons: true,
        dangerMode: true,
    });
      $.ajax({
        url: "{{ url('/access/change') }}/" + data_user + '/' + data_access,
        method: "GET",
        success:function(data_1){
          var my_account = "{{ auth()->user()->id }}";
          $.ajax({
            url: "{{ url('/access/check') }}/" + my_account,
            method: "GET",
            success:function(data_2){
              if(data_2 == 'benar'){
                $('tbody').html(data_1);
                refreshNav();
              }else{
                window.open("{{ url('/dashboard') }}", "_self");
              }
            }
          });
        }
      });
    }
  });
</script>
@endsection