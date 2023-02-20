<input type="hidden" id="popup" value="<?php echo $popup; ?>"/>
<input type="hidden" id="track" value="<?php echo $queryRequest['editor']; ?>"/>
<input type="hidden" id="cur_dir" value="https://cdn.xoso666.com/upload"/>
<input type="hidden" id="cur_dir_thumb" value="<?php echo IMG_URL . $thumbs_path . $subdir; ?>"/>
<input type="hidden" id="root" value="<?php echo WEB_URL; ?>"/>
<input type="hidden" id="insert_folder_name"
       value="<?php echo \Common\Languages::files()['lang_Insert_Folder_Name']; ?>"/>
<input type="hidden" id="new_folder" value="<?php echo \Common\Languages::files()['lang_New_Folder']; ?>"/>
<input type="hidden" id="base_url" value="<?php //echo WEB_URL ?>"/>
<input type="hidden" id="editor" value="<?php echo $_GET['editor']; ?>"/>
<script>
    var media_type = '';
    <?php if(isset($queryRequest['media_type']) && !empty($queryRequest['media_type'])) : ?>
    media_type = <?php echo $queryRequest['media_type'] ?>
    <?php endif;?>
</script>

<!----- uploader div start ------->
<?php if ($upload_files): ?>
    <div class="uploader" style="top:40px;display:block;">
        <form action="" method="apost" enctype="multipart/form-data" id="myAwesomeDropzoneImg" class="dropzone">
            <input type="hidden" name="path" value="<?php echo $cur_path ?>"/>
            <input type="hidden" name="path_thumb" value="<?php echo $thumbs_path . $subdir ?>"/>
            <div class="fallback">
                <?php echo \Common\Languages::files()['lang_Upload_file']; ?>:<br/>
                <input name="file" type="file"/>
                <input type="hidden" name="fldr"
                       value="<?php echo isset($queryRequest['fldr']) ? $queryRequest['fldr'] : ''; ?>"/>
                <input type="hidden" name="type"
                       value="<?php echo isset($queryRequest['type']) ? $queryRequest['type'] : ''; ?>"/>
                <input type="hidden" name="field_id"
                       value="<?php echo isset($queryRequest['field_id']) ? $queryRequest['field_id'] : ''; ?>"/>
                <input type="hidden" name="popup" value="<?php echo $popup; ?>"/>
                <input type="hidden" name="editor"
                       value="<?php echo isset($queryRequest['editor']) ? $queryRequest['editor'] : ''; ?>"/>
                <input type="hidden" name="lang"
                       value="<?php echo isset($queryRequest['lang']) ? $queryRequest['lang'] : ''; ?>"/>
                <input type="hidden" name="subfolder"
                       value="<?php echo isset($queryRequest['subfolder']) ? $queryRequest['subfolder'] : ''; ?>"/>
                <input type="submit" name="submit" value="OK"/>
            </div>
        </form>
        <center>
            <button class="btn btn-large btn-inverse close-uploaderimg"><i class="icon-backward icon-white"></i> Quay lại danh sách
            </button>
        </center>
        <div class="space10"></div>
        <div class="space10"></div>
    </div>
<?php endif; ?>
<!----- uploader div start ------->


