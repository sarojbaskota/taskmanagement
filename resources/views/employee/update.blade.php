@extends('layouts.employee')

@section('content')
<section class="content-header">
      <h1>
        Task Page
        <small>{{config('app.name')}}</small>
      </h1>
      <ol class="breadcrumb">
          <li><a href="#"><i class="fa fa-dashboard"></i> Daily Updates</a></li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content container-fluid">
    <div class="box">
    <div class="box-header">
       
    </div>
        <!-- /.box-header -->
    <div class="box-body">
        <form id="employee_form">
        <div class="direct-chat-msg">
        <input type="hidden" class="user_id" value="{{Auth::user()->id}}">
                          <div class="direct-chat-info clearfix">
                            <span class="direct-chat-name pull-left">Project Manager</span>
                          </div>
                          <!-- /.direct-chat-info -->
                          <img class="direct-chat-img" src="{{asset('images/defaults/avatar.png')}}" alt="message user image">
                          <!-- /.direct-chat-img -->
                          <div class="direct-chat-text">
                            What Have You Done Today ?
                          </div>
                          <!-- /.direct-chat-text -->
        </div>
        <!-- replay  -->
        <div class="direct-chat-msg right">
                        <div class="direct-chat-info clearfix">
                          <span class="direct-chat-name pull-right">From  {{Auth::user()->full_name}} side</span>
                        </div>
                        <!-- /.direct-chat-info -->
                        <img class="direct-chat-img" src="{{asset('images/avatar/'.Auth::user()->avatar)}}" alt="message user image">
                        <!-- /.direct-chat-img -->
                          <textarea name="todo"  cols="142" rows="10" class="update_of_fullday direct-chat-text" ></textarea>
                        <!-- /.direct-chat-text -->
        </div>
                <button type="submit" class="btn btn-primary" >Update</button>
        </form>
    </div>
</div>

    </section>
@endsection
@section('scripts')
<script>
// register form
var base_url = "http://localhost:8000";
$('#employee_form').submit(function(event) {
    event.preventDefault();
    var user_id = $('.user_id').val();
    var update_of_fullday = $('.update_of_fullday').val();
    // alert(update_of_fullday);
    $.ajax({
        url: base_url+'/employee/updates/store',
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: 'POST',
        data: {user_id:user_id,update_of_fullday:update_of_fullday},
        success: function(result)
        {
            console.log(result);
          swal({
          title: result,
          text: 'success',
          icon: 'success',
          }).then(function(){
            location.reload();
          });
        },
        error: function(result)
        {
          $('.alert-danger').html('');
          $.each(result.errors, function(key, value){
            $('.alert-danger').show();
            $('.alert-danger').append('<li>'+value+'<li>');
          });
        },
});

});
</script>
@endsection