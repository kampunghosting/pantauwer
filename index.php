<?php
include 'function.php';
if(isset($_POST['url'])){
    $domain = $_POST['url'];
    addDomain($domain);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Google Index Pantauwers</title>

    <link href="//maxcdn.bootstrapcdn.com/bootswatch/3.3.6/yeti/bootstrap.min.css" rel="stylesheet" integrity="sha384-yxFy3Tt84CcGRj9UI7RA25hoUMpUPoFzcdPtK3hBdNgEGnh9FdKgMVM+lbAZTKN2" crossorigin="anonymous">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->

    <style>
        .header {
            border-bottom: 1px solid #e5e5e5;
        }

        @media (min-width: 768px) {
            .container {
                max-width: 730px;
            }

            .header {
                margin-bottom: 30px;
            }
        }
    </style>


</head>
<body>

    <div class="container">

        <div class="header text-center">
            <h3 class="text-muted">Google Index Pantauwers</h3>
        </div>

        <div class="row">
            <div class="col-md-12">
                <form id="form-demo" action="" class="form-horizontal" method="POST">
                    
                    <div class="form-group">
                        <div class="col-md-3">
                            Add your new URL : 
                        </div>
                        <div class="col-md-7">
                            <input type="text" class="form-control text-center" id="url" name="url" placeholder="Enter URL">
                        </div>
                        <div class="col-md-2 form-group text-center">
                            <button id="btn" type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </div>
                
                    <table id="result" class="table table-bordered table-striped">
                        <tr>
                           <td width="2%">No</td>
                            <td width="20%">site</td>
                            <td width="20%">Index</td>
                            <td width="20%">Status</td>

                        </tr>
                    <?php
                        $linex = file("domain.txt", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
                        $no = 1;
                        foreach ($linex as $url) {
                            $data = GoogleIndexPage($url);
                            sleep(5);
                           echo '<tr>
                                    <td>'.$no.'</td>
                                    <td><a href="http://'.$data['url'].'" target="_blank">'.$data['url'].'</a></td>
                                    <td>'.$data['count'].'</td>
                                    <td><a href="'.$data['google'].'" target="_blank">'.$data['status'].'</a></td>

                                </tr>';
                                $no++;
                        }
                    ?>
                    </table>

                </form>
            </div>
        </div>
    </div>

</body>
</html>