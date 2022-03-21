<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet"/>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap" rel="stylesheet"/>
    <!-- MDB -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/3.11.0/mdb.min.css" rel="stylesheet"/>
    <!-- MDB -->
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/3.11.0/mdb.min.js"></script>
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <style type="text/css">
       
    </style>
  <link href="{{ asset('css/app.css') }}" rel="stylesheet"/>
    <title>Todo List</title>
</head>
<body>
    
    <section class="vh-100 gradient-custom">
        <div class="container py-4 h-100">
            <div class="row d-flex justify-content-center align-items-center h-100">
            <div class="col col-xl-10">
        
                <div class="card">
                <div class="card-body p-2">

                    <div class="text-center pt-3 pb-2">
                        <h2 class="my-3" style="color: #fff;">Todo List</h2>
                        </div>
        
                    <form class="mb-4" id="add-todo" method="post" onsubmit="addTodo(); return false;">
                        @csrf
                        <input type="hidden" name="priority" id="priority"  value="0" />
                    <div class="form-white form-outline">
                        
                        <input type="text" name="title" id="title" class="form-control" placeholder="Task..."/>
                        <span type="submit" class="add-task-btn btn btn-info ms-2" onclick="addTodo(); return false;">Add</span>
                    </div>
                    </form>
        
        
                <!-- Tabs content -->
                    
                <div class="list-group" id="todo-list-active">
                    @foreach ($todos_active as $key => $value)
                            @if ($value->priority==1)
                                @php
                                    $class="list-group-item";
                                @endphp
                            @elseif ($value->priority==2)
                                @php
                                    $class="list-group-item list-group-item-warning";
                                @endphp
                            @elseif ($value->priority==3)
                                @php
                                    $class="list-group-item list-group-item-danger";
                                @endphp
                            @else
                                @php
                                    $class="list-group-item";
                                @endphp
                            @endif
                            <div class="{{$class}}" id="todo-{{$value->id}}">
                                <input class="form-check-input" type="checkbox" onclick="completeTodo({{$value->id}}); return false;">
                                <div class="todo-title" contenteditable="true">{{$value->title}}</div>
                                <div class="todo-priority-container">
                                    <i class="fas fa-ellipsis-h"></i>
                                    <div class="todo-priority-box">
                                        <div class="todo-priority-option todo-priority-normal" onclick="todoPriorityChange({{$value->id}},1); return false;">Normal</div>
                                        <div class="todo-priority-option todo-priority-warning" onclick="todoPriorityChange({{$value->id}},2); return false;">Warning</div>
                                        <div class="todo-priority-option todo-priority-danger" onclick="todoPriorityChange({{$value->id}},3); return false;">Danger</div>
                                    </div>
                                </div>
                                <i class="fas fa-trash-alt" onclick="deleteTodo({{$value->id}})"></i>
                            </div>
                    @endforeach
                </div>

                <div class="mt-3 mb-4"><span class="done-task-title">Done</span></div>

                <div class="list-group" id="todo-list-completed">
                    @foreach ($todos_completed as $key => $value)
                            @php
                                $class="list-group-item list-group-item-dark text-decoration-line-through";
                            @endphp
                            <div class="{{$class}}" id="todo-{{$value->id}}">
                                <input class="form-check-input" checked type="checkbox" onclick="unCompleteTodo({{$value->id}}); return false;">
                                <div class="todo-title">{{$value->title}}</div>
                                <i class="fas fa-trash-alt" onclick="deleteTodo({{$value->id}})"></i>
                            </div>
                    @endforeach
                </div>
                    <!-- Tabs content -->
        
                </div>
                </div>
        
            </div>
            </div>
        </div>
        </section>
        
