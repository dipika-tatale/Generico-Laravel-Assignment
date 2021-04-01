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
                    <h2>Add Author</h2>
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
                            <input type="text" name="aname" class="aname form-control" value="" />
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
                $.post("/authors/store",{author_name: $.trim($(".aname").val()), "_token": "{{ csrf_token() }}"}, function(resp) {
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
