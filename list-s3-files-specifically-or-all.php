# composer dependencies
require '/vendor/aws-autoloader.php';
//AWS access info  DEFINE command makes your Key and Secret more secure
if (!defined('awsAccessKey')) define('awsAccessKey', 'ACCESS_KEY_HERE');///  <- put in your key instead of ACCESS_KEY_HERE
if (!defined('awsSecretKey')) define('awsSecretKey', 'SECRET_KEY_HERE');///  <- put in your secret instead of SECRET_KEY_HERE


use Aws\S3\S3Client;

$config = [
    's3-access' => [
        'key' => awsAccessKey,
        'secret' => awsSecretKey,
        'bucket' => 'bucket',
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
$bucket = 'bucket';

$objects = $s3->getIterator('ListObjects', array(
    "Bucket" => $bucket,
    "Prefix" => 'filename' //must have the trailing forward slash for folders "folder/" or just type the beginning of a filename "pict" to list all of them like pict1, pict2, etc.
));

foreach ($objects as $object) {
    echo $object['Key'] . "<br>";
}