<div class="container-fluid">

    <!----- header div start ------->
    <div class="filters">
        <div class="row-fluid">
            <div class="span4">
                <?php if ($upload_files): ?>
                    <button class="btn btn-inverse upload-btn" style="margin-left:5px;"><i
                            class="icon-upload icon-white"></i> <?php echo \Common\Languages::files()['lang_Upload_file']; ?>
                    </button>
                <?php endif; ?>
                <?php if ($create_folder): ?>
                    <button class="btn new-folder" style="margin-left:5px;"><i
                            class="icon-folder-open"></i> <?php echo \Common\Languages::files()['lang_New_Folder']; ?>
                    </button>
                <?php endif; ?>
            </div>
            <div class="span4 pull-left">
                <div class="span4 pull-right">
                    <?php /**  if (isset($queryRequest['type']) && in_array($queryRequest['type'], array(0, 2))) { ?>
                     * <div class="pull-right"><?php echo \Common\Languages::files()['lang_Filter']; ?> :
                     * <input id="select-type-all" name="radio-sort" type="radio" data-item="ff-item-type-all"
                     * class="hide"/>
                     * <label id="ff-item-type-all" for="select-type-all"
                     * class="btn btn-inverse ff-label-type-all"><?php echo \Common\Languages::files()['lang_All']; ?></label>
                     * <input id="select-type-1" name="radio-sort" type="radio" data-item="ff-item-type-1"
                     * checked="checked" class="hide"/>
                     * <label id="ff-item-type-1" for="select-type-1"
                     * class="btn ff-label-type-1"><?php echo \Common\Languages::files()['lang_Files']; ?></label>
                     * <input id="select-type-2" name="radio-sort" type="radio" data-item="ff-item-type-2"
                     * class="hide"/>
                     * <label id="ff-item-type-2" for="select-type-2"
                     * class="btn ff-label-type-2"><?php echo \Common\Languages::files()['lang_Images']; ?></label>
                     * <input id="select-type-3" name="radio-sort" type="radio" data-item="ff-item-type-3"
                     * class="hide"/>
                     * <label id="ff-item-type-3" for="select-type-3"
                     * class="btn ff-label-type-3"><?php echo \Common\Languages::files()['lang_Archives']; ?></label>
                     * <input id="select-type-4" name="radio-sort" type="radio" data-item="ff-item-type-4"
                     * class="hide"/>
                     * <label id="ff-item-type-4" for="select-type-4"
                     * class="btn ff-label-type-4"><?php echo \Common\Languages::files()['lang_Videos']; ?></label>
                     * <input id="select-type-5" name="radio-sort" type="radio" data-item="ff-item-type-5"
                     * class="hide"/>
                     * <label id="ff-item-type-5" for="select-type-5"
                     * class="btn ff-label-type-5"><?php echo \Common\Languages::files()['lang_Music']; ?></label>
                     * </div>
                     * <?php } **/ ?>
                </div>
            </div>

        </div>
        <!----- header div end ------->

        <!----- breadcrumb div start ------->
        <div class="row-fluid">
            <?php
            $mediaType = $media_type;//isset($queryRequest['media_type']) ? $queryRequest['media_type'] : '';

            $link = $this->url->get('/file/dialogimg') . "?type=" . (isset($queryRequest['type']) ? $queryRequest['type'] : 2) . "&editor=";
            $link .= isset($queryRequest['editor']) ? $queryRequest['editor'] : 'mce_0';
            $link .= "&popup=" . $popup . "&lang=";
            $link .= isset($queryRequest['lang']) ? $queryRequest['lang'] : 'en_EN';
            $link .= "&field_id=";
            $link .= isset($queryRequest['field_id']) ? $queryRequest['field_id'] : '';
            $link .= "&subfolder=" . $subfolder;
            $link .= "&media_type=". $mediaType;
            $link .= "&fldr=";

            ?>
            <ul class="breadcrumb">
                <li class="pull-left"><a href="<?php echo $link ?>"><i class="icon-home"></i></a></li>
                <li><span class="divider">/</span></li>
                <?php
                $bc = explode('/', $subdir);
                $tmp_path = '';
                if (!empty($bc))
                    foreach ($bc as $k => $b) {
                        $tmp_path .= $b . "/";
                        if ($k == count($bc) - 2) {
                            ?>
                            <li class="active"><?php echo $b ?></li><?php
                        } elseif ($b != "") { ?>
                            <li><a href="<?php echo $link . $tmp_path ?>"><?php echo $b ?></a></li>
                            <li><span class="divider">/</span></li>
                        <?php }
                    }
                ?>
                <li class="pull-right"><a id="refresh" onclick="loading.open()"
                                          href="<?php echo $this->url->get('/file/dialogimg') ?>?type=<?php echo isset($queryRequest['type']) ? $queryRequest['type'] : 2; ?>&editor=<?php echo isset($queryRequest['editor']) ? $queryRequest['editor'] : 'mce_0'; ?>&subfolder=<?php echo $subfolder ?>&popup=<?php echo $popup; ?>&field_id=<?php echo isset($queryRequest['field_id']) ? $queryRequest['field_id'] : ''; ?>&lang=<?php echo isset($queryRequest['lang']) ? $queryRequest['lang'] : 'en_EN'; ?>&fldr=<?php echo $subdir ?>&<?php echo uniqid() ?>&media_type=<?php echo $mediaType?>">
                        <i class="icon-refresh"></i></a></li>
            </ul>
        </div>
        <!----- breadcrumb div end ------->


        <div class="row-fluid ff-container">
            <div class="span12">
                <?php if (!empty($year) || ($is_month && !empty($month)) || ($is_day && empty($day))) { ?>
                    <h4 id="help">Swipe the name of file/folder to show options</h4>

                    <!--ul class="thumbnails ff-items"-->
                    <ul class="grid cs-style-2">
                        <?php
                        $class_ext = '';
                        $src = '';
                        $i = 0;
                        $start = false;
                        $end = false;
                        if (isset($queryRequest['type']) && $queryRequest['type'] == 1) $apply = 'apply_img';
                        // elseif (isset($queryRequest['type']) && $queryRequest['type'] == 2) $apply = 'apply_link';
                        elseif (isset($queryRequest['type']) && $queryRequest['type'] == 2) $apply = 'apply';
                        elseif (isset($queryRequest['type']) && $queryRequest['type'] == 0 && isset($queryRequest['field_id']) && $queryRequest['field_id'] == '') $apply = 'apply_none';
                        elseif (isset($queryRequest['type']) && $queryRequest['type'] == 3 || isset($queryRequest['type']) && $queryRequest['type'] == 4 || isset($queryRequest['type']) && $queryRequest['type'] == 5) $apply = 'apply_video';
                        else $apply = 'apply';

                        if (!$is_files) {
                            $folder = $year;
                            $folder = $is_month ? $month : $folder;
                            $folder = $is_day ? $day : $folder;
                            foreach ($folder as $_sub) {
                                $sub = $_sub[0] > 9 ? $_sub[0] : '0' . $_sub[0];
                                //add in thumbs folder if not exist
                                $class_ext = 3;
                                $src = $_src = $subdir . $sub . "/";
                                if ($i == 0 && trim($subdir) != '') {
                                    $_src = explode('/', $subdir);
                                    unset($_src[count($_src) - 2]);
                                    $_src = implode('/', $_src);
                                }
                                ?>
                                <?php if ($i == 0) { ?>
                                    <li>
                                        <figure>
                                            <a title="<?php echo \Common\Languages::files()['lang_Open']; ?>" onclick="loading.open()"
                                               href="<?php echo $this->url->get('/file/dialogimg'); ?>?type=<?php echo isset($queryRequest['type']) && $queryRequest['type'] ? $queryRequest['type'] : 2 ?>&subfolder=<?php echo $subfolder ?>&editor=<?php echo isset($queryRequest['editor']) ? $queryRequest['editor'] : 'mce_0'; ?>&popup=<?php echo $popup; ?>&field_id=<?php echo isset($queryRequest['field_id']) ? $queryRequest['field_id'] : ''; ?>&lang=<?php echo isset($queryRequest['lang']) ? $queryRequest['lang'] : 'en_EN'; ?>&fldr=<?php echo $_src ?>&<?php echo uniqid(); ?>&media_type=<?php echo $mediaType?>">
                                                <div class="img-precontainer">
                                                    <div class="img-container directory"><span></span>
                                                        <img class="directory-img"
                                                             src="<?php echo STATIC_URL; ?>/lib/tinymce/plugins/filemanager/ico/folder_return.png"
                                                             alt="folder"/>
                                                    </div>
                                                </div>
                                            </a>
                                        </figure>
                                    </li>
                                <?php } ?>

                                <li>
                                    <figure>
                                        <a title="<?php echo \Common\Languages::files()['lang_Open']; ?>" onclick="loading.open()"
                                           href="<?php echo $this->url->get('/file/dialogimg'); ?>?type=<?php echo isset($queryRequest['type']) && $queryRequest['type'] ? $queryRequest['type'] : 2 ?>&subfolder=<?php echo $subfolder ?>&editor=<?php echo isset($queryRequest['editor']) ? $queryRequest['editor'] : 'mce_0'; ?>&popup=<?php echo $popup; ?>&field_id=<?php echo isset($queryRequest['field_id']) ? $queryRequest['field_id'] : ''; ?>&lang=<?php echo isset($queryRequest['lang']) ? $queryRequest['lang'] : 'en_EN'; ?>&fldr=<?php echo $src ?>&<?php echo uniqid(); ?>&media_type=<?php echo $mediaType?>">
                                            <div class="img-precontainer">
                                                <div class="img-container directory"><span></span>
                                                    <img class="directory-img"
                                                         src="<?php echo STATIC_URL; ?>/lib/tinymce/plugins/filemanager/ico/folder.png"
                                                         alt="folder"/>
                                                </div>
                                            </div>
                                        </a>
                                        <div class="box">
                                            <h4><?php echo $sub ?></h4>
                                        </div>
                                    </figure>
                                </li>
                                <?php
                                $i++;
                            }
                        }

                        if ($is_files && !empty($files)) {
                            foreach ($files as $nu => $objFile) {
                                $file = $objFile['name'];
                                $is_img = false;
                                $is_video = false;
                                $show_original = false;
                                $file_ext = strtolower(substr(strrchr($file, '.'), 1));
                                if (in_array($file_ext, $ext)) {
                                    if (in_array($file_ext, $ext_img)) {
                                        $src = WEB_URL . DIRECTORY_SEPARATOR . $cur_dir . $file;
                                        $folder_thumb = BASE_PATH . DIRECTORY_SEPARATOR . FOLDER_UPLOAD . $thumbs_path . $subdir;
                                        if (!is_dir($folder_thumb)) \Common\Images::createDir($folder_thumb);
                                        //add in thumbs folder if not exist
                                        $src_thumb = $thumbs_path . $subdir . $file;
                                        if (!file_exists(BASE_PATH . DIRECTORY_SEPARATOR . FOLDER_UPLOAD . $thumbs_path . $subdir . $file)) {
                                            $info = getimagesize(BASE_PATH . DIRECTORY_SEPARATOR . FOLDER_UPLOAD . DIRECTORY_SEPARATOR . $upload_dir . $subfolder . DIRECTORY_SEPARATOR . $subdir . $file);
                                            if (!$info) {
                                                $src_thumb = $upload_dir . DIRECTORY_SEPARATOR . $subdir . $file;
                                                $show_original = true;
                                            } else {
                                                \Common\Images::imageResize(BASE_PATH . DIRECTORY_SEPARATOR . FOLDER_UPLOAD . DIRECTORY_SEPARATOR . $upload_dir . $subfolder . DIRECTORY_SEPARATOR . $subdir . $file, BASE_PATH . DIRECTORY_SEPARATOR . FOLDER_UPLOAD . $thumbs_path . $subdir . $file, 122, 91);
                                            }
                                        }
                                        $is_img = true;
                                    } elseif (file_exists('ico/' . strtoupper($file_ext) . ".png")) {
                                        $src = $src_thumb = 'ico/' . strtoupper($file_ext) . ".png";
                                    } else {
                                        $src = $src_thumb = "ico/Default.png";
                                    }

                                    if (in_array($file_ext, $ext_video)) {
                                        $class_ext = 4;
                                        $is_video = true;
                                    } elseif (in_array($file_ext, $ext_img)) {
                                        $class_ext = 2;
                                    } elseif (in_array($file_ext, $ext_music)) {
                                        $class_ext = 5;
                                    } elseif (in_array($file_ext, $ext_misc)) {
                                        $class_ext = 3;
                                    } else {
                                        $class_ext = 1;
                                    }
                                    $_src = $subdir;
                                    if ($i == 0 && trim($subdir) != '') {
                                        $_src = explode('/', $subdir);
                                        unset($_src[count($_src) - 2]);
                                        $_src = implode('/', $_src);
                                    }
                                    if ($i == 0) { ?>
                                        <li>
                                            <figure>
                                                <a title="<?php echo \Common\Languages::files()['lang_Open']; ?>"
                                                   onclick="loading.open()"
                                                   href="<?php echo $this->url->get('/file/dialogimg'); ?>?media_type=<?php echo isset($queryRequest['media_type']) && $queryRequest['media_type'] ? $queryRequest['media_type'] : '' ?>&type=<?php echo isset($queryRequest['type']) && $queryRequest['type'] ? $queryRequest['type'] : 2 ?>&subfolder=<?php echo $subfolder ?>&editor=<?php echo isset($queryRequest['editor']) ? $queryRequest['editor'] : 'mce_0'; ?>&popup=<?php echo $popup; ?>&field_id=<?php echo isset($queryRequest['field_id']) ? $queryRequest['field_id'] : ''; ?>&lang=<?php echo isset($queryRequest['lang']) ? $queryRequest['lang'] : 'en_EN'; ?>&fldr=<?php echo $_src ?>&<?php echo uniqid(); ?>&media_type=<?php echo $mediaType?>">
                                                    <div class="img-precontainer">
                                                        <div class="img-container directory"><span></span>
                                                            <img class="directory-img"
                                                                 src="<?php echo STATIC_URL; ?>/lib/tinymce/plugins/filemanager/ico/folder_return.png"
                                                                 alt="folder"/>
                                                        </div>
                                                    </div>
                                                </a>
                                            </figure>
                                        </li>
                                    <?php }
                                    if ((!(isset($queryRequest['type']) && $queryRequest['type'] == 1 && !$is_img) && !(isset($queryRequest['type']) && $queryRequest['type'] >= 3 && !$is_video))) {
                                        ?>
                                        <li class="ff-item-type-<?php echo $class_ext; ?>" style="position: relative;">
                                            <?php if (isset($queryRequest['field_id']) && empty($queryRequest['field_id']) || !isset($queryRequest['field_id'])): ?>
                                                <label style="position: absolute; bottom:0; right:5px; z-index: 999" for="file_<?php echo $i; ?>">
                                                </label>
                                            <?php endif; ?>
                                            <figure>
                                                <a href="javascript:void(0);"
                                                   title="<?php echo \Common\Languages::files()['lang_Select']; ?>"
                                                   onclick="<?php echo $apply . "('" . $file . "'," . (isset($queryRequest['type']) ? $queryRequest['type'] : 2) . ",'" . (isset($queryRequest['field_id']) ? $queryRequest['field_id'] : '') . "');"; ?>">
                                                    <div class="img-precontainer">
                                                        <div class="img-container"><span></span>
                                                            <img data-src="holder.js/122x91"
                                                                 alt="image" <?php echo $show_original ? "class='original'" : "" ?>
                                                                 src="<?php echo IMG_URL . $src_thumb; ?>">
                                                        </div>
                                                    </div>
                                                </a>
                                                <div class="box">
                                                    <h4><?php echo substr($file, 0, '-' . (strlen($file_ext) + 1)); ?></h4>

                                                </div>
                                                <figcaption>
                                                    <form action="<?php echo $this->url->get('/file/forcedownload'); ?>"
                                                          method="post"
                                                          class="download-form"
                                                          id="form<?php echo $nu; ?>">
                                                        <input type="hidden" name="path"
                                                               value="<?php echo IMG_URL . $cur_dir . $file ?>"/>
                                                        <input type="hidden" name="name" value="<?php echo $file ?>"/>

                                                        <a title="<?php echo \Common\Languages::files()['lang_Download']; ?>"
                                                           class=""
                                                           href="javascript:void(0);"
                                                           onclick="$('#form<?php echo $nu; ?>').submit();"><i
                                                                class="icon-download"></i></a>
                                                        <?php if ($is_img) { ?>
                                                            <a class="preview"
                                                               title="<?php echo \Common\Languages::files()['lang_Preview']; ?>"
                                                               data-url="<?php echo $src; ?>"
                                                               data-toggle="lightbox" href="#previewLightbox"><i
                                                                    class=" icon-eye-open"></i></a>
                                                        <?php } else { ?>
                                                            <a class="preview disabled"><i
                                                                    class="icon-eye-open icon-white"></i></a>
                                                        <?php } ?>
                                                        <?php /*
                                <a href="javascript:void('');" class="erase-button"
                                   <?php if ($delete_file){ ?>onclick=" if(confirm('<?php echo \Common\Languages::files()['lang_Confirm_del']; ?>')){ delete_file('<?php echo BASE_PATH . $cur_dir . $file; ?>','<?php echo $thumbs_path . $subdir . $file; ?>'); $(this).parent().parent().parent().parent().hide(200); return false;}"<?php } ?>
                                   title="<?php echo \Common\Languages::files()['lang_Erase']; ?>"><i
                                        class="icon-trash <?php if (!$delete_file) echo 'icon-white'; ?>"></i></a>
                                */ ?>
                                                    </form>
                                                </figcaption>
                                            </figure>

                                        </li>
                                        <?php
                                        $i++;
                                    }
                                }
                            }
                        }
                        ?>
                    </ul>
                <?php } ?>
            </div>

        </div>
    </div>
