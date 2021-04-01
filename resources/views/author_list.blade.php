<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Generico Assignment</title>

        <link href="/css/bootstrap.min.css" rel="stylesheet">
        <link href="/css/jquery.dataTables.min.css" rel="stylesheet">

        <style>
            body {
                font-family: 'Montserrat', sans-serif;
                -webkit-font-smoothing: antialiased;
                font-size: 14px;
            }

            thead tr th, tbody tr td {
                text-align: center;
            }
        </style>
    </head>
    <body class="">
        <div class="row">
            <div class="col-md-12" style="display: inline-flex;">
                <div class="col-md-10" style="margin: 10px 0px;">
                    <h2>Author List</h2>
                </div>

                <div class="col-md-1" style="margin: 15px 0px;">
                    <a href="/" class="btn btn-info btn-sm">
                        Book List
                    </a>
                </div>

                <div class="col-md-1" style="margin: 15px 0px;">
                    <a href="authors/add" class="btn btn-info btn-sm">
                        Add Author
                    </a>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-11" style="margin: 15px 10px;">
                <table id="author_list" class="display" style="width:100%">
                    <thead>
                        <tr>
                            <th data-orderable="false">#</th>
                            <th>Name</th>
                            <th>Created On</th>
                            <th data-orderable="false">Status</th>
                            <th data-orderable="false">Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </body>
    <script src="/js/jquery-3.5.1.js" type="text/javascript"></script>
    <script src="/js/bootstrap.min.js" type="text/javascript"></script>
    <script src="/js/jquery.dataTables.min.js" type="text/javascript"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('#author_list').DataTable({
                "processing": true,
                "serverSide": true,
                "scrollCollapse": true,
                "order": [],
                "ajax":{
                    url :"/authors",
                    type: "post",
                    data: {
                        "_token": "{{ csrf_token() }}"
                    }
                }
            });

            $(document).on("click", ".delete_author", function(){
                var flgConfirm = confirm('Are you sure, You want to delete this author?');

                if (flgConfirm)
                {
                    $.post("/authors/delete",{row_id: $.trim($(this).attr('row_id')), "_token": "{{ csrf_token() }}"}, function(resp) {
                        console.log(resp);
                        
                        if(resp.status == 'success')
                        {
                            window.location.href = '/authors';
                        }
                    }, 'json');
                }
            });
        });
    </script>
</html>
