<?php
    namespace Acme\Service;

    use Acme\Model\ResourceAbstract;
    use Acme\Model\ProductModel;
    use Slim\Http\UploadedFile;

    class ProductService extends ResourceAbstract
    {
        protected $view;
        public $filesArr = array();

        public function __construct($view) {
            $this->view = $view;
        }

        public function getLandingPage($request, $response, $args) {
            $dataArr        =   array(
                                    'name'      => "Slim",
                                    'base_url'  => $request->getUri()->getBaseUrl()
                                );
            $response       =   $this->view->render(
                $response, "index.phtml", $dataArr
            );
        }


        /**
         * @param $id
         *
         * @return string
         */
        public function getProduct($id = null)
        {
            $resDataArr = array();

            if ($id) {
                $repository = $this->getEntityManager()->getRepository(
                    'Acme\Model\ProductModel'
                );
                $products = $repository->find($id);
                if ($products) {
                    $resDataArr = $this->convertToArray($products);
                }
            }

            return json_encode($resDataArr);
        }

        /**
         * @param $id
         *
         * @return string
         */
        public function getProducts()
        {
            $resDataArr = array();

            $products = $this->getEntityManager()->getRepository(
                            'Acme\Model\ProductModel'
                        )->findAll();

            if ($products) {
                $products = array_map(
                    function ($product) {
                        return $this->convertToArray($product);
                    },
                    $products
                );
                $resDataArr = $products;
            }

            return json_encode($resDataArr);
        }

        /**
         * @param $request
         *
         * @return string
         */
        public function createProduct($request, $response, $arg)
        {
            $resDataArr = array();
            $dataArr = $request->getParsedBody();
            $productName = filter_var($dataArr['productName'],
                                FILTER_SANITIZE_STRING);

            $product = new ProductModel();
            $product->setProductName($productName);
            $product->setCreatedAt(new \Datetime());
            $product->setUpdatedAt(new \DateTime());
            $this->getEntityManager()->persist($product);
            $this->getEntityManager()->flush();

            $resDataArr = array(
                'productName' => $product->getProductName()
            );

            return json_encode($resDataArr);
        }


        /**
         * @param $request
         *
         * @return string
         */
        public function updateProduct($request, $response, $arg)
        {
            $resDataArr = array();
            $dataArr = $request->getParsedBody();
            $id = $arg['id'];
            $productName = filter_var($dataArr['productName'],
                                FILTER_SANITIZE_STRING);

            $repository = $this->getEntityManager()->getRepository(
                                'Acme\Model\ProductModel');
            $product = $repository->find($id);

            if ($product) {
                $product->setProductName($productName);
                $product->setUpdatedAt(new \Datetime());

                $this->getEntityManager()->persist($product);
                $this->getEntityManager()->flush();

                $resDataArr = array(
                    'productName' => $product->getProductName()
                );
            }

            return json_encode($resDataArr);
        }

        /**
         * @param $request
         *
         * @return string
         */
        public function deleteProduct($request, $response, $arg)
        {
            $resDataArr = array();
            $id = $arg['id'];
            $repository = $this->getEntityManager()->getRepository(
                            'Acme\Model\ProductModel');
            $product = $repository->find($id);

            if ($product === null) {
                return false;
            }

            $this->getEntityManager()->remove($product);
            $this->getEntityManager()->flush();

            return true;
        }

        private function convertToArray(ProductModel $product) {
            return array(
                'id'            => $product->getId(),
                'productName'   => $product->getProductName()
            );
        }

        public function getDataSet($request, $response, $args) {
            $dataArr        =   array(
                                    'name'      => "Slim",
                                    'base_url'  => $request->getUri()->getBaseUrl()
                                );
            $response       =   $this->view->render(
                $response, "dataset.phtml", $dataArr
            );
        }

        public function listFolder($path, $uploadedFileName = ''){
            $dir_handle = @opendir($path) or die("Unable to open $path");
            $temp = explode("/", $path);
            //$this->filesArr[] = end($temp);

            while (false !== ($file = readdir($dir_handle)))
            {
                if($file!="." && $file!="..")
                {
                    if(is_file($path . DIRECTORY_SEPARATOR . $file)){

                        $this->filesArr[] = $path . DIRECTORY_SEPARATOR . $file;
                    }
                    if (is_dir($path . DIRECTORY_SEPARATOR . $file))
                    {
                        $this->listFolder($path . DIRECTORY_SEPARATOR . $file);
                        echo "<br>";
                    }
                }
            }
            closedir($dir_handle);

            // $ffs = scandir($path);

            // unset($ffs[array_search('.', $ffs, true)]);
            // unset($ffs[array_search('..', $ffs, true)]);

            // // prevent empty ordered elements
            // if (count($ffs) < 1)
            //     return;

            // echo '<ol>';
            // foreach($ffs as $ff){
            //     echo '<li>';
            //     print_r($ff);
            //     if(is_dir($path.'/'.$ff)) $this->listFolder($path.'/'.$ff);
            //     echo '</li>';
            // }
            // echo '</ol>';


            // if(is_array($this->filesArr) && count($this->filesArr) > 0){
            //     foreach ($this->filesArr as $key => $val) {
            //         header('Content-type: text/xml');
            //         if(is_file($val) && isset(pathinfo($val)['extension'])
            //             && pathinfo($val)['extension'] == 'xml')
            //         {

            //             $file = file_get_contents($val);
            //             $cont = new \SimpleXMLElement($file);
            //             echo '<br/><b>' . 'File Name : '. pathinfo($val)['basename'] . '</b><br/>';
            //             echo $file.'<br/>';
            //         }
            //     }
            // }

            //exit;
        }

        public function uploadFile($request, $response, $args){
            $dataArr = $request->getParsedBody();
            $artFileName = strtoupper(str_replace('_', '',
                            filter_var($dataArr['art-file-name'],
                                FILTER_SANITIZE_STRING)));

            $artFileNameNo = intval(preg_replace('/[^0-9]+/', '', $artFileName), 10);
            $uploadedFiles = $request->getUploadedFiles();

            // echo "<pre>";
            // echo $artFileName;
            // print_r($uploadedFiles);
            // echo "</pre>";
            //exit;

            $root_dir = __DIR__
            . DIRECTORY_SEPARATOR
            . '..'
            . DIRECTORY_SEPARATOR
            . '..'
            . DIRECTORY_SEPARATOR
            . '..'
            . DIRECTORY_SEPARATOR
            . 'web'
            . DIRECTORY_SEPARATOR
            . 'uploads';

            $extract_path = $root_dir
                            . DIRECTORY_SEPARATOR
                            . 'extract';
            $uniqeIdFileNm = uniqid() . '_' . $artFileName;
            $raw_path = $root_dir
                        . DIRECTORY_SEPARATOR
                        . 'raw';

            $rawOrigPath = $raw_path
                            . DIRECTORY_SEPARATOR
                            . $uniqeIdFileNm;
            if(!is_dir($rawOrigPath)){
                mkdir($rawOrigPath,  0777);
            }

            $mainPckgPath = $rawOrigPath
                            . DIRECTORY_SEPARATOR
                            . $artFileName;
            if(!is_dir($mainPckgPath)){
                mkdir($mainPckgPath,  0777);
            }

            $subMainPckgPath = $mainPckgPath
                            . DIRECTORY_SEPARATOR
                            . $artFileNameNo;
            if(!is_dir($subMainPckgPath)){
                mkdir($subMainPckgPath,  0777);
            }

            $subMainPckgImgPath = $subMainPckgPath
                            . DIRECTORY_SEPARATOR
                            . 'images';
            if(!is_dir($subMainPckgImgPath)){
                mkdir($subMainPckgImgPath,  0777);
            }



            
            // $uploadedFiles = $request->getUploadedFiles();
            $msg = '';
            if(isset($uploadedFiles) && is_array($uploadedFiles) && count($uploadedFiles) > 0){
                $errorArr = array();
                foreach ($uploadedFiles as $uploadedFile) {
                    if(!$uploadedFile->getError())
                    {

                        $uploadedFileName = $this->moveUploadedFile(
                            $rawOrigPath,
                            $uploadedFile,
                            $artFileName, 
                            $artFileNameNo);

                        if($uploadedFileName)
                        {

                        }else{
                            $errorArr[] = 'Unable to upload file.';
                        }
                    }else{
                        $errorArr[] = 'Error in the file.';
                    }
                }

                if(is_array($errorArr) && count($errorArr) == 0){
                    $zipname = $artFileName . '.zip';

                    // Get real path for our folder
                    $rootPath = realpath($rawOrigPath);

                    // Initialize archive object
                    $zip = new \ZipArchive();
                    $zip->open($rawOrigPath . DIRECTORY_SEPARATOR . $zipname,
                                \ZipArchive::CREATE | \ZipArchive::OVERWRITE);


                    // Create recursive directory iterator
                    /** @var SplFileInfo[] $files */
                    $files = new \RecursiveIteratorIterator(
                        new \RecursiveDirectoryIterator($rootPath),
                        \RecursiveIteratorIterator::LEAVES_ONLY
                    );



                    foreach ($files as $name => $file)
                    {

                        // Skip directories (they would be added automatically)
                        if (!$file->isDir())
                        {
                            // Get real and relative path for current file
                            $filePath = $file->getRealPath();
                            $relativePath = substr($filePath, strlen($rootPath) + 1);

                            // Add current file to archive
                            $zip->addFile($filePath, $relativePath);
                        }
                    }

                    // Zip archive will be created only after closing object
                    $zip->close();

                    echo $uniqeIdFileNm;
                }else{

                }
            }else{
                $msg = 'invalid File';
            }
            echo $msg;
            exit;
        }


        /**
         * Moves the uploaded file to the upload directory and assigns it
         * a unique name
         * to avoid overwriting an existing uploaded file.
         *
         * @param string $directory directory to which the file is moved
         * @param UploadedFile $uploaded file uploaded file to move
         * @return string filename of moved file
         */
        public function moveUploadedFile($directory, UploadedFile $uploadedFile,
                                         $artFileName, $artFileNameNo)
        {
            $orig_file_name = $uploadedFile->getClientFilename();
            $file_name = strtolower(pathinfo($orig_file_name,PATHINFO_FILENAME));
            $extension = strtolower(pathinfo($orig_file_name,PATHINFO_EXTENSION));
            $mediaType =    strtolower($uploadedFile->getClientMediaType());

            $subMainPath = $directory 
                            . DIRECTORY_SEPARATOR 
                            . $artFileName
                            . DIRECTORY_SEPARATOR 
                            . $artFileNameNo;

            $subMainImgPath = $subMainPath
                               . DIRECTORY_SEPARATOR 
                               . 'images';

            $uniq_filename = $file_name . '.' . $extension;

            if(is_writable ($directory))
            {
                if($extension)
                {
                    if(strpos($mediaType, 'image') !== false)
                    {
                        $uploadedFile->
                        moveTo(
                        $subMainImgPath . DIRECTORY_SEPARATOR . $uniq_filename);
                    } else if (strpos($mediaType, 'pdf') !== false || strpos($mediaType, 'xml') !== false) {
                        $uploadedFile->moveTo(
                        $subMainPath . DIRECTORY_SEPARATOR . $uniq_filename);
                    }
                    
                    return $uniq_filename;
                } else {
                    if ($extension !== 'zip')
                    {
                        echo 'Invalid zip file.';
                        exit;
                    } else if ($size > 2097152){
                        echo 'File size must not be more than 2 MB.';
                        exit;
                    }
                }

            } else {
                echo 'Folder is not writable.';
                exit;
            }
        }

        public function download($fileName = ''){
            if(strpos($fileName, '_') !== false){
                $fileNameArr = explode('_', $fileName);
            }

            $xmlFile = __DIR__
                        . DIRECTORY_SEPARATOR
                        . '..'
                        . DIRECTORY_SEPARATOR
                        . '..'
                        . DIRECTORY_SEPARATOR
                        . '..'
                        . DIRECTORY_SEPARATOR
                        . 'web'
                        . DIRECTORY_SEPARATOR
                        . 'uploads'
                        . DIRECTORY_SEPARATOR
                        . 'raw'
                        . DIRECTORY_SEPARATOR
                        . $fileName
                        . DIRECTORY_SEPARATOR
                        .$fileNameArr[1];
            $this->listFolder($xmlFile);

                        echo "<pre>";
            print_r($this->filesArr);
            echo "</pre>";
            exit;


            $xml = new \DOMDocument();
            
            $packageDtl = $xml->createElement('package-details');

            $timeStamp = $xml->createElement('timestamp', date('Y-m-d h:i:s'));

            $proofCorrectors = $xml->createElement('proof-correctors');
            $subProofCorrectors = $xml->createElement('proof-corrector');
            $subProofCorrectors->setAttribute('task', 'Author Corrections');
            $subProofCorrectors->setAttribute('actor', 'Author');
            $subProofCorrectors->setAttribute('role', 'Proof Corrector');
            $subProofCorrectors->setAttribute('name', 'Maheshwari M');
            $subProofCorrectors->setAttribute('email', 'maheshwari.m@tnqsoftware.co.in');
            $proofCorrectors->appendChild( $subProofCorrectors );

            $metaData = $xml->createElement('metadata');


            $supplier =  $xml->createElement('supplier', 'TNQ');
            $packageId =  $xml->createElement('package-id', 'JASN201605716');


            $articleInfo =  $xml->createElement('article-info');


            $journalId =  $xml->createElement('journal-id', 'JASN');
            $articleId =  $xml->createElement('article-id', '201605716');
            $articleType =  $xml->createElement('article-type', 'research-article');
            $articleTitle =  $xml->createElement('article-title', 
                'Clot Structure: A Potent Mortality Risk Factor in Patients on Hemodialysis');

            $correspondingAuthors =  $xml->createElement('corresponding-authors');
            $contrib1 =  $xml->createElement('contrib');
            $name1 = $xml->createElement('name', 'Maheshwari M');
            $email1 = $xml->createElement('email', 'maheshwari.m@tnqsoftware.co.in');
            $contrib1->appendChild( $name1 );
            $contrib1->appendChild( $email1 );

            $contrib2 =  $xml->createElement('contrib');
            $name2 = $xml->createElement('name', 'Jeevitha S');
            $email2 = $xml->createElement('email', 'jeevitha.s@tnqsoftware.co.in');
            $contrib2->appendChild( $name2 );
            $contrib2->appendChild( $email2 );

            $correspondingAuthors->appendChild( $contrib1 );
            $correspondingAuthors->appendChild( $contrib2 );

        
            $articleInfo->appendChild( $journalId );
            $articleInfo->appendChild( $articleId );
            $articleInfo->appendChild( $articleType );
            $articleInfo->appendChild( $articleTitle );
            $articleInfo->appendChild( $correspondingAuthors );


            $metaData->appendChild( $supplier );
            $metaData->appendChild( $packageId );
            $metaData->appendChild( $articleInfo );


            $fileInfo = $xml->createElement('file-info', "fil");

            $packageDtl->appendChild( $timeStamp );
            $packageDtl->appendChild( $proofCorrectors );
            $packageDtl->appendChild( $metaData );
            $packageDtl->appendChild( $fileInfo );

            $xml->appendChild( $packageDtl );

            $xml->save("$xmlFile/test.xml");
            exit;


            //echo $fileName;
            if($fileName != ''){
                if(strpos($fileName, '_') !== false){
                    $fileNameArr = explode('_', $fileName);
                }
                $zipFile = __DIR__
                            . DIRECTORY_SEPARATOR
                            . '..'
                            . DIRECTORY_SEPARATOR
                            . '..'
                            . DIRECTORY_SEPARATOR
                            . '..'
                            . DIRECTORY_SEPARATOR
                            . 'web'
                            . DIRECTORY_SEPARATOR
                            . 'uploads'
                            . DIRECTORY_SEPARATOR
                            . 'raw'
                            . DIRECTORY_SEPARATOR
                            . $fileName
                            . DIRECTORY_SEPARATOR
                            .$fileNameArr[1] . '.zip';

                $file_name = basename($zipFile);
                header("Content-type: application/zip"); 
                header("Content-Disposition: attachment; filename=$file_name");
                header("Content-length: " . filesize($zipFile));
                header("Pragma: no-cache"); 
                header("Expires: 0"); 
                readfile($zipFile);
            }
            else {
                echo 'Invalid file';
            }
            exit;
        }

        public function createXML(){

        }
    }
