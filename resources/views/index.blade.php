<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>File Uploads</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">

</head>
<body>
  <div class="container">
    <form method="post" action="uploadfile" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
        <label for="email">Image:</label>
             <input type="file" name="image" accept="Image.*" class="form-control"  required="true" />
        </div>
        <button type="submit" class="btn btn-info" >Upload</button>
    </form>

    @if ($images = Session::get('images'))
           <p>Input Image:</p>
               <img src="{{ $images['image_name'] }}">
           <br/>
        
   @endif


</div>
</body>
</html>