<script>
function addTodo(){
    var form = $('#add-todo')[0];
	var data = new FormData(form);
    var title= $('#title').text();
    $.ajax({
        type:'POST',
        data: data,
        processData: false,
        contentType: false,
        url:'{{route('addTodo')}}',
        success : function(rtrn){
            if(rtrn.id==""){
                alert("Error!");
            }else{
                $( "#todo-list-active" ).append( '<div class="list-group-item" id="todo-'+rtrn.id+'"><input class="form-check-input" type="checkbox" onclick="completeTodo('+rtrn.id+'); return false;"><div class="todo-title">'+rtrn.title+'</div><div class="todo-priority-container"><i class="fas fa-ellipsis-h"></i><div class="todo-priority-box"><div class="todo-priority-option todo-priority-normal" onclick="todoPriorityChange('+rtrn.id+',1); return false;">Normal</div><div class="todo-priority-option todo-priority-warning" onclick="todoPriorityChange('+rtrn.id+',2); return false;">Warning</div><div class="todo-priority-option todo-priority-danger" onclick="todoPriorityChange('+rtrn.id+',3); return false;">Danger</div></div></div><i class="fas fa-trash-alt" onclick="deleteTodo('+rtrn.id+')"></i></div>' );
                $('#title').val('');
                alert("Task Successfully Added");
                
            }
        }
	});
    
}
function completeTodo(id){
    $.ajax({
        type:'GET',
        processData: false,
        contentType: false,
        url:'{{route('completeTodo')}}/'+id,
        success : function(rtrn){
            if(rtrn.id!=""){
                $( '#todo-'+id ).remove();
                $( "#todo-list-completed" ).append( '<div class="list-group-item list-group-item-dark text-decoration-line-through" id="todo-'+id+'"><input class="form-check-input" checked type="checkbox" onclick="unCompleteTodo('+id+'); return false;"><div class="todo-title">'+rtrn.title+'</div><i class="fas fa-trash-alt" onclick="deleteTodo('+id+')"></i></div>' );
            }else{
                alert("Error!");
            }
        }
	});
}

function unCompleteTodo(id){
    $.ajax({
        type:'GET',
        processData: false,
        contentType: false,
        url:'{{route('unCompleteTodo')}}/'+id,
        success : function(rtrn){
            if(rtrn.id!=""){
                $( '#todo-'+id ).remove();
                $( "#todo-list-active" ).append( '<div class="list-group-item" id="todo-'+id+'"><input class="form-check-input" type="checkbox" onclick="completeTodo('+id+'); return false;"><div class="todo-title">'+rtrn.title+'</div><div class="todo-priority-container"><i class="fas fa-ellipsis-h"></i><div class="todo-priority-box"><div class="todo-priority-option todo-priority-normal" onclick="todoPriorityChange('+rtrn.id+',1); return false;">Normal</div><div class="todo-priority-option todo-priority-warning" onclick="todoPriorityChange('+rtrn.id+',2); return false;">Warning</div><div class="todo-priority-option todo-priority-danger" onclick="todoPriorityChange('+rtrn.id+',3); return false;">Danger</div></div></div><i class="fas fa-trash-alt" onclick="deleteTodo('+id+')"></i></div>' );
                if(rtrn.priority==2)
                {
                    $("#todo-"+id).addClass('list-group-item-warning');
                }
                if(rtrn.priority==3){
                    $("#todo-"+id).addClass('list-group-item-danger');
                }
            }else{
                alert("Error!");
            }
        }
	});
}

function deleteTodo(id){
    $.ajax({
        type:'GET',
        processData: false,
        contentType: false,
        url:'{{route('deleteTodo')}}/'+id,
        success : function(rtrn){
            if(rtrn==1){
                $( '#todo-'+id ).remove();
            }else{
                alert("Error!");
            }
        }
	});
}

function todoPriorityChange(todoId,priority){
    

    $.ajax({
        type:'GET',
        processData: false,
        contentType: false,
        url:'{{route('priorityTodo')}}/'+todoId+'/'+priority,
        success : function(rtrn){
            if(rtrn==1){
                $("#todo-"+todoId).removeClass();
                $("#todo-"+todoId).addClass('list-group-item');
                if(priority==2)
                {
                    $("#todo-"+todoId).addClass('list-group-item-warning');
                }
                if(priority==3){
                    $("#todo-"+todoId).addClass('list-group-item-danger');
                }
                $(".todo-priority-box").hide();
            }else{
                alert("Error!");
            }
        }
	});

    


}

$(document).on('input', '.todo-title', function()
{
    console.log("its cool.");
});

$(document).on('click', '.todo-priority-container', function()
{
    $(".todo-priority-box").hide();
    $(this).children().next().toggle();
});

$(document).click(function(event) {
    if ( !$(event.target).hasClass('fa-ellipsis-h')) {
        $(".todo-priority-box").hide();
    }
});

</script>

</body>
</html>