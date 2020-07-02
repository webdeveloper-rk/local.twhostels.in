<?php
include "vendor/autoload.php";
use Aws\S3\S3Client;
use League\Flysystem\AwsS3v3\AwsS3Adapter;
use League\Flysystem\Filesystem;
  function saveToSpace(){

    $client = S3Client::factory([
        'credentials' => [
            'key' => 'RY6YA4BF3RXRJD6ZD6V3',
            'secret' => 'Mvd/nqlzUpLswMEpUAmRVHnt3OU+93KUYj81fPGKuro'
        ],
      
        'region' => 'nyc3', // Region you selected on time of space creation
        'endpoint' => 'https://nyc3.digitaloceanspaces.com',
        'version' => 'latest',
		'scheme'  => 'http'
    ]);
    
    $adapter = new AwsS3Adapter($client, 'apsocial');
    $filesystem = new Filesystem($adapter);
	
   	
	$file_name  =   uniqid()."-".basename($_FILES['fileToUpload']['name']);
	//echo file_get_contents($_FILES['fileToUpload']['tmp_name']);die;
    $flag = $filesystem->put($file_name, file_get_contents($_FILES['fileToUpload']['tmp_name']),['visibility' => 'public']   );
	if($flag)
	{
		$url = "https://apsocial.nyc3.digitaloceanspaces.com/".$file_name;
	}
        
	echo $url; 
}
if(isset($_FILES['fileToUpload']['name'])){
saveToSpace();
}

?>

<div>
    <form action="" method="post" enctype="multipart/form-data">
        Select image to upload:
        <input type="file" name="fileToUpload" id="fileToUpload">
        <input type="submit" value="Upload Image" name="submit">
    </form>

   
</div>