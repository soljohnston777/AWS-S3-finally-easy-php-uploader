
<?php
if(isset($_FILES["file"])) {    // make sure file is uploaded

    # composer dependencies
    require '/vendor/aws-autoloader.php';
    //AWS access info  DEFINE command makes your Key and Secret more secure
    if (!defined('awsAccessKey')) define('awsAccessKey', 'ACCESS_KEY_HERE');///  <- put in your key instead of ACCESS_KEY_HERE
    if (!defined('awsSecretKey')) define('awsSecretKey', 'SECRET_KEY_HERE');///  <- put in your secret instead of SECRET_KEY_HERE


    $config = [
        's3-access' => [
            'key' => awsAccessKey,
            'secret' => awsSecretKey,
            'bucket' => 'videos.for.secret-lesson',
            'region' => 'us-east-1', // 'US East (N. Virginia)' is 'us-east-1', research this because if you use the wrong one it won't work!
            'version' => 'latest',
            'acl' => 'public-read',
            'private-acl' => 'private'
        ] 
    ]; 

    # initializing s3 
    $s3 = Aws\S3\S3Client::factory([ 
        'credentials' => [ 
            'key' => $config['s3-access']['key'],
            'secret' => $config['s3-access']['secret'] 
        ], 
        'version' => $config['s3-access']['version'], 
        'region' => $config['s3-access']['region'] 
    ]);

    # initializes everything
    // require_once __DIR__.'/initialize.php';

    # lets upload our file to s3 
    try{ 
        # file to upload
    // echo getcwd();

        $file_name = $_FILES['file']['name']; // File name
        $size = $_FILES['file']['size']; // File size
        $tmp = $_FILES['file']['tmp_name']; //temporary area uploaded files goes to on server
        $ext = strtolower(pathinfo($file_name,PATHINFO_EXTENSION)); // Extension for example .jpg , .gif, .png, .mp4, etc...

        $file_path = './'; # this is the sample file that we are going to upload
        $file_name_and_path = $file_path . $file_name;
        // $file_name = pathinfo($file_path)['basename'];

        # actual uploading 
        $request_status = $s3->putObject([ 
            'Bucket' => $config['s3-access']['bucket'], 
            'Key' => $file_name_and_path, # 'from_php_script' will be our folder on s3 (this would be automatically created)
            'Body' => fopen($tmp, 'rb'), # reading the file in the 'binary' mode
            'ACL' => $config['s3-access']['acl']
        ]); 

        # printing result
        ?>
    <pre>
    <?php print_r($request_status); /* <pre> makes it readable output and print_r shows you the data of the array of info */    ?>
    </pre>
    <?php
    }catch(Exception $ex){
        echo "Error Occurred\n", $ex->getMessage(); 
    }
}
?>
 

<form action="" method='post' enctype="multipart/form-data">
Upload image file here
<input type='file' name='file'/>
<input type='submit' value='Upload Image'/>
</form>
