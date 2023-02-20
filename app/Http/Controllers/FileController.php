<?php



class FileController extends Controller
{
    private $media_type = '';
    private $upload_dir   = array(
        'images/news',
        'article',
        'contract'
    );

    private $thumb_upload = array(
        '/images/newsthumbs/',
        '/images/projectsthumbs/'
    );


//**********************
//Image config
//**********************
//set max width pixel or the max height pixel for all images
//If you set dimension limit, automatically the images that exceed this limit are convert to limit, instead
//if the images are lower the dimension is maintained
//if you don't have limit set both to 0
    private $image_max_width  = 0;
    private $image_max_height = 0;

//Automatic resizing //
//If you set true $image_resizing the script convert all images uploaded in image_width x image_height resolution
//If you set width or height to 0 the script calcolate automatically the other size
    private $image_resizing = false;
    private $image_width    = 600;
    private $image_height   = 0;

//******************
//Permits config
//******************
    private $delete_file   = false;
    private $create_folder = false;
    private $delete_folder = false;
    private $upload_files  = true;


//**********************
//Allowed extensions
//**********************
    private $ext_img   = array('jpg', 'JPG', 'jpeg', 'JPEG', 'png', 'PNG', 'gif', 'bmp', 'tiff'); //Images
    private $ext_file  = array('doc', 'docx', 'pdf', 'xls', 'xlsx', 'txt', 'csv', 'html', 'psd', 'sql', 'log', 'fla', 'xml', 'ade', 'adp', 'ppt', 'pptx'); //Files
    private $ext_video = array('mov', 'mpeg', 'mp4', 'avi', 'mpg', 'wma'); //Videos
    private $ext_music = array('mp3', 'm4a', 'ac3', 'aiff', 'mid'); //Music
    private $ext_misc  = array('zip', 'rar', 'gzip'); //Archives

    private function getConfig()
    {
        $configs = new ConfigIni(APPLICATION . '/configs/' . APPLICATION_ENV . '.ini');
        return $configs;
    }
    public function beforeExecuteRoute()
    {
        if (!\Common\UserAuth::getInstance()->getUser()) {
            $this->response->redirect('/login');
            return false;
        }
    }

    public function initialize()
    {
        $this->view->setLayout('filemanager');
    }

