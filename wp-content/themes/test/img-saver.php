<?php

    global $wpdb;

    /**
     * @Save_img_to_\uploads\
     */

    if( wp_verify_nonce( $_POST['fileup_nonce'], 'my_file_upload' ) ){

        if ( ! function_exists( 'wp_handle_upload' ) )
            require_once( ABSPATH . 'wp-admin/includes/file.php' );

        $file = & $_FILES['my_file_upload'];
        $file_types = ['jpeg', 'jpg', 'png'];

        $overrides = [ 'test_form' => false ];

        $movefile = wp_handle_upload( $file, $overrides );

        if ( $movefile && empty($movefile['error']) ) {
            if($file['type'] == $file_types){
                echo "Файл был успешно загружен.\n";
                print_r( $movefile );
            }
        } else {
            echo "Братан, чет не так. Походу это не картинка((\n";
        }
    }

    /**
     * @Save_img_to_DB
     */

    // файл должен находиться в директории загрузок WP.
    $filename = $file['name'];

    // Проверим тип поста, который мы будем использовать в поле 'post_mime_type'.
    $filetype = wp_check_filetype( basename( $filename ), null );

    // Получим путь до директории загрузок.
    $wp_upload_dir = wp_upload_dir();

    // Подготовим массив с необходимыми данными для вложения.
    $attachment = array(
        'guid'           => $wp_upload_dir['url'] . '/' . basename( $filename ),
        'post_mime_type' => $filetype['type'],
        'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $filename ) ),
        'post_content'   => '',
        'post_status'    => 'inherit'
    );

    // Вставляем запись в базу данных.
    $attach_id = wp_insert_attachment( $attachment, $filename );

    // Подключим нужный файл, если он еще не подключен
    // wp_generate_attachment_metadata() зависит от этого файла.
    require_once( ABSPATH . 'wp-admin/includes/image.php' );

    // Создадим метаданные для вложения и обновим запись в базе данных.
    $attach_data = wp_generate_attachment_metadata( $attach_id, $filename );
    wp_update_attachment_metadata( $attach_id, $attach_data );

    /**
     * @SQL
     */

    $get_all_images = $wpdb->get_results('SELECT * FROM `wp_posts` WHERE `post_mime_type` = "image/jpeg" OR  `post_mime_type` = "image/png"', ARRAY_A);

?>

<style>
    h2 {
        margin: 50px 0;
    }
    section {
        flex-grow: 1;
    }
    .file-drop-area {
        position: relative;
        display: flex;
        align-items: center;
        width: 450px;
        max-width: 100%;
        padding: 25px;
        border: 1px dashed rgba(163, 193, 255, 0.91);
        border-radius: 3px;
        transition: 0.2s;
    }
    .file-drop-area.is-active {
        background-color: rgba(255, 255, 255, 0.05);
    }
    .fake-btn {
        flex-shrink: 0;
        background-color: rgba(163, 193, 255, 0.91);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 3px;
        padding: 8px 15px;
        margin-right: 10px;
        font-size: 12px;
        text-transform: uppercase;
    }
    .file-msg {
        font-size: small;
        font-weight: 300;
        line-height: 1.4;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .file-input {
        position: absolute;
        left: 0;
        top: 0;
        height: 100%;
        width: 100%;
        cursor: pointer;
        opacity: 0;
    }
    .file-input:focus {
        outline: none;
    }
    .container{
        width: 100%;
        display: flex;
        flex-direction: row;
        padding: 0 15px;
        justify-content: space-between;
    }
    .flex-item{
        width: 48%;
    }
    .flex-item ul{
        display: flex;
        flex-direction: column;
        justify-content: space-around;
    }
    .flex-item li{
        margin-bottom: 10px;
    }
    .flex-item li img{
        display: block;
        width: 100%;
    }
</style>

<div class="container">
    <div class="wrap flex-item">
        <h2><?php echo get_admin_page_title() ?></h2>

        <img src="<?= get_template_directory_uri() ?>/borka.jpg" alt="asd" style="display: block; width: 15%; margin-bottom: 50px;">

        <form action="" method="POST" enctype="multipart/form-data">
            <?php wp_nonce_field( 'my_file_upload', 'fileup_nonce' ); ?>
            <!----------BOOTSTRAP----------https://codepen.io/prasanjit/pen/NxjZMO---------->
            <h2>Положи картинку сюда, пес</h2>
            <div class="file-drop-area">
                <span class="fake-btn">давай выбирай</span>
                <span class="file-msg">иль сюда кинь, ска</span>
                <input class="file-input" type="file" name="my_file_upload" multiple>
            </div>
            <!----------BOOTSTRAP---------->
            <input type="submit" value="Upload Image" name="submit">

        </form>
    </div>
    <div class="flex-item">
        <?php foreach ($get_all_images as $img){ ?>
        <ul>
            <li>
                <img src="<?= $img['guid'] ?>" alt="asd">
            </li>
        </ul>
        <?php } ?>
    </div>
</div>
<?php dump( $get_all_images ) ?>

<script>
  var $fileInput = jQuery('.file-input');
  var $droparea = jQuery('.file-drop-area');

  // highlight drag area
  $fileInput.on('dragenter focus click', function() {
    $droparea.addClass('is-active');
  });

  // back to normal state
  $fileInput.on('dragleave blur drop', function() {
    $droparea.removeClass('is-active');
  });

  // change inner text
  $fileInput.on('change', function() {
    var filesCount = jQuery(this)[0].files.length;
    var $textContainer = jQuery(this).prev();

    if (filesCount === 1) {
      // if single file is selected, show file name
      var fileName = jQuery(this).val().split('\\').pop();
      $textContainer.text(fileName);
    } else {
      // otherwise show number of files
      $textContainer.text(filesCount + ' files selected');
    }
  });
</script>