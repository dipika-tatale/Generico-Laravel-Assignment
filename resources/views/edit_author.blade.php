<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Generico Assignment</title>

        <link href="/css/bootstrap.min.css" rel="stylesheet">

        <style>
            body {
                font-family: 'Montserrat', sans-serif;
                -webkit-font-smoothing: antialiased;
                font-size: 14px;
            }
        </style>
    </head>
    <body class="">
        <div class="row">
            <div class="col-md-12" style="display: inline-flex;">
                <div class="col-md-10" style="margin: 10px 0px;">
                    <h2>Update Author</h2>
                    <input type="hidden" name="author_id" class="author_id form-control" value="{{ $author->id }}" />
                </div>

                <div class="col-md-2" style="margin: 15px 0px;">
                    <a href="/authors" class="btn btn-info btn-sm">
                        Back
                    </a>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12" style="margin: 10px 15px;">
                <div class="row">
                    <div class="col-sm-6 show_errors">
                        
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="Name">Name</label>
                            <input type="text" name="author_name" class="author_name form-control" value="{{ $author->name }}" />
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-6" style="display: inline-flex;">
                        <div class="form-group">
                            <label for="Status">Status</label>
                            
                            <input type="checkbox" name="astatus" class="form-control astatus" value="1" @if((int) $author->status == 1) checked @endif />
                        </div>
                    </div>
                </div>

                <div class="row">
                                    
                    <div class="col-sm-6">
                        <div class="form-group">
                            <a href="javascript:void(0)" class="btn btn-sm btn-success save_author">Save</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
    <script src="/js/jquery-3.5.1.js" type="text/javascript"></script>
    <script src="/js/bootstrap.min.js" type="text/javascript"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $(document).on("click", ".save_author", function(){
                var checkbox_value = 0;
                if($.trim($(".astatus:checked").val()) == 1)
                {
                    checkbox_value = 1;
                }

                $.post("/authors/update",{author_id: $.trim($(".author_id").val()), author_name: $.trim($(".author_name").val()), status: checkbox_value, "_token": "{{ csrf_token() }}"}, function(resp) {
                    console.log(resp);
                    
                    if(resp.status == 'success')
                    {
                        $(".show_errors").html('');
                        window.location.href = '/authors';
                    } else if(resp.status == 'error')
                    {
                        $(".show_errors").html(resp.html);
                    }
                }, 'json');
            });
        });
    </script>
</html>