    public function dialogAction()
    {
        $query = $this->request->getQuery();
        $subdir = $subfolder = '';

        if(isset($query['media_type']) && !empty($query['media_type']))
            $this->media_type = $query['media_type'];
        else
            $this->media_type = 0;

        $uploadDir = $this->upload_dir[$this->media_type];
        $thumbUpload = $this->thumb_upload[$this->media_type];

        /* if(isset($query['file_upload_dir']) && !empty($query['file_upload_dir']))
             $this->upload_dir = $query['file_upload_dir'];

         if(isset($query['file_upload_thumb_dir']) && !empty($query['file_upload_thumb_dir']))
             $this->thumb_upload = $query['file_upload_thumb_dir'];*/


        if (isset($query['fldr']) && !empty($query['fldr']))
            $subdir = trim($query['fldr'], '/') . '/';

        if (isset($query['subfolder']) && $query['subfolder'] != "undefined" && !empty($query['subfolder'])) {
            $subfolder = $query['subfolder'];
            $cur_dir = $uploadDir. $subfolder . '/' . $subdir;
            $cur_path = $uploadDir . '/' . $subfolder . '/' . $subdir;
            $thumbs_path = $thumbUpload . $subfolder . '/';
        } else {
            $cur_dir = $uploadDir . '/' . $subdir;
            $cur_path = $uploadDir . '/' . $subdir;
            $thumbs_path = $thumbUpload;
        }

        $y = $m = $d = $is_files = $is_month = $is_day = false;
        $dir = explode('/',$subdir);
        $files = $res = array();
        if (count($dir) == 4) {
            $y = $dir[0];
            $m = $dir[1];
            $d = $dir[2];
            /*
            $files = ($y && $m && $d) ? \Services\NewsImgs::getInstance()->getFiles($y, $m, $d) : array();
            if (empty($files)) {
                if (@opendir(BASE_PATH . DIRECTORY_SEPARATOR . FOLDER_UPLOAD . $cur_dir))
                    $files = scandir(BASE_PATH . DIRECTORY_SEPARATOR . FOLDER_UPLOAD . $cur_dir);
                if (count($files))
                    foreach ($files as $file) {
                        if ($file != '.' && $file != '..')
                            $res[] = \Services\NewsImgs::getInstance()->add(array(
                                'year'  => $dir[0],
                                'month' => $dir[1],
                                'day'   => $dir[2],
                                'name'  => $file
                            ));
                    }
                @closedir(BASE_PATH . DIRECTORY_SEPARATOR . FOLDER_UPLOAD . $cur_dir);
            }
            */
            $is_files = true;

        }
        if(count($dir) == 3){
            $y = $dir[0];
            $m = $dir[1];
            $is_day = true;
        }
        if(count($dir) == 2){
            $y = $dir[0];
            $is_month = true;
        }

        if($this->media_type == 1)
            $obj = \Services\Articlefiles::getInstance();
        else
            $obj = \Services\NewsImgs::getInstance();

        $year = $obj->getYDir();
        $month = $y ? $obj->getYMonth($y) : array();
        $day = ($y && $m) ? $obj->getYMDay($y, $m) : array();
        $files = ($y && $m && $d) ? $obj->getFiles($y, $m, $d) : array();

        $this->view->setVars(array(
            'year'          => $year,
            'month'         => $month,
            'day'           => $day,
            'files'         => $files,
            'is_files'      => $is_files,
            'is_month'      => $is_month,
            'is_day'        => $is_day,
            'queryRequest'  => $query,
            'subdir'        => $subdir,
            'subfolder'     => $subfolder,
            'upload_dir'    => $uploadDir,
            'media_type'    =>  $this->media_type,
            'cur_dir'       => $cur_dir,
            'thumbs_path'   => $thumbs_path,
            'cur_path'      => $cur_path,
            'upload_files'  => $this->upload_files,
            'delete_file'   => $this->delete_file,
            'create_folder' => $this->create_folder,
            'delete_folder' => $this->delete_folder,
            'popup'         => isset($query['popup']) ? $query['popup'] : 0,
            'title_doc'     => $query['title'],
            'ext'           => array_merge($this->ext_img, $this->ext_file, $this->ext_misc, $this->ext_video, $this->ext_music), //allowed extensions
            'ext_img'       => $this->ext_img,
            'ext_file'      => $this->ext_file,
            'ext_misc'      => $this->ext_misc,
            'ext_music'     => $this->ext_music,
            'ext_video'     => $this->ext_video
        ));

    }

    public function dialogImgAction()
    {
        $query = $this->request->getQuery();
        $subdir = $subfolder = '';

        if(isset($query['media_type']) && !empty($query['media_type']))
            $this->media_type = $query['media_type'];
        else
            $this->media_type = 0;

        $uploadDir = $this->upload_dir[$this->media_type];
        $thumbUpload = $this->thumb_upload[$this->media_type];

        if (isset($query['fldr']) && !empty($query['fldr']))
            $subdir = trim($query['fldr'], '/') . '/';

        if (isset($query['subfolder']) && $query['subfolder'] != "undefined" && !empty($query['subfolder'])) {
            $subfolder = $query['subfolder'];
            $cur_dir = $uploadDir. $subfolder . '/' . $subdir;
            $cur_path = $uploadDir . '/' . $subfolder . '/' . $subdir;
            $thumbs_path = $thumbUpload . $subfolder . '/';
        } else {
            $cur_dir = $uploadDir . '/' . $subdir;
            $cur_path = $uploadDir . '/' . $subdir;
            $thumbs_path = $thumbUpload;
        }

        $y = $m = $d = $is_files = $is_month = $is_day = false;
        $dir = explode('/',$subdir);
        $files = $res = array();
        if (count($dir) == 4) {
            $y = $dir[0];
            $m = $dir[1];
            $d = $dir[2];
            $is_files = true;
        }
        if(count($dir) == 3){
            $y = $dir[0];
            $m = $dir[1];
            $is_day = true;
        }
        if(count($dir) == 2){
            $y = $dir[0];
            $is_month = true;
        }

        if($this->media_type == 1)
            $obj = \Services\NewsImgs::getInstance();
        else
            $obj = \Services\NewsImgs::getInstance();

        $year = $obj->getYDir();
        $month = $y ? $obj->getYMonth($y) : array();
        $day = ($y && $m) ? $obj->getYMDay($y, $m) : array();
        $files = ($y && $m && $d) ? $obj->getFiles($y, $m, $d) : array();

        $this->view->setVars(array(
            'year'          => $year,
            'month'         => $month,
            'day'           => $day,
            'files'         => $files,
            'is_files'      => $is_files,
            'is_month'      => $is_month,
            'is_day'        => $is_day,
            'queryRequest'  => $query,
            'subdir'        => $subdir,
            'subfolder'     => $subfolder,
            'upload_dir'    => $uploadDir,
            'media_type'    =>  $this->media_type,
            'cur_dir'       => $cur_dir,
            'thumbs_path'   => $thumbs_path,
            'cur_path'      => $cur_path,
            'upload_files'  => $this->upload_files,
            'delete_file'   => $this->delete_file,
            'create_folder' => $this->create_folder,
            'delete_folder' => $this->delete_folder,
            'popup'         => isset($query['popup']) ? $query['popup'] : 0,
            'title_doc'     => $query['title'],
            'ext'           => array_merge($this->ext_img, $this->ext_file, $this->ext_misc, $this->ext_video, $this->ext_music), //allowed extensions
            'ext_img'       => $this->ext_img,
            'ext_file'      => $this->ext_file,
            'ext_misc'      => $this->ext_misc,
            'ext_music'     => $this->ext_music,
            'ext_video'     => $this->ext_video
        ));

    }

    /**
     * function execute route file/upload
     * @return mixed
     */
    public function uploadAction()
    {
        $this->view->setRenderLevel(View::LEVEL_NO_RENDER);

        $config = \Common\Helper::getConfig()->file;
        $res = array('file_path' => '', 'message' => '');
        $language = \Common\Languages::files();

        if ($this->request->isPost() && $this->request->hasFiles() == true) {
            $file = $this->request->getUploadedFiles()[0];

            if (isset($file) && $file) {
                if ($file->getSize() > $config->upload->max_size) {
                    $res['message'] = $language['fileTooLarge'];
                } else {
                    $file_type = strtolower($file->getType());

                    //if ($file_type == 'image/png' || $file_type == 'image/gif'|| $file_type == 'image/jpg' || $file_type == 'image/jpeg'
                    //    || $file_type == 'application/msword' || $file_type == 'application/pdf' || $file_type == 'application/vnd.ms-excel'
                    //) {

                    $mediaType = $this->request->get('media_type');
                    // request check not resize image
                    $notresize = 'false'; $title_doc = '';
                    $dataRequest = $this->request->getPost();
                    $notresize   = $dataRequest['notresize'];
                    if($notresize=='true'&&isset($dataRequest['title_doc']))
                        $title_doc = \Common\Helper::getInstance()->ascii_link($dataRequest['title_doc']);

                    if(isset($mediaType) && !empty($mediaType) && $mediaType == 1){
                        $folderPath = \Common\Helper::getInstance()->generatePathArticleDir();
                    }else if(isset($mediaType) && !empty($mediaType) && $mediaType == 2){
                        $folderPath = \Common\Helper::getInstance()->generatePathcontractDir();
                    }else {
                        $folderPath = \Common\Helper::getInstance()->generatePathDir();
                    }

                    //create folder
                    \Common\Images::createDir(FOLDER_UPLOAD . "/" . $folderPath);

                    //upload file
                    $filePath = $this->resultUpload($file, $folderPath, $config, $mediaType, $title_doc);
                    if ($filePath) {
                        //success
                        $res["file_path"] = $filePath;
                    } else {
                        //fail
                        $res['message'] = $language['uploadFail'];
                    }

                    // } else {
                    //     $res['message'] = $language['imageInvalid'];
                    // }
                }
            } else {
                $res['message'] = $language['fileNotFound'];
            }
        } else {
            $res['message'] = $language['fileNotFound'];
        }
        return $this->response->setContent(json_encode($res));

    }

    /**
     * @param $file
     * @param $folderPath
     * @param $userConfig
     * @return null|string
     */
    private function resultUpload($file, $folderPath, $config, $mediaType, $title_doc='')
    {
        $fileName = \Common\Images::getInstance()->renameFileExist(FOLDER_UPLOAD . "/" . $folderPath, $file->getName());
        //doi ten file up tuannd 13/04/2018
        //$salt = \Common\UserAuth::getInstance()->generateString(6);
        if( $title_doc!='' ){
            $fileNameArr = explode('.', $fileName);
            if($fileNameArr[1]!=''){
                $fileName    = $title_doc.'.'.$fileNameArr[1];
            }
        }
        $fullPath = BASE_PATH . "/" . FOLDER_UPLOAD . "/" . $folderPath . $fileName;

        if ($file->moveTo($fullPath)) {
            if($mediaType == 2)
                return "/" . $folderPath . $fileName;

            if($mediaType == 1)
                $obj = \Services\Articlefiles::getInstance();
            else
                $obj = \Services\NewsImgs::getInstance();

            $obj->add(array(
                'year'  => date("Y"),
                'month' => date("m"),
                'day'   => date("d"),
                'name'  => $fileName
            ));
            if($mediaType == 1)
                return "/" . $folderPath . $fileName;

            $size = getimagesize($fullPath);
            $max_width = $config->image->width;
            $max_height = $config->image->height;

            if( ($size[0] >= $max_width || $size[1] >= $max_height) ) {
                \Common\Images::getInstance()->imageResize($fullPath, $fullPath, $max_width, $max_height);
            } else {
                \Common\Images::getInstance()->imageResize($fullPath, $fullPath, $size[0], $size[1]);
            }
            return "/" . $folderPath . $fileName;
        }

        return null;
    }


    public function forcedownloadAction()
    {
        $this->view->setRenderLevel(View::LEVEL_NO_RENDER);

        $path = $_POST['path'];
        $name = $_POST['name'];

        if(isset($query['media_type']) && !empty($query['media_type']))
            $this->media_type = $query['media_type'];
        else
            $this->media_type = 0;

        if (strpos($path, $this->upload_dir[$this->media_type]) === FALSE) die('wrong path');

        header('Pragma: private');
        header('Cache-control: private, must-revalidate');
        header("Content-Type: application/octet-stream");
        header("Content-Length: " . (string)(filesize($path)));
        header('Content-Disposition: attachment; filename="' . ($name) . '"');
        readfile($path);
        exit;
    }

    public function dialogDirAction(){

        $query = $this->request->getQuery();
        $subdir = $subfolder = '';

        if(isset($query['media_type']) && !empty($query['media_type']))
            $this->media_type = $query['media_type'];
        else
            $this->media_type = 0;

        $uploadDir = $this->upload_dir[$this->media_type];
        $thumbUpload = $this->thumb_upload[$this->media_type];

        if (isset($query['fldr']) && !empty($query['fldr']))
            $subdir = trim($query['fldr'], '/') . '/';

        if (isset($query['subfolder']) && $query['subfolder'] != "undefined" && !empty($query['subfolder'])) {
            $subfolder = $query['subfolder'];
            $cur_dir = $uploadDir . $subfolder . '/' . $subdir;
            $cur_path = $uploadDir . '/' . $subfolder . '/' . $subdir;
            $thumbs_path = $thumbUpload . $subfolder . '/';
        } else {
            $cur_dir = $uploadDir . '/' . $subdir;
            $cur_path = $uploadDir . '/' . $subdir;
            $thumbs_path = $thumbUpload;
        }

        $this->view->setVars(array(
            'queryRequest'  => $query,
            'subdir'        => $subdir,
            'subfolder'     => $subfolder,
            'upload_dir'    => $uploadDir,
            'cur_dir'       => $cur_dir,
            'thumbs_path'   => $thumbs_path,
            'cur_path'      => $cur_path,
            'upload_files'  => $this->upload_files,
            'delete_file'   => $this->delete_file,
            'create_folder' => $this->create_folder,
            'delete_folder' => $this->delete_folder,
            'popup'         => isset($query['popup']) ? $query['popup'] : 0,
            'ext'           => array_merge($this->ext_img, $this->ext_file, $this->ext_misc, $this->ext_video, $this->ext_music), //allowed extensions
            'ext_img'       => $this->ext_img,
            'ext_file'      => $this->ext_file,
            'ext_misc'      => $this->ext_misc,
            'ext_music'     => $this->ext_music,
            'ext_video'     => $this->ext_video
        ));


    }


}